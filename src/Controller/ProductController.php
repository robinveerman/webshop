<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Reacties;
use App\Entity\Settings;
use App\Form\ProductType;
use App\Form\ReactiesType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController {
	/**
	 * @Route("/", name="product_index", methods="GET")
	 */
	public function index( ProductRepository $productRepository ): Response {
		$em = $this->getDoctrine()->getManager();
		$settings = $em->getRepository(Settings::class)->findAll();

		return $this->render( 'product/index.html.twig',
			[
				'products' => $productRepository->findAll(),
				'settings' => $settings,
			] );
	}

	/**
	 * @Route("/new", name="product_new", methods="GET|POST")
	 */
	public function new( Request $request ): Response {
		if ( $this->isGranted( 'ROLE_SUPER_ADMIN' ) ) {
			$product = new Product();
			$form    = $this->createForm( ProductType::class, $product );
			$form->handleRequest( $request );

			if ( $form->isSubmitted() && $form->isValid() ) {

				/** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
				$file = $form->get( 'imagepath' )->getData();

				// Genereert unieke naam voor images
				$fileName = md5( uniqid() ) . '.' . $file->guessExtension();

				// Verplaatst file naar uploads-directory
				$file->move(
					$this->getParameter( 'uploads' ),
					$fileName
				);

				// Zet het imagePath in de database. Hier staat $product maar je moet dat natuurlijk
				// aanpassen zodat het klop met jouw database.
				$product->setImagepath( $fileName );

				// Gooi alles in de DB
				$em = $this->getDoctrine()->getManager();

				// Pas ook hier $product aan naar eigen DB
				$em->persist( $product );
				$em->flush();

				return $this->redirectToRoute( 'product_index' );
			}

			return $this->render( 'product/new.html.twig', [
				'product' => $product,
				'form'    => $form->createView(),
			] );
		} else {
			return $this->render( 'default/accessdenied.html.twig' );
		}
	}
	/**
	 * @Route("/{id}", name="product_show", methods="POST|GET")
	 */
	public function show( Product $product = null, Request $request ): Response {
		$em = $this->getDoctrine()->getManager();
		if ( $product == null ) {
			return $this->render( 'default/error.html.twig',
				[
					'error'   => 'Dit product bestaat niet',
					'message' => 'Het product dat u probeert te bekijken bestaat niet (meer)'
				] );
		}
		$settings  = $em->getRepository( Settings::class )->findAll();
		$producten = $em->getRepository( Product::class )->findBy( [ 'categorie' => $product->getCategorie() ] );
		$ratings   = $em->getRepository( Reacties::class )->createQueryBuilder( 'r' )
		                ->where( 'r.rating != 0 and r.product = :product ' )
		                ->setParameter( ':product', $product->getId() )
		                ->getQuery()
		                ->getResult();
		$reacties  = $em->getRepository( Reacties::class )->findBy( [ 'product' => $product ], [ 'timestamp' => 'DESC' ] );
		// Reactie toevoegen
		$reactie = new Reacties();
		$form   = $this->createForm( ReactiesType::class, $reactie );
		$form->handleRequest( $request );
		if ( $form->isSubmitted() && $form->isValid() ) {
			$reactie->setUser( $this->getUser() );
			$reactie->setProduct( $product );
			$reactie->setIpAdres( $request->getClientIp() );
			$reactie->setTimestamp( new \DateTime( 'now' ) );
			$em = $this->getDoctrine()->getManager();
			$em->persist( $reactie );
			$em->flush();
			return $this->redirectToRoute( 'product_show', [ 'id' => $product->getId() ] );
		}
		return $this->render( 'product/show.html.twig', [
			'product'  => $product,
			'related'  => $producten,
			'reacties' => $reacties,
			'settings' => $settings,
			'ratings'  => $ratings,
			'form'     => $form->createView()
		] );
	}

	/**
	 * @Route("/{id}/edit", name="product_edit", methods="GET|POST")
	 */
	public function edit( Request $request, Product $product ): Response {
		if ( $this->isGranted( 'ROLE_SUPER_ADMIN' ) ) {
			$form = $this->createForm( ProductType::class, $product );
			$form->handleRequest( $request );

			if ( $form->isSubmitted() && $form->isValid() ) {
				$this->getDoctrine()->getManager()->flush();

				return $this->redirectToRoute( 'product_index', [ 'id' => $product->getId() ] );
			}

			return $this->render( 'product/edit.html.twig', [
				'product' => $product,
				'form'    => $form->createView(),
			] );
		} else {
			return $this->render( 'default/accessdenied.html.twig' );
		}
	}

	/**
	 * @Route("/{id}", name="product_delete", methods="DELETE")
	 */
	public function delete( Request $request, Product $product ): Response {
		if ( $this->isCsrfTokenValid( 'delete' . $product->getId(), $request->request->get( '_token' ) ) ) {
			$em = $this->getDoctrine()->getManager();
			$em->remove( $product );
			$em->flush();
		}

		return $this->redirectToRoute( 'product_index' );
	}
}

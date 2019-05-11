<?php

namespace App\Controller;

use App\Entity\Merk;
use App\Entity\Product;
use App\Form\MerkType;
use App\Repository\MerkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/merk")
 */
class MerkController extends AbstractController {
	/**
	 * @Route("/", name="merk_index", methods="GET")
	 */
	public function index( MerkRepository $merkRepository ): Response {
		return $this->render( 'merk/index.html.twig', [ 'merks' => $merkRepository->findAll() ] );
	}

	/**
	 * @Route("/new", name="merk_new", methods="GET|POST")
	 */
	public function new( Request $request ): Response {
		if ( $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {
			$merk = new Merk();
			$form = $this->createForm( MerkType::class, $merk );
			$form->handleRequest( $request );

			if ( $form->isSubmitted() && $form->isValid() ) {

				/** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */

				// Haal file uit form
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
				$merk->setImagepath( $fileName );

				// Gooi alles in de DB
				$em = $this->getDoctrine()->getManager();

				// Pas ook hier $product aan naar eigen DB
				$em->persist( $merk );
				$em->flush();

				return $this->redirectToRoute( 'merk_index' );
			}

			return $this->render( 'merk/new.html.twig', [
				'merk' => $merk,
				'form' => $form->createView(),
			] );
		} else {
			return $this->render( 'default/accessdenied.html.twig' );
		}
	}

	/**
	 * @Route("/{merk}", name="merk_show", methods="GET")
	 */
	public function show(  $merk ): Response {
		$em        = $this->getDoctrine()->getManager();
		$merks      = $em->getRepository( Merk::class )->findBy( [ 'naam' => $merk ] );

		if(!isset($merks[0])){
			return $this->render('default/error.html.twig',[
				'error'=> 'Geen merk gevonden',
				'message'=>'Sorry, dit merk bestaat niet'
			]);
		}

		$producten = $em->getRepository( Product::class )->findBy( [ 'merk' => $merks[0]->getId() ] );

		return $this->render( 'merk/show.html.twig', [
			'merk'       => $merks[0],
			'productens' => $producten,
		] );
	}

	/**
	 * @Route("/{id}/edit", name="merk_edit", methods="GET|POST")
	 */
	public function edit( Request $request, Merk $merk ): Response {
		if ( $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {
			$form = $this->createForm( MerkType::class, $merk );
			$form->handleRequest( $request );

			if ( $form->isSubmitted() && $form->isValid() ) {
				$this->getDoctrine()->getManager()->flush();

				return $this->redirectToRoute( 'merk_index', [ 'id' => $merk->getId() ] );
			}

			return $this->render( 'merk/edit.html.twig', [
				'merk' => $merk,
				'form' => $form->createView(),
			] );
		} else {
			return $this->render( 'default/accessdenied.html.twig' );
		}
	}

	/**
	 * @Route("/{id}", name="merk_delete", methods="DELETE")
	 */
	public function delete( Request $request, Merk $merk ): Response {
		if ( $this->isCsrfTokenValid( 'delete' . $merk->getId(), $request->request->get( '_token' ) ) ) {
			$em = $this->getDoctrine()->getManager();
			$em->remove( $merk );
			$em->flush();
		}

		return $this->redirectToRoute( 'merk_index' );
	}
}

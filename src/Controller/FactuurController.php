<?php

namespace App\Controller;

use App\Entity\Factuur;
use App\Entity\NAW;
use App\Entity\Product;
use App\Entity\Regel;
use App\Form\FactuurType;
use App\Repository\FactuurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/factuur")
 */
class FactuurController extends AbstractController {
	/**
	 * @Route("/", name="factuur_index", methods="GET")
	 */
	public function index( FactuurRepository $factuurRepository ): Response {
		if ( $this->getUser() == true ) {

			$em  = $this->getDoctrine()->getManager();
			$naw = $em->getRepository( NAW::class )->findAll();

			if ( $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {

				return $this->render( 'factuur/index.html.twig',
					[
						'factuurs' => $factuurRepository->findAll(),
						'naw'      => $naw,

					] );

			} else {

				return $this->render( 'factuur/index.html.twig',
					[
						'factuurs' => $factuurRepository->findBy( array( 'klantId' => $this->getUser() ) ),
						'naw'      => $naw,
					] );
			}
		} else {
			return $this->render( 'default/error.html.twig', [
				'code'    => 101,
				'error'   => 'Geen gebruiker gevonden',
				'message' => 'Log in om bestellingen te kunnen zien.'
			] );
		}
	}

	/**
	 * @Route("/new", name="factuur_new", methods="GET|POST")
	 */
	public function new( Request $request ): Response {
		if ( $this->isGranted( 'ROLE_SUPER_ADMIN' ) ) {
			$factuur = new Factuur();
			$form    = $this->createForm( FactuurType::class, $factuur );
			$form->handleRequest( $request );

			if ( $form->isSubmitted() && $form->isValid() ) {
				$em = $this->getDoctrine()->getManager();
				$em->persist( $factuur );
				$em->flush();

				return $this->redirectToRoute( 'factuur_index' );
			}

			return $this->render( 'factuur/new.html.twig', [
				'factuur' => $factuur,
				'form'    => $form->createView(),
			] );
		} else {
			return $this->render( 'default/accessdenied.html.twig' );
		}
	}

	/**
	 * @Route("/{id}", name="factuur_show", methods="GET")
	 */
	public function show( Factuur $factuur ): Response {

		if ( $factuur->getKlantId() === $this->getUser() || $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {
			$em = $this->getDoctrine()->getManager();

			$regels   = $em->getRepository( Regel::class )->findby( [ 'factuurId' => $factuur->getId() ] );
			$products = $em->getRepository( Product::class )->findall();
			$naw      = $em->getRepository( NAW::class )->findBy( array( 'user' => $factuur->getKlantId() ) );

			// dump($regels);
//	    $deleteForm = $this->createDeleteForm($factuur);

			return $this->render( 'factuur/show.html.twig', array(
				'factuur'  => $factuur,
				'naw'      => $naw,
				'products' => $products,
				'regels'   => $regels,
//		    'delete_form' => $deleteForm->createView(),
			) );

		} else {
			return $this->render( 'default/error.html.twig', [
				'code'    => 102,
				'error'   => 'Dit is niet uw factuur.',
				'message' => 'De factuur die u heeft opgevraagd is niet van u.'
			] );
		}
	}

	/**
	 * @Route("/{id}/edit", name="factuur_edit", methods="GET|POST")
	 */
	public function edit( Request $request, Factuur $factuur ): Response {
		if ( $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {
			$em       = $this->getDoctrine()->getManager();
			$regels   = $em->getRepository( Regel::class )->findby( [ 'factuurId' => $factuur->getId() ] );
			$products = $em->getRepository( Product::class )->findall();

			$factuur->setRegels( $regels );

			$form = $this->createForm( FactuurType::class, $factuur );

			$form->handleRequest( $request );

			if ( $form->isSubmitted() && $form->isValid() ) {

				dump( $factuur->getRegels() );
				// put in collection of forms items
				foreach ( $factuur->getRegels() as $regel ) {
					if ( $regel->getId() == null ) {
						$reg = new Regel();
						$reg->setProductId( $regel->getProductId() );
						$reg->setAantal( $regel->getAantal() );
						$reg->setFactuurId( $regel->getFactuurId() );

						$em->persist( $reg );
					}
				}

				$em->persist( $factuur );
				$this->getDoctrine()->getManager()->flush();

				return $this->redirectToRoute( 'factuur_show', array( 'id' => $factuur->getId() ) );
			}

			return $this->render( 'factuur/edit.html.twig', array(
				'factuur'  => $factuur,
				'products' => $products,
				'form'     => $form->createView(),
			) );
		} else {
			return $this->render( 'default/accessdenied.html.twig' );
		}
	}

	/**
	 * @Route("/{id}", name="factuur_delete", methods="DELETE")
	 */
	public function delete( Request $request, Factuur $factuur ): Response {
		if ( $this->isCsrfTokenValid( 'delete' . $factuur->getId(), $request->request->get( '_token' ) ) ) {
			$em = $this->getDoctrine()->getManager();
			$em->remove( $factuur );
			$em->flush();
		}

		return $this->redirectToRoute( 'factuur_index' );
	}
}

<?php

namespace App\Controller;

use App\Entity\Factuur;
use App\Entity\Kortingscode;
use App\Form\KortingscodeType;
use App\Repository\KortingscodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/kortingscode")
 */
class KortingscodeController extends AbstractController {
	/**
	 * @Route("/", name="kortingscode_index", methods="GET")
	 */
	public function index( KortingscodeRepository $kortingscodeRepository ): Response {
		if ( $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {

			return $this->render( 'kortingscode/index.html.twig', [ 'kortingscodes' => $kortingscodeRepository->findAll() ] );
		} else {
			return $this->render( 'default/accessdenied.html.twig' );
		}
	}

	/**
	 * @Route("/new", name="kortingscode_new", methods="GET|POST")
	 */
	public function new( Request $request ): Response {
		if ( $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {
			$kortingscode = new Kortingscode();
			$form         = $this->createForm( KortingscodeType::class, $kortingscode );
			$form->handleRequest( $request );

			if ( $form->isSubmitted() && $form->isValid() ) {
				$em = $this->getDoctrine()->getManager();
				$em->persist( $kortingscode );
				$em->flush();

				return $this->redirectToRoute( 'kortingscode_index' );
			}

			return $this->render( 'kortingscode/new.html.twig', [
				'kortingscode' => $kortingscode,
				'form'         => $form->createView(),
			] );
		} else {
			return $this->render( 'default/accessdenied.html.twig' );
		}
	}

	/**
	 * @Route("/{id}", name="kortingscode_show", methods="GET")
	 */
	public function show( Kortingscode $kortingscode ): Response {
		if ( $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {
			return $this->render( 'kortingscode/show.html.twig', [ 'kortingscode' => $kortingscode ] );
		} else {
			return $this->render( 'default/accessdenied.html.twig' );
		}
	}

	/**
	 * @Route("/{id}/edit", name="kortingscode_edit", methods="GET|POST")
	 */
	public function edit( Request $request, Kortingscode $kortingscode ): Response {
		if ( $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {
			$form = $this->createForm( KortingscodeType::class, $kortingscode );
			$form->handleRequest( $request );

			if ( $form->isSubmitted() && $form->isValid() ) {
				$this->getDoctrine()->getManager()->flush();

				return $this->redirectToRoute( 'kortingscode_index', [ 'id' => $kortingscode->getId() ] );
			}

			return $this->render( 'kortingscode/edit.html.twig', [
				'kortingscode' => $kortingscode,
				'form'         => $form->createView(),
			] );
		} else {
			return $this->render( 'default/accessdenied.html.twig' );
		}
	}

	/**
	 * @Route("/{id}", name="kortingscode_delete", methods="DELETE")
	 */
	public function delete( Request $request, Kortingscode $kortingscode ): Response {
		if ( $this->isCsrfTokenValid( 'delete' . $kortingscode->getId(), $request->request->get( '_token' ) ) ) {
			$em = $this->getDoctrine()->getManager();
			$em->remove( $kortingscode );
			$em->flush();
		}

		return $this->redirectToRoute( 'kortingscode_index' );
	}

	/**
	 * @Route("/add/{code}", name="kortingscode_add")
	 */
	public function add( $code ) {
		$em       = $this->getDoctrine()->getManager();
		$korting  = $em->getRepository( Kortingscode::class )->findOneBy( [ 'code' => $code ] );
		$facturen = $em->getRepository( Factuur::class )->findBy( [ 'klantId' => $this->getUser() ] );

		$session = $this->get( 'request_stack' )->getCurrentRequest()->getSession();
		$coupon  = $session->get( 'coupon', array() );

		// Controleer of coupon bestaat
		if ( $korting == null ) {
			$this->addFlash( ' alert alert-danger', 'Kortingsbon bestaat niet!' );

			return $this->redirectToRoute( 'cart' );
		}

		// Controleer of coupon niet verlopen is
		$date = new \DateTime( 'now' );
		if ( $korting->getEinddatum() != null && $korting->getEinddatum() < $date ) {
			$this->addFlash( ' alert alert-danger', 'Kortingsbon is verlopen' );

			return $this->redirectToRoute( 'cart' );
		}

		//Controleer of coupon niet al gebruikt is
		$gebruikt = [];

		foreach ( $facturen as $f ) {
			foreach ( $f->getKortingscode() as $korting ) {
				array_push( $gebruikt, [ $korting->getCode() => $korting ] );
			}
		}

		if ( in_array( $code, $gebruikt ) ) {
			$this->addFlash( ' alert alert-danger', 'Kortingsbon al eerder gebruikt!' );

			return $this->redirectToRoute( 'cart' );
		}


		if ( empty( $coupon ) ) {
			array_push( $coupon, [ $code => $korting ] );
			$session->set( 'coupon', $coupon );
		} else {

			// Controle of coupon al gebruikt wordt
			foreach ( $coupon as $c ) {
				if ( isset( $c[ $code ] ) ) {
					$this->addFlash( ' alert alert-danger', 'Kortingsbon wordt al gebruikt' );

					return $this->redirectToRoute( 'cart' );
				} else {
					array_push( $coupon, [ $code => $korting ] );
					$session->set( 'coupon', $coupon );
				}
			}
		}

		return $this->redirectToRoute( 'cart' );
	}

	/**
	 * @Route("/remove/all", name="kortingscode_remove")
	 */
	public function remove() {
		$session = $this->get( 'request_stack' )->getCurrentRequest()->getSession();
		$coupon  = $session->get( 'coupon', array() );

		unset( $coupon );
		$coupon = [];

		$session->set( 'coupon', $coupon );

		return $this->redirectToRoute( 'cart' );
	}
}

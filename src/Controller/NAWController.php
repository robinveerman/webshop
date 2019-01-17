<?php

namespace App\Controller;

use App\Entity\NAW;
use App\Entity\User;
use App\Form\NAWType;
use App\Repository\NAWRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/naw")
 */
class NAWController extends AbstractController {
	protected $security;

	public function __construct( Security $security ) {
		$this->security = $security;
	}

	/**
	 * @Route("/", name="n_a_w_index", methods="GET")
	 */
	public function index( NAWRepository $nAWRepository ): Response {
		if ( $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {
			return $this->render( 'naw/index.html.twig', [ 'n_a_ws' => $nAWRepository->findAll() ] );
		} else {
			return $this->render( 'naw/index.html.twig', [ 'n_a_ws' => $nAWRepository->findBy( array( 'user' => $this->getUser() ) ) ] );
		}
	}

	/**
	 * @Route("/new", name="n_a_w_new", methods="GET|POST")
	 */
	public function new( Request $request ): Response {
		$em             = $this->getDoctrine()->getManager();
		$nawCurrentUser = $em->getRepository( NAW::class )->findBy( array( 'user' => $this->getUser() ) );

		if ( $nawCurrentUser == null || $this->isGranted( "ROLE_ADMIN" ) ) {
			dump( $nawCurrentUser );

			$nAW  = new NAW();
			$form = $this->createForm( NAWType::class, $nAW );
			$form->handleRequest( $request );

			if ( $form->isSubmitted() && $form->isValid() ) {
				if ( ! $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {
					$nAW->setUser( $this->getUser() );
				}

				$em = $this->getDoctrine()->getManager();
				$em->persist( $nAW );
				$em->flush();

				return $this->redirectToRoute( 'n_a_w_index' );
			}

			return $this->render( 'naw/new.html.twig', [
				'n_a_w' => $nAW,
				'form'  => $form->createView(),
			] );
		} else {
			return $this->redirectToRoute( 'n_a_w_index' );
		}
	}

	/**
	 * @Route("/{id}/edit", name="n_a_w_edit", methods="GET|POST")
	 */
	public function edit( Request $request, NAW $nAW ): Response {
		if ( $nAW->getUser() === $this->getUser() || $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {
			$form = $this->createForm( NAWType::class, $nAW );
			$form->handleRequest( $request );

			if ( $form->isSubmitted() && $form->isValid() ) {
				$this->getDoctrine()->getManager()->flush();

				return $this->redirectToRoute( 'n_a_w_index', [ 'id' => $nAW->getId() ] );
			}

			return $this->render( 'naw/edit.html.twig', [
				'n_a_w' => $nAW,
				'form'  => $form->createView(),
			] );
		} else {
			return $this->render( 'default/error.html.twig', [
				'code'    => 102,
				'error'   => 'Gegevens van een andere klant',
				'message' => 'U kunt alleen uw eigen gegevens bewerken.'
			] );
		}
	}

	/**
	 * @Route("/{id}", name="n_a_w_delete", methods="DELETE")
	 */
	public
	function delete(
		Request $request, NAW $nAW
	): Response {
		if ( $this->isCsrfTokenValid( 'delete' . $nAW->getId(), $request->request->get( '_token' ) ) ) {
			$em = $this->getDoctrine()->getManager();
			$em->remove( $nAW );
			$em->flush();
		}

		return $this->redirectToRoute( 'n_a_w_index' );
	}
}

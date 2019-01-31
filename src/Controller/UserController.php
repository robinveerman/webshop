<?php

namespace App\Controller;

use App\Entity\NAW;
use App\Entity\User;
use App\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController {
	/**
	 * @Route("/", name="user_index", methods="GET")
	 */
	public function index(): Response {
//		if ( $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {
		$users = $this->getDoctrine()
		              ->getRepository( User::class )
		              ->findAll();
		$naw   = $this->getDoctrine()
		              ->getRepository( NAW::class )
		              ->findAll();

		return $this->render( 'user/index.html.twig', [
			'users' => $users,
			'naw'   => $naw,
		] );
//		} else {
//			return $this->render( "default/accessdenied.html.twig" );
//		}
	}

	/**
	 * @Route("/new", name="user_new", methods="GET|POST")
	 */
	public function new( Request $request ): Response {
//		if ( $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {
		$user = new User();
		$form = $this->createForm( UserType::class, $user );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$em = $this->getDoctrine()->getManager();
			$em->persist( $user );
			$em->flush();

			return $this->redirectToRoute( 'user_index' );
		}

		return $this->render( 'user/new.html.twig', [
			'user' => $user,
			'form' => $form->createView(),
		] );
//		} else {
//			return $this->render( 'default/accessdenied.html.twig' );
//		}
	}

	/**
	 * @Route("/{id}", name="user_show", methods="GET")
	 */
	public function show( User $user ): Response {
//		if ( $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {
		return $this->render( 'user/show.html.twig', [ 'user' => $user ] );
//		} else {
//			return $this->render( 'default/accessdenied.html.twig' );
//		}
	}

	/**
	 * @Route("/{id}/edit", name="user_edit", methods="GET|POST")
	 */
	public function edit( Request $request, User $user ): Response {
//		if ( $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {
		$form = $this->createForm( UserType::class, $user );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$this->getDoctrine()->getManager()->flush();

			return $this->redirectToRoute( 'user_index', [ 'id' => $user->getId() ] );
		}

		return $this->render( 'user/edit.html.twig', [
			'user' => $user,
			'form' => $form->createView(),
		] );
//		} else {
//			return $this->render( 'default/accessdenied.html.twig' );
//		}
	}

	/**
	 * @Route("/{id}", name="user_delete", methods="DELETE")
	 */
	public function delete( Request $request, User $user ): Response {
		if ( $this->isCsrfTokenValid( 'delete' . $user->getId(), $request->request->get( '_token' ) ) ) {
			$em = $this->getDoctrine()->getManager();
			$em->remove( $user );
			$em->flush();
		}

		return $this->redirectToRoute( 'user_index' );
	}

	/**
	 * Rol action
	 *
	 * @Route("/role/{id}", name="role_action")
	 * @Method("GET")
	 */
	public function roleAction( $id ) {
		if ( $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {
			$em = $this->getDoctrine()->getManager();


			$user = $em->getRepository( "App:User" )->find( $id );
			if ( $user->getId() == 1 ) {
				return $this->redirectToRoute( 'user_index' );
			} else {

				if ( $user ) {
					if ( in_array( "ROLE_SUPER_ADMIN", $user->getRoles() ) ) {
						$user->removeRole( "ROLE_SUPER_ADMIN" );
					} else {

						//Voeg admin rol toe
						$user->addRole( "ROLE_SUPER_ADMIN" );
					}
					//Save it to the database
					$em->persist( $user );
					$em->flush();
				}
				$users = $em->getRepository( 'App:User' )->findAll();

				return $this->render( 'user/index.html.twig', array(
					'users' => $users,
				) );
			}
		} else {
			return $this->render( 'default/accessdenied.html.twig' );
		}
	}

	/**
	 * @Route("/block/{user}", name="blokken_user")
	 */
	public function block( User $user ) {
		$em = $this->getDoctrine()->getManager();

		$user = $em->getRepository( User::class )->find( $user );

		if ( $user->isEnabled() ) {
			$user->setEnabled( 0 );
		} else {
			$user->setEnabled( 1 );
		}

		$em->persist( $user );
		$em->flush();

		return $this->redirectToRoute( 'user_index' );

	}
}

<?php

namespace App\Controller;

use App\Entity\Reacties;
use App\Form\ReactiesType;
use App\Repository\ReactiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reacties")
 */
class ReactiesController extends AbstractController {
	/**
	 * @Route("/", name="reacties_index", methods="GET")
	 */
	public function index( ReactiesRepository $reactiesRepository ): Response {
		if ( $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {
			return $this->render( 'reacties/index.html.twig', [ 'reacties' => $reactiesRepository->findBy([],['timestamp'=>'DESC'])] );
		} else {
			return $this->render( 'default/accessdenied.html.twig' );
		}
	}


	/**
	 * @Route("/{id}/edit", name="reacties_edit", methods="GET|POST")
	 */
	public function edit( Request $request, Reacties $reacty ): Response {
		if ( $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {
			$form = $this->createForm( ReactiesType::class, $reacty );
			$form->handleRequest( $request );

			if ( $form->isSubmitted() && $form->isValid() ) {
				$this->getDoctrine()->getManager()->flush();

				return $this->redirectToRoute( 'reacties_index', [ 'id' => $reacty->getId() ] );
			}

			return $this->render( 'reacties/edit.html.twig', [
				'reacty' => $reacty,
				'form'   => $form->createView(),
			] );
		} else {
			return $this->render( 'default/accessdenied.html.twig' );
		}
	}

	/**
	 * @Route("/{id}", name="reacties_delete", methods="DELETE")
	 */
	public function delete( Request $request, Reacties $reacty ): Response {
		if ( $this->isCsrfTokenValid( 'delete' . $reacty->getId(), $request->request->get( '_token' ) ) ) {
			$em = $this->getDoctrine()->getManager();
			$em->remove( $reacty );
			$em->flush();
		}

		return $this->redirectToRoute( 'reacties_index' );
	}
}

<?php

namespace App\Controller;

use App\Entity\Afbeeldingen;
use App\Form\AfbeeldingenType;
use App\Repository\AfbeeldingenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/afbeeldingen")
 */
class AfbeeldingenController extends AbstractController {
	/**
	 * @Route("/", name="afbeeldingen_index", methods="GET|POST")
	 */
	public function index( AfbeeldingenRepository $afbeeldingenRepository ): Response {
		if ( $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {
			return $this->render( 'afbeeldingen/index.html.twig', [ 'afbeeldingens' => $afbeeldingenRepository->findAll() ] );
		} else {
			return $this->render( 'default/accessdenied.html.twig' );
		}
	}

	/**
	 * @Route("/new", name="afbeeldingen_new", methods="GET|POST")
	 */
	public function new( Request $request ): Response {
		if ( $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {
			$afbeeldingen = new Afbeeldingen();
			$form         = $this->createForm( AfbeeldingenType::class, $afbeeldingen );
			$form->handleRequest( $request );

			$afbeeldingen->setTimestamp(new \DateTime('now'));

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
				$afbeeldingen->setImagepath( $fileName );

				// Gooi alles in de DB
				$em = $this->getDoctrine()->getManager();

				// Pas ook hier $product aan naar eigen DB
				$em->persist( $afbeeldingen );
				$em->flush();

				return $this->redirectToRoute( 'afbeeldingen_index' );
			}


			return $this->render( 'afbeeldingen/new.html.twig', [
				'afbeeldingen' => $afbeeldingen,
				'form'         => $form->createView(),
			] );
		} else {
			return $this->render( 'default/accessdenied.html.twig' );
		}
	}

	/**
	 * @Route("/{id}", name="afbeeldingen_show", methods="GET")
	 */
	public function show( Afbeeldingen $afbeeldingen ): Response {
		if ( $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {
			return $this->render( 'afbeeldingen/show.html.twig', [ 'afbeeldingen' => $afbeeldingen ] );
		} else {
			return $this->render( 'default/accessdenied.html.twig' );
		}
	}

	/**
	 * @Route("/{id}/edit", name="afbeeldingen_edit", methods="GET|POST")
	 */
	public function edit( Request $request, Afbeeldingen $afbeeldingen ): Response {
		if ( $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {
			$form = $this->createForm( AfbeeldingenType::class, $afbeeldingen );
			$form->handleRequest( $request );

			if ( $form->isSubmitted() && $form->isValid() ) {
				$this->getDoctrine()->getManager()->flush();

				return $this->redirectToRoute( 'afbeeldingen_index', [ 'id' => $afbeeldingen->getId() ] );
			}

			return $this->render( 'afbeeldingen/edit.html.twig', [
				'afbeeldingen' => $afbeeldingen,
				'form'         => $form->createView(),
			] );
		} else {
			return $this->render( 'default/accessdenied.html.twig' );
		}
	}

	/**
	 * @Route("/{id}", name="afbeeldingen_delete", methods="DELETE")
	 */
	public function delete( Request $request, Afbeeldingen $afbeeldingen ): Response {
		if ( $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {
			if ( $this->isCsrfTokenValid( 'delete' . $afbeeldingen->getId(), $request->request->get( '_token' ) ) ) {
				$em = $this->getDoctrine()->getManager();
				$em->remove( $afbeeldingen );
				$em->flush();
			}

			return $this->redirectToRoute( 'afbeeldingen_index' );
		} else {
			return $this->render( 'default/accessdenied.html.twig' );
		}
	}
}

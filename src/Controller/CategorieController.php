<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Monolog\Handler\IFTTTHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categorie")
 */
class CategorieController extends AbstractController
{
    /**
     * @Route("/", name="categorie_index", methods="GET")
     */
    public function index(CategorieRepository $categorieRepository): Response
    {
    	if($this->isGranted("ROLE_SUPER_ADMIN")) {
		    return $this->render( 'categorie/index.html.twig', [ 'categories' => $categorieRepository->findAll() ] );
	    }
    	else{
    		return $this->render("default/accessdenied.html.twig");
	    }
    }

    /**
     * @Route("/new", name="categorie_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
	    if($this->isGranted("ROLE_SUPER_ADMIN")) {
		    $categorie = new Categorie();
		    $form      = $this->createForm( CategorieType::class, $categorie );
		    $form->handleRequest( $request );

		    if ( $form->isSubmitted() && $form->isValid() ) {
			    $em = $this->getDoctrine()->getManager();
			    $em->persist( $categorie );
			    $em->flush();

			    return $this->redirectToRoute( 'categorie_index' );
		    }

		    return $this->render( 'categorie/new.html.twig', [
			    'categorie' => $categorie,
			    'form'      => $form->createView(),
		    ] );
	    }
	    else{
	    	return $this->render("default/accessdenied.html.twig");
	    }
    }

    /**
     * @Route("/{id}", name="categorie_show", methods="GET")
     */
    public function show(Categorie $categorie): Response
    {
	    if($this->isGranted("ROLE_SUPER_ADMIN")) {
		    return $this->render( 'categorie/show.html.twig', [ 'categorie' => $categorie ] );
	    }
	    else{
	    	return $this->render("default/accessdenied.html.twig");
	    }
    }

    /**
     * @Route("/{id}/edit", name="categorie_edit", methods="GET|POST")
     */
    public function edit(Request $request, Categorie $categorie): Response
    {
	    if($this->isGranted("ROLE_SUPER_ADMIN")) {
		    $form = $this->createForm( CategorieType::class, $categorie );
		    $form->handleRequest( $request );

		    if ( $form->isSubmitted() && $form->isValid() ) {
			    $this->getDoctrine()->getManager()->flush();

			    return $this->redirectToRoute( 'categorie_index', [ 'id' => $categorie->getId() ] );
		    }

		    return $this->render( 'categorie/edit.html.twig', [
			    'categorie' => $categorie,
			    'form'      => $form->createView(),
		    ] );
	    }
	    else{
	    	return $this->render("default/accessdenied.html.twig");
	    }
    }

    /**
     * @Route("/{id}", name="categorie_delete", methods="DELETE")
     */
    public function delete(Request $request, Categorie $categorie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($categorie);
            $em->flush();
        }

        return $this->redirectToRoute('categorie_index');
    }
}

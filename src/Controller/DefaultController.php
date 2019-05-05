<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {
	    $em      = $this->getDoctrine()->getEntityManager();
	    $product = $em->getRepository( Product::class )->findAll();

	    return $this->render( 'default/index.html.twig',
		    [
			    'name'      => 'Hallo',
			    'body'      => 'dit is de body',
			    'productens' => $product,
		    ] );
    }
}

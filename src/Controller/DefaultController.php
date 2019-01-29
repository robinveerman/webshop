<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Settings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController {
	/**
	 * @Route("/", name="default")
	 */
	public function index() {
		$em      = $this->getDoctrine()->getEntityManager();
		$product = $em->getRepository( Product::class )->findAll();

		return $this->render( 'default/index.html.twig',
			[
				'name'       => 'Hallo',
				'body'       => 'dit is de body',
				'productens' => $product,
			] );
	}

	/**
	 * @Route("/theme", name="theme")
	 */
	public function themes() {
		$em    = $this->getDoctrine()->getManager();
		$theme = $em->getRepository( Settings::class )->findAll();

		return $this->render( 'default/theme.html.twig',
			[ 'theme' => $theme[0]->getThemes() ]
		);
	}
}

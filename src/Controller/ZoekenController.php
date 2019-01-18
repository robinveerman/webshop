<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ZoekenController extends AbstractController {
	/**
	 * @Route("/zoeken/{search}", name="zoeken")
	 */
	public function index( $search ) {
		$em = $this->getDoctrine()->getManager();

		$result = $em->getRepository( Product::class )->createQueryBuilder( 'p' )
		             ->where( 'p.naam LIKE :search ' )
		             ->setParameter( ':search', $search )
		             ->getQuery()
		             ->getResult();


		return $this->render( 'zoeken/index.html.twig', [
			'producten' => $result,
			'search'    => $search
		] );
	}
}

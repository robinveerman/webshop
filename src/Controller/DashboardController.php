<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Regel;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController {
	/**
	 * @Route("/dashboard", name="dashboard")
	 */
	public function index() {
		if ( $this->isGranted( "ROLE_SUPER_ADMIN" ) ) {
			$em = $this->getDoctrine()->getManager();

			$producten = $em->getRepository( Product::class )->findAll();
			$klanten   = $em->getRepository( User::class )->findAll();
			$regel     = $em->getRepository( Regel::class )->findAll();

			$repository = $em->getRepository( Regel::class );
			$query      = $repository->createQueryBuilder( 't' )
			                         ->select( 'Count(t.productId)' )
			                         ->where( 't.productId is not null' )
			                         ->groupBy( 't.productId' )
			                         ->getQuery()
			                         ->getResult();

			return $this->render( 'dashboard/index.html.twig', array(
				'producten' => $producten,
				'regels'    => $query,
				'klanten'   => $klanten,
				'regel'     => $regel,
			) );
		} else {
			return $this->render( 'default/accessdenied.html.twig' );
		}
	}
}

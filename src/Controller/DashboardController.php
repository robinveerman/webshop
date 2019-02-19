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

			$RAW_QUERY = 'select SUM((select prijs from product where product.id = product_id_id ) * aantal) As productId from regel Where product_id_id is not null GROUP BY product_id_id ;';

			$statement = $em->getConnection()->prepare( $RAW_QUERY );
			$statement->execute();

			$punten = $statement->fetchAll();


			return $this->render( 'dashboard/index.html.twig', array(
				'producten' => $producten,
				'regels'    => $query,
				'klanten'   => $klanten,
				'punten'    => $punten,
				'regel'     => $regel,
			) );
		} else {
			return $this->render( 'default/accessdenied.html.twig' );
		}
	}

	/**
	 * @Route("/omzet", name="omzet_totaal")
	 */
	public function omzet() {
		$em = $this->getDoctrine()->getManager();

		$RAW_QUERY = 'select distinct MONTH(datum), YEAR(datum) from factuur;';


		$statement = $em->getConnection()->prepare( $RAW_QUERY );
		$statement->execute();

		$distdate = $statement->fetchAll();

		$datums = [];
		foreach ( $distdate as $key => $value ) {
			array_push( $datums, $value['MONTH(datum)'] );
		}

		$regels = $em->getRepository(Regel::class)->findAll();
		return $this->render( 'dashboard/omzet.html.twig', [
			'dates' => $distdate,
			'data'  => $datums,
			'regel'  => $regels,
		] );
	}
}

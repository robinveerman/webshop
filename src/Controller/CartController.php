<?php

namespace App\Controller;

use App\Entity\Factuur;
use App\Entity\NAW;
use App\Entity\Product;
use App\Entity\Regel;
use App\Entity\Settings;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Cart controller.
 *
 * @Route("cart")
 */
class CartController extends Controller {
	/**
	 * @Route("/", name="cart")
	 */
	public function indexAction() {

		$em = $this->getDoctrine()->getManager();

		$settings = $em->getRepository( Settings::class )->findAll();
		$naw      = $em->getRepository( NAW::class )->findBy( array( 'user' => $this->getUser() ) );


		// get the cart from  the session
		$session = $this->get( 'request_stack' )->getCurrentRequest()->getSession();
		// $cart = $session->set('cart', '');
		$cart = $session->get( 'cart', array() );


		// fetch the information using query and ids in the cart
		if ( $cart != '' ) {


			if ( isset( $cart ) ) {
				$em      = $this->getDoctrine()->getEntityManager();
				$product = $em->getRepository( Product::class )->findAll();
			} else {
				return $this->render( 'Cart/cart.html.twig', array(
					'empty' => true,
					'naw'   => $naw,
				) );
			}

			return $this->render( 'Cart/cart.html.twig', array(
				'empty'    => false,
				'product'  => $product,
				'naw'      => $naw,
				'settings' => $settings,
			) );
		} else {
			return $this->render( 'Cart/cart.html.twig', array(
				'empty' => true,
				'naw'   => $naw,
			) );
		}
	}

	public function showAction() {
		// get the cart from  the session
		$session = $this->get( 'request_stack' )->getCurrentRequest()->getSession();
		// $cart = $session->set('cart', '');
		$cart = $session->get( 'cart', array() );

		// fetch the information using query and ids in the cart
		if ( $cart != '' ) {


			if ( isset( $cart ) ) {
				$em      = $this->getDoctrine()->getEntityManager();
				$product = $em->getRepository( Product::class )->findAll();
			} else {
				return $this->render( 'Cart/cart.html.twig', array(
					'empty' => true,
				) );
			}


			return $this->render( 'Cart/cart.html.twig', array(
				'empty'   => false,
				'product' => $product,
			) );
		} else {
			return $this->render( 'Cart/cart.html.twig', array(
				'empty' => true,
			) );
		}
	}

	/**
	 * @Route("/add/{id}", name="cart_add")
	 */
	public function addAction( $id ) {
		// fetch the cart
		$em      = $this->getDoctrine()->getManager();
		$product = $em->getRepository( Product::class )->find( $id );
		//print_r($product->getId()); die;
		$session = $this->get( 'request_stack' )->getCurrentRequest()->getSession();
		$cart    = $session->get( 'cart', array() );

		if ( $product->getVoorraad() == 0 ) {
			return $this->render( 'default/error.html.twig', [
				'error'   => 'Product uitverkocht',
				'message' => 'Sorry, dit product is (tijdelijk) uitverkocht.'
			] );
		}

		// check if the $id already exists in it.
		if ( $product == null ) {
			return $this->render( 'default/error.html.twig', [
				'error'   => 'Dit product bestaat niet',
				'message' => 'Het product dat u probeert toe te voegen aan uw winkelwagen bestaat niet (meer) '
			] );
		} else {
			if ( isset( $cart[ $id ] ) ) {

				++ $cart[ $id ];

			} else {
				$cart[ $id ] = 1;
			}

			$session->set( 'cart', $cart );
			//echo('<pre>');
			//print_r($cart); echo ('</pre>');die();
			return $this->redirect( $this->generateUrl( 'cart' ) );

		}
	}

	/**
	 * @Route("/checkout", name="checkout")
	 * @throws \Exception
	 */
	public function checkoutAction() {

		$session = $this->get( 'request_stack' )->getCurrentRequest()->getSession();
		// $cart = $session->set('cart', '');
		$cart = $session->get( 'cart', array() );


		if ( $this->getUser() == null ) {
			return $this->render( 'default/error.html.twig', [
				'error'   => 'Niet ingelogd',
				'message' => 'Log in om af te rekenen'
			] );
		}

		if ( $cart != null ) {

			// aanmaken factuur regel.
			$em         = $this->getDoctrine()->getManager();
			$userAdress = $em->getRepository( User::class )->findOneBy( array( 'id' => $this->getUser()->getId() ) );

			if ( ! $userAdress ) {
				$userAdress = new User();
				$userAdress->setId( $this->getUser()->getId() );
			}

			$factuur = new Factuur();
			$factuur->setDatum( new \DateTime( "now" ) );
			$factuur->setKlantId( $this->getUser() );
			$factuur->setStatus( 'wachtend op betaling' );

			// Regels in DB
			if ( isset( $cart ) ) {
				// $data = $this->get('request_stack')->getCurrentRequest()->request->all();
				$em->persist( $factuur );
				$em->flush();
				// put basket in dbase
				foreach ( $cart as $id => $quantity ) {
					$regel = new Regel();
					$regel->setFactuurId( $factuur );

					$em      = $this->getDoctrine()->getManager();
					$product = $em->getRepository( Product::class )->find( $id );

					$regel->setAantal( $quantity );
					$regel->setProductId( $product );

					$em = $this->getDoctrine()->getManager();
					$em->persist( $regel );
					$em->flush();
				}
			}

			// email ophalen
			$email = $em->getRepository(Settings::class)->findBy(['id'=>2]);

			// Factuur mailen
			$regel   = $em->getRepository( Regel::class )->findBy( [ 'factuurId' => $factuur->getId() ] );
			$product = $em->getRepository( Product::class )->findAll();

			$message = $this->render( 'email/bestelling.html.twig', array(
				'factuur'  => $factuur,
				'products' => $product,
				'regels'   => $regel,
				'klant'    => $factuur->getKlantId()
			) );

			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

			$subject = "Bestelling";

			$headers .= 'From: ' . $email[0]->getEmail() .' '. "\r\n";
			mail( $email[0]->getEmail(), $subject, $message, $headers );


			// voorraad aanpassen
			foreach ( $cart as $product => $quantity ) {
				$em       = $this->getDoctrine()->getManager();
				$products = $em->getRepository( Product::class )->find( $product );

				$oud   = $products->getVoorraad();
				$nieuw = $oud - $quantity;

				$products->setVoorraad( $nieuw );

				$em->persist( $products );
				$em->flush();

			}

			//Cart leeg
			$session->clear();

			return $this->redirectToRoute( 'factuur_show', [ 'id' => $factuur->getId() ] );

		} else {
			return $this->render( 'default/error.html.twig', [
				'code'    => 103,
				'error'   => 'Winkelwagen is leeg',
				'message' => 'Voeg eerst een product toe'
			] );
		}
	}

	/**
	 * @Route("/remove/{id}", name="cart_remove")
	 */
	public function removeAction( $id ) {
		// check the cart
		$session = $this->get( 'request_stack' )->getCurrentRequest()->getSession();
		$cart    = $session->get( 'cart', array() );

//		Check of product wel bestaat
		$em      = $this->getDoctrine()->getManager();
		$product = $em->getRepository( Product::class )->find( $id );

		if ( $product == null ) {
			return $this->render( 'default/error.html.twig', [
				'code'    => 104,
				'error'   => 'Dit product bestaat niet',
				'message' => 'Het product dat u probeert te verwijderen uit uw winkelwagen bestaat niet (meer) '
			] );
		}

		// if it doesn't exist redirect to cart index page. end
		if ( isset( $cart[ $id ] ) == false ) {
			return
				$this->render( 'default/error.html.twig', [
					'code'    => 105,
					'error'   => 'Dit product zit niet in uw winkelwagen',
					'message' => 'U moet het product eerst toevoegen voordat u het kan verwijderen'
				] );
		}

		// check if the $id already exists in it.
		if ( isset( $cart[ $id ] ) ) {
			$cart[ $id ] = $cart[ $id ] - 1;
			if ( $cart[ $id ] < 1 ) {
				unset( $cart[ $id ] );
			}
		} else {
			return $this->redirect( $this->generateUrl( 'cart' ) );
		}

		$session->set( 'cart', $cart );

		//echo('<pre>');
		//print_r($cart); echo ('</pre>');die();

		return $this->redirect( $this->generateUrl( 'cart' ) );
	}

	/**
	 * @Route("/clear" ,name="cart_clear")
	 */
	public function clear() {
		// check the cart
		$session = $this->get( 'request_stack' )->getCurrentRequest()->getSession();
		$cart    = $session->get( 'cart', array() );

		$session->clear();

		return $this->redirectToRoute( 'cart' );

	}

}

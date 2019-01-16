<?php

namespace App\Form;

use App\Entity\NAW;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class NAWType extends AbstractType {
	protected $security;

	public function __construct( Security $security ) {
		$this->security = $security;
	}

	public function buildForm( FormBuilderInterface $builder, array $options ) {

		if ( $this->security->isGranted( "ROLE_SUPER_ADMIN" ) ) {
			$builder->add( 'user', null, array('label'=>'Gebruiker') );
		}

		$builder
			->add( 'Voornaam' )
			->add( 'Achternaam' )
			->add( 'Adres' )
			->add( 'Postcode' )
			->add( 'Woonplaats' );

	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( [
			'data_class' => NAW::class,
		] );
	}
}

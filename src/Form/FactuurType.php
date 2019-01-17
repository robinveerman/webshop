<?php

namespace App\Form;

use App\Entity\Factuur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactuurType extends AbstractType {
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add( 'datum' )
			->add( 'status', ChoiceType::class, [
				'choices' =>
					[
						'wachtend op betaling' => 'Wachtend op betaling',
						'in behandeling'       => 'In behandeling',
						'afgerond'             => 'Afgerond',
						'geannuleerd'          => 'Geannuleerd',
						'terugbetaald'         => 'Terugbetaald',

					]
			] )
			->add( 'regels', CollectionType::class, array(
				'entry_type'    => RegelType::class,
				'entry_options' => array( 'label' => false ),
				'allow_add'     => true,
				'allow_delete'  => true,
				'delete_empty'  => true,
				'prototype'     => true,
				'attr'          => array(
					'class' => 'my-selector',
				),
				'by_reference'  => false
			) )
			->add( 'klantId' );
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( [
			'data_class' => Factuur::class,
		] );
	}
}

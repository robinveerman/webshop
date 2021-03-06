<?php

namespace App\Form;

use App\Entity\Factuur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactuurType extends AbstractType {
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add( 'datum' )
			->add( 'regels', CollectionType::class, array(
				'entry_type'    => RegelType::class,
				'entry_options' => array( 'label' => false ),
				'allow_add'     => true,
				'allow_delete'  => true,
				'delete_empty'  => true,
				'prototype'     => true,
				'by_reference'  => false,
				'attr'          => [
					'class' => 'my-selector',
				],
			) )
			->add( 'klantId' );
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( [
			'data_class' => Factuur::class,
		] );
	}
}

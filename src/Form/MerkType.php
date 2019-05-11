<?php

namespace App\Form;

use App\Entity\Merk;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MerkType extends AbstractType {
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add( 'naam' )
			->add( 'omschrijving' )
			->add( 'imagepath', FileType::class, [
				'data_class' => null,
				'empty_data' => $builder->getForm()->getData( 'imagepath' )->getImagepath(),
				'label'      => 'Logo',
				'required'   => false
			] );
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( [
			'data_class' => Merk::class,
		] );
	}
}

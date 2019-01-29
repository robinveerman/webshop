<?php

namespace App\Form;

use App\Entity\Settings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsType extends AbstractType {
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add( 'AddCartAjax', null )
			->add( 'ClearCart', null )
			->add( 'email', EmailType::class )
			->add( 'themes', ChoiceType::class, [
				'choices' =>
					[
						'4.2.1' => '4.2.1',
						'2.3.2'       => '2.3.2',

					]
			] );
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( [
			'data_class' => Settings::class,
		] );
	}
}

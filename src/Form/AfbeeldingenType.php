<?php

namespace App\Form;

use App\Entity\Afbeeldingen;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AfbeeldingenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add( 'imagepath', FileType::class, [
		        'data_class' => null,
		        'empty_data' => $builder->getForm()->getData( 'imagepath' )->getImagepath(),
		        'label'      => 'Afbeelding',
		        'required'   => false
	        ] )

	        ->add('omschrijving')
            ->add('alttekst')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Afbeeldingen::class,
        ]);
    }
}

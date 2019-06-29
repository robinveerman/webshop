<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('naam')
            ->add('maat')
            ->add('prijs')
            ->add('categorie')
            ->add('relatedProducts', null, ['attr'=>['class'=>'js-example-basic-multiple']])
            ->add('omschrijving')
            ->add('actieprijs')
            ->add('merk')
            ->add('voorraad')
	       ->add('imagepath', null, ['label'=>'Afbeeldingen', 'attr'=>['class'=>'js-example-basic-multiple']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

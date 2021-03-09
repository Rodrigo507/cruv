<?php

namespace App\Form;

use App\Entity\Post;
use PhpParser\Node\Scalar\MagicConst\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('detail')
            ->add('imagen',FileType::class,[
                'label'=>'Imagen para el post',
                'required'=>false,
                'mapped'=>false,
            ])
            ->add('estado',CheckboxType::class,[
                'label'=>'Aprobado ',
                'required'=>false,
                'attr'=>['class'=>'form-check-input']
            ])
            ->add('publisheddate')
//            ->add('owner')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}

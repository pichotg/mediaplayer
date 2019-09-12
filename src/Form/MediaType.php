<?php

namespace App\Form;

use App\Entity\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('media', FileType::class, [
                'label' => 'Media file ( Video : 3gp, webm, ogv / Audio : aac, ogg, mp3 )',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '5000k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                            //Video : 3gp, webm, ogv
                            //Audio : aac, ogg, mp3
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Media file',
                    ])
                ],
            ])
            //->add('extension')
            ->add('vignette', FileType::class, [
                'label' => 'Vignette Image file (jpg, jpeg, png, gif)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '4096k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                            // jpg, jpeg, png, gif
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Media file',
                    ])
                ],
            ])
            ->add('send', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Genre;
use App\Entity\Media;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('genre', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'name',
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                }
            ])
            ->add('media', FileType::class, [
                'label' => 'Media file ( Video : 3gp, webm, ogv / Audio : aac, ogg, mp3 )',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '5000k',
                        'mimeTypes' => [
                            //Video : 3gp, webm, ogv
                            'video/webm',
                            'video/ogg',
                            'video/ogv',
                            'video/3gp',
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
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/gif',
                            // jpg, jpeg, png, gif
                        ],
                        'mimeTypesMessage' => 'Please upload a valid vignette Media file',
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

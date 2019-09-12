<?php

namespace App\Controller;

use App\Entity\Media;
use App\Form\MediaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends Controller
{
    /**
     * @Route("/media", name="media")
     */
    public function list(Request $request, EntityManagerInterface $em)
    {
        $lis_media = $em->getRepository(Media::class)->findAll();

        return $this->render('media/index.html.twig', [
            'page_name' => 'Media List',
            'medias' => $lis_media,
        ]);
    }

    /**
     * @Route("/media/add", name="add_media")
     */
    public function add(Request $request, EntityManagerInterface $em)
    {

        $media = new Media();
        // set date creation
        $media->setDate(new \DateTime());

        // creat form
        $form = $this->createForm(MediaType::class, $media);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $idea = $form->getData();
            $fileMedia = $form['media']->getData();

            if ($fileMedia) {
                $originalFilename = pathinfo($fileMedia->getClientOriginalName(), PATHINFO_FILENAME);
                $fileExtension = $fileMedia->guessExtension();
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' .$fileExtension ;
                // Move the file to the directory where brochures are stored
                try {
                    $fileMedia->move(
                        '../public/files/media',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $media->setExtension($fileExtension);
                $media->setMedia($newFilename);
            }

            $fileVignette = $form['vignette']->getData();
            if ($fileVignette) {
                $originalFilename = pathinfo($fileVignette->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $fileVignette->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $fileVignette->move(
                        '../public/files/vignette',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $media->setVignette($newFilename);
            }
            // ... persist the $product variable or any other work
            $em->persist($media);
            $em->flush();
            $this->addFlash('success', 'Media successfully added !');

            return $this->redirectToRoute('add_media');
        }
        return $this->render('media/add_media.html.twig', [
            'page_name' => 'Media Add',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/media/delete{id}", name="delete_media" , requirements={"id"="\d+"})
     */
    public function delete(Media $media,Request $request, EntityManagerInterface $em)
    {
        $media = $em->getRepository(Media::class)->find($request->get('id'));

        $filename = $media->getMedia();
        $filesystem = new Filesystem();
        $filesystem->remove($filename);

        $filename = $media->getVignette();
        $filesystem = new Filesystem();
        $filesystem->remove($filename);

        $em->remove($media);
        $em->flush();
        $this->addFlash('success', 'Media was deleted !');

        return $this->redirectToRoute('media');
    }
}
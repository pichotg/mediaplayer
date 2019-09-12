<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends Controller
{
    private $_genrelist = null;

    /**
     * @Route("/genre", name="genre")
     */
    public function list(Request $request, EntityManagerInterface $em)
    {
        $this->_genrelist = $em->getRepository(Genre::class)->findAll();

        return $this->render('genre/index.html.twig', [
            'page_name' => 'Genre',
            'genres' => $this->_genrelist
        ]);
    }

    /**
     * @Route("/genre/add" , name="add_genre")
     */
    public function add(Request $request, EntityManagerInterface $em){
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);
        $form -> handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $genre = $form->getData();
            $em->persist($genre);
            $em->flush();
            $this->addFlash('success', 'Genre successfully added !');
            return $this->redirectToRoute('genre');
        }
        return $this->render('genre/add_genre.html.twig', [
            'page_name' => 'Genre Add',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/genre/{id}", name="edit_genre")
     */
    public function edit(Genre $genre, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $genre = $form->getData();

            $em->persist($genre);
            $em->flush();
            $this->addFlash('success', 'Genre successfully modified !');

            $this->_genrelist = $em->getRepository(Genre::class)->findAll();

            return $this->redirectToRoute('genre');
        }

        return $this->render('genre/genre_edit.html.twig', [
            'page_name' => 'Genre Edit',
            'genre' => $genre,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/genre/delete/{id}", name="delete_genre" , requirements={"id"="\d+"})
     */
    public function delete(Genre $genre,Request $request, EntityManagerInterface $em)
    {
        $genre = $em->getRepository(Genre::class)->find($request->get('id'));

        $em->remove($genre);
        $em->flush();
        $this->addFlash('success', 'Genre was deleted !');

        return $this->redirectToRoute('genre');
    }
}

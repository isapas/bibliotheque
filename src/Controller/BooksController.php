<?php

namespace App\Controller;

use App\Entity\Books;
use App\Entity\Users;
use App\Entity\Category;
use App\Form\SortType;
use App\Form\BorrowType;
use App\Form\BooksType;
use App\Repository\BooksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

        /**
        * Require ROLE_ADMIN for *every* controller method in this class.
        *
        * @IsGranted("ROLE_ADMIN")
        */

/**
 * @Route("/books")
 */
class BooksController extends AbstractController
{
    /**
     * @Route("/", name="books_index", methods={"GET", "POST"})
     */
    public function index(BooksRepository $booksRepository, Request $request): Response
    {
        $form = $this->createForm(SortType::class);
        $form->handleRequest($request);
        //si le form est envoyer je le stoque dans la variable $books
        if ($form->isSubmitted() && $form->isValid()) {
            //stock les données rentrées dans le formulaire dans la variable $category
            $category = $form->getData();
            dump($category);
            //stocker la jointure dans la variable books
            $books = $booksRepository->findByCategory($category['category']);
        }
        else {
            //si y'a rien dans le form tout les livres seront affichés
            $books = $booksRepository->findAll();
        }
        //afficher la listes des livres trier en chargean le formulaire
        return $this->render('books/index.html.twig', [
            'books' => $books,
            'form' => $form->createView()

        ]);
    }

        /**
     * @Route("/new", name="books_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $book = new Books();
        $form = $this->createForm(BooksType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();
            return $this->redirectToRoute('books_index');
            
        }

        return $this->render('books/new.html.twig', [
            'book' => $book,
            'form' => $form->createView()
        ]);
    }

        /**
     * @Route("/{id}", name="books_show", methods={"GET", "POST"})
     */
    public function show($id, Request $request, Books $book): Response
    {
        $form = $this->createForm(BorrowType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //stock les données rentrées dans le formulaire dans la variable $data
            $data = $form->getData();
            //on appel le setter User pour pouvoir lui donner une nouvelle valeur
            // $book->setUsers(null);            
            // $data->persist($book);
            // $book->flush();
            // dump($data);
            return $this->redirectToRoute("books_index");
        }
        return $this->render('books/show.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }


    /** 
     * @Route("/{id}/edit", name="books_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Books $book): Response
    {
        $form = $this->createForm(BooksType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('books_index', [
                'id' => $book->getId(),
            ]);
        }

        return $this->render('books/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="books_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Books $book): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($book);
            $entityManager->flush();
        }
        return $this->redirectToRoute('books_index');
    }
    
}
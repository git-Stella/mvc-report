<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Library;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\LibraryRepository;

/**
 * Controller class for the library routes.
 */
final class LibraryController extends AbstractController
{
    /**
     * Route for the library landing page.
     */
    #[Route('/library', name: 'app_library')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'Library',
        ]);
    }
    /**
     * Route to view all books in the library.
     */
    #[Route('/library/view', name: 'library_view_all')]
    public function viewAllLibrary(
        LibraryRepository $libraryRepository
    ): Response {
        $library = $libraryRepository
            ->findAll();

        $data = [
            'library' => $library
        ];
        return $this->render('library/view.html.twig', $data);
    }
    /**
     * Route to view all books in the library in a JSON api format.
     */
    #[Route('/api/library/books', name: 'api_library')]
    public function viewApiLibrary(
        LibraryRepository $libraryRepository
    ): Response {
        $library = $libraryRepository
            ->findAll();

        $books = [];
        foreach ($library as $book) {
            $books[] = "Title: " .
            $book->getTitle() . " Author: " .
            $book->getAuthor() . " ISBN: " .
            $book->getIsbn() . " Image url: " .
            $book->getPicture();
        }

        $data = [
            'library' => $books
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    /**
     * Route to look at a specific book in JSON api format, filtered by its isbn.
     */
    #[Route('/api/library/book/{isbn}', name: 'api_isbn')]
    public function viewApiBook(
        LibraryRepository $libraryRepository,
        string $isbn
    ): Response {
        $book = $libraryRepository
            ->findOneByIsbn($isbn);
        $data = [
            'title' => $book->getTitle(),
            'author' => $book->getAuthor(),
            'isbn' => $book->getIsbn(),
            'image' => $book->getPicture()
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    /**
     * Route to view one specific book in the library.
     */
    #[Route('/library/one/{id}', name: 'library_view_id')]
    public function viewOneLibrary(
        LibraryRepository $libraryRepository,
        int $id
    ): Response {
        $library = $libraryRepository
            ->find($id);

        $data = [
            'book' => $library
        ];

        return $this->render('library/one.html.twig', $data);
    }
    /**
     * Route to html form for creating a new book for the library.
     */
    #[Route('/library/create', name: 'app_library_create', methods: ['GET'])]
    public function create_book(): Response
    {
        return $this->render('library/create.html.twig');
    }
    /**
     * Route to create the book based on the html form.
     */
    #[Route('/library/create_new', name: 'post_library_create', methods: ['POST'])]
    public function create_a_book(
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        //$numDice = $request->request->get('num_dices');
        $entityManager = $doctrine->getManager();
        $title = $request->request->get('title');
        $author = $request->request->get('author');
        $isbn = $request->request->get('isbn');
        $image = $request->request->get('image');
        $library = new Library();

        $library->setTitle($title);
        $library->setIsbn($isbn);
        $library->setAuthor($author);
        $library->setPicture($image);

        $this->addFlash(
            'notice',
            'Book added to library'
        );
        $entityManager->persist($library);
        $entityManager->flush();
        //return $this->render('library/create.html.twig');
        return $this->redirectToRoute('library_view_all');
    }
    /**
     * Route for the html form to update a book.
     */
    #[Route('/library/update/{id}', name: 'app_library_update', methods: ['GET'])]
    public function update_book(
        LibraryRepository $libraryRepository,
        int $id
    ): Response {
        $library = $libraryRepository
            ->find($id);

        $data = [
            'book' => $library
        ];
        return $this->render('library/update.html.twig', $data);
    }
    /**
     * Route to update the book based on the html form in the previous route.
     */
    #[Route('/library/update_post', name: 'post_library_update', methods: ['POST'])]
    public function update_the_book(
        //LibraryRepository $libraryRepository,
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();
        $id = $request->request->get('id');
        $title = $request->request->get('title');
        $author = $request->request->get('author');
        $isbn = $request->request->get('isbn');
        $image = $request->request->get('image');
        $library = $entityManager->getRepository(Library::class)->find($id);

        if (!$library) {
            $this->addFlash(
                'warning',
                'Book does not exist'
            );
            return $this->redirectToRoute('library_view_all');
        }

        $library->setTitle($title);
        $library->setIsbn($isbn);
        $library->setAuthor($author);
        $library->setPicture($image);

        $this->addFlash(
            'notice',
            'Book updated'
        );
        //$entityManager->persist($library);
        $entityManager->flush();
        //return $this->render('library/create.html.twig');
        return $this->redirectToRoute('library_view_all');
    }
    /**
     * Route to for deleting a book.
     */
    #[Route('/library/delete/{id}', name: 'app_library_delete', methods: ['GET'])]
    public function delete_book(
        LibraryRepository $libraryRepository,
        int $id
    ): Response {
        $library = $libraryRepository
            ->find($id);

        $data = [
            'book' => $library
        ];
        return $this->render('library/delete.html.twig', $data);
    }
    /**
     * Route for deleting the book if it was confirmed on the previous one.
     */
    #[Route('/library/delete_post', name: 'post_library_delete', methods: ['POST'])]
    public function delete_the_book(
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();
        $id = $request->request->get('id');
        $book = $entityManager->getRepository(Library::class)->find($id);

        if (!$book) {
            $this->addFlash(
                'warning',
                'Book does not exist'
            );
            return $this->redirectToRoute('library_view_all');
        }

        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('library_view_all');
    }
}

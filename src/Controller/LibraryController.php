<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Library;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\LibraryRepository;

final class LibraryController extends AbstractController
{
    #[Route('/library', name: 'app_library')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'Library',
        ]);
    }
    #[Route('/library/view', name: 'library_view_all')]
    public function viewAllLibrary(
        LibraryRepository $libraryRepository
    ): Response {
        $library = $libraryRepository
            ->findAll();

        $data = [
            'library' => $library
        ];
        //gotta change the view here to show a table or something

        return $this->render('library/view.html.twig', $data);
    }
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
    #[Route('/library/create', name: 'app_library_create', methods: ['GET'])]
    public function create_book(): Response
    {
        return $this->render('library/create.html.twig');
    }
    #[Route('/library/create_new', name: 'post_library_create', methods: ['POST'])]
    public function create_a_book(
        Request $request,
        ManagerRegistry $doctrine
    ): Response
    {
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
            'This worked'
        );
        $entityManager->persist($library);
        $entityManager->flush();
        //return $this->render('library/create.html.twig');
        return $this->redirectToRoute('library_view_all');
    }
    #[Route('/library/update/{id}', name: 'app_library_update', methods: ['GET'])]
    public function update_book(
        LibraryRepository $libraryRepository,
        int $id
    ): Response
    {
        $library = $libraryRepository
            ->find($id);

        $data = [
            'book' => $library
        ];
        return $this->render('library/create.html.twig');
    }
    #[Route('/library/delete/{id}', name: 'app_library_delete', methods: ['GET'])]
    public function delete_book(
        LibraryRepository $libraryRepository,
        int $id
    ): Response
    {
        $library = $libraryRepository
            ->find($id);

        $data = [
            'book' => $library
        ];
        return $this->render('library/create.html.twig');
    }
}

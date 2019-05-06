<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Book;
use AppBundle\Entity\Author;
use Doctrine\DBAL\DBALException;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;


class BookController extends Controller
{

    /**
     * @Route("/", name="mainpage")
     * @return Response
     *
     * @throws DBALException
     */

    public function index(){
        $books = $this->getDoctrine()->getRepository(Book::class)->findAll();
        foreach ($books as $book){
            $book->setAuthors($this->getDoctrine()->getRepository
            (Author::class)->findAllAuthorsByBookId($book->getId()));
        }
        return $this->render('HelloWorld/HelloWorld.html.twig', array('books' => $books));
    }

    /**
     * @Route("/book/{id}", name="book")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function getBook(Request $request, $id){
        $book = $this->getDoctrine()->getRepository(Book::class)->findOneBy(['id' => $id]);
        $form = $this->createFormBuilder($book)->add('delete', SubmitType::class, array('label' => 'Удалить книгу'))
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($book);
            $em->flush();
            return $this->redirectToRoute('mainpage');
        }

        return $this->render('HelloWorld/book.html.twig', array('book' => $book, 'form' => $form->createView()));
    }

    /**
     * @Route("/editbook/{id}", name="editBook")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function editBook(Request $request, $id){
        $book = $this->getDoctrine()->getRepository(Book::class)->findOneBy(['id' => $id]);
        $form = $this->createFormBuilder($book)
            ->add('name', TextType::class)
            ->add('caption', TextType::class)
            ->add('picture_url', TextType::class)
            ->add('publicyear', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Редактировать книгу'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $book = $form->getData();
            $em->flush();
            return $this->redirectToRoute('book', ['id' => $book->getId()]);
        }

        return $this->render('HelloWorld/editBook.html.twig', array(
            'form' => $form->createView()));
    }


    /**
     * @Route("/addbook", name="addBook")
     * @param Request $request
     * @return string
     */

    public function goToAddBook(Request $request){
        //  создаёт задачу и задаёт в ней фиктивные данные для этого примера
        $book = new Book();

        $form = $this->createFormBuilder($book)
            ->add('name', TextType::class)
            ->add('caption', TextType::class)
            ->add('picture_url', TextType::class)
            ->add('publicyear', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Добавить книгу'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('mainpage');
        }

        return $this->render('HelloWorld/addbook.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/deletebook/{id}", name="deletebook")
     * @param $id
     * @return RedirectResponse
     */

    public function deleteBook($id){
        $em = $this->getDoctrine()->getManager();
        $book = $this->getDoctrine()->getRepository(Book::class)->find($id);
        $em->remove($book);
        $em->flush();
        return $this->redirectToRoute('mainpage');
    }

}
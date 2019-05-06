<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Book;
use AppBundle\Entity\Author;
use Doctrine\DBAL\DBALException;
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
     */
    public function getBook($id){
        $book = $this->getDoctrine()->getRepository(Book::class)->findOneBy(['id' => $id]);
        return $this->render('HelloWorld/book.html.twig', array('book' => $book));
    }
}
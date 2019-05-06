<?php


namespace AppBundle\Repository;

use AppBundle\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\Tests\Service;

class AuthorRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Author::class);
    }

    /**
     * @param $name
     * @return array
     * @throws DBALException
     */
    public function findAllAuthorsByBookId($name) : array
    {
        error_reporting(0);
        $query = $this->getEntityManager()->getConnection()->prepare('
        SELECT author_id FROM author_to_book
        WHERE book_name = :name;
        ');
        $query->bindValue('name', $name);
        $query->execute();
        $conn = $this->getEntityManager()->getConnection();
        $ids = $query->fetchAll();
        $sql = '
        SELECT * FROM authors WHERE id IN (:ids)
        ';
        $stmt = $conn->prepare($sql);
        $ids_clear = array_keys($ids);
        $stmt->bindValue('ids', $ids_clear);
        $stmt->execute();
        return $stmt->fetchAll();
    }

}
<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Author;
use ArrayObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use function Sodium\add;
use Symfony\Bridge\Doctrine\RegistryInterface;

class AuthorRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Author::class);
    }

    /**
     * @param $id
     * @return array
     * @throws DBALException
     */
    public function findAllAuthorsByBookId($id): array
    {
        error_reporting(0);
        $query = $this->getEntityManager()->getConnection()->prepare(
            '
        SELECT author_id FROM author_to_book
        WHERE book_name = :name;
        '
        );
        $query->bindValue('name', $id);
        $query->execute();
        $ids = $query->fetchAll();
        $sql =
            '
        SELECT author_name FROM authors WHERE id IN (?);
        '
        ;
        $ids_clear = array();
        foreach ($ids as $key => $value) {
            foreach ($value as $k => $val){
                $ids_clear[] = $val;
            }
        }
        $types = [Connection::PARAM_INT_ARRAY];
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql, [$ids_clear], $types);
        $res = $stmt->fetchAll();
        $authors = array();
        foreach ($res as $key => $value){
            foreach ($value as $k => $val){
                array_push($authors, $val);
            }
        }
        return $authors;
    }
}
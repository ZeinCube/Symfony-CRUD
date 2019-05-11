<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Author;
use ArrayObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * @return ArrayObject
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
        $stmt = $this->getEntityManager()->getConnection()->prepare(
            '
        SELECT author_name FROM authors WHERE id IN (:ids);
        '
        );
        $ids_clear = [];
        foreach ($ids as $key => $value) {
            array_push($ids_clear, $value);
        }
        $stmt->bindValue('ids', $ids_clear);
        $stmt->execute();
        $res = $stmt->fetchAll();
        return $res;
    }
}
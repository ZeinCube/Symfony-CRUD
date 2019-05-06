<?php

namespace AppBundle\DBQuery;

class DB
{

    public function findAll() : array {
        $manager = $this->getEntityManager();
        $query = $manager->createQuery('SELECT * FROM book_store 
                            INNER JOIN author_to_book WHERE book_name = book_store.name');
        return $query->execute();
    }

}
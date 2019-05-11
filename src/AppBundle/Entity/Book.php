<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="book_store")
 */
class Book
{
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $caption;

    /**
     * @ORM\Column(type="integer")
     */
    private $public_year;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $picture_url;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @ORM\Column(type="string", length=100)
     */

    /**
     * @return mixed
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param mixed $caption
     */
    public function setCaption($caption): void
    {
        $this->caption = $caption;
    }

    /**
     * @return mixed
     */
    public function getPublicYear()
    {
        return $this->public_year;
    }

    /**
     * @param mixed $public_year
     */
    public function setPublicYear($public_year): void
    {
        $this->public_year = $public_year;
    }

    /**
     * @return mixed
     */
    public function getPictureUrl()
    {
        return $this->picture_url;
    }

    /**
     * @param mixed $picture_url
     */
    public function setPictureUrl($picture_url): void
    {
        $this->picture_url = $picture_url;
    }

    private $authors;

    public function getAuthors() : array
    {
        return $this->authors;
    }

    public function setAuthors($authors_array): void
    {
        $this->authors = $authors_array;
    }
}
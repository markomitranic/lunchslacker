<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document()
 */
class Meal
{

    /**
     * @var string
     *
     * @MongoDB\String()
     * @MongoDB\Id()
     */
    private $id;

    /**
     * @var string
     *
     * @MongoDB\String()
     */
    private $day;

    /**
     * @var string
     *
     * @MongoDB\String()
     */
    private $name;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Meal
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Meal
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param string $day
     * @return Meal
     */
    public function setDay($day)
    {
        $this->day = $day;
        return $this;
    }

}
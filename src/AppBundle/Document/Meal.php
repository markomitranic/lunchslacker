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
     * @var User
     * @MongoDB\ReferenceOne(targetDocument="User")
     */
    private $user;

    /**
     * @var string
     *
     * @MongoDB\String()
     */
    private $meal;

    /**
     * @var string
     *
     * @MongoDB\Date()
     */
    private $date;

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
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Meal
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getMeal()
    {
        return $this->meal;
    }

    /**
     * @param string $meal
     * @return Meal
     */
    public function setMeal($meal)
    {
        $this->meal = $meal;
        return $this;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $date
     * @return Meal
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

}
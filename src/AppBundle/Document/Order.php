<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document()
 */
class Order
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
     * @var Meal
     *
     * @MongoDB\ReferenceOne(targetDocument="Meal")
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
     * @return Order
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
     * @return Order
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Meal
     */
    public function getMeal()
    {
        return $this->meal;
    }

    /**
     * @param Meal $meal
     * @return Order
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
     * @return Order
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

}
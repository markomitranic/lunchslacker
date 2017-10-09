<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="AppBundle\Document\OrderRepository")
 *
 */
class Order
{

    /**
     * @var string
     *
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
     * @MongoDB\Field(type="string")
     */
    private $day;

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
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param string $day
     * @return Order
     */
    public function setDay($day)
    {
        $this->day = $day;
        return $this;
    }

}
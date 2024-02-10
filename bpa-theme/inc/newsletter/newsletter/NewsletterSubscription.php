<?php

class NewsletterSubscriptionTitles
{
    const MR = 'mr';
    const MRS = 'mrs';
    const OTHER = 'other';
}
class NewsletterSubscription
{

    private $id;
    private $email;
    private $subscribed;
    private $acknowledged;
    private $title;
    private $firstname;
    private $lastname;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getSubscribed()
    {
        return $this->subscribed;
    }

    /**
     * @param mixed $subscribed
     */
    public function setSubscribed($subscribed)
    {
        $this->subscribed = $subscribed;
    }

    /**
     * @return mixed
     */
    public function getAcknowledged()
    {
        return $this->acknowledged;
    }

    /**
     * @param mixed $acknowledged
     */
    public function setAcknowledged($acknowledged)
    {
        $this->acknowledged = $acknowledged;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }
}
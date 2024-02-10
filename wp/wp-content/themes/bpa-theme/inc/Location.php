<?php


class Location
{
    private $id;
    private $name;
    private $street;
    private $city;
    private $postalCode;

    /**
     * Location constructor.
     * @param $name
     * @param $street
     * @param $city
     * @param $postalCode
     */
    public function __construct($name = "", $street = "", $city = "", $postalCode = "")
    {
        $this->name = $name;
        $this->street = $street;
        $this->city = $city;
        $this->postalCode = $postalCode;
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
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param mixed $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

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

    public function urlParam(){
        return urlencode( $this->street . ", " . $this->postalCode. " " . $this->city );
    }

}
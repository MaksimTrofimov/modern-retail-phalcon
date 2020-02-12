<?php

namespace App\Models;

class Address extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $address_id;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $city;

    /**
     *
     * @var integer
     */
    public $postcode;

    /**
     *
     * @var string
     */
    public $region;

    /**
     *
     * @var string
     */
    public $street;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("modern_retail");
        $this->setSource("Address");
        $this->belongsTo('user_id', 'App\Models\User', 'user_id', ['alias' => 'User']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Address[]|Address|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Address|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}

<?php

namespace App\Models;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\PresenceOf;

class User extends \Phalcon\Mvc\Model
{
    const TYPE_USER_ADMIN = 0;
    const TYPE_USER = ['admin', 'client'];

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $firstname;

    /**
     *
     * @var string
     */
    public $lastname;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var integer
     */
    public $user_type;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var integer
     */
    public $created_at;

    /**
     * Validations and business logic
     *
     * @return boolean
     */

    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'firstname',
            new PresenceOf(
                [
                    'message' => 'The firstname is required',
                ]
            )
        );

        $validator->add(
            'lastname',
            new PresenceOf(
                [
                    'message' => 'The lastanme is required',
                ]
            )
        );

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
                ],
            )
        );

        $validator->add(
            'email', new Uniqueness(
                [
                    'message' => 'Duplicate entry for ":fields" field',
                ]
            )
        );

        $validator->add(
            'password',
            new PresenceOf(
                [
                    'message' => 'The password is required',
                ]
            )
        );

        $validator->setFilters('firstname', 'trim');
        $validator->setFilters('lastanme', 'trim');
        $validator->setFilters('email', 'trim');
        $validator->setFilters('password', 'trim');

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("modern_retail");
        $this->setSource("User");
        $this->hasMany(
            'user_id',
            'App\Models\Address',
            'user_id',
            ['alias' => 'Address']
        );
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return User[]|User|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function typeUser()
    {
        $this->user_type = self::TYPE_USER[$this->user_type];
    }

    public function convertDate()
    {
        $this->created_at = date('Y-m-d H:i:s', (int)$this->created_at);
    }

}

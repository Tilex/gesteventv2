<?php

/**
 * Created by PhpStorm.
 * User: Jean Baptiste
 * Date: 16/09/2016
 * Time: 11:38
 */

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\PresenceOf;


class RegisterForm extends Form
{
    public function initialize($entity = null, $options = null){
        //Login
        $login = new Text('login');
        $login->setLabel('Login');
        $login->setFilters(array('alpha'));
        $login->addValidators(array(
            new PresenceOf(array(
                'message' => 'Please enter your desired user name'
            ))
        ));
        $this->add($login);

        // Password
        $password = new Password('password');
        $password->setLabel('Password');
        $password->addValidators(array(
            new PresenceOf(array(
                'message' => 'Password is required'
            ))
        ));
        $this->add($password);
    }
}
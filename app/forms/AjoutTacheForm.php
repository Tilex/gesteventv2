<?php
use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Date;
use Phalcon\Validation\Validator\Email;
use Phalcon\Forms\Element\Check ;
use Phalcon\Forms\Element\Select ;
use Phalcon\Paginator\Adapter\Model as Paginator;


/**
 * Created by PhpStorm.
 * User: Octaedra
 * Date: 16/01/2017
 * Time: 14:02
 */
class AjoutTacheForm extends Form
{
    public function initialize($entity = null, $options = null){
        $numberPage = 1;
        $projet = Projet::find();
        $acteur = Acteur::find();
        $categorie = Categorie::find();

        $paginator = new Paginator([
            'data' => $projet,
            'limit'=> 1000000000,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();

        $paginator2 = new Paginator([
            'data' => $acteur,
            'limit'=> 100000000,
            'page' => $numberPage
        ]);

        $this->view->page2 = $paginator2->getPaginate();

        $paginator3 = new Paginator([
            'data' => $categorie,
            'limit'=> 1000000000,
            'page' => $numberPage
        ]);

        $this->view->page3 = $paginator3->getPaginate();
    }
}
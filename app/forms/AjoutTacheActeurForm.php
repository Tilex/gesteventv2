<?php
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Forms\Form;
/**
 * Created by PhpStorm.
 * User: Octaedra
 * Date: 17/01/2017
 * Time: 16:03
 */
class AjoutTacheActeurForm extends Form
{
    public function initialize($entity = null, $options = null){
        $numberPage = 1;
        $projet = Projet::find();

        $paginator = new Paginator([
            'data' => $projet,
            'limit'=> 1000000000,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();

        $categorie = Categorie::find();

        $paginator2 = new Paginator([
            'data' => $categorie,
            'limit'=> 10000000,
            'page' => $numberPage
        ]);

        $this->view->page2 = $paginator2->getPaginate();


    }
}
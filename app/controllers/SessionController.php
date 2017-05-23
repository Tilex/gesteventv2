<?php
use Phalcon\Session\Bag as SessionBag;
/**
 * Created by PhpStorm.
 * User: Octaedra
 * Date: 10/01/2017
 * Time: 14:51
 */
class SessionController extends ControllerBase
{
        public function indexAction()
    {
        $form = new RegisterForm();
        $this->assets->addCss("css/bootstrap.min.css");
        $this->assets->addCss("css/bootstrap.min.css");
        $this->view->form = $form;
    }

    public function acteurAction(){
        $parameters["acteur"] = "id";
    }

    public function adminAction(){

    }

}
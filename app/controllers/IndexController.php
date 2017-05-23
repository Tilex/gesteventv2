<?php

use Phalcon\Mvc\Model\Query\Builder;


class IndexController extends ControllerBase
{

    public function indexAction()
    {
        $this->session->destroy();
        $form = new RegisterForm();
        $this->view->form = $form;
    }


    public function startAction()
    {
        if ($this->request->getPost()) {
            $login = $this->request->getPost('login');
            $mdp = $this->request->getPost('password');

            $user = Acteur::findFirst(array(
                "login = :login: AND mdp = :mdp:",
                'bind' => array('login' => $login, 'mdp' => $mdp)
            ));
            if ($user != false) {
                $this->session->set('id', $user->id);
                if ($user->categorie == "admin") {
                    $this->dispatcher->forward([
                        "controller" => "session",
                        "action" => "admin"
                    ]);
                }
                if ($user->categorie == "acteur") {
                    $this->dispatcher->forward([
                        "controller" => "session",
                        "action" => "acteur"
                    ]);
                }
            } else {
                $this->dispatcher->forward([
                    "controller" => "index",
                    "action" => "index"
                ]);
            }
        }
    }
}
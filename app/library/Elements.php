<?php

/**
 * Created by PhpStorm.
 * User: Jean Baptiste
 * Date: 22/09/2016
 * Time: 16:19
 */
use Phalcon\Mvc\User\Component;

class Elements extends Component
{
    private $_tabMultipleAdmin = array(
        'Accueil' => array(
            'Accueil' => array(
                'controller' => 'session',
                'action' => 'admin',
                'any' => false
            )
        ),
        'Projets' => array(
            'Projets' => array(
                'controller' => 'projet',
                'action' => 'indexAdmin',
                'any' => false
            ),

        ),
        'Acteurs' => array(
            'Acteurs' => array(
                'controller' => 'acteur',
                'action' => 'index',
                'any' => false
            ),

        ),
        'Tâches' => array(
            'Tâches' => array(
                'controller' => 'tache',
                'action' => 'indexAdmin',
                'any' => false
            )
        ),
        'Ajout tâche' => array(
            'Ajout tâche' => array(
                'controller' => 'ajoutTache',
                'action' => 'indexAdmin',
                'any' => false
            )
        ),
        'Récapitulatif journée' => array(
            'Récapitulatif journée' => array(
                'controller' => 'recapJournee',
                'action' => 'index',
                'any' => false
            )
        ),
        'Déconnexion' => array(
            'Déconnexion' => array(
                'controller' => 'index',
                'action' => 'index',
                'any' => false
            )
        )
    );

    private $_tabMultipleActeur = array(
        'Accueil' => array(
            'Accueil' => array(
                'controller' => 'session',
                'action' => 'acteur',
                'any' => false
            )
        ),
        'Mes Projets' => array(
            'Mes Projets' => array(
                'controller' => 'projet',
                'action' => 'indexActeur',
                'any' => false
            ),
        ),
        'Mes Tâches' => array(
            'Mes Tâches' => array(
                'controller' => 'tache',
                'action' => 'indexActeur',
                'any' => false
            )
        ),
        'Ajout tâche' => array(
            'Ajout tâche' => array(
                'controller' => 'ajoutTache',
                'action' => 'indexActeur',
                'any' => false
            )
        ),
        'Déconnexion' => array(
            'Déconnexion' => array(
                'controller' => 'index',
                'action' => 'index',
                'any' => false
            )
        )
    );

    /**
     * Returns menu tabs
     */
    public function getTabsAdmin()
    {
        $controllerName = $this->view->getControllerName();
        $actionName = $this->view->getActionName();
        echo '<ul class="nav nav-tabs">';

        foreach ($this->_tabMultipleAdmin as $position => $menu) {
            foreach ($menu as $controller => $option) {
                if ($controllerName == $controller) {
                    echo '<li class="active">';
                } else {
                    echo '<li>';
                }
                echo $this->tag->linkTo($option['controller'] . '/' . $option['action'], $controller), '</li>';
                echo '</li>';
            }
        }

        echo '<ul class="nav text-right"><h4>Administrateur</h4></ul>';
        echo '</ul>';

    }

    public function getTabsActeur()
    {
        $controllerName = $this->view->getControllerName();
        $actionName = $this->view->getActionName();
        echo '<ul class="nav nav-tabs">';

        foreach ($this->_tabMultipleActeur as $position => $menu) {
            foreach ($menu as $controller => $option) {
                if ($controllerName == $controller) {
                    echo '<li class="active">';
                } else {
                    echo '<li>';
                }
                echo $this->tag->linkTo($option['controller'] . '/' . $option['action'], $controller), '</li>';
                echo '</li>';
            }
        }
        echo '<ul class="nav text-right"><h4>acteur</h4></ul>';
        echo '</ul>';
    }
}
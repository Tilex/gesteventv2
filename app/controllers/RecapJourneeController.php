<?php
use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Paginator\Adapter\Model as Paginator;
/**
 * Created by PhpStorm.
 * User: Octaedra
 * Date: 25/01/2017
 * Time: 11:35
 */
class RecapJourneeController extends ControllerBase
{
    public function indexAction(){
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Tache', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $dateDuJour = date("y.m.d");

        $builder = new Builder();
        $builder->columns('*');
        $builder->From('acteur');
        $builder->join('lien','acteur.id = lien.id_acteur');
        $builder->join('projet','lien.id_projet = projet.id');
        $builder->join('tache','lien.id_tache = tache.id');
        $builder->join('categorie','lien.id_categorie = categorie.id');
        $builder->where('tache.dateMaj = :tacheDateMaj:',array('tacheDateMaj'=>$dateDuJour));
        $query = $builder->getQuery();
        $result = $query->execute();

        $res = array();
        foreach ($result as $r) {

            $dateDebut = $r->tache->dateDebut;
            $dateFin = $r->tache->dateFin;

            if ($dateDebut != NULL) {
                $a_dateDebut = explode("-", $dateDebut);
                $dateDebut = $a_dateDebut[2] . "-" . $a_dateDebut[1] . "-" . $a_dateDebut[0];
                $r->tache->dateDebut = $dateDebut;

            } else {
                $r->tache->dateDebut = "";
            }
            //var_dump($r->projet->dateDebut);

            if ($dateFin != NULL) {
                $a_dateFin = explode("-", $dateFin);
                $dateFin = $a_dateFin[2] . "-" . $a_dateFin[1] . "-" . $a_dateFin[0];
                $r->tache->dateFin = $dateFin;
            } else {
                $r->tache->dateFin = "";
            }


            $res[$r->tache->id]=array('dateDebut'=>$dateDebut,'dateFin'=>$dateFin);
        }

        $this->view->setVar('date',$res);

        $paginator = new Paginator([
            'data' => $result,
            'limit'=> 1000000,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();


    }


    public function getInfosProjetAction($id){
        $rep='';
        $idProjet = $id;
        $builder = new Builder();
        $builder->columns('*');
        $builder->distinct(true);
        $builder->From('projet');
        $builder->join('acteur','projet.chefProjet = acteur.id');
        $builder->where('projet.id = :idProjet:',array('idProjet'=>$idProjet));
        $query = $builder->getQuery();
        $result = $query->execute();


        foreach ($result as $r){
            $rep.='<ul class = list-group>
                        <li class="list-group-item list-group-item-warning">Chef du projet : '. $r->acteur->nom." ".$r->acteur->prenom .'</li>
                        <li class="list-group-item list-group-item-info">Etat : '.$r->projet->etat.'</li>
                        <li class="list-group-item list-group-item-info">Avancée : '. 100*$r->projet->avancee.'%</li>
                        <li class="list-group-item list-group-item-info">Date Debut : '.$r->projet->dateDebut.'</li>
                        <li class="list-group-item list-group-item-info">Date Fin Prévue : '.$r->projet->dateFinPrevue.'</li>
                        <li class="list-group-item list-group-item-info">Date Fin : '.$r->projet->dateFin.'</li>
                        </li>
                    </ul>';
        }

        return $rep;
    }

    public function getInfosTacheAction($id){
        $rep='';
        $idTache = $id;
        $builder = new Builder();
        $builder->columns('*');
        $builder->distinct(true);
        $builder->From('tache');
        $builder->join('lien','tache.id = lien.id_tache');
        $builder->join('categorie','lien.id_categorie = categorie.id');
        $builder->where('tache.id = :idTache:',array('idTache'=>$idTache));
        $query = $builder->getQuery();
        $result = $query->execute();


        foreach ($result as $r){
            $rep.='<ul class = list-group>
                        <li class="list-group-item list-group-item-warning">Libellé : '. $r->tache->libelle .'</li>
                        <li class="list-group-item list-group-item-info">Catégorie : '.$r->categorie->libelle.'</li>
                        <li class="list-group-item list-group-item-info">Etat : '. $r->tache->etat.'</li>
                        <li class="list-group-item list-group-item-info">Durée : '.$r->tache->duree.'</li>
                        <li class="list-group-item list-group-item-info">Date Debut : '.$r->tache->dateDebut.'</li>
                        <li class="list-group-item list-group-item-info">Date Fin : '.$r->tache->dateFin.'</li>
                        <li class="list-group-item list-group-item-info">Description : '.$r->tache->description.'</li>
                        <li class="list-group-item list-group-item-info">Commentaire : '.$r->tache->commentaire.'</li>
                    </ul>';
        }

        return $rep;
    }
}
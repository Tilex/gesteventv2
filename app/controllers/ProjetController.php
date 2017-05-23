<?php
use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Forms\Element\Date;
/**
 * Created by PhpStorm.
 * User: Octaedra
 * Date: 27/01/2017
 * Time: 17:06
 */
class ProjetController extends ControllerBase
{
    public function indexAction()
    {
    }

    public function indexActeurAction()
    {
        $idActeur = $this->session->get('id');
        $builder = new Builder();
        $builder->columns('projet.libelle,projet.etat,projet.dateDebut,projet.dateFinPrevue,projet.dateFin,projet.id,projet.chefProjet,projet.avancee');
        $builder->distinct(true);
        $builder->From('acteur');
        $builder->join('lien','acteur.id = lien.id_acteur');
        $builder->join('projet','lien.id_projet = projet.id');
        $builder->where('lien.id_acteur = :idActeur:',array('idActeur'=>$idActeur));
        $query = $builder->getQuery();
        $result = $query->execute();


        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Projet', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        $paginator = new Paginator([
            'data' => $result,
            'limit'=> 5,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();

    }
    public function indexAdminAction()
    {
        $numberPage = 1;


        $builder = new Builder();
        $builder->columns('*');
        $builder->From('projet');
        $builder->join('acteur','acteur.id = projet.chefProjet');
        $query = $builder->getQuery();
        $resultat = $query->execute();
        $res = array();
        $i=0;
        foreach ($resultat as $r) {

            $dateDebut = $r->projet->dateDebut;
            $dateFin = $r->projet->dateFin;
            $dateFinPrevue = $r->projet->dateFinPrevue;


            if ($dateDebut != NULL) {
                $a_dateDebut = explode("-", $dateDebut);
                $dateDebut = $a_dateDebut[2] . "-" . $a_dateDebut[1] . "-" . $a_dateDebut[0];
                $r->projet->dateDebut = $dateDebut;

            } else {
                $r->projet->dateDebut = "";
            }

            if ($dateFin != NULL) {
                $a_dateFin = explode("-", $dateFin);
                $dateFin = $a_dateFin[2] . "-" . $a_dateFin[1] . "-" . $a_dateFin[0];
                $r->projet->dateFin = $dateFin;
            } else {
                $r->projet->dateFin = "";
            }


            if ($dateFinPrevue != NULL) {
                $a_dateFinPrevue = explode("-", $dateFinPrevue);
                $dateFinPrevue = $a_dateFinPrevue[2] . "-" . $a_dateFinPrevue[1] . "-" . $a_dateFinPrevue[0];
                $r->projet->dateFinPrevue = $dateFinPrevue;
            } else {
                $r->projet->dateFinPrevue = "";
            }

            $res[$r->projet->id]=array('dateDebut'=>$dateDebut,'dateFin'=>$dateFin,'dateFinPrevue'=>$dateFinPrevue);
        }

        $this->view->setVar('date',$res);
        $paginator = new Paginator([
            'data' => $resultat,
            'limit'=> 1000000000,
            'page' => $numberPage
        ]);
        $this->view->page = $paginator->getPaginate();
    }

    public function editAction($id)
    {
        $projet = Projet::findFirst($id);
        $acteur = Acteur::find();

        $builder = new Builder();
        $builder->columns('acteur.nom,acteur.prenom,acteur.id');
        $builder->distinct(true);
        $builder->From('projet');
        $builder->join('acteur','acteur.id = projet.chefProjet');
        $builder->where('acteur.id = :chefProjet:',array('chefProjet'=>$projet->chefProjet));
        $query = $builder->getQuery();
        $resultat = $query->execute();


        $rep="";
        $rep.=$this->tag->form(
            [
                "projet/save",
                "autocomplete" => "off",
                "class" => "form-horizontal"
            ]
        );


        $rep.='<div class="form-group">
            <label for="fieldLibelle" class="col-sm-2 control-label pull-left" style="position: relative;display: block">Libelle</label>
            <div class="col-sm-8" style="margin-left: 13%">';
                $rep.=$this->tag->textField(["name"=>"libelle","id"=>"libelle","value" => $projet->libelle, "size" => 33, "class" => "form-control"]);
            $rep.='</div>
        </div>
        <br><br>
        <div class="form-group">
            <label for="field" class="col-sm-1 control-label pull-left">Chef</label>
            <label for="field" class="col-sm-1 control-label pull-left">de</label>
            <label for="field" class="col-sm-0 control-label pull-left">projet</label>
            <div class="col-sm-8">
                <select class="form-control" id="projetId" name="chefProjet" style="width: 294px">';
                     foreach ($acteur as $a):
                         foreach($resultat as $r):
                         if($a->id == $r->id){
                         $rep.="<option selected value=$a->id>$a->nom $a->prenom</option>";
                         }else{
                         $rep.="<option value=$a->id>$a->nom $a->prenom</option>";
                         }endforeach;
                         endforeach;
                $rep.='</select>
            </div>
        </div>
     
        <div class="form-group hidden">
            <div class="col-sm-10">';
                $rep.=$this->tag->textField(["name"=>"etat","id"=>"etat","value" => $projet->etat, "size" => 30, "class" => "form-control"]);
            $rep.='</div>
        </div>
        <br><br>
        <div class="form-group">
            <label for="fieldDatedebut" class="col-sm-1 control-label" style="position: relative;display: block">DateDebut</label>
            <div class="col-sm-8" style="margin-left: 84px">';
                $rep.=$this->tag->dateField(["id"=>"dateDebut","name"=>"dateDebut","value" => $projet->dateDebut, "size" => 33, "class" => "form-control"]);
            $rep.='</div>
        </div>
        <br><br>
        <div class="form-group">
            <label for="fieldDatefinprevue" class="col-sm-1 control-label pull-left" style="position: relative;display: block">DateFinPrevue</label>
            <div class="col-sm-8" style="margin-left: 82px">';
                $rep.=$this->tag->dateField(["id"=>"dateFinPrevue","name"=>"dateFinPrevue","value" => $projet->dateFinPrevue, "size" => 33, "class" => "form-control"]);
            $rep.='</div>
        </div>
        <br><br>
        <div class="form-group">
            <label for="fieldDatefin" class="col-sm-1 control-label pull-left" style="position: relative;display: block">DateFin</label>
            <div class="col-sm-8" style="margin-left: 85px">';
                $rep.=$this->tag->dateField(["id"=>"dateFin","name"=>"dateFin","value" => $projet->dateFin, "size" => 33, "class" => "form-control"]);
            $rep.='</div>
        </div>
        <br><br>
        <div class="form-group hidden">
            <div class="col-sm-10">';
                $rep.=$this->tag->textField(["id"=>"avancee","name"=>"avancee","value" => $projet->avancee, "size" => 30, "class" => "form-control"]);
            $rep.='
            </div>
        </div>
        
        '.$this->tag->hiddenField("id") .'
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal">Annuler</button>
            <button class="btn btn-primary pull-left" type="submit">Sauvegarder</button>
        </div>
        
        </div>
        '.$this->tag->endForm();

        return $rep;

    }

    /**
     * Creates a new project
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "projet",
                'action' => 'indexAdmin'
            ]);

            return;
        }

        $projet = new Projet();
        $projet->libelle = $this->request->getPost("libelle");
        $projet->etat = "Non débuté";
        if($this->request->getPost("dateDebut")==""){
            $projet->dateDebut = NULL;
        }else{
            $projet->dateDebut = $this->request->getPost("dateDebut");
        }
        if($this->request->getPost("dateFinPrevue")==""){
            $projet->dateFinPrevue = NULL;
        }else{
            $projet->dateFinPrevue = $this->request->getPost("dateFinPrevue");
        }
        if($this->request->getPost("dateFin")==""){
            $projet->dateFin = NULL;
        }else{
            $projet->dateFin = $this->request->getPost("dateFin");
        }
        $projet->avancee = 0;
        $projet->chefProjet = $this->request->getPost("chefProjet");


        if (!$projet->save()) {
                foreach ($projet->getMessages() as $message) {
                    $this->flash->error($message);
                }

                $this->dispatcher->forward([
                    'controller' => "projet",
                    'action' => 'indexAdmin'
                ]);

                return;
        }

        $this->flash->success("Le projet a été créé avec succès !");

        $this->dispatcher->forward([
            'controller' => "projet",
            'action' => 'indexAdmin'
        ]);
    }

    /**
     * Saves a acteur edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "projet",
                'action' => 'indexAdmin'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $projet = Projet::findFirstByid($id);

        if (!$projet) {
            $this->flash->error("Le projet n'existe pas :" . $id);

            $this->dispatcher->forward([
                'controller' => "projet",
                'action' => 'indexAdmin'
            ]);

            return;
        }

        $projet->libelle = $this->request->getPost("libelle");
        $projet->etat = $this->request->getPost("etat");
        $projet->chefProjet = $this->request->getPost("chefProjet");
        if($this->request->getPost("dateDebut")==""){
            $projet->dateDebut = NULL;
        }else{
            $projet->dateDebut = $this->request->getPost("dateDebut");
        }
        if($this->request->getPost("dateFinPrevue")==""){
            $projet->dateFinPrevue = NULL;
        }else{
            $projet->dateFinPrevue = $this->request->getPost("dateFinPrevue");
        }
        if($this->request->getPost("dateFin")==""){
            $projet->dateFin = NULL;
        }else{
            $projet->dateFin = $this->request->getPost("dateFin");
        }
        $projet->avancee = $this->request->getPost("avancee");
        $projet->chefProjet = $this->request->getPost("chefProjet");

        if (!$projet->save()) {

            foreach ($projet->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "projet",
                'action' => 'indexAdmin'
            ]);

            return;
        }

        $this->flash->success("Le projet a été modifié avec succès !");

        $this->dispatcher->forward([
            'controller' => "projet",
            'action' => 'indexAdmin'
        ]);
    }

    /**
     * Deletes a project
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $projet = Projet::findFirstByid($id);
        if (!$projet) {
            $this->flash->error("Projet introuvable");

            $this->dispatcher->forward([
                'controller' => "projet",
                'action' => 'indexAdmin'
            ]);

            return;
        }

        if (!$projet->delete()) {

            foreach ($projet->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "projet",
                'action' => 'indexAdmin'
            ]);

            return;
        }

        $this->flash->success("Le projet a été supprimé avec succès !");

        $this->dispatcher->forward([
            'controller' => "projet",
            'action' => "indexAdmin"
        ]);
    }

    public function newAction()
    {
        $acteur = Acteur::find();
        $this->view->acteur = $acteur;
        $projet = new Projet();
        $projet->avancee = 0;
    }

    public function leProjetActeurAction($id)
    {
        $numberPage = 1;

        $projet = Projet::find($id);

        foreach ($projet as $p){
            if(!empty($p->chefProjet)){
                $chefProjet = $p->chefProjet;}
        }

        $builder = new Builder();
        $builder->columns('*');
        $builder->distinct(true);
        $builder->From('acteur');
        $builder->join('projet','acteur.id = projet.chefProjet');
        $builder->where('acteur.id = :chefProjet:',array('chefProjet'=>$chefProjet));
        $builder->andWhere('projet.id = :idProjet:',array('idProjet'=>$id));
        $query = $builder->getQuery();
        $result = $query->execute();

        $paginator = new Paginator([
            'data' => $result,
            'limit'=> 5,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    public function getProjetParActeurAction(){
        $rep='';
        $idProjet = $this->request->getPost('id');
        $idActeur = $this->session->get('id');

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
                        <li class="list-group-item list-group-item-info">Nom du projet : '.$r->projet->libelle.'</li>
                        <li class="list-group-item list-group-item-info">Chef du projet : '. $r->acteur->nom." ".$r->acteur->prenom .'</li>
                        <li class="list-group-item list-group-item-info">Etat : '.$r->projet->etat.'</li>
                        <li class="list-group-item list-group-item-info">Avancée : '. 100*$r->projet->avancee.'%</li>
                        <li class="list-group-item list-group-item-info">Date Debut : '.$r->projet->dateDebut.'</li>
                        <li class="list-group-item list-group-item-info">Date Fin Prévue : '.$r->projet->dateFinPrevue.'</li>
                        <li class="list-group-item list-group-item-info">Date Fin : '.$r->projet->dateFin.'</li>
                        </li>
                    </ul>';
        }

        $rep.='<div class=" pull-right">
                    <submit class="btn btn-primary glyphicon glyphicon-chevron-up" onclick="Projet.ferme('.$idProjet.');"></submit>
               </div><br><br>';
        return $rep;

    }

    public function createProjetAction(){

        $acteur = Acteur::find();
        $this->view->acteur = $acteur;
        $projet = new Projet();
        $projet->avancee = 0;

        $rep = "";
        $rep.=$this->tag->form(
            [
                "projet/create",
                "autocomplete" => "off",
                "class" => "form-horizontal"
            ]
        );
        $rep.='<div class="form-group">
            <label for="fieldLibelle" class="col-sm-1 control-label pull-left" style="position: relative;display: block">Libelle</label>
            <div class="col-sm-8" style="margin-left: 14%">';
        $rep.=$this->tag->textField(["name"=>"libelle","id"=>"libelle","size" => 33, "class" => "form-control"]);
        $rep.='</div>
        </div>
        <br>
        <div class="form-group">
            <label for="field" class="col-sm-1 control-label pull-left">Chef de projet</label>
            <div class="col-sm-8" style="margin-left: 84px">
                <select class="form-control" id="projetId" name="chefProjet" style="width: 294px">';
        foreach ($acteur as $a):
                    $rep.="<option value=$a->id>$a->nom $a->prenom</option>";
        endforeach;
        $rep.='</select>
            </div>
        </div>
     
        <div class="form-group hidden">
            <div class="col-sm-10">';
        $rep.=$this->tag->textField(["name"=>"etat","id"=>"etat","value" => "Non débuté", "size" => 30, "class" => "form-control"]);
        $rep.='</div>
        </div>
        <br>
        <div class="form-group">
            <label for="fieldDatedebut" class="col-sm-1 control-label" style="position: relative;display: block">DateDebut</label>
            <div class="col-sm-8" style="margin-left: 84px">';
        $rep.=$this->tag->dateField(["id"=>"dateDebut","name"=>"dateDebut", "size" => 33, "class" => "form-control"]);
        $rep.='</div>
        </div>
        <br>
        <div class="form-group">
            <label for="fieldDatefinprevue" class="col-sm-1 control-label pull-left" style="position: relative;display: block">DateFinPrevue</label>
            <div class="col-sm-8" style="margin-left: 83px">';
        $rep.=$this->tag->dateField(["id"=>"dateFinPrevue","name"=>"dateFinPrevue", "size" => 33, "class" => "form-control"]);
        $rep.='</div>
        </div>
        <br>
        <div class="form-group">
            <label for="fieldDatefin" class="col-sm-1 control-label pull-left" style="position: relative;display: block">DateFin</label>
            <div class="col-sm-8" style="margin-left: 85px">';
        $rep.=$this->tag->dateField(["id"=>"dateFin","name"=>"dateFin", "size" => 33, "class" => "form-control"]);
        $rep.='</div>
        </div>
        <br>
        <div class="form-group hidden">
            <div class="col-sm-10">';
        $rep.=$this->tag->textField(["id"=>"avancee","name"=>"avancee","value" => 0, "size" => 30, "class" => "form-control"]);
        $rep.='
            </div>
        </div>
       
        '.$this->tag->hiddenField("id").'
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal">Annuler</button>
            <button class="btn btn-primary pull-left" type="submit">Sauvegarder</button>
        </div>
        
        </div>
        '.$this->tag->endForm();


        return $rep;
    }
    public function snakeAction(){}
}
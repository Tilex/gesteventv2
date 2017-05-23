<?php

use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Forms\Element\Date;

class ActeurController extends ControllerBase
{
    /**
     * Index action
     * cet indexAction recupere tout les acteurs
     */
    public function indexAction()
    {
        $numberPage = 1;

        $acteur = Acteur::find();

        $paginator = new Paginator([
            'data' => $acteur,
            'limit'=> 1000000,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();

    }

    public function acteurParIdAction($id)
    {
        $numberPage = 1;
        $acteur = Acteur::find($id);

        $paginator = new Paginator([
            'data' => $acteur,
            'limit'=> 5,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }
    /**
     * Searches for acteur
     */
    public function searchAction()
    {
        $numberPage = 1;
        $acteur = new Acteur();
        $acteur->id = $this->request->getPost("listeNomActeur");

        $builder = new Builder();
        $builder->columns('*');
        $builder->From('acteur');
        if(!($acteur->id == "@")){
            $builder->Where('acteur.id = :idActeur:',array('idActeur'=>$acteur->id));
        }
        $query = $builder->getQuery();
        $resultat = $query->execute();

        if (count($resultat) == 0) {
            $this->flash->notice("La recherche n'a retournée aucun acteur");

            $this->dispatcher->forward([
                "controller" => "acteur",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $resultat,
            'limit'=> 5,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a acteur
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $acteur = acteur::findFirstByid($id);
            if (!$acteur) {
                $this->flash->error("Acteur introuvable");

                $this->dispatcher->forward([
                    'controller' => "acteur",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $acteur->id;

            $this->tag->setDefault("id", $acteur->id);
            $this->tag->setDefault("nom", $acteur->nom);
            $this->tag->setDefault("prenom", $acteur->prenom);
            $this->tag->setDefault("categorie", $acteur->categorie);
            $this->tag->setDefault("login", $acteur->login);
        }
    }

    /**
     * Creates a new acteur
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "acteur",
                'action' => 'index'
            ]);

            return;
        }

        $acteur = new Acteur();
        $acteur->nom = $this->request->getPost("nom");
        $acteur->prenom = $this->request->getPost("prenom");
        $acteur->categorie = $this->request->getPost("categorie");
        $acteur->login = $this->request->getPost("login");
        $acteur->mdp = $this->request->getPost("mdp");
        $acteur->trigramme = substr($acteur->nom,0,2)."-".substr($acteur->prenom,0,1);


        if (!$acteur->save()) {
            foreach ($acteur->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "acteur",
                'action' => 'index'
            ]);

            return;
        }

        $this->flash->success("L'acteur a été créé avec succès !");

        $this->dispatcher->forward([
            'controller' => "acteur",
            'action' => 'index'
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
                'controller' => "acteur",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $acteur = Acteur::findFirstByid($id);

        if (!$acteur) {
            $this->flash->error("L'acteur n'existe pas :" . $id);

            $this->dispatcher->forward([
                'controller' => "acteur",
                'action' => 'index'
            ]);

            return;
        }

        $acteur->nom = $this->request->getPost("nom");
        $acteur->prenom = $this->request->getPost("prenom");
        $acteur->categorie = "acteur";
        $acteur->login = $this->request->getPost("login");
        $acteur->trigramme = substr($acteur->nom,0,2)."-".substr($acteur->prenom,0,1);


        if (!$acteur->save()) {
            foreach ($acteur->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "acteur",
                'action' => 'edit',
                'params' => [$acteur->id]
            ]);

            return;
        }

        $this->flash->success("L'acteur a été modifié avec succès !");

        $this->dispatcher->forward([
            'controller' => "acteur",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a acteur
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $acteur = Acteur::findFirstByid($id);
        if (!$acteur) {
            $this->flash->error("Acteur introuvable");

            $this->dispatcher->forward([
                'controller' => "acteur",
                'action' => 'index'
            ]);

            return;
        }

        if (!$acteur->delete()) {

            foreach ($acteur->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "acteur",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("L'acteur a été supprimé avec succès !");

        $this->dispatcher->forward([
            'controller' => "acteur",
            'action' => "index"
        ]);
    }
    public function getProjetParActeurAction(){
        $rep='';
        $idActeur = $this->request->getPost('id');
        $builder = new Builder();
        $builder->columns('projet.id,projet.libelle');
        $builder->distinct(true);
        $builder->From('projet');
        $builder->join('lien','projet.id = lien.id_projet');
        $builder->where('lien.id_acteur = :idActeur:',array('idActeur'=>$idActeur));
        $query = $builder->getQuery();
        $result = $query->execute();

        foreach ($result as $r){
            $rep.='<a href="#" class="btn-link" onclick=Modal.afficheProjet("'.$r->id.'") id="openBtn">'.$r->libelle.'<br></a>';
                    $rep.='<div id="k'.$r->id.'" class="modal fade" tabindex="-1" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">×</button>
                                    <h3>Projet : '.$r->libelle.'</h3>
                                </div>
                                <div class="modal-body" id="modalBody'.$r->id.'">
                                    <!-- Contenu du controller -->
                                </div>
                            </div>
                        </div>
                    </div>';
        }
        $rep.='<div class=" pull-right">
                                <submit class="btn btn-primary glyphicon glyphicon-chevron-up" onclick="Projet.ferme('.$idActeur.');"></submit>
                            </div><br><br>';
        return $rep;
    }

    public function getTacheParActeurAction(){
        $rep='';
        $idActeur = $this->request->getPost('id');
        $builder = new Builder();
        $builder->columns('tache.id,tache.libelle as tachelibelle,projet.libelle as projetlibelle');
        $builder->From('tache');
        $builder->join('lien','tache.id = lien.id_tache');
        $builder->join('projet','lien.id_projet = projet.id');
        $builder->where('lien.id_acteur = :idActeur:',array('idActeur'=>$idActeur));
        $query = $builder->getQuery();
        $result = $query->execute();

        foreach ($result as $r){
            $rep.='<a href="#" class="btn-link" onclick=Modal.afficheTache("'.$r->id.'") id="openBtn">'.$r->projetlibelle.' : '.$r->tachelibelle.'<br></a>';
            $rep.='<div id="k'.$r->id.'" class="modal fade" tabindex="-1" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">×</button>
                                    <h3>Tâche : '.$r->tachelibelle.'</h3>
                                </div>
                                <div class="modal-body" id="modalBody'.$r->id.'">
                                    <!-- Contenu du controller -->
                                </div>
                            </div>
                        </div>
                    </div>';
        }
        $rep.='<div class=" pull-right">
                                <submit class="btn btn-primary glyphicon glyphicon-chevron-up" onclick="TacheParActeur.ferme('.$idActeur.');"></submit>
                            </div><br><br>';

        return $rep;
    }

    public function createActeurAction(){

        $rep='';
        $rep.=$this->tag->form(
            [
                "acteur/create",
                "autocomplete" => "off",
                "class" => "form-horizontal"
            ]
        );
        $rep.='<div class="form-group">
            <label for="fieldNom" class="col-sm-2 control-label">Nom</label>
            <div class="col-sm-10">';
                $rep.=$this->tag->textField(["name"=>"nom", "size" => 30, "class" => "form-control", "id" => "nom"]);
            $rep.='</div>
        </div>
        
        <div class="form-group">
            <label for="fieldPrenom" class="col-sm-2 control-label">Prenom</label>
            <div class="col-sm-10">';
                $rep.=$this->tag->textField(["name"=>"prenom", "size" => 30, "class" => "form-control", "id" => "prenom"]);
            $rep.='</div>
        </div>
        
        <div class="form-group hidden">
            <div class="col-sm-10">';
                $rep.= $this->tag->textField(["name"=>"categorie","value"=>"acteur", "size" => 30, "class" => "form-control", "id" => "categorie"]);
            $rep.='</div>
        </div>
        
        <div class="form-group">
            <label for="fieldLogin" class="col-sm-2 control-label">Login</label>
            <div class="col-sm-10">';
                $rep.= $this->tag->textField(["name"=>"login", "size" => 30, "class" => "form-control", "id" => "login"]);
        $rep.='</div>
        </div>
        
        <div class="form-group">
            <label for="fieldMdp" class="col-sm-2 control-label">Mot de passe</label>
            <div class="col-sm-10">';
            $rep.=$this->tag->textField(["name"=>"mdp", "size" => 30, "class" => "form-control", "id" => "mdp"]);
            $rep.='</div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal">Annuler</button>
            <button class="btn btn-primary pull-left" type="submit">Sauvegarder</button>
        </div>';
        $rep.=$this->tag->endForm();

        return $rep ;
    }

    public function editActeurAction($id){

        $acteur = Acteur::findFirst($id);
        $this->tag->setDefault("id",$id);
        $rep="";
        $rep.= $this->tag->form(
                    [
                        "acteur/save",
                        "autocomplete" => "off",
                        "class" => "form-horizontal"
                    ]
                );

            $rep.='<div class="form-group" style="color: black;">
                <label for="fieldNom" class="col-sm-2 control-label">Nom</label>
                <div class="col-sm-10">';
                    $rep.=$this->tag->textField(["nom","value"=> $acteur->nom, "size" => 30, "class" => "form-control", "id" => "fieldNom"]);
                $rep.='</div>
            </div>

            <div class="form-group" style="color: black;">
                <label for="fieldPrenom" class="col-sm-2 control-label">Prenom</label>
                <div class="col-sm-10">';
                    $rep.=$this->tag->textField(["prenom","value"=>$acteur->prenom, "size" => 30, "class" => "form-control", "id" => "fieldPrenom"]);
                $rep.='</div>
            </div>

            <div class="form-group" style="color: black;">
                <label for="fieldLogin" class="col-sm-2 control-label">Login</label>
                <div class="col-sm-10">';
                    $rep.=$this->tag->textField(["login","value"=>$acteur->login, "size" => 30, "class" => "form-control", "id" => "fieldLogin"]);
                $rep.='</div>
            </div>';


            $rep.=$this->tag->hiddenField("id");

        $rep.='<div class="modal-footer">
                    <button class="btn" data-dismiss="modal" style="color: black">Annuler</button>
                    <button class="btn btn-primary pull-left" type="submit">Sauvegarder</button>
                </div>';

            $rep.=$this->tag->endForm();
            return $rep;
    }

}

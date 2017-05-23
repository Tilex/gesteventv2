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
class TacheController extends ControllerBase
{
    public function indexAction()
    {
    }

    public function indexActeurAction()
    {
        $idActeur = $this->session->get('id');
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Tache', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        $builder = new Builder();
        $builder->columns('*');
        $builder->From('tache');
        $builder->join('lien','tache.id = lien.id_tache');
        $builder->join('projet','lien.id_projet = projet.id');
        $builder->join('categorie','lien.id_categorie = categorie.id');
        $builder->join('acteur','lien.id_acteur = acteur.id');
        $builder->where('acteur.id = :idActeur:',array('idActeur'=>$idActeur));

        $query = $builder->getQuery();
        $resultat = $query->execute();


        $paginator = new Paginator([
            'data' => $resultat,
            'limit'=> 4,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }
    public function indexAdminAction()
    {
        $numberPage = 1;

        $builder = new Builder();
        $builder->columns('*');
        $builder->From('tache');
        $builder->join('lien','tache.id = lien.id_tache');
        $builder->join('projet','lien.id_projet = projet.id');
        $builder->join('categorie','lien.id_categorie = categorie.id');
        $builder->join('acteur','lien.id_acteur = acteur.id');
        $query = $builder->getQuery();
        $resultat = $query->execute();

        $res = array();
        $i=0;
        foreach ($resultat as $r) {

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
            'data' => $resultat,
            'limit'=> 1000000000,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    public function editAdminAction($id)
    {
        $tache = Tache::find();

        $projet = Projet::find();
        $acteur = Acteur::find();
        $categorie = Categorie::find();
        $matache = Tache::findFirstByid($id);

        $builder = new Builder();
        $builder->columns('*');
        $builder->distinct(true);
        $builder->From('tache');
        $builder->join('lien','tache.id = lien.id_tache');
        $builder->join('acteur','lien.id_acteur = acteur.id');
        $builder->join('projet','lien.id_projet = projet.id');
        $builder->join('categorie','lien.id_categorie = categorie.id');
        $builder->where('tache.id = :tacheId:',array('tacheId'=>$id));
        $query = $builder->getQuery();
        $resultat = $query->execute();


        $rep="";
        $rep.=$this->tag->form(
            [
                "tache/saveAdmin",
                "autocomplete" => "off",
                "class" => "form-horizontal"
            ]
        );


        $rep.='<div class="form-group">
            <label for="fieldLibelle" class="col-sm-2 control-label pull-left" style="position: relative;display: block">Libelle</label>
            <div class="col-sm-8" style="margin-left: 13%">';
        foreach ($resultat as $r):
            $rep.=$this->tag->textField(["name"=>"libelle","id"=>"libelle","value" => $r->tache->libelle, "size" => 33, "class" => "form-control"]);
        endforeach;
        $rep.='</div>
        </div>

        <div class="form-group hidden">
            <label for="fieldlibelle" class="col-sm-1 control-label pull-left">Projet</label>
            <div class="col-sm-8" style="margin-left: 21.7%">
                <select class="form-control" id="projetId" name="idProjet" style="width: 294px">';
        foreach ($projet as $a):
            foreach($resultat as $r):
                if($a->id == $r->projet->id){
                    $rep.="<option selected value=$a->id>$a->libelle</option>";
                }else{
                    $rep.="<option value=$a->id>$a->libelle</option>";
                }endforeach;
        endforeach;
        $rep.='</select>
            </div>
        </div>
        <br><br>
        <div class="form-group">
            <label for="fieldcategorie" class="col-sm-1 control-label pull-left">Catégorie</label>
            <div class="col-sm-8" style="margin-left: 20%">
                <select class="form-control" id="CategorieId" name="idCategorie" style="width: 294px">';
        foreach ($categorie as $a):
            foreach($resultat as $r):
                if($a->id == $r->categorie->id){
                    $rep.="<option selected value=$a->id>$a->libelle</option>";
                }else{
                    $rep.="<option value=$a->id>$a->libelle</option>";
                }endforeach;
        endforeach;
        $rep.='</select>
            </div>
        </div>
        <br><br>
        <div class="form-group">
            <label for="fieldActeur" class="col-sm-1 control-label pull-left">acteur</label>
            <div class="col-sm-8" style="margin-left: 21.5%">
                <select class="form-control" id="ActeurId" name="idActeur" style="width: 294px">';
        foreach ($acteur as $a):
            foreach($resultat as $r):
                if($a->id == $r->acteur->id){
                    $rep.="<option selected value=$a->id>$a->nom $a->prenom</option>";
                }else{
                    $rep.="<option value=$a->id>$a->nom $a->prenom</option>";
                }endforeach;
        endforeach;
        $rep.='</select>
            </div>
        </div>
        <br><br>
        <div class="form-group">
            <label for="fieldActeur" class="col-sm-1 control-label pull-left">Etat</label>
            <div class="col-sm-8" style="margin-left: 22.9%">
                <select class="form-control" id="Etat" name="etat" style="width: 294px">';
        if($matache->etat == "Terminée"){
            $rep.='<option selected value="Terminée">Terminée</option>';
            $rep.='<option value="En cours">En cours</option>';
            $rep.='<option value="Non débutée">Non débutée</option>';
        }
        if($matache->etat == "En cours"){
            $rep.='<option selected value="En cours">En cours</option>';
            $rep.='<option value="Terminée">Terminée</option>';
            $rep.='<option value="Non débutée">Non débutée</option>';
        }if($matache->etat == "Non débutée"){
            $rep.='<option selected value="Non débutée">Non débutée</option>';
            $rep.='<option value="En cours">En cours</option>';
            $rep.='<option value="Terminée">Terminée</option>';
        }
        $rep.='</select>
            </div>
        </div>
        <br><br>
        <div class="form-group">
            <label for="fieldDuree" class="col-sm-1 control-label pull-left" style="position: relative;display: block">Durée</label>
            <div class="col-sm-8" style="margin-left: 23.7%">';
        $rep.=$this->tag->textField(["id"=>"duree","name"=>"duree","value" => $matache->duree, "size" => 30, "class" => "form-control"]);
        $rep.='
            </div>
        </div>
        <br><br>
        <div class="form-group">
            <label for="fieldDatedebut" class="col-sm-1 control-label" style="position: relative;display: block">DateDebut</label>
            <div class="col-sm-8" style="margin-left: 87px">';
        $rep.=$this->tag->dateField(["id"=>"dateDebut","name"=>"dateDebut","value" => $matache->dateDebut, "size" => 33, "class" => "form-control"]);
        $rep.='</div>
        </div>
        <br><br>
        <div class="form-group">
            <label for="fieldDatefin" class="col-sm-1 control-label pull-left" style="position: relative;display: block">DateFin</label>
            <div class="col-sm-8" style="margin-left: 88px">';
        $rep.=$this->tag->dateField(["id"=>"dateFin","name"=>"dateFin","value" => $matache->dateFin, "size" => 33, "class" => "form-control"]);
        $rep.='</div>
        </div>
        <br><br>
        <div class="form-group">
            <label for="fieldDescription" class="col-sm-1 control-label pull-left" style="position: relative;display: block">Description</label>
            <div class="col-sm-8" style="margin-left: 85px">';
        $rep.=$this->tag->textArea(["id"=>"description","name"=>"description","value" => $matache->description, "size" => 30, "class" => "form-control"]);
        $rep.='
            </div>
        </div>
        <br><br>
        <div class="form-group">
            <label for="fieldCommentaire" class="col-sm-1 control-label pull-left" style="position: relative;display: block">Commentaire</label>
            <div class="col-sm-8" style="margin-left: 85px">';
        $rep.=$this->tag->textArea(["id"=>"commentaire","name"=>"commentaire","value" => $matache->commentaire, "size" => 30, "class" => "form-control"]);
        $rep.='
            </div>
        </div>
        <br><br>
        '.$this->tag->hiddenField("id") .'
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal">Annuler</button>
            <button class="btn btn-primary pull-left" type="submit">Sauvegarder</button>
        </div>
        
        </div>
        '.$this->tag->endForm();

        return $rep;

    }

    public function saveAdminAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "tache",
                'action' => 'indexAdmin'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $tache = Tache::findFirstByid($id);

        $builder = new Builder();
        $builder->columns('projet.id,projet.avancee,projet.libelle,projet.etat,projet.dateDebut,projet.dateFin,projet.dateFinPrevue,projet.chefProjet');
        $builder->From('tache');
        $builder->join('lien','tache.id = lien.id_tache');
        $builder->join('projet','lien.id_projet = projet.id');
        $builder->where('lien.id_tache = :idTache:',array('idTache'=>$id));
        $query = $builder->getQuery();
        // resultat = Le projet par rapport a la tache selectionnée.
        $resultat = $query->execute();

        foreach ($resultat as $r){
            $idProjet = $r->id;
        }

        $builder2 = new Builder();
        $builder2->columns('count(*) as nbTache');
        $builder2->From('lien');
        $builder2->join('tache','lien.id_tache = tache.id');
        $builder2->where('lien.id_projet = :idProjet:',array('idProjet'=>$idProjet));
        $builder2->andWhere('tache.prevue = :prevue:',array('prevue'=>1));
        $query = $builder2->getQuery();
        // resultat 2 = le nombre de tache qu'il y a dans le projet trouvé dans $resultat.
        $resultat2 = $query->execute();


        if (!$tache) {
            $this->flash->error("La tâche n'existe pas :" . $id);

            $this->dispatcher->forward([
                'controller' => "tache",
                'action' => 'indexAdmin'
            ]);

            return;
        }

        $tache->libelle = $this->request->getPost("libelle");
        $tache->etat = $this->request->getPost("etat");
        if($this->request->getPost("dateDebut")==""){
            $tache->dateDebut = NULL;
        }else{
            $tache->dateDebut = $this->request->getPost("dateDebut");
        }

        if($this->request->getPost("dateFin")==""){
            $tache->dateFin = NULL;
        }else{
            $tache->dateFin = $this->request->getPost("dateFin");
        }

        $tache->duree = $this->request->getPost("duree");
        $tache->description = $this->request->getPost("description");
        $tache->commentaire = $this->request->getPost("commentaire");
        $tache->dateMaj = date("y.m.d");

        if (!$tache->save()) {

            foreach ($tache->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "tache",
                'action' => 'editAdmin',
                'params' => [$tache->id]
            ]);

            return;
        }

        $builder4= new Builder();
        $builder4->columns('lien.id_tache,lien.id_categorie,lien.id_projet,lien.id_acteur');
        $builder4->From('lien');
        $builder4->where('lien.id_tache = :idTache:',array('idTache'=>$id));
        $query = $builder4->getQuery();
        //resultat 4 = tache dans la table lien.
        $resultat4 = $query->execute();
        foreach ($resultat4 as $r){

        $lien = new Lien();
        if($r->id_categorie != $this->request->getPost("idCategorie") || $r->id_acteur != $this->request->getPost("idActeur")) {
            $lien->id_tache = $id;
            $lien->id_projet = $idProjet;
            $lien->id_categorie = $this->request->getPost("idCategorie");
            $lien->id_acteur = $this->request->getPost("idActeur");
            if (!$lien->save()) {

                foreach ($lien->getMessages() as $message) {
                    $this->flash->error("$message");
                }

                $this->dispatcher->forward([
                    'controller' => "tache",
                    'action' => 'editAdmin',
                    'params' => [$tache->id]
                ]);

                return;
            }
            $lien2 = new Lien();
            $lien2->id_acteur = $r->id_acteur;
            $lien2->id_categorie = $r->id_categorie;
            $lien2->id_projet = $r->id_projet;
            $lien2->id_tache = $r->id_tache;

            if (!$lien2->delete()) {

                foreach ($lien2->getMessages() as $message) {
                    $this->flash->error("$message");
                }

                $this->dispatcher->forward([
                    'controller' => "tache",
                    'action' => 'editAdmin',
                    'params' => [$tache->id]
                ]);

                return;
            }
        }
    }


        $builder3 = new Builder();
        $builder3->columns('count(*) as nbTacheTerminee');
        $builder3->From('lien');
        $builder3->join('tache','lien.id_tache = tache.id');
        $builder3->where('lien.id_projet = :idProjet:',array('idProjet'=>$idProjet));
        $builder3->andWhere('tache.etat = :etat:',array('etat'=>"Terminée"));
        $builder3->andWhere('tache.prevue = :prevue:',array('prevue'=>1));
        $query = $builder3->getQuery();
        //resultat 2 = le nombre de tache TERMINEE qu'il y a dans le projet trouvé dans $resultat.
        $resultat3 = $query->execute();

        foreach ($resultat2 as $r2){
            $res2 = $r2->nbTache;
        }
        foreach ($resultat3 as $r3){
            $res3 = $r3->nbTacheTerminee;
        }
        $resultat2 = (int)$res2;
        $resultat3 = (int)$res3;
        $res = $resultat3/$resultat2;

        $projet = new Projet();
        $projet->avancee = $res;

        foreach ($resultat as $r){
            $projet->id = $r->id;
            $projet->libelle = $r->libelle;
            $projet->chefProjet = $r->chefProjet;
            $projet->dateDebut = $r->dateDebut;
            $projet->dateFin = $r->dateFin;
            $projet->dateFinPrevue = $r->dateFinPrevue;
            if($projet->etat == 0){
                $projet->etat = "Non débuté";
            }
            if($projet->avancee > 0){
                $projet->etat = "En cours";
            }
            if($projet->avancee == 1){
                $projet->etat = "Terminé";
            }
        }

        if (!$projet->save()) {

            foreach ($projet->getMessages() as $message) {
                $this->flash->error("$message");
            }

            $this->dispatcher->forward([
                'controller' => "tache",
                'action' => 'editAdmin',
                'params' => [$tache->id]
            ]);

            return;
        }


        $this->flash->success("La tâche a été modifiée avec succès !");

        $this->dispatcher->forward([
            'controller' => "tache",
            'action' => 'indexAdmin'
        ]);
    }

    /**
     * Deletes a
     *
     * @param string $id
     */
    public function deleteAdminAction($id)
    {
        $tache = Tache::findFirstByid($id);
        if (!$tache) {
            $this->flash->error("Tâche introuvable");

            $this->dispatcher->forward([
                'controller' => "tache",
                'action' => 'indexAdmin'
            ]);

            return;
        }

        $builder = new Builder();
        $builder->columns('projet.id,projet.avancee,projet.libelle,projet.etat,projet.dateDebut,projet.dateFin,projet.dateFinPrevue,projet.chefProjet');
        $builder->From('tache');
        $builder->join('lien','tache.id = lien.id_tache');
        $builder->join('projet','lien.id_projet = projet.id');
        $builder->where('lien.id_tache = :idTache:',array('idTache'=>$id));
        $query = $builder->getQuery();
        // resultat = Le projet par rapport a la tache selectionnée.
        $resultat = $query->execute();

        foreach ($resultat as $r){
            $idProjet = $r->id;
        }

        if (!$tache->delete()) {

            foreach ($tache->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "tache",
                'action' => 'indexAdmin'
            ]);

            return;
        }
        $builder2 = new Builder();
        $builder2->columns('count(*) as nbTache');
        $builder2->From('lien');
        $builder2->join('tache','lien.id_tache = tache.id');
        $builder2->where('lien.id_projet = :idProjet:',array('idProjet'=>$idProjet));
        $builder2->andWhere('tache.prevue = :prevue:',array('prevue'=>"1"));
        $query = $builder2->getQuery();
        // resultat 2 = le nombre de tache qu'il y a dans le projet trouvé dans $resultat.
        $resultat2 = $query->execute();

        $builder3 = new Builder();
        $builder3->columns('count(*) as nbTacheTerminee');
        $builder3->From('lien');
        $builder3->join('tache','lien.id_tache = tache.id');
        $builder3->where('lien.id_projet = :idProjet:',array('idProjet'=>$idProjet));
        $builder3->andWhere('tache.etat = :etat:',array('etat'=>"Terminée"));
        $builder3->andWhere('tache.prevue = :prevue:',array('prevue'=>"1"));
        $query = $builder3->getQuery();
        //resultat 2 = le nombre de tache TERMINEE qu'il y a dans le projet trouvé dans $resultat.
        $resultat3 = $query->execute();

        foreach ($resultat2 as $r2){
            $res2 = $r2->nbTache;
        }
        foreach ($resultat3 as $r3){
            $res3 = $r3->nbTacheTerminee;
        }
        $resultat2 = (int)$res2;
        $resultat3 = (int)$res3;
        if($resultat2 == 0){
            $res=0;
        }else{
            $res = $resultat3/$resultat2;
        }
        $projet = new Projet();
        $projet->avancee = $res;

        foreach ($resultat as $r){
            $projet->id = $r->id;
            $projet->libelle = $r->libelle;
            $projet->chefProjet = $r->chefProjet;
            $projet->dateDebut = $r->dateDebut;
            $projet->dateFin = $r->dateFin;
            $projet->dateFinPrevue = $r->dateFinPrevue;
            if($projet->etat == 0){
                $projet->etat = "Non débuté";
            }
            if($projet->avancee > 0){
                $projet->etat = "En cours";
            }
            if($projet->avancee == 1){
                $projet->etat = "Terminé";
            }
        }

        if (!$projet->save()) {

            foreach ($projet->getMessages() as $message) {
                $this->flash->error("$message");
            }

            $this->dispatcher->forward([
                'controller' => "tache",
                'action' => 'indexAdmin',
            ]);

            return;
        }

        $this->flash->success("La tâche a été supprimée avec succès !");

        $this->dispatcher->forward([
            'controller' => "tache",
            'action' => "indexAdmin"
        ]);
    }

    public function saveActeurAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "tache",
                'action' => 'indexActeur'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $tache = Tache::findFirstByid($id);

        if (!$tache) {
            $this->flash->error("La tâche n'existe pas :" . $id);

            $this->dispatcher->forward([
                'controller' => "tache",
                'action' => 'indexActeur'
            ]);

            return;
        }

        $tache->libelle = $this->request->getPost("libelle");
        $tache->etat = $this->request->getPost("etat");
        if($this->request->getPost("dateDebut")==""){
            $tache->dateDebut = NULL;
        }else{
            $tache->dateDebut = $this->request->getPost("dateDebut");
        }

        if($this->request->getPost("dateFin")==""){
            $tache->dateFin = NULL;
        }else{
            $tache->dateFin = $this->request->getPost("dateFin");
        }

        $tache->duree = $this->request->getPost("duree");
        $tache->description = $this->request->getPost("description");
        $tache->commentaire = $this->request->getPost("commentaire");
        $tache->dateMaj = date("y.m.d");

        if (!$tache->save()) {

            foreach ($tache->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "tache",
                'action' => 'editActeur',
                'params' => [$tache->id]
            ]);

            return;
        }

        $builder = new Builder();
        $builder->columns('projet.id,projet.avancee,projet.libelle,projet.etat,projet.dateDebut,projet.dateFin,projet.dateFinPrevue,projet.chefProjet');
        $builder->From('tache');
        $builder->join('lien','tache.id = lien.id_tache');
        $builder->join('projet','lien.id_projet = projet.id');
        $builder->where('lien.id_tache = :idTache:',array('idTache'=>$id));
        $query = $builder->getQuery();
        // resultat = Le projet par rapport a la tache selectionnée.
        $resultat = $query->execute();

        foreach ($resultat as $r){
            $idProjet = $r->id;
        }

        $builder2 = new Builder();
        $builder2->columns('count(*) as nbTache');
        $builder2->From('lien');
        $builder2->join('tache','lien.id_tache = tache.id');
        $builder2->where('lien.id_projet = :idProjet:',array('idProjet'=>$idProjet));
        $builder2->andWhere('tache.prevue = :prevue:',array('prevue'=>1));
        $query = $builder2->getQuery();
        // resultat 2 = le nombre de tache qu'il y a dans le projet trouvé dans $resultat.
        $resultat2 = $query->execute();

        $builder3 = new Builder();
        $builder3->columns('count(*) as nbTacheTerminee');
        $builder3->From('lien');
        $builder3->join('tache','lien.id_tache = tache.id');
        $builder3->where('lien.id_projet = :idProjet:',array('idProjet'=>$idProjet));
        $builder3->andWhere('tache.etat = :etat:',array('etat'=>"Terminée"));
        $builder3->andWhere('tache.prevue = :prevue:',array('prevue'=>1));
        $query = $builder3->getQuery();
        //resultat 2 = le nombre de tache TERMINEE qu'il y a dans le projet trouvé dans $resultat.
        $resultat3 = $query->execute();

        foreach ($resultat2 as $r2){
            $res2 = $r2->nbTache;
        }
        foreach ($resultat3 as $r3){
            $res3 = $r3->nbTacheTerminee;
        }
        $resultat2 = (int)$res2;
        $resultat3 = (int)$res3;
        $res = $resultat3/$resultat2;

        $projet = new Projet();
        $projet->avancee = $res;

        foreach ($resultat as $r){
            $projet->id = $r->id;
            $projet->libelle = $r->libelle;
            $projet->chefProjet = $r->chefProjet;
            $projet->dateDebut = $r->dateDebut;
            $projet->dateFin = $r->dateFin;
            $projet->dateFinPrevue = $r->dateFinPrevue;
            if($projet->etat == 0){
                $projet->etat = "Non débuté";
            }
            if($projet->avancee > 0){
                $projet->etat = "En cours";
            }
            if($projet->avancee == 1){
                $projet->etat = "Terminé";
            }
        }

        if (!$projet->save()) {

            foreach ($projet->getMessages() as $message) {
                $this->flash->error("$message");
            }

            $this->dispatcher->forward([
                'controller' => "tache",
                'action' => 'indexActeur',
            ]);

            return;
        }

        $this->flash->success("La tâche a été modifiée avec succès !");

        $this->dispatcher->forward([
            'controller' => "tache",
            'action' => 'indexActeur'
        ]);
    }

    public function deleteActeurAction($id)
    {
        $tache = Tache::find($id);
        if (!$tache) {
            $this->flash->error("Tâche introuvable");

            $this->dispatcher->forward([
                'controller' => "tache",
                'action' => 'indexActeur'
            ]);

            return;
        }

        $builder = new Builder();
        $builder->columns('projet.id,projet.avancee,projet.libelle,projet.etat,projet.dateDebut,projet.dateFin,projet.dateFinPrevue,projet.chefProjet');
        $builder->From('tache');
        $builder->join('lien','tache.id = lien.id_tache');
        $builder->join('projet','lien.id_projet = projet.id');
        $builder->where('lien.id_tache = :idTache:',array('idTache'=>$id));
        $query = $builder->getQuery();
        // resultat = Le projet par rapport a la tache selectionnée.
        $resultat = $query->execute();

        foreach ($resultat as $r){
            $idProjet = $r->id;
        }

        if (!$tache->delete()) {

            foreach ($tache->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "tache",
                'action' => 'indexActeur'
            ]);

            return;
        }

        $builder2 = new Builder();
        $builder2->columns('count(*) as nbTache');
        $builder2->From('lien');
        $builder2->join('tache','lien.id_tache = tache.id');
        $builder2->where('lien.id_projet = :idProjet:',array('idProjet'=>$idProjet));
        $builder2->andWhere('tache.prevue = :prevue:',array('prevue'=>1));
        $query = $builder2->getQuery();
        // resultat 2 = le nombre de tache qu'il y a dans le projet trouvé dans $resultat.
        $resultat2 = $query->execute();

        $builder3 = new Builder();
        $builder3->columns('count(*) as nbTacheTerminee');
        $builder3->From('lien');
        $builder3->join('tache','lien.id_tache = tache.id');
        $builder3->where('lien.id_projet = :idProjet:',array('idProjet'=>$idProjet));
        $builder3->andWhere('tache.etat = :etat:',array('etat'=>"Terminée"));
        $builder3->andWhere('tache.prevue = :prevue:',array('prevue'=>1));
        $query = $builder3->getQuery();
        //resultat 2 = le nombre de tache TERMINEE qu'il y a dans le projet trouvé dans $resultat.
        $resultat3 = $query->execute();

        foreach ($resultat2 as $r2){
            $res2 = $r2->nbTache;
        }
        foreach ($resultat3 as $r3){
            $res3 = $r3->nbTacheTerminee;
        }
        $resultat2 = (int)$res2;
        $resultat3 = (int)$res3;
        if($resultat2 == 0){
            $res=0;
        }else{
            $res=$resultat3/$resultat2;
        }

        $projet = new Projet();
        $projet->avancee = $res;

        foreach ($resultat as $r){
            $projet->id = $r->id;
            $projet->libelle = $r->libelle;
            $projet->chefProjet = $r->chefProjet;
            $projet->dateDebut = $r->dateDebut;
            $projet->dateFin = $r->dateFin;
            $projet->dateFinPrevue = $r->dateFinPrevue;
            if($projet->etat == 0){
                $projet->etat = "Non débuté";
            }
            if($projet->avancee > 0){
                $projet->etat = "En cours";
            }
            if($projet->avancee == 1){
                $projet->etat = "Terminé";
            }
        }

        if (!$projet->save()) {

            foreach ($projet->getMessages() as $message) {
                $this->flash->error("$message");
            }

            $this->dispatcher->forward([
                'controller' => "tache",
                'action' => 'indexActeur',
            ]);

            return;
        }

        $this->flash->success("La tâche a été supprimée avec succès !");

        $this->dispatcher->forward([
            'controller' => "tache",
            'action' => "indexActeur"
        ]);
    }

    public function editActeurAction($id)
    {
        if (!$this->request->isPost()) {

            $tache = Tache::findFirstByid($id);
            if (!$tache) {
                $this->flash->error("Tâche introuvable");

                $this->dispatcher->forward([
                    'controller' => "tache",
                    'action' => 'indexActeur'
                ]);

                return;
            }

            $this->view->id = $tache->id;

            $this->tag->setDefault("id", $tache->id);
            $this->tag->setDefault("libelle", $tache->libelle);
            $this->tag->setDefault("duree", $tache->duree);
            $this->tag->setDefault("dateDebut", $tache->dateDebut);
            $this->tag->setDefault("dateFin", $tache->dateFin);
            $this->tag->setDefault("etat", $tache->etat);
            $this->tag->setDefault("description", $tache->description);
            $this->tag->setDefault("commentaire", $tache->commentaire);
        }
    }
    public function createCategorieAction()
    {
        $rep = "";
        $rep.=$this->tag->form(
            [
                "tache/saveAdminCategorie",
                "autocomplete" => "off",
                "class" => "form-horizontal"
            ]
        );
        $rep.='<div class="form-group">
            <label for="fieldLibelle" class="col-sm-2 control-label pull-left" style="position: relative;display: block">Libelle de la catégorie</label>
            <div class="col-sm-8" style="margin-left: 13%">';
            $rep.=$this->tag->textField(["name"=>"libelle","id"=>"libelle", "size" => 33, "class" => "form-control"]);
        $rep.='</div>
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

    public function saveAdminCategorieAction()
    {
       $categorie = new Categorie();
       $categorie->libelle = $this->request->getPost("libelle");

        if (!$categorie->save()) {

            foreach ($categorie->getMessages() as $message) {
                $this->flash->error("$message");
            }

            $this->dispatcher->forward([
                'controller' => "tache",
                'action' => 'indexAdmin',
            ]);

            return;
        }
        $this->flash->success("La catégorie a été créée avec succes !");

        $this->dispatcher->forward([
            'controller' => "tache",
            'action' => "indexAdmin"
        ]);

    }
}
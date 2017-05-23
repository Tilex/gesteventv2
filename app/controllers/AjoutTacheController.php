<?php
use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Criteria;
/**
 * Created by PhpStorm.
 * User: Octaedra
 * Date: 16/01/2017
 * Time: 13:59
 */
class AjoutTacheController extends ControllerBase
{
    public function indexAdminAction(){

        $form = new AjoutTacheForm();
        $this->assets->addCss("css/bootstrap.min.css");
        $this->assets->addCss("css/bootstrap.min.css");
        $this->view->form = $form;
    }

    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "ajoutTache",
                'action' => 'index'
            ]);

            return;
        }

        $tache = new Tache();
        $tache->libelle = $this->request->getPost("libelle");
        $tache->etat = $this->request->getPost("listeEtat");
        $tache->duree = $this->request->getPost("dureePrevue");
        $tache->dateDebut = $this->request->getPost("dateDebut");
        if($tache->dateDebut == ""){
            $tache->dateDebut = NULL;
        }else{
            $tache->dateDebut = $this->request->getPost("dateDebut");
        }
        $tache->description = $this->request->getPost("description");
        $tache->commentaire = $this->request->getPost("commentaire");
        $tache->prevue = $this->request->getPost('prevue');
        $tache->dateMaj = date("y.m.d");

        $lien = new Lien();
        $lien->id_categorie = $this->request->getPost("listeCategorie");
        $lien->id_projet = $this->request->getPost("listeProjet");
        $lien->id_acteur = $this->request->getPost("listeActeur");
        $lien->id_tache = 1;

        if($lien->id_projet == "@" || $lien->id_acteur == "@" || $lien->id_categorie == "@"){
            $erreurProjet =  "Veuillez associer un projet à la tâche";
            $this->flash->error($erreurProjet);
            $erreurActeur =  "Veuillez associer un acteur à la tâche";
            $this->flash->error($erreurActeur);
            $erreurCategorie =  "Veuillez associer une catégorie à la tâche";
            $this->flash->error($erreurCategorie);

            $this->dispatcher->forward([
                'controller' => "ajoutTache",
                'action' => 'indexAdmin'
            ]);

            return ;
        }


        if (!$tache->save()) {
            foreach ($tache->getMessages() as $message) {
                $this->flash->error($message);
            }
            $this->dispatcher->forward([
                'controller' => "ajoutTache",
                'action' => 'indexAdmin'
            ]);

            return;
        }

        $builder = new Builder();
        $builder->columns('MAX(t.id) as max ');
        $builder->addFrom('tache','t');

        $query = $builder->getQuery();
        $rep= $query->execute();

        foreach ($rep as $r){
            $idTacheMax = $r->max;
        }


        $lien->id_tache = $idTacheMax;

        if (!$lien->save()) {
            foreach ($lien->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "ajoutTache",
                'action' => 'indexAdmin'
            ]);

            return;
        }
        $builder = new Builder();
        $builder->columns('projet.id,projet.avancee,projet.libelle,projet.etat,projet.dateDebut,projet.dateFin,projet.dateFinPrevue,projet.chefProjet');
        $builder->From('tache');
        $builder->join('lien','tache.id = lien.id_tache');
        $builder->join('projet','lien.id_projet = projet.id');
        $builder->where('lien.id_tache = :idTache:',array('idTache'=>$idTacheMax));
        $query = $builder->getQuery();
        // resultat = Le projet par rapport a la tache selectionnée.
        $resultat = $query->execute();

        foreach ($resultat as $r){
            $idProjet = $r->id;
        }

        $builder2 = new Builder();
        $builder2->columns('count(*) as nbTache');
        $builder2->From('lien');
        $builder2->where('lien.id_projet = :idProjet:',array('idProjet'=>$idProjet));
        $query = $builder2->getQuery();
        // resultat 2 = le nombre de tache qu'il y a dans le projet trouvé dans $resultat.
        $resultat2 = $query->execute();

        $builder3 = new Builder();
        $builder3->columns('count(*) as nbTacheTerminee');
        $builder3->From('lien');
        $builder3->join('tache','lien.id_tache = tache.id');
        $builder3->where('lien.id_projet = :idProjet:',array('idProjet'=>$idProjet));
        $builder3->andWhere('tache.etat = :etat:',array('etat'=>"Terminée"));
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
                'action' => 'indexAdmin',
            ]);

            return;
        }

        $this->flash->success("La tâche a été créée avec succès !");

        $this->dispatcher->forward([
            'controller' => "ajoutTache",
            'action' => 'indexAdmin'
        ]);
    }

    public function indexActeurAction(){

        $form = new AjoutTacheActeurForm();
        $this->assets->addCss("css/bootstrap.min.css");
        $this->assets->addCss("css/bootstrap.min.css");
        $this->view->form = $form;
    }

    public function createActeurAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "ajoutTache",
                'action' => 'indexActeur'
            ]);

            return;
        }

        $tache = new Tache();
        $tache->libelle = $this->request->getPost("libelle");
        $tache->duree = $this->request->getPost("dureePrevue");
        $tache->etat = $this->request->getPost("listeEtat");

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

        $tache->description = $this->request->getPost("description");
        $tache->commentaire = $this->request->getPost("commentaire");
        $tache->prevue = $this->request->getPost('prevue');
        $tache->dateMaj = date("y.m.d");

        $lien = new Lien();
        $lien->id_projet = $this->request->getPost("listeProjet");
        $lien->id_categorie = $this->request->getPost("listeCategorie");
        $lien->id_acteur = $this->session->get("id");
        $lien->id_tache = 1;

        if($lien->id_projet == "@" || $lien->id_acteur == NULL || $lien->id_categorie == "@"){
            $erreurProjet =  "Veuillez associer un projet à la tâche";
            $this->flash->error($erreurProjet);
            $erreurCategorie =  "Veuillez associer une catégorie à la tâche";
            $this->flash->error($erreurCategorie);

            $this->dispatcher->forward([
                'controller' => "ajoutTache",
                'action' => 'indexActeur'
            ]);

            return ;
        }

        if (!$tache->save()) {
            foreach ($tache->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "ajoutTache",
                'action' => 'indexActeur'
            ]);

            return;
        }

        $builder = new Builder();
        $builder->columns('MAX(t.id) as max ');
        $builder->addFrom('tache','t');

        $query = $builder->getQuery();
        $rep= $query->execute();

        foreach ($rep as $r){
            $idTacheMax = $r->max;
        }


        $lien->id_tache = $idTacheMax;

        if (!$lien->save()) {
            foreach ($lien->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "ajoutTache",
                'action' => 'indexActeur'
            ]);
            return;
        }

        $builder = new Builder();
        $builder->columns('projet.id,projet.avancee,projet.libelle,projet.etat,projet.dateDebut,projet.dateFin,projet.dateFinPrevue,projet.chefProjet');
        $builder->From('tache');
        $builder->join('lien','tache.id = lien.id_tache');
        $builder->join('projet','lien.id_projet = projet.id');
        $builder->where('lien.id_tache = :idTache:',array('idTache'=>$idTacheMax));
        $query = $builder->getQuery();
        // resultat = Le projet par rapport a la tache selectionnée.
        $resultat = $query->execute();

        foreach ($resultat as $r){
            $idProjet = $r->id;
        }

        $builder2 = new Builder();
        $builder2->columns('count(*) as nbTache');
        $builder2->From('lien');
        $builder2->where('lien.id_projet = :idProjet:',array('idProjet'=>$idProjet));
        $query = $builder2->getQuery();
        // resultat 2 = le nombre de tache qu'il y a dans le projet trouvé dans $resultat.
        $resultat2 = $query->execute();

        $builder3 = new Builder();
        $builder3->columns('count(*) as nbTacheTerminee');
        $builder3->From('lien');
        $builder3->join('tache','lien.id_tache = tache.id');
        $builder3->where('lien.id_projet = :idProjet:',array('idProjet'=>$idProjet));
        $builder3->andWhere('tache.etat = :etat:',array('etat'=>"Terminée"));
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
                'action' => 'indexAdmin',
            ]);

            return;
        }

        $this->flash->success("La tâche a été créée avec succès !");

        $acteur = Acteur::find($lien->id_acteur);
        foreach ($acteur as $a){
            $nomacteur = $a->nom;
            $prenomacteur = $a->prenom;
        }
        $projet = Projet::find($lien->id_projet);
        foreach ($projet as $p){
            $libelleprojet = $p->libelle;
        }
        $categorie = Categorie::find($lien->id_categorie);
        foreach ($categorie as $c){
            $libellecategorie = $c->libelle;
        }

        $subject = "Nouvelle tâche : $tache->libelle dans le projet : $libelleprojet";
        $to = "angerobertluvari@gmail.com";
        $msg ="La tâche : $tache->libelle \nActeur : $prenomacteur $nomacteur \nProjet : $libelleprojet \nCatégorie : $libellecategorie \nEtat : $tache->etat \nDate du début de la tâche : $tache->dateDebut \nDescription de la tâche : $tache->description \nCommentaire de l'acteur : $tache->commentaire";


        $this->dispatcher->forward([
            'controller' => "ajoutTache",
            'action' => 'send',
            'params' => [$to,$subject,$msg],
        ]);
    }

    public function sendAction($to,$subject,$msg)
    {
        $success = mail($to,$subject,$msg);
        if($success){
            $this->flash->success("Le mail a été envoyé avec succès !");
        }else{
            $this->flash->error("Une erreur s'est produite lors de l'envoi du mail !");
        }

        $this->dispatcher->forward([
            'controller' => "ajoutTache",
            'action' => 'indexActeur',
        ]);
    }

}
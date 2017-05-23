<?php

class Acteur extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $nom;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $prenom;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $categorie;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $login;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $mdp;
    public $trigramme;
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("gesteventv2");
        $this->hasMany('id', 'Lien', 'id_acteur', ['alias' => 'Lien']);
        $this->hasMany('id', 'Projet', 'chefProjet', ['alias' => 'Projet']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'acteur';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Acteur[]|Acteur
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Acteur
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}

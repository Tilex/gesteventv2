<?php

class Projet extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=255, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $libelle;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $etat;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $dateDebut;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $dateFinPrevue;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $dateFin;

    /**
     *
     * @var integer
     * @Column(type="integer", length=255, nullable=true)
     */
    public $chefProjet;
    public $avancee;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("gesteventv2");
        $this->hasMany('id', 'Lien', 'id_projet', ['alias' => 'Lien']);
        $this->belongsTo('chefProjet', '\Acteur', 'id', ['alias' => 'acteur']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'projet';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Projet[]|Projet
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Projet
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}

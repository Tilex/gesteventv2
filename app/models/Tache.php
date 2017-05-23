<?php

class Tache extends \Phalcon\Mvc\Model
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
    public $libelle;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $etat;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $duree;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $dateDebut;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $dateFin;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    public $description;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    public $commentaire;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $dateMaj;
    public $prevue;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("gesteventv2");
        $this->hasMany('id', 'Lien', 'id_tache', ['alias' => 'Lien']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'tache';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Tache[]|Tache
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Tache
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}

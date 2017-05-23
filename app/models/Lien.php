<?php

class Lien extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id_projet;

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id_categorie;

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id_acteur;

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id_tache;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("gesteventv2");
        $this->belongsTo('id_acteur', '\Acteur', 'id', ['alias' => 'acteur']);
        $this->belongsTo('id_categorie', '\Categorie', 'id', ['alias' => 'Categorie']);
        $this->belongsTo('id_projet', '\Projet', 'id', ['alias' => 'Projet']);
        $this->belongsTo('id_tache', '\Tache', 'id', ['alias' => 'Tache']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'lien';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Lien[]|Lien
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Lien
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}

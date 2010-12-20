<?php
/**Caracterise une requete sql surcouche de l'objet DB
 *
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 *
 */
class Sql extends Object{

    //proprieter
    private $requete = null;
    private $nom = null;
    private $vide = false;

    Public function __construct($position = null)
    {
        parent::__construct($position);
    }


    Public function initialiserAttribut()
    {

        if($this->construitParXml)
        {
          $this->setNom($this->position['nom']);
          $this->setRequete($this->position['requete']);
          $this->setVide($this->position["vide"]);
        }


    }

    /**
     * permet de modifier l'attribut requete de sql
     * @param $nom attribut nom de l'objet sql
     */
    public function setNom($nom)
    {
        $this->nom = strval($nom);
    }
    /**
     * Permet de lire le nom de l'objet sql
     * @return nom
     */
    public function getNom()
    {
        return $this->nom;
    }

     /**
     * permet de modifier l'attribut requete de sql
     * @param $valeur attribut requete de l'objet sql
     */
    public function setRequete($requete)
    {
        $this->requete = $this->recupValeur($requete);
    }
    /**
     * Permet de lire la reuquete de l'objet sql
     * @return requete
     */
    public function getRequete()
    {
        return $this->requete;
    }

    public function getVide() {
        return $this->vide;
    }

    public function setVide($vide) {
        if($vide == "true")
        {
            $this->vide = true;
        }
    }


    //La balise sql
    private function sql(){
            Core::libs("db");
            $db = new DB;
            $db->lire_requete($this->requete);
            $table = $db->convtableaux("" ,$this->nom);
            //var_dump($table);
            if(is_array($table))
            {
                
                    foreach($table as $key => $v)
                    {
                            //je vais passer le tableaux en parametre de l'objet pour que ces fils y est acces partout
                            self::$variableSql[$this->nom] = $v;
                            $rendu = $this->calculerFils();
                    }
            }
            else if($this->vide)
            {
                    $rendu = $this->calculerFils();
                    
            }
            unset(self::$variableSql[$this->nom]); //une fois la requete terminer l'ont supprimer les donnée stocker dans le tableaux
            return $rendu;
    }

    /**
     * Calcule le rendu html de variable;
     * @return html
     */
    Public function renduHtml()
    {
         return $this->sql();
    }

    /**
     * Calcule le rendu Texte plain de variable;
     * @return html
     */
    Public function renduTextPlain()
    {
         return $this->sql();
    }

}
?>

<?php
/**Caracterise une boucle parcoure les tableaux
 *
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 *
 */
class boucle extends Object{

    //proprieter
    private $nom = null;
    private $vide = false;
    private $valeur = array();

    Public function __construct($position = null)
    {
        parent::__construct($position);
    }


    Public function initialiserAttribut()
    {

        if($this->construitParXml)
        {
          $this->setNom($this->position['nom']);
          $this->setValeur($this->position['valeur']);
          $this->setVide($this->position["vide"]);
        }


    }



    public function getValeur() {
        return $this->valeur;
    }

    public function setValeur($valeur) {
        $this->valeur = self::$variablePhp[$this->recupValeur($valeur)];
    }




    /**
     * permet de modifier l'attribut requete de boucle
     * @param $nom attribut nom de l'objet boucle
     */
    public function setNom($nom)
    {
        $this->nom = strval($nom);
    }

    /**
     * Permet de lire le nom de l'objet boucle
     * @return nom
     */
    public function getNom()
    {
        return $this->nom;
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


    //La balise boucle
    private function boucle(){
           
            if(is_array($this->valeur))
            {
                   
                    foreach($this->valeur as $key => $v)
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
         return $this->boucle();
    }

    /**
     * Calcule le rendu Texte plain de variable;
     * @return html
     */
    Public function renduTextPlain()
    {
         return $this->boucle();
    }

}
?>

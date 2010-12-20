<?php
/**Caracterise une Variable
 *
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 *
 */
class Variable extends Object{

    //proprieter
    private $valeur = null;



    Public function __construct($position = null)
    {
        parent::__construct($position);
        $this->etreParent = false; //cette object ne peut pas avoir d'enfant il est final
     
    }


    Public function initialiserAttribut()
    {

        if($this->construitParXml)
        {
           $this->setValeur($this->position);
        }
    }

    /**
     * permet de modifier l'attribut valeur de variable
     * @param $valeur attribut valeur de l'objet Variable
     */
    public function setValeur($valeur)
    {
        $this->valeur = $this->recupValeur($valeur);
    }
    /**
     * Permet de lire la valeur de l'objet variable
     * @return valeur
     */
    public function getValeur()
    {
        return $this->valeur;
    }


    /**
     * Calcule le rendu html de variable;
     * @return html
     */
    Public function renduHtml()
    {
    
        $rendu = "<div ".$this->idHtml." ".$this->classHtml.">".$this->valeur;
        $rendu .= $this->calculerFils();
        $rendu .= "</div>";
        return $rendu;
    }

    /**
     * Calcule le rendu Texte plain de variable;
     * @return html
     */
    Public function renduTextPlain()
    {

       
        $rendu = $valeur;
        $rendu .= $this->calculerFils();
        return $rendu;
    }



}
?>

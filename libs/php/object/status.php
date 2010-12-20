<?php
/**Caracterise un status de formulaire
 *
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 *
 */
class Status extends ObjectForm{

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
     * permet de modifier l'attribut valeur de status
     * @param $valeur attribut valeur de l'objet Status
     */
    public function setValeur($valeur)
    {
       $this->valeur = $this->recupValeurForm($valeur);

        if($this->valeur == "modification" || $this->valeur == "creation")
        {
            //je modifie le status du formulaire actuel
             self::$statusForm[self::$clee] = $this->valeur;
        }
        else
        {
            Erreur::declarer_dev(30, "Formulaire :".Form::getName());
        }
        
    }
    /**
     * Permet de lire la valeur de l'objet status
     * @return valeur
     */
    public function getValeur()
    {
        return $this->valeur;
    }


    /**
     * Calcule le rendu html de status;
     * @return html
     */
    Public function renduHtml()
    {
        //vide
    }

    /**
     * Calcule le rendu Texte plain de status;
     * @return html
     */
    Public function renduTextPlain()
    {
        $rendu = $valeur;
        return $rendu;
    }



}
?>

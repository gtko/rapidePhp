<?php
/**Caracterise un Choix
 *
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 *
 */
class Choix extends Object{

    private $valeur = null;
    private $choix = null;

    Public function __construct($position = null)
    {
        parent::__construct($position);
    }


    Public function initialiserAttribut()
    {

        if($this->construitParXml)
        {
           $this->setValeur($this->position);
           if(!empty($this->position['egal']))
           {
            $this->setChoix($this->position['egal']);
           }
           else if(!empty($this->position['different']))
           {
            $this->setChoix($this->position['different']);
           }
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
     * permet de modifier l'attribut valeur de variable
     * @param $valeur attribut valeur de l'objet Variable
     */
    public function setChoix($choix)
    {
        $this->choix = $this->recupValeur($choix);
    }
    /**
     * Permet de lire la valeur de l'objet variable
     * @return valeur
     */
    public function getChoix()
    {
        return $this->valeur;
    }


    /**
     * Permet de calculer si le choix est valide ou non
     * @param $choix Quel actions effectuer sur le choix
     * @return string
     */
    private function choixEffectuer()
    {
        $valider = false;//on mets le forrmulaire a faux
        $valeur = $this->recupValeur($this->position['valeur']);//on recupêre la valeur a comparer

        if($this->position['egal'])
        {
          
            if($this->choix == $valeur)
            {
                $valider = true;
            }
        }
        else if($this->position['different'])
        {
          
            if($this->choix != $valeur)
            {
                $valider = true;
            }
        }
        else
        {
            Erreur::declarer_dev(27);
        }

        return $valider;

    }

    /**
     * Calcule le rendu html de variable;
     * @return html
     */
    Public function renduHtml()
    {
        if($this->choixEffectuer())
        {
            $rendu = $this->calculerFils();
        }
        return $rendu;
    }

    /**
     * Calcule le rendu Texte plain de variable;
     * @return html
     */
    Public function renduTextPlain()
    {
        if($this->choixEffectuer())
        {
            $rendu = $this->calculerFils();
        }
        return $rendu;
    }

}
?>

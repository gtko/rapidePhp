<?php
/**Caracterise un bouton envoyer de type submit
 *
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 *
 */
class Submit extends ObjectForm{

    //proprieter
    private $valeur = null;

    Public function __construct($position = null)
    {
        parent::__construct($position);
       
    }


    Public function initialiserAttribut()
    {

        if($this->construitParXml)
        {
         
           $this->setValeur($this->position["valeur"]);
           
        }
    }

    /**
     * permet de modifier l'attribut valeur de Submit
     * @param $valeur attribut valeur de l'objet Submit
     */
    public function setValeur($valeur)
    {
        $this->valeur = $this->recupValeur($valeur);
    }

    /**
     * Permet de lire le valeur de l'objet Submit
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
        $rendu = " <div class='ligne'>";
        $rendu .=  "<input type='submit' value='$this->valeur' ".$this->idHtml." ".$this->classHtml."/>";
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


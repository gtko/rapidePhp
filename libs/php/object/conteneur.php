<?php
/**Caracterise une Variable
 *
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 *
 */
class Conteneur extends Object{


    Public function __construct($position = null)
    {
        parent::__construct($position);
    }


    Public function initialiserAttribut()
    {

        if($this->construitParXml)
        {

        }
    }

   
    /**
     * Calcule le rendu html de variable;
     * @return html
     */
    Public function renduHtml()
    {

        $rendu = "<div ".$this->idHtml." ".$this->classHtml." ".$this->title.">";
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

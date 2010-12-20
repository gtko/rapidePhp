<?php
/**Caracterise un champ select
 *
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 *
 */
class Select extends ObjectForm{

    //proprieter
    private $valeur = null;
    private $name = null;
    private $label = null;


    Public function __construct($position = null)
    {
        parent::__construct($position);

    }


    Public function initialiserAttribut()
    {

        if($this->construitParXml)
        {

           $this->setValeur($this->position["valeur"]);
           $this->setLabel($this->position["label"]);
           $this->setName($this->position["name"]);
           
           $this->verificationDonnee();
        }
    }

    /**
     * permet de modifier l'attribut valeur de Select
     * @param $valeur attribut valeur de l'objet Select
     */
    public function setValeur($valeur)
    {
 
            if(self::$etatForm[self::$clee] == true)
            {
               $this->valeur = $this->recupValeurForm($this->position["name"]);

            }
            else
            {
                $this->valeur = $this->recupValeurForm($valeur);

            }

    }

    /**
     * Permet de lire le valeur de l'objet Select
     * @return valeur
     */
    public function getValeur()
    {
        return $this->valeur;
    }


    /**
     * permet de modifier l'attribut name de Select
     * @param $valeur attribut valeur de l'objet Select
     */
    public function setName($name)
    {
        $this->name = $this->recupValeur($name);
    }


    /**
     * Permet de lire le name de l'objet Select
     * @return valeur
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * permet de modifier l'attribut label de Select
     * @param $valeur attribut valeur de l'objet Select
     */
    public function setLabel($label)
    {
        $this->label = "<label for='".$this->position["name"]."'>".$this->recupValeur($label)."</label>";
    }


    /**
     * Permet de lire le label de l'objet Select
     * @return valeur
     */
    public function getLabel()
    {
        return $this->label;
    }


    /**
     * Calcule le rendu html de variable;
     * @return html
     */
    Public function renduHtml()
    {
        $rendu  = "<div class='ligne'>";
        $rendu .= $this->label;
        $rendu .= "<select title='".$this->getTitle()."' name='$this->name' value='$this->valeur' ".$this->idHtml." ".$this->classHtml.">";
        $rendu .= $this->calculerFils();
        $rendu .= "</select>";
        $rendu .= "<span class='info $this->typeInfo'>".$this->info."</span>";
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


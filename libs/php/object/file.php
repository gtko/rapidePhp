<?php
/**Caracterise un champ file
 *
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 *
 */
class File extends ObjectForm{

    //proprieter
    private $valeur = null;
    private $name = null;
    private $label = null;
    private $type = null;

    Public function __construct($position = null)
    {
        parent::__construct($position);

    }


    Public function initialiserAttribut()
    {

        if($this->construitParXml)
        {

           $this->setValeur($this->position);
           $this->setLabel($this->position["label"]);
           $this->setType($this->position["type"]);
           $this->setName($this->position["name"]);
           $this->verificationDonnee();
        }
    }

    /**
     * permet de modifier l'attribut valeur de File
     * @param $valeur attribut valeur de l'objet File
     */
    public function setValeur($valeur)
    {
        if(self::$etatForm[self::$clee] == true)
        {
            $this->valeur = $this->recupValeurForm($this->position["name"]);
           
        }
        else
        {
            $this->valeur = $this->recupValeur($valeur);
            
        }
    }
    /**
     * Permet de lire le valeur de l'objet File
     * @return valeur
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * permet de modifier l'attribut name de File
     * @param $valeur attribut valeur de l'objet File
     */
    public function setName($name)
    {
        $this->name = $this->recupValeur($name);
    }

    /**
     * Permet de lire le name de l'objet File
     * @return valeur
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * permet de modifier l'attribut type de File
     * @param $valeur attribut valeur de l'objet File
     */
    public function setType($type)
    {
        $this->type = $this->recupValeur($type);
    }

   /**
    * Permet de lire le type de l'objet File
    * @return valeur
    */
    public function getType()
    {
        return $this->type;
    }

    /**
     * permet de modifier l'attribut label de File
     * @param $valeur attribut valeur de l'objet File
     */
    public function setLabel($label)
    {
        $this->label = "<label for='".$this->position["name"]."'>".$this->recupValeur($label)."</label>";
    }

    /**
     * Permet de lire le label de l'objet File
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
        $rendu .= "<input type='file' name='$this->name' value='$this->valeur' ".$this->idHtml." ".$this->classHtml."/>";
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


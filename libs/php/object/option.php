<?php
/**Caracterise un champ option
 *
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 *
 */
class Option extends ObjectForm{

    //proprieter
    private $valeur = null;
    private $label = null;
    private $selectedDefaults = null;
    private $value = null;


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
           $this->setValue($this->position["value"]);
           $this->setSelected($this->position["selected"]);


           //$this->verificationDonnee();
        }
    }

    /**
     * permet de modifier l'attribut valeur de Option
     * @param $valeur attribut valeur de l'objet Option
     */
    public function setValeur($valeur)
    {
        $this->valeur = $this->recupValeur($valeur);
    }
    /**
     * Permet de lire le valeur de l'objet Option
     * @return valeur
     */
    public function getValeur()
    {
        return $this->valeur;
    }

  

    /**
     * permet de modifier l'attribut label de Option
     * @param $valeur attribut valeur de l'objet Option
     */
    public function setLabel($label)
    {
        if(!empty($label))
        {
            $this->label = "label='".$this->recupValeur($label)."'";
        }
    }

    /**
     * Permet de lire le label de l'objet Option
     * @return valeur
     */
    public function getLabel()
    {
        return $this->label;
    }



    public function getSelected() {
        return $this->selectedDefaults;
    }

    public function setSelected($selected) {
        //on recupere la valeur du pere select si il est egal a option alors il est selectionner
        $parent = $this->getParent("select");
       
        
        if(!empty($selected) || $this->value == $parent->getValeur())
        { 
            $this->selectedDefaults = "selected='selected'";
        }

    }


    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        $this->value = $this->recupValeur($value);
    }




    /**
     * Calcule le rendu html de variable;
     * @return html
     */
    Public function renduHtml()
    {
        $rendu .= "<option ".$this->label." value='$this->value' ".$this->idHtml." ".$this->classHtml." ".$this->selectedDefaults.">$this->valeur</option>";
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


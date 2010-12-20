<?php
/**Caracterise une Variable
 *
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 *
 */
class lien extends Object{

    //proprieter
    private $modules = null;
    private $actions = null;
    private $geta = null;
    private $ancre = null;
    private $href = null;


    Public function __construct($position = null)
    {
        parent::__construct($position);

    }


    Public function initialiserAttribut()
    {

        if($this->construitParXml)
        {
            
           $this->setModules($this->position["modules"]);
           $this->setActions($this->position["actions"]);
           $this->setGeta($this->position["geta"]);
           $this->setAncre($this->position["ancre"]);
           
           if(!empty($this->position["lien"]))
           {        
            $this->setLien($this->position["lien"]);
           }
           else
           {
              $this->href = "index.php?modules=".$this->modules.$this->actions.$this->geta.$this->ancre;
           }
        }
    }

    /**
     * permet de modifier l'attribut lien de lien
     * @param $valeur attribut lien de l'objet lien
     */
    public function setLien($valeur)
    {
        $this->href = $this->recupValeur($valeur);
    }
    /**
     * Permet de lire le lien de l'objet lien
     * @return valeur
     */
    public function getLien()
    {
        return $this->lien;
    }

     /**
     * permet de modifier l'attribut Modules de lien
     * @param $valeur attribut Modules de l'objet lien
     */
    public function setModules($valeur)
    {
        $this->modules = $this->recupValeur($valeur);
    }
    /**
     * Permet de lire le Modules de l'objet lien
     * @return valeur
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * permet de modifier l'attribut Actions de lien
     * @param $valeur attribut Actions de l'objet lien
     */
    public function setActions($valeur)
    {
        if(!empty($valeur))
        {
            $this->actions = "&actions=".$this->recupValeur($valeur);
        }
    
    }
    /**
     * Permet de lire l'Actions de l'objet lien
     * @return valeur
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * permet de modifier l'attribut geta de lien
     * @param $valeur attribut geta de l'objet lien
     */
    public function setGeta($valeur)
    {
        if(!empty($valeur))
        {
            $this->geta = "&a=".$this->recupValeur($valeur);
        }
    }
    /**
     * Permet de lire le geta de l'objet lien
     * @return valeur
     */
    public function getGeta()
    {
        return $this->geta;
    }

        /**
     * permet de modifier l'attribut ancre de lien
     * @param $valeur attribut ancre de l'objet lien
     */
    public function setAncre($valeur)
    {
        if(!empty($valeur))
        {
            $this->ancre = "#".$this->recupValeur($valeur);
        }
    }
    /**
     * Permet de lire le geta de l'objet lien
     * @return valeur
     */
    public function getAncre()
    {
        return $this->ancre;
    }

    /**
     * Calcule le rendu html de variable;
     * @return html
     */
    Public function renduHtml()
    {

        $rendu = "<a ".$this->idHtml." ".$this->classHtml." ".$this->title." href='".$this->href."'>";
        $rendu .= $this->calculerFils();
        $rendu .= "</a>";
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

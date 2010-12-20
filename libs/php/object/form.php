<?php
/**Caracterise un Formulaire
 *
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 *
 */
class Form extends ObjectForm{

    //proprieter
    private $modules = null;
    private $actions = null;
    private static  $name = null;

    Public function __construct($position = null)
    {
        parent::__construct($position);
        $this->setRacine($position);
        Core::libs("passagedonnee");
    }


    Public function initialiserAttribut()
    {
        
        
        if($this->construitParXml)
        {
           $this->setModules($this->position["modules"]);
           $this->setActions($this->position["actions"]);
           $this->setName($this->position["name"]);
           
        }

        self::$statusForm[self::$clee] = "creation";

    }





    /**
     * permet de modifier l'attribut Modules de Form
     * @param $valeur attribut Modules de l'objet Form
     */
    public function setModules($valeur)
    {
        if(!empty($valeur))
        {
            $this->modules = $this->recupValeur($valeur);
        }
        else
        {
            $this->modules = Entrer::$modules;
        }

    }
    /**
     * Permet de lire le Modules de l'objet Form
     * @return valeur
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * permet de modifier l'attribut Actions de Form
     * @param $valeur attribut Actions de l'objet Form
     */
    public function setActions($valeur)
    {

        if(!empty($valeur))
        {
             $this->actions = $this->recupValeur($valeur);
        }
        else
        {
             $this->actions = Entrer::$actions;
        }

    }
    /**
     * Permet de lire le Actions de l'objet Form
     * @return valeur
     */
    public function getActions()
    {
        return $this->actions;
    }

        /**
     * permet de modifier l'attribut name de Form
     * @param $valeur attribut name de l'objet Form
     */
    public function setName($valeur)
    {
        self::$name = $this->recupValeur($valeur);
        self::$clee = md5(self::$name);
        self::$valide[self::$clee] = true;
        self::$tableauValeurForm[self::$clee] = (isset($_SESSION['post'][self::$clee]))?$_SESSION['post'][self::$clee]:array();
    }
    /**
     * Permet de lire le name de l'objet Form
     * @return valeur
     */
    public static function getName()
    {
        return self::$name;
    }


    /**
     * Place le formulaire en etat de verification
     */
    private function etatForm()
    {
        //je verifie si le formulaire est en verification
        $verif = PassageDonnee::Singleton();

        if($verif->getClee() == self::$clee)
        {
            self::$etatForm[self::$clee]  = true;
            //on recupere le true sur le form valider
            if($_SESSION['post']['clee'] == self::$clee)
            {
                
                self::$tableauValeurForm[self::$clee] = $_SESSION['post'][self::$clee];
                
            }
        }
        else
        {
            self::$etatForm[self::$clee]  = false;
        }
    }

    /**
     * Calcule le rendu html de variable;
     * @return html
     */
    Public function renduHtml()
    {
        $this->etatForm();
        $rendu = "<form ".$this->idHtml." ".$this->classHtml." name='".self::$name."' method='POST' action='index.php?modules=".$this->modules."&actions=rapide_nettoyeur' enctype='multipart/form-data'>";
        $rendu .= "<input type='hidden' name='actions' value='".$this->actions."'/>";
        $rendu .= "<input type='hidden' name='clee' value='".self::$clee."'/>";
        $rendu .= $this->calculerFils();
        $rendu .= "</form>";
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


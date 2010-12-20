<?php
/**
 * Caracterise un objet en plusieur format.
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 * @modification 
 * -suppression de l'erreur 26 qui dit que la variable php introuvable;
 */
abstract class Object {

    public static $typeRendu = array(
        0 => "Html"
        
    );

    //type de donnée d'un object
    protected static $variablePhp = array(); //tableaux de variable php arrivant de l'exterieur
    protected static $variableSql = array(); //tableaux venant d'une base de donnée
    

    protected $racine;
    protected $position = null;
    protected $class;
    protected $id;
    protected $construitParXml = true;
    protected $etreParent = true;
    protected $rendu;

    protected static $parent = array();
    protected $classHtml = null;
    protected $idHtml = null;
    protected $title = null;
    protected $titleHtml = null;
    /**
     * Le constructeur peut directement definir la position , la class , l'id , et la racine
     * @param $position Pere xml
     */
    public function __construct($position = null , $racine = null , $class = null , $id = null)
    {
         
    }

    /**
     * Permet d'initialiser les attribut d'un object
     */
    abstract function initialiserAttribut();

    /**
     * accesseur set $position
     */
    public function setPosition($position)
    {

        $this->position = $position;
    }

    /**
     * accesseur get $position
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * accesseur set $racine
     */
    public function setRacine($racine)
    {
        $this->racine = $racine;
    }

    /**
     * accesseur get $racine
     */
    public function getRacine()
    {
        return $this->racine;
    }

    public function getParent($name =null) {
        if(is_null($name))
        {
            return self::$parent;
        }
        else
        {
            return self::$parent[$name];
        }
    }

    public function setParent($name,$parent) {
               
            self::$parent[$name] = $parent;
   
    }

    

    /**
     * accesseur tableaux php
     */
    public function  setVariablePhp($tableaux)
    {
        self::$variablePhp = array();
        if(is_array($tableaux))
        {
            self::$variablePhp = $tableaux;
        }
    }

    public function getVariablePhp($index)
    {
        if(key_exists($index,self::$variablePhp))
        {
            return self::$variablePhp[$index];
        }
        else
        {
            //Erreur::declarer_dev(26, "","objet :".$this->position->getName()." ,function : getVariablePhp() , argument : $index");
            return "";
        }
    }

    /**
     * accesseur set $class
     */
    public function setClass($class)
    {
        $this->class = $this->recupValeur($class);
        $this->classHtml = "class='".$this->class."'";
    }

    /**
     * accesseur get $class
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * accesseur set $id
     */
    public function setId($id)
    {
        $this->id = $this->recupValeur($id);
        $this->idHtml = "id='".$this->id."'";
    }

    /**
     * accesseur get $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * accesseur set $title
     */
    public function setTitle($title)
    {
        $this->title = $this->recupValeur($title);
        $this->titleHtml = "title='".$this->recupValeur($title)."'";
    }

    /**
     * accesseur get $id
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * calculer le rendu de la page du xml
     */
    public function rendu()
    {
       
        //on regarde le type de rendu demander
        foreach(self::$typeRendu as $clee => $value )
        {
         
          if(method_exists($this,"rendu".$value))
          {
            $this->rendu = $this->{"rendu".$value}();
          }
        }
        return $this->rendu;
    }




    public function calculerFils()
    {
      
        if($this->etreParent && count($this->position) >= 1)
        {
            foreach($this->position->children() as $enfant)
            {
                
                $nomFils = $enfant->getName();
                if($nomFils != "class" && $nomFils != "id")
                {


                       if(Core::objectExist($nomFils))
                       {
                        //on appel la dependance de l'objet
                        Core::object($nomFils);
                        $fils = new $nomFils();
                        $fils->setPosition($enfant);
                        $this->setParent($this->position->getName(),$this);
                        $fils->initialiserAttribut();

                        //on place la class
                        if(!empty($enfant['class']))
                        {
                            $fils->setClass($enfant['class']);
                        }
                        else if(!empty($enfant->class[0]))
                        {
                            $fils->setClass($enfant->class[0]);
                        }

                        //on place l'id
                        if(!empty($enfant['id']))
                        {
                            $fils->setId($enfant['id']);
                        }
                        else if(!empty($enfant->id[0]))
                        {
                            $fils->setId($enfant->id[0]);
                        }

                        //on place le title
                        if(!empty($enfant['title']))
                        {
                            $fils->setTitle($enfant['title']);
                        }
                        $this->rendu .= $fils->rendu();
                    }

                    }
                }
            
        }
        else if(!$this->etreParent && count($this->position) >= 1)
        {
           
            Erreur::declarer_dev("25","object xml: ".$this->position->getName());
        }
        return $this->rendu;
    }


    /**
     *
     */
    protected function transformationLangue()
    {

    }


    protected function recupValeur($valeur)
    {
            $valeur = strval($valeur);
            $valeur = $this->recupValeurConstante($valeur); //on regarde si la valeur n'est pas une Constante php
            $valeur = $this->recupValeurPhp($valeur); //on regarde si la valeur n'est pas une variable php
            $valeur = $this->recupValeurSql($valeur); //on regarde si la valeur n'est pas une variable sql
            
        
            return $this->patchBlanc($valeur);
    }


    protected function patchBlanc($valeur)
    {
            //on regarde si la valeur contient autre chose que du blanc
            if(preg_match("/^\s+$/", $valeur))
            {
                $valeur = "";
            }

            if(preg_match_all("/^(\s*)(@?.*)(\s*)$/",$valeur,$blancDevant))
            {

                 $valeur = str_replace($blancDevant[1][0],"",$valeur);
                 $valeur = str_replace($blancDevant[3][0],"",$valeur);

            }

            return $valeur;
    }




     protected function recupValeurConstante($valeur)
     {
                //si c'est une constante
                if(preg_match_all('/[A-Z_][A-Z0-9_]+/', $valeur , $tabConst))
                {
                       
                          foreach($tabConst[0] as $clee => $value)
                          {
                             if(defined($tabConst[0][$clee]))
                             {
                                 $valeur = str_replace($tabConst[0][$clee], constant($tabConst[0][$clee]),$valeur);
                             }
                             else if(defined(strtolower($tabConst[0][$clee])))
                             {
                                 $valeur = str_replace($tabConst[0][$clee], constant(strtolower($tabConst[0][$clee])),$valeur);
                             }
                          }
                }

            return $valeur;
     }


     private function recupValeurTableaux($valeur)
     {

        foreach($valeur as $clee => $value)
        {
           //on construit le tableaux

        }
        return $valeur;
     }

     protected function recupValeurPhp($valeur)
     {
            //pour les variable xml en php
         
            //on regarde si c'est un tableau php qui est demander

            if(preg_match('/\$[a-zA-Z0-9_#|]*(\[[a-zA-Z0-9_#|]+\])+/', $valeur , $tab))
            {
                preg_match('/\$([A-Z0-9a-z_#|]*)/', $valeur , $index); //je recupere l'index
                preg_match_all("/\[([a-zA-Z0-9_#|]+)\]/" , $tab[0] , $tet);//je recupere les sous tables

                $index = $this->recupValeur($index[1]);
                if(key_exists($index,self::$variablePhp))
                {
                    $this->valeurPhpTableaux = self::$variablePhp[$index]; // je me place dans le tableaux chercher
                    foreach($tet[1] as $value)
                    {
                        //on construit le tableaux
                        $value = $this->recupValeur($value);
                        if(key_exists($value,$this->valeurPhpTableaux))
                        {
                            $this->valeurPhpTableaux = $this->valeurPhpTableaux[$value];
                            //si se n'est pas un tableaux en enregistre la valeur
                            if(!is_array($this->valeurPhpTableaux))
                            {
                                $valeurTab = $this->recupValeur($this->valeurPhpTableaux);
                            }
                        }
                    }
                    //on remplace la valeur dans le xml
                    $valeur = str_replace($tab[0], $valeurTab,$valeur);

                }
                else
                {
                    $valeur = "";
                }
            }
            else
            {

                if(preg_match_all('/\$([A-Z0-9a-z_]+)/', $valeur , $tabPhp))
                {

                    foreach($tabPhp[0] as $clee => $value)
                    {
                        if(is_array($this->getVariablePhp($tabPhp[1][$clee])))
                        {   
                             self::$variableSql[$value] = $this->getVariablePhp($tabPhp[1][$clee]);
                             return $tabPhp[1][$clee];
                        }
                        else
                        {
                             $valeur = str_replace($tabPhp[0][$clee], $this->getVariablePhp($tabPhp[1][$clee]),$valeur);
                        }
                    }
                }
                else if(preg_match_all('/\$!([A-Z0-9a-z_]+)/', $valeur , $tabPhp)) //si c'est ue variable forme
                {
                    if(method_exists(Form,getForm))
                    {
                        foreach($tabPhp[0] as $clee => $value)
                        {
                            if(key_exists($tabPhp[1][$clee], Form::$tableauValeurForm[Form::$clee]))
                            {
                                    $valeur = str_replace($tabPhp[0][$clee], Form::$tableauValeurForm[Form::$clee][$tabPhp[1][$clee]],$valeur);
                            }
                            else
                            {
                                  $valeur = str_replace($tabPhp[0][$clee], $this->getVariablePhp($tabPhp[1][$clee]),$valeur);
                            }
                        }
                    }
                    else
                    {
                        foreach($tabPhp[0] as $clee => $value)
                        {
                         $valeur = str_replace($tabPhp[0][$clee], $this->getVariablePhp($tabPhp[1][$clee]),$valeur);

                        }
                    }
                }
                else if(preg_match_all('/\$!{([A-Za-z0-9_]*)}([A-Z0-9a-z_]+)/', $valeur , $tabPhp)) //si c'est ue variable forme
                {

                        foreach($tabPhp[0] as $clee => $value)
                        {


                            if(key_exists($tabPhp[2][$clee], Form::$tableauValeurForm[md5($tabPhp[1][$clee])]))
                            {
                                $valeur = str_replace($tabPhp[0][$clee], Form::$tableauValeurForm[md5($tabPhp[1][$clee])][$tabPhp[2][$clee]],$valeur);
                            }
                            else
                            {
                               
                                $valeur = str_replace($tabPhp[0][$clee], $this->getVariablePhp($tabPhp[2][$clee]),$valeur);
                            }
                        }
                }
             
            }

            return $valeur;
     }

     protected function recupValeurSql($valeur)
     {
       
            //pour les variable xml sql en php


             if(preg_match_all('/#([A-Z0-9a-z_]+)\|([A-Z0-9a-z_]+)/', $valeur , $tab))
             {
                 foreach($tab[0] as $clee => $value)
                 {
                     $new_val = self::$variableSql[$tab[1][$clee]][$tab[2][$clee]];
                     $valeur = str_replace($tab[0][$clee], $new_val,$valeur);
                
                 }
             }
            return $valeur;

     }

}
?>

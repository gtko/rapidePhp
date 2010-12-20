<?php
/**\brief langue s'occupe de l'internatinalisation en transformant tous les texte en constante ...\n
 *
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 *
 * Langue s'occupe de l'internationnalisation des texte d'un site\n
 * il va gerer les 3 fichier d'internationnalisation : la source : le fichier (fr).php sourceFr.lang\n
 * @code
 * @endcode
 *
 *
 *
 */
class Langue{


    /* ######### Public ########## */
    public static $instance = null;

    //parametre
    private $texteSource = null; //le texte source a modifier traduire remplacer

    private $langueSource = null; //langue de devellopement
    private $repertoireLang = null;//repertoire de langue
    private $shemaRegexSource = "/{[ ]*\(([A-Z0-9_]+)\)([a-zA-Z0-9_ !éèêâà'Ä@°£\$ç:]+)(\([a-zA-Z0-9_]*\))?}/"; //shema qui va determiner les textes sources
    private $shemaRegexLang = '/define\("([A-Za-z0-9_\(\).? ,]*)"[ ]*,[ ]*"([A-Za-z0-9_\(\).? ,!éèêâà\'Ä@°£\$ç]*)"\)[ ]*;(\/\/ Modules: \([A-Za-z0-9_]*\) Actions: \([A-Za-z0-9_]*\) [A-Za-z]* le [0-9]{2}\/[0-9]{2}\/[0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2}\n)/'; //shema des constante dans le fichier lang

    private $captureLangue = null; //parametre de la capture ds texte en cours;
    private $tableauCapture = array(); // tableaux des textes capturer
    private $tableauActualise = array(); //tableaux qui va mettre a jour les texte dans le fichier langue

    private $drapeauFichier = null;//le drapeau du fichier dans lequel se trouve le texte

    /* ############################### */
    /* ####### Singleton      ######## */
    /* ############################### */

     /**
     * Permet d'instancier l'objet PassageDonnee en singleton evite la double instanciation.
     * @return object
     */
    public static function Singleton()
    {
        if(self::$instance == null)
        {
           self::$instance = new Langue();
        }
        return self::$instance;
    }

    //constructeur
    private function __construct(){

    }


    //dependances

    public function  Objets()
    {
        Core::libs("entrer");
    }

    //accesseur

    /**
     * Le texte a traduire modifier , remplacer;
     * @param $texte
     */
    public function setEntrer($texte)
    {
        $this->texteSource = $texte;
    }

    /**
     * afficher le texte source
     */
    public function getSortie()
    {
        return $this->texteSource;
    }

    /**
     * Permet de modifier la langue de devellopement
     * @param $langue
     */
    public function setLangueSource($langue){
        $this->langueSource = $langue;
    }

    /**
     * affiche la langue de devellopement utiliser
     */
    public function getLangueSource()
    {
        return $this->langueSource;
    }

    /**
     * Modifie les repertoire des fichiers lang
     * @param $repertoire 
     */
    public function setRepertoireLang($repertoire)
    {
        $this->repertoireLang = $repertoire;
    }

    /**
     * affiche les repertoire langue utiliser
     */
    public function getRepertoireLang(){
        return $this->repertoireLang;
    }

    /**
     * Permet de modifier la regex qui capture les textes sources
     * @param $regex
     */
    public function setShemaRegexSource($regex)
    {
        $this->shemaRegexSource = $regex;
    }

    /**
     * affiche la regex qui capture les textes sources
     */
    public function getShemaRegexSource()
    {
        return $this->shemaRegexSource;
    }

    /**
     * Permet de modifier la regex qui capture les textes Constante du repertoire langue
     * @param $regex
     */
    public function setShemaRegexLang($regex)
    {
        $this->shemaRegexLang = $regex;
    }

    /**
     * Affiche la regex qui capture les texte Constante du repertoire langue
     */
    public function getShemaRegexLang(){
        return $this->shemaRegexLang;
    }


    //methode private

    /**
     * placer un drapeau
     */
     private function drapeau($quoi)
     {
         return "// Modules: ".(empty($this->drapeauFichier)?"(".Entrer::$modules.")":$this->drapeauFichier)." Actions: ".(empty(Entrer::$actions)?"(defaults)":"(".Entrer::$actions.")")." ".$quoi." le ".date("d/m/Y H:i:s",time());
     }

    //methode public
    /**
     * Methode qui compile les sources php en transformant les textes en constante
     * il va produire un repertoire des module compiler.(devellopement inutilisable)
     */
    public function compilerSite(){

        //rien poour le moment prochaine mise a jour du framework peut etre

    }

     /**
      * Voir Texte d'un module
      */
     Public function searchTexteModules($modules)
     {
         //va chercher les texte dans un modules
         $test = strval(file_get_contents($this->repertoireLang.$this->langueSource.".php"));
         preg_match_all('/define\("([A-Za-z0-9_\(\).? ,]*)"[ ]*,[ ]*"([A-Za-z0-9_\(\).? ,!éèêâà\'Ä@°£\$ç]*)"\)[ ]*;(\/\/ Modules: \('.$modules.'\) Actions: \([A-Za-z0-9_]*\) [A-Za-z]* le [0-9]{2}\/[0-9]{2}\/[0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2}\n)/',$test, $tableaux);
         foreach($tableaux[2] as $clee => $value)
         {
             echo $tableaux[1][$clee]." => ".$value."<br/>";
         }

     }

     /**
      * Voir Texte d'une actions
      */
     Public function searchTexteActions($actions)
     {
         //va chercher les texte dans un modules
         $test = strval(file_get_contents($this->repertoireLang.$this->langueSource.".php"));
         preg_match_all('/define\("([A-Za-z0-9_\(\).? ,]*)"[ ]*,[ ]*"([A-Za-z0-9_\(\).? ,!éèêâà\'Ä@°£\$ç]*)"\)[ ]*;(\/\/ Modules: \([A-Za-z0-9_]*\) Actions: \([A-Za-z0-9_]*\) [A-Za-z]* le [0-9]{2}\/[0-9]{2}\/[0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2}\n)/',$test, $tableaux);
         foreach($tableaux[2] as $clee => $value)
         {
             echo $tableaux[1][$clee]." => ".$value."<br/>";
         }

     }

     /**
      * Voir un etat modifier ajouter traduit corriger
      */
     Public function searchTexteEtat($etat)
     {
         //va chercher les texte ayant pour etat
         $test = strval(file_get_contents($this->repertoireLang.$this->langueSource.".php"));
         preg_match_all('/define\("([A-Za-z0-9_\(\).? ,]*)"[ ]*,[ ]*"([A-Za-z0-9_\(\).? ,!éèêâà\'Ä@°£\$ç]*)"\)[ ]*;(\/\/ Modules: \([A-Za-z0-9_]*\) Actions: \([A-Za-z0-9_]*\) '.$etat.' le [0-9]{2}\/[0-9]{2}\/[0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2}\n)/',$test, $tableaux);
         foreach($tableaux[2] as $clee => $value)
         {
             echo $tableaux[1][$clee]." => ".$value."<br/>";
         }

     }


    /**
     * Permet de modifier la langue utiliser par le site
     * il utilise un cookie pour cela
     * @param $langue
     */
    public function selectLang($langue){

        if(empty($_COOKIE["langueSite"]) || $_COOKIE["langueSite"] != $langue)
        {
            Entrer::$langue = $langue;
            setcookie("langueSite", $langue, (time()+3600)*34, "/");
        }
        else
        {
           Entrer::$langue = $_COOKIE["langueSite"];
          
        }
    }

    public function recupLang()
    {
        if(empty($_COOKIE["langueSite"]))
        {
            return LANGUE_DEFAULTS;
        }
        else
        {
            return $_COOKIE["langueSite"];
        }

    }

    /**
     * Generer le fichier de traduction
     */

    public function genererTraduction()
    {

    }

    /**
     * on demarre la capture des texte que l'on va modifier et mettre a jour
     */
    public function bootcapture()
    {
       //je recupere le fichier langue
	$this->captureLangue = strval(file_get_contents($this->repertoireLang.$this->langueSource.".php"));
       //je supprime les balise php
	$this->captureLangue = substr($this->captureLangue , 0,-3);

       
        if(preg_match_all($this->shemaRegexSource, $this->texteSource , $this->tableauCapture))
	{
          $this->remplacerTexte();
          $this->actualiseLangue();
	}

    }

    /**
     * Remplace les textes dans le texte source
     */
    public function remplacerTexte(){

           foreach($this->tableauCapture[0] as $clee => $value)
           {

   

             if(defined($this->tableauCapture[1][$clee]))
             {
                 $this->texteSource = str_replace($this->tableauCapture[0][$clee], constant($this->tableauCapture[1][$clee]),$this->texteSource);
             }
             else if(defined(strtolower($this->tableauCapture[1][$clee])))
             {
                 $this->texteSource = str_replace($this->tableauCapture[0][$clee], constant(strtolower($this->tableauCapture[1][$clee])),$this->texteSource);
             }
          }
    }

    /**
     * mets a jour les fichier langue par rapport a la source php
     */
    public function actualiseLangue(){

         //on ecrit les constantes ou on les mets a jours
         preg_match_all($this->shemaRegexLang, $this->captureLangue , $this->tableauActualise);
         foreach($this->tableauCapture[0] as $clee => $value)
         {
             //le drapeau de localisation
             $this->drapeauFichier = $this->tableauCapture[3][$clee];
            if(preg_match('/"'.$this->tableauCapture[1][$clee].'"/' ,$this->captureLangue))
            {
                   foreach( $this->tableauActualise[0] as $key => $val)
                   {
                        if( $this->tableauActualise[1][$key] == $this->tableauCapture[1][$clee] && $this->tableauActualise[2][$key] != $this->tableauCapture[2][$clee])
                        {
                         
                           $remplace = 'define("'.$this->tableauCapture[1][$clee].'","'.$this->tableauCapture[2][$clee].'");'.$this->drapeau("modifier")."\n";
                           $this->captureLangue = str_replace( $this->tableauActualise[0][$key], $remplace,$this->captureLangue);
                        }
                   }
            
            }
            else
            {
             if(!empty($this->tableauCapture[1][$clee]) && !empty($this->tableauCapture[2][$clee]))
             {
               $this->captureLangue .= 'define("'.$this->tableauCapture[1][$clee].'","'.$this->tableauCapture[2][$clee].'");'.$this->drapeau("ajouter")."\n";
             }
            }
            $this->drapeauFichier = null;
         }

        //on enregistre le fichier lang
	$this->captureLangue .= " ?>";
	$enregistrerOk = file_put_contents($this->repertoireLang.$this->langueSource.".php",$this->captureLangue);

        if($enregistrerOk == false)
        {
            Erreur::declarer_dev(11 , "  Le fichier ".$this->repertoireLang.$this->langueSource.".php");
        }

    }

}
?>

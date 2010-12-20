<?php
/**\brief passageDonnée s'occupe de tout les passage de donnée possible en session , en POST et en GET.\n
 *
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 *
 * PassageDonnee , Gère le passage des données des session par les data , des post par les forms \n
 * ainsi que des get et du du geta du framework.\n
 * L'objet passagedonnée est un singleton.\n
 *
 * Modification :\n
 * le 16 novembre 2010 => \n
 * -permet de rajouter en plusieur fois des donnée a la session \n
 * -suppression de la function split dans setData car deprecier\n
 *
 * @code
 *  Exemple pour instancier PassageDonnee :
 *  $objet = PassageDonnee::Singleton();
 *  $objet->setData("sessionTest" , "fourmis" , "famille=insect");
 *  $objet->getData("sessionTest");
 * @endcode
 *
 *
 *
 */

class PassageDonnee{


    /* ############################### */
    /* ######### Propriéter ########## */
    /* ############################### */

	/* ######### Public ########## */
        public static $instance = null;


	/* ######### Private ########## */
        /**
         * Propriéter qui stocke le dernier $_POST.
         * @var Array
         */
	private $post = null;

        /**
         * Propriéter qui stocke le dernier $_GET.
         * @var Array
         */
        private $get = null;
        /**
         * Actions demander lors du passage de donnée d'un formulaire.
         * @var String
         */
        private $actions = null;

        /**
         * Get a du framework rapidePhp.
         * @var String
         */
	private $geta = null;
        /**
         * Clee d'identification d'un formulaire pour eviter la validation de 2 formulaire.
         * @var String
         */
	private $clee = null;

        /**
         * Historique du geta sur 5 niveaux.
         * @var array
         */
        private $hisGeta = array(
            0 => null,
            1 => null,
            2 => null,
            3 => null,
            4 => null
        );

        /**
         * Passage de donnée par session
         * @var array
         */
        private $data = array();
        /**
         * Pour savoir si l'ont supprime la session
         * @var boolean
         */
        private $supprimerData = false;

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
           self::$instance = new PassageDonnee();
        }
        return self::$instance;
    }

    /* ############################### */
    /* ####### Constructeur   ######## */
    /* ############################### */

        /**
         * Constructeur passageDonnee , il verifie a la construction de l'objet les session en cour de data et de l'historique geta.
         */

	private function __construct(){


        //chargement de la clee
            if(isset($_SESSION["clee"]) && !empty($_SESSION["clee"]))
            {
                $this->clee = $_SESSION["clee"];
            }

        //on recupere la session histgeta
        if(!empty($_SESSION['passageDonnee']['hisgeta']))
        {
            $this->hisGeta = $_SESSION['passageDonnee']['hisgeta'];
        }


        //on recupere les datas si il existe
        if(!empty($_SESSION['passageDonnee']['data']))
        {
            $this->data = $_SESSION['passageDonnee']['data'];
        }

		//si le post existe alors on l'enregistre dans l'objet	
		if(isset($_POST))
		{
			$this->setPost($_POST);
            $this->setActions($_POST['actions']);
            $this->setGeta($_POST['geta']);



		}

        if(isset($_GET['a']))
        {
            $this->setGeta($_GET['a']);
        }

        $this->setGet($_GET);
	}

    /* ############################### */
    /* ####### Destructeur    ######## */
    /* ############################### */

        /**
         * Destructeur de passagedonnee , enregistre ou efface les session data et historique geta .
         */
	Public function __destruct(){
        //on enregistre la session hisgeta
        $_SESSION['passageDonnee']['hisgeta'] = $this->hisGeta;

        //on enregistre la session data
        if(!empty($this->data) && !$this->supprimerData)
        {
          $_SESSION['passageDonnee']['data'] = $this->data;
        }
        else
        {
            unset($_SESSION['passageDonnee']['data']);
        }
	}
	
    /* ############################### */
    /* ####### Accesseur SET ######### */
    /* ############################### */

    /**
     * accesseur set $Post
     * @return void
     */
    public function setPost($post = null){
        //si le post est different de vide
        if(!empty($post))
        {
           //creation de la clée d'identification du formulaire
           $this->post = $_POST;
        }
    }


     /**
     * accesseur set $get
     * @return void
     */
    public function setGet($get = null){
       if(is_array($get))
       {
           //je parcour le tableaux des get pour recuperer les valeur passer
           foreach($get as $clee => $valeur)
           {
               if($clee != "modules" && $clee != "actions" && $clee != "a" && $clee != "clee")
               {
                   $this->get[$clee] = $valeur;
               }
           }    
       }
    }

    /**
     * accesseur set actions
     * @return void
     */
    public function setActions($actions = null){

        //si l'actions n'existe pas
        if(!empty($actions))
        {
            $this->actions = $actions;
        }
    }

    /**
     * accesseur set geta
     * @return void
     */
    public function setGeta($geta = null){

        //si l'actions n'existe pas
        if(!empty($geta))
        {
            $this->geta = $geta;
            $this->construireGeta($geta);

        }
    }

    /**
     * acceseur set de data , le premier parametre represente le nom du tablaux declarer.
     * @code
     *  exemple pour crée une session avec 2 entrer:
     *    //instanciation de l'objet passageDonnee  
     *    $objet = PassageDonnee::Singleton();
     *    //on crée une session "sessionTest" avec 2 entrer : [1] = Test1 , [indexTest] = test2 
     *    $objet->setData("sessionTest" , "test1" , "indexTest=test2");
     * @endcode
     * @return void
     */
     Public function setData(){
       // je recupere le nombre de parametre envoyer ainsi que le tableaux de ceux ci
        $tableau_param =  func_get_args();

         foreach( $tableau_param as $clee => $value)
         {

            if($clee == 0)
            {
                $sousTable = $value;
                if(!key_exists($value, $this->data))
                {
                    $this->data[$value] = array();
                }
            }
            else
            {
                 if(preg_match("/([a-zA-Z0-9_ ]+)=([a-zA-Z0-9_ ]+)/", $value , $decouper))
                 {
                     //$decouper = split("=", $value);
                     $this->data[$sousTable][$decouper[1]] = $decouper[2];
                 }
                 else
                 {
                
                    $this->data[$sousTable][] = $value;
                 }
            }
         }
    }




    /* ############################### */
    /* ####### Accesseur Get ######### */
    /* ############################### */

    /**
     * accesseur get $Post
     * @return postArray
     */
    public function getPost(){
         return $this->post;
    }

     /**
     * accesseur get $actions
     * @return ACTIONS
     */
    public function getActions(){
         return $this->actions;
    }

    /**
     * accesseur get $geta
     * @param index si ce parametre est placer sert a remonter dans l'historique du geta
     * @return Geta
     */
    public function getGeta($index = null){
        //si l'index est different de null on regarde dans l'historique
        if(!is_null($index))
        {
            return $this->hisGeta[$index];
        }  
        else
        {
           return $this->geta;
        }
    }

    /**
     * accesseur get $clee
     * @return String
     */
    public function getClee(){
         return $this->clee;
    }

     /**
     * accesseur get $get , si l'on choisi de placer un index il se comportera comme un tableaux.
     * @param index  index du tableaux get
     * @return GetArray
     */
    public function getGet($index = null){

        if($index == null)
        {
            return $this->get;
        }
        else
        {
            return $this->get[$index];
        }
    }

    /**
     * accesseur get $data , on peut effectuer differente action sur cette accesseur
     *
     * @param index index du tableaux data
     * @param qui  index du sous tableaux demander
     * @param supprimer sert a supprimer ou non le tableaux apres lecture
     * @code
     *  exemple de recuperation :
     *    //instanciation de l'objet passageDonnee
     *     $objet = PassageDonnee::Singleton();
     *    //on recupere l'entrer indexTest de sessionTest , est on supprime le tableaux sessionTest;
     *     $objet->getData("sessionTest" ,"indexTest" , true);
     * @endcode
     * @return DataArray
     */
    public function getData($index = null ,$qui = null ,  $supprimer = false){

        $this->supprimerData = $supprimer;

        if($index == null)
        {
            return $this->data;
        }
        else
        {
            if(is_null($qui) || empty($qui))
            {
                return $this->data[$index];
            }
            else
            {
                return $this->data[$index][$qui];
            }
        }
    }

    /* ############################### */
    /* ####### Methode private ####### */
    /* ############################### */


    //private :
    /**
     * construction de la session post avec la clée d'identification
     * il va construire la session post
     * @return void
     */
    private function construireSession()
    {
        //on construit le session post
        foreach($this->post as $clee => $valeur)
        {
           if($clee != "clee" && $clee != "actions" && $clee != "geta")
           {
             $_SESSION['post'][$this->clee][$clee] = $valeur;
           }
        }

    }

    /**
     * construire l'historique du geta jusqu'a 5 niveaux
     */
    private function construireGeta($geta)
    {
        //on enregistre l'historique dans une variable
        $his0 = $this->hisGeta[0];
        $his1 = $this->hisGeta[1];
        $his2 = $this->hisGeta[2];
        $his3 = $this->hisGeta[3];
        //on descend d'un cran les geta dans l'historique
        $this->hisGeta[0] = $geta;
        $this->hisGeta[1] = $his0;
        $this->hisGeta[2] = $his1;
        $this->hisGeta[3] = $his2;
        $this->hisGeta[4] = $his3;

    }



    /* ############################### */
    /* ####### Methode public ######## */
    /* ############################### */

    /**
     * Methode qui redirige la page une fois que les informations post auront etait traiter.
     * Si le geta existe il sera inclus dans la redirection.
     */
    public function redirectionForm()
    {
        //on recupere l'actions ou l'on va se rediriger
         if(!empty($this->geta))
         {
           header("location: index.php?modules=".$_GET['modules']."&actions=".$this->actions."&a=".$this->geta."&clee=".$this->clee);
         }
         else
         {
           header("location: index.php?modules=".$_GET['modules']."&actions=".$action."&clee=".$this->clee);
         }

    }

    /**
     * supprimer session data
     */
    public function supprimerData($index = NULL)
    {
       if(is_null($index))
       {
           Erreur::declarer_dev(1, "objet : passageDonnee , methode : supprimerData , arguments = index");
       }
       else
       {
           if(is_array($this->data) && key_exists($index, $this->data))
           {
               foreach($this->data as $clee => $value)
               {
                   if($clee != $index)
                   {
                       $data[$clee] = $value;
                   }
               }
               $this->data = $data;
           }
           
       }

    }


    /**
     * Methode pour detruire la session du nettoyeur
     *
     */
    Public function detruirePost(){
            //detruit la session post
           unset($_SESSION['post']);
    }



    /**
     * methode pour voir l'historique des geta pratique en devellopement
     */
    public function voirHistorique()
    {
        var_dump($this->hisGeta);
    }

     /**
     * methode pour voir Le data pratique en devellopement
     */
    public function voirData()
    {
        var_dump($this->Data);
    }


     
}
?>

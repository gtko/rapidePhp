<?php

/**
 * Model formualire version 2.0 ,il genere un formulaire et effectue les verifications
 * @param $var_xml egal a un fichier xml charger par l'objet xml
 * @method formulaire
 * @copyright gtux
 * @author ohanessian gregoire
 */
class Formulairev2 extends GeneralLibs {
   
    /** si le formulaire est en etest on affiche tout meme les erreurs remplit par une phrase */
    Public $var_test;
    public $var_xml;
    public $var_donnee = array();
    public $var_a = null;

     Public $etat_form;

    public $var_id;

    public $var_valide_form;
    public $var_erreur_form;


    /**
     *modifier la valeur de l'action du formulaire
     * @var string
     */
     public $new_actions = "";

    private $preremplir = null;

    /*variable privee*/

     private  $different = null;

    private $var_array;
    private $var_enf;
   
    private $var_clee_array;

    private $var_petitchild;
    private $var_valeur;
    private $var_principal_tableaux;
    private $var_principal_child;
    private $var_actuel_dbtable;
    private $var_modif_dbtable;


    // variable qui nous indique la validiter du formulaire
    public $form_valide_interne;
    public $form_valide;


    Public function objets(){

        Core::libs("db","java");
        
    }



    /*** accesseur public **/

    /***
     * function qui sert a preremplir des champ d'un formulaire
     * Syntaxe a la css : "
     * nomChamp:Valeur;
     * nomChamp2:Valeur2;"
     */

    Public function set_preremplir($variable)
    {

        /**je decoupe la variable par pair **/

        $pair = explode(";",$variable);

        /*je decoupe les pair en 2 la clee la value*/

        foreach($pair as $clee => $value)
        {

            $attribu = explode(":",$value);

            $this->preremplir[$attribu[0]] = $attribu[1];


        }
   

    }

    Public function formulaire(){


        /** je regarde si le formulaire doit s'afficher ou etre verifier **/

        if($_GET['form'] == 'verif')
        {
                          if($_GET['java'] == true)
                          {
                                  $this->var_donnee['table_1'] = $_POST;
                         
                          }
                          else
                          {
                              $this->var_donnee = $_SESSION['post'];
                          }
                       
                         return $this->verifier_formulaire();
                 
        }
        elseif(!isset($_GET['form']) && empty($_GET['form']))
        {
           $_SESSION['formulairev2_id'] = $_GET['a'];
           return $this->afficher_formulaire();
        }
        else
        {
            echo "<strong style='color:red;font-size:20px'>ERREUR DU GET form</strong>";
            return $this->afficher_formulaire();
        }

    }



    /**
     * function qui s'occupe de l'affichage d'un formulaire
     * @param $this->var_xml
     * @author Ohanessian gregoire
     * @copyright gtux
     */


    Public function afficher_formulaire()
    {



        /*je verifie les entre de l'objet*/
        $retour = $this->verif_entre();
        if(!$retour)
            {
                return $retour;
            }

        /*je place Le debut de la balise form avec ses option*/
            /*c'est a dire je regarde si l'attr modules et vide ou non et l'attr actions pour effectuer les changements si je veux un fonctionnement different*/
        $modules = (empty($this->var_xml['modules']))?$_GET['modules']:$this->var_xml['modules'];
        $actions = "rapide_nettoyeur";
        $html ="<div class='".$this->var_xml['name']."'>";
        $html .= "<form name='".$this->var_xml['name']."' method='POST' action='index.php?modules=".$modules."&actions=".$actions."' enctype='multipart/form-data'>";
      
        /* je crée le champ hidden actions qui va indiquer au rapide_netoyyeur quel actriosn appeler pour traiter le formulaire */
       if($this->var_a != null)
       {
                 $html .="<input type='hidden' name='geta' value='".$this->var_a."'/>";

       }

       if(empty($this->new_actions))
       {
              $html .="<input type='hidden' name='actions' value='".$this->var_xml['actions']."'/>";
       }
       else
       {
               $html .="<input type='hidden' name='actions' value='".$this->new_actions."'/>";
       }


           /** je retourne le contenu generer**/




        /** nouvelle façon de faire avec creation modification **/
        switch($this->etat_form)
                {
                     case 'creation':


                             /** fixe bug des array_key_exist**/
                             if(!is_array($this->var_donnee) || empty($this->var_donnee))
                             {
                                $this->var_array = array();
                                $this->var_clee_array = 'table_1';
                                $html .= $this->parcourir_xml();

                             }
                             else
                             {
                               $html .= $this->tableaux_donnee();
                             }

                        break;

                     case 'modification' :
                            
                             $html .= $this->tableaux_donnee();
                        break;


                }

                return $html;
                
              
    }


    /** function qui va parcourire le tableaux de donnée en entré
     * @return la valeur du tableaux
     * @author Ohanessian gregoire
     * @copyright gtux
    **/
    private function tableaux_donnee()
    {
            if(!is_array($this->var_donnee))
            {
             
               return   "<strong style='color:red' >La variable var_donnée en entré de l'objet formulaire n'est pas un array</strong>";
            }
            foreach($this->var_donnee as $clee => $value)
                {
                    if(is_array($value))
                        {

                            $this->var_array = $value;
                            $this->var_clee_array = $clee;
                            $html .= $this->parcourir_xml();

                        }
                }

                return $html;



    }

    /** function qui va parcourire le xml en entré
     * @return la valeur du xml
     * @author Ohanessian gregoire
     * @copyright gtux
    **/
    private function parcourir_xml()
    {

            /*je parcour 1  a 1 Les elements du xml pour construire le form avec*/
                foreach ($this->var_xml->children() as $child)
                    {

                            /** je vais empecher les div sans name de s'afficher**/
                            if(!empty($child['name']))
                            {

                                         
                                $html .= "<div id='ligne' class='".$child['name']."'>";

                                $this->var_enf = $child;
                            
                                $html .= $this->{$child->getName()}();

                                /** il on affiche Les erreurs si il y en a **/
                                $name .=$child['name'];



                                if($this->form_valide_interne != "true")
                                {
                                    if(!empty($this->var_erreur_form[$name]))
                                    {

                                        $html .= $this->var_erreur_form[$name];

                                    }
                                    else
                                    {
                                        if(!empty($this->var_valide_form[$name]))
                                        {
                                             $html .= $this->var_valide_form[$name];
                                        }
                                    }
                                 }
                                    unset($name);
                                    $html .= "</div>";
                             
                            }
                            else
                            {

                                // j'execute les function lier au balise meme si je ne les affiche pas
                                $this->var_enf = $child;
                                $html .=$this->{$child->getName()}();
                            }

                  }

                

        $html .="</div>";
        $html .="</form>";
       
        return $html;





    }



    /**
     * tous se qui concerne Les champ input
     */
    Private function input()
    {

        switch($this->var_enf['type'])
        {
            case 'text':
                if(!empty($this->var_enf['label']))
                {
                   $label = "<div class='label'>".$this->verif_constant($this->var_enf['label'])."</div>";
                }
                
                $html .= "$label<div class='formx'><input type='text' name='$this->var_clee_array[".$this->var_enf['valeur']."]' value='".$this->recup()."'/></div>";


                break;

           case 'password':
                if(!empty($this->var_enf['label']))
                {
                   $label = "<div class='label'>".$this->verif_constant($this->var_enf['label'])."</div>";
                }

                $html .= "$label<div class='formx'><input type='password' name='$this->var_clee_array[".$this->var_enf['valeur']."]' value='".$this->recup()."'/></div>";

               break;

           case 'checkbox':
                if(!empty($this->var_enf['label']))
                {
                   $label = "<div class='label'>".$this->verif_constant($this->var_enf['label'])."</div>";
                }

                $html .= "$label<div class='formx'><input type='checkbox' name='$this->var_clee_array[".$this->var_enf['valeur']."]' value='".$this->recup()."'/></div>";

               break;
               
           case 'checkbox_db':
         
                $database = new DB;
                $database->var_base = $this->verif_constant($this->var_enf['base']);
                $database->table = $this->var_enf['table'];

                if(isset($this->var_enf['requete']))
                {
                    $database->lire_requete(strval($this->var_enf['requete']));
                }
                else {
                      $database->lire_DB("","",strval($this->var_enf['condition']));
                }
                $table = $database->convtableaux();

                if(is_array($table))
                {
                          foreach($table as $clee => $value)
                          {

                                if(is_array($this->var_array[strval($this->var_enf['valeur'])]))
                                {
                                          foreach($this->var_array[strval($this->var_enf['valeur'])] as $clee => $v)
                                          {
                                                    $test = ltrim(stripcslashes($v));
                                                    if($test == $value[strval($this->var_enf['valeur'])])
                                                    {

                                                          $cocher = "checked='checked'";
                                                   }


                                          }
                                }
                                 $html .= "$label<div class='formx'><input type='checkbox' name='$this->var_clee_array[".$this->var_enf['valeur']."][".$value[strval($this->var_enf['valeur'])]."]'  $cocher  value='".$value[strval($this->var_enf['valeur'])]."'/>".$value[strval($this->var_enf['titre'])]."</div>";
                                  unset($cocher);
                          }
                }

               break;

            case 'submit':
                if(!empty($this->var_enf['label']))
                {
                   $label = "<div class='label'>".$this->verif_constant($this->var_enf['label'])."</div>";
                }
               
                $html .= "$label<div class='formx'><input type='submit' name='$this->var_clee_array[".$this->var_enf['valeur']."]' value='".$this->var_enf['valeur']."'/></div>";
                break;
                
                case 'hidden':
               
                $html .= "<input type='hidden' name='$this->var_clee_array[".$this->var_enf['valeur']."]' value='".$this->recup()."'/>";

                break;
            default:
                $html .= "Le type ".$this->var_enf['type']." n'existe pas";
                break;
        }

        

        return $html;


    }

    /**
     * s'occupe de recuperer les valeur des champs
     */

    Private function recup()
    {

        $clee_tab = strval($this->var_enf['valeur']);


        if(!empty($this->var_array))
        {
        
                    $html = ltrim(stripcslashes($this->var_array[$clee_tab]));
        
        }

        if(!empty($this->preremplir[$clee_tab]) &&$_GET['form'] != 'verif')
        {
            $html =  ltrim(stripcslashes($this->preremplir[$clee_tab]));
        }

        return $html;

    }


    /**
     * tous se qui concerne Les champ select
     */
    Private function select()
    {

        /*je regarde de quel type de select j'ai affaire*/
        $multiple = (empty($this->var_enf['multiple']) ||$this->var_enf['multiple'] == "Non" )?"":"multiple='multiple'";

        /*je recupere Les champ option du xml*/
        foreach($this->var_enf->children()   as $k)
                {
                        if(empty($k['table']))
                            {
                          
                                if($this->recup() == $k['value'])
                                    {
                                        $selected = "selected='selected'";
                                        $option .= "<option value='".$k['value']."' $selected>".$this->verif_constant($k)."</option>";
                                    }
                                    else
                                    {
                                        $option .= "<option value='".$k['value']."'>".$this->verif_constant($k)."</option>";

                                    }
                                 
                            }
                        else
                            {
                                $database = new DB;
                                $database->var_base = "";
                                $database->table = $k['table'];
                                $requete = $database->lire_db();

                                $table = $database->convtableaux($requete,'option');



                                foreach($table as $clee => $val)
                                    {
                                        if(is_array($val))
                                            {
                                                $value .= $k['value'];
                                                $titre .= $k['titre'];
                                              
                                                if($k['ajout'] == 'true')
                                                    {
                                                        /** je recupere le texte a afficher avec la variable et je remplace Le {quelquechose} par la variable*/
                                                        $chaine = $this->verif_constant($k);
                                                        $final = str_replace("{".$titre."}", $val[$titre], $chaine);
                                                    }
                                                    else
                                                    {
                                                        $final = $val[$titre];
                                                    }

                                                    if($this->recup() == $val[$value])
                                                    {
                                                        $selected = "selected='selected'";
                                                          
                                                    }
                                    
                                                $option .="<option value='".$val[$value]."' $selected $multiple>".$final."</option>";
                                                unset($value);
                                                unset($titre);
                                                unset($selected);
                                                unset($final);
                                        }


                                }
                        }
                }

                if(!empty($this->var_enf['label']))
                {
                   $label = "<div class='label'>".$this->verif_constant($this->var_enf['label'])."</div>";
                }

                $html .= "$label




                <div class='formx'>
                    <select name='$this->var_clee_array[".$this->var_enf['valeur']."]' $multiple>
                        $option
                    </select>
                </div>";
       
        return $html;


    }


        /**
         * function qui va gerer le champ input file qui va avoir 2 fonctinnement un pour les image et les autre pour les fichier de base
         * avec la possibilité de rajouter des option differente pour les autre format de fichier tel que le pdf etc ...
         * @param aucun
         * @author Ohanessian gregoire
         * @copyright gtux
         */

         Private function fichier()
        {

              /*dans un premier temps je recupere toutes les variable importante et si elle manque je le signale */
             if(empty($this->var_enf['rep']))
                 {
                        return "<br/> je suis l'input file et il me manque L'attr rep dans Mes options.<br/>";
             }

             
             $rep = constant($this->var_enf['rep']);

             /*Je vais faire un choix en rapport avec le type du file */
             /*pour le moment je ne prevoit que 2 possibilite Les images uploader et le reste comme des fichier lambda */

             switch($this->var_enf['type'])
                     {

                     case 'image':
                            /*suivant l'etat du formulaire je peut sois afficher l'image deja existante sois afficher une image de type vide*/
                            switch($this->etat_form)
                             {
                                    case 'modification':
                                             $image = $this->var_enf['image'];
                                        break;

                                    case 'creation':
                                            $image = $this->var_enf['vide'];
                                        break;

                                    default :
                                          return "erreur Le formulaire n'as pas d'etat";
                                       break;
                             }
                             /*je vais chercher le nom de l'image dans le tableaux de donneé*/
                             /*si la valeur n'est pas une clée du tableaux alors je recherche dans le rep si le fichier existe
                              * et si le fichier n'existe pas j'essaye de mettre l'image vide
                              * et si l'image vide n'existe pas je retourn un erreur
                              */
                             $clee_enf .= $this->var_enf['valeur'];
                              if(array_key_exists($clee_enf, $this->var_array))
                                     {
                                     
                                     $nom_image = $this->var_array[$clee_enf];
                                     
                             }
                             else
                                 {
                                    $clee_format .= $this->var_enf['format'];
                                  
                                    if(file_exists($rep.$this->var_array[$clee_enf].".".$this->var_enf['format']))
                                     {

                                        $nom_image = $this->var_enf['valeur'];

                                    }
                                    else
                                    {
                                        $clee_enf2 .=  $this->var_enf['vide'];

                                        if(file_exists($rep.$clee_enf2.".".$this->var_enf['format']))
                                        {
                                            $nom_image = $this->var_enf['vide'];
                                        }
                                        else
                                         {
                                            $src = "erreur L'image est introuvable";

                                        }
                                    }

                             }

                                                   /* Si l'image existe je remplace le $src*/
                             if(file_exists($rep.$nom_image.".".$this->var_enf['format']))
                                 {
                                    $src = "<img class='img_preview' src='".$rep.$nom_image.".".$this->var_enf['format']."' alt=''/>";
                                 }

                         
                             $html .= (empty($this->var_enf['label']))?"":"<div class='label'>".$this->verif_constant($this->var_enf['label'])."</div>";
                             $html .= $src;
                             $html .="<input type='hidden' name='$this->var_clee_array[$clee_enf]' value='$nom_image' />";
                             $html .= "<div class='formx'><input name='".$this->var_enf['name']."' type='file'  ></div>";

                              /*j'efface les clee*/
                             unset($clee_enf);
                             unset($clee_enf2);
                         break;

                     default:

                             $html .= (!empty($this->var_enf['label']))?"":"<div class='label'>".$this->verif_constant($this->var_enf['label'])."</div>";
                             $html .= "<div class='formx'><input name='".$this->var_enf['name']."' type='file'  ></div>";

                         break;

             }

             return $html;


         }

      /**
       * tous se qi concerne les textarea
       */
       Private function textarea()
         {//comme cela la securiter ne depend que de la secu personne


            /**
             * je regarde son type
             * j'estime a 2 type possible le type fichier | le type html | ou sans type
             * si c'est un type fichier on regarde si il a une variable pour chercher le fichier a mettre dedans
             */
              if($this->etat_form == 'creation')
               {
                   $onfaitquoi = 'defaults';
               }
               else
               {
                   $onfaitquoi = $this->var_enf['type'];
               }


               if($this->form_valide_interne != "false" && $this->form_valide_interne != "true")
               {

                           switch($onfaitquoi)
                                   {

                                   case 'fichier' :

                                       /*je mets en place Le repertoire du fichier*/

                                       $rep = constant($this->var_enf['rep']);

                                        if(!file_exists($rep))
                                            {
                                                return "repertoire inexistant";

                                        }


                                       /*je vais verifier si la variavle est une clée du tableaux
                                        * sinon je vais chercher si le fichier existe dans le repertoire donnée
                                        * sinon j'affiche dedans un message comme quoi aucun texte trouver
                                        */
                                         $clee_enf .= $this->var_enf['valeur'];
                                          if(array_key_exists($clee_enf, $this->var_array))
                                                 {

                                                 $nom_fichier = $this->var_array[$clee_enf];

                                         }
                                         else
                                             {

                                                if(file_exists($rep.$clee_enf.".".$this->var_enf['format']))
                                                 {

                                                    $nom_fichier = $clee_enf;

                                                }
                                                else
                                                {
                                                    $contenu = "aucun texte trouver";
                                                }

                                         }

                                        if(file_exists($rep.$nom_fichier.".".$this->var_enf['format']))
                                                {

                                            $contenu = file_get_contents($rep.$nom_fichier.".".$this->var_enf['format']);

                                        }
                                        else
                                            {
                                                $contenu ='Texte impossible a charger';

                                        }
                                        $html .= (empty($this->var_enf['label']))?"":"<div class='label'>".$this->verif_constant($this->var_enf['label'])."</div>";

                                        $html .= "<div class='formx'><textarea name='$this->var_clee_array[".$this->var_enf['valeur']."]' >$contenu</textarea></div>";

                                       break;

                                   default :

                                        $html .= (empty($this->var_enf['label']))?"":"<div class='label'>".$this->verif_constant($this->var_enf['label'])."</div>";
                                        $html .= "<div class='formx'><textarea name='$this->var_clee_array[".$this->var_enf['valeur']."]' >".$this->recup()."</textarea></div>";
                                       break;



                           }
               }
               else
               {
                                         $html .= (empty($this->var_enf['label']))?"":"<div class='label'>".$this->var_enf['label']."</div>";
                                        $html .= "<div class='formx'><textarea name='$this->var_clee_array[".$this->var_enf['valeur']."]' >".$this->recup()."</textarea></div>";

               }



           return $html;
        }

      /**
         * function privet de l'objet qui verifie la validité des entrés
         * @param NONE
         * @author Ohanessian gregoire
         * @copyright gtux
         */
        Private function verif_entre()
        {

           if(!is_array($this->var_donnee))
           {

                return 'le parametre var_donnee doit etre un tableau';

            }

           if(empty($this->var_xml))
                {
                    return "Le parametre var_xml doit etre un objet xml , il est cependant vide";
            }
            elseif(!is_object($this->var_xml))
                    {
                    return "Le parametre var_xml n'est pas un objet";
            }

            return true;

        }


        /**
         * function qui va verifier la validiter du formualire par rapport au tableaux et au fichier xml du formulaire
         * @param array $this->var_donnee
         * @param object $this->var_xml
         * @return array
         * @author Ohanessian gregoire
         * @copyright gtux
         */

        Public function verifier_formulaire()
        {
            /*on va crée un tableaux vierge avec toutes le clée valide de la base de donnée*/

            $database = new DB;

            $database->table = $this->verif_constant($this->var_xml->connection['table']);
            $database->base = $this->verif_constant($this->var_xml->connection['base']);


           // $tableau_db_vierge = $database->convtableaux_vierge($database->lire_DB('','','','',"limit 1"));

            

           /**j'ai maintenant en ma possesion un tableau vide
            * qui est conforme au champ de la database
            */

            if(is_array($this->var_donnee))
            {
            foreach($this->var_donnee as $clee => $valeur)
            {
         
                 if(is_array($valeur))
                {
                   
                        /** mainenant je peut faire une premiere verification concernant le xml
                         * je vais chercher si il a un elements avec l'attribut $sous_clee qui existe
                         * si ce n'est pas le case je ne traite pas l'information
                         */
                       
                                            
                         /* j'initialise le tableaux de donne */
                         $this->var_actuel_tableau = $valeur;
                         reset($valeur);

                          foreach($this->var_xml->children() as $child)
                          {
                              
                             
                                                    /** maintenant je vais faire une verification de la valeur tableaux
                                                     * il faut que je trouve Les verifications a effectuer **/
                                                     foreach($child->children() as $petitchild)
                                                     {
                                                         /*j'enregistre dans l'objet les valeur importante*/
                                                         $this->var_papa_child = $child;
                                                     
                                                         if(!is_array($valeur[strval($child['valeur'])]))
                                                         {
                                                             $this->var_valeur = ltrim($valeur[strval($child['valeur'])]);
                                                         }
                                                         $this->var_petitchild = $petitchild;

                                                         /*j'appel la function type de la verif*/
                                                         if(method_exists($this, $petitchild['type']."_verif"))
                                                         {
                                                              
                                                             $verif = $this->{$petitchild['type']."_verif"}();

                                                         /* si la verification est bonne ou mauvaise*/
                                                         /*je construit un tableaux multi*/

                                                         if($verif == true)
                                                         {
                                                              /** je regarde si il ya un message de reussite **/
                                                             if(!empty($petitchild->valide))
                                                             {

                                                                 $name = strval($child['name']);
                                                                  $this->var_valide_form[$name] = "<div id='info' class='valide'><strong style='color:green'>".$this->verif_constant($petitchild->valide)."</strong></div>";

                                                             }


                                                         }
                                                         elseif($verif == false)
                                                         {
                                                             /** je regarde si il ya un message d'erreur **/
                                                             if(!empty($petitchild->erreur))
                                                             {
                                                                 $name = strval($child['name']);
                                                                 $this->var_erreur_form[$name] = "<div id='info' class='erreur'><strong style='color:red'>".$this->verif_constant($petitchild->erreur)."</strong></div>";

                                                             }

                                                               $nbr_erreur ++;

                                                         }
                                                         else
                                                         {
                                                                var_dump("valeur inatendu");
                                                          }
                                                     }
                                                     else
                                                     {
                                                     //var_dump("<strong style='color:red'>erreur de devs la methodes ".$petitchild['type']."_verif n'existe pas</strong><br/> ");
                                                     continue;
                                                      }

                                                 }

                          }

                }
                else
                {
                   // var_dump("aucune valeur dans le tableau");

                }


            }
            
            
            }
            else
            {
                $nbr_erreur = 1;
            }


         
      
            if(!empty($nbr_erreur) || $nbr_erreur != 0)
            {
                /** si une erreur je raffiche Le formulaire **/
                $this->form_valide_interne = "false";
                $this->form_valide = false;
                 return $this->afficher_formulaire();
            }
            else
            {
                  /** sinon j'enregistre l'action**/
               $this->form_valide_interne = "true";
               $this->form_valide = true;

               //formulaire valide on protege Les données //
               foreach ($this->var_donnee as $key => $value) {

                   if(is_array($value))
                   {

                       foreach($value as $k => $v)
                       {

                              if(is_array($v))
                              {
                                 foreach($v as $q => $w)
                                 {

                                       $tableau[$clee][$k][$q] = htmlspecialchars($w , ENT_QUOTES);
                                 }
                              }
                              else
                              {
                                   $tableau[$clee][$k] = htmlspecialchars($v , ENT_QUOTES);
                              }


                       }

                       
                   }
                   else
                   {
                        $tableau[$key] = htmlspecialchars($value , ENT_QUOTES);
                          
                   }


               }

   
               $this->var_donnee = $tableau;
                return $this->afficher_formulaire();
            }


        }



        /** verification de type regex **/

        Private function regex_verif()
        {
            /** je recup Les variable de parametre*///comme cela la securiter ne depend que de la secu personne
            $v =  $this->var_valeur;
            $petitchild = $this->var_petitchild;

             $retour = preg_match( "/".$petitchild['regex']."/",$v);
            return $retour;

        }

        /** verification obligatoire **/
        Private function obligatoire_verif()
        {

            /** je recup Les variable de parametrecomme cela la securiter ne depend que de la secu personne*/
            $v =  $this->var_valeur;
            $petitchild = $this->var_petitchild;

                if(empty($v))
                {
                        return false;
                }
                else
                {
                    return true;
                 }

                
        }

            /** verification obligatoire **/
        Private function confirmation_verif()
        {

            /** je recup Les variable de parametrecomme cela la securiter ne depend que de la secu personne*/
            $v =  $this->var_valeur;
            $petitchild = $this->var_petitchild;

               if($v != $this->var_actuel_tableau[strval($petitchild['qui'])])
                {
                        return false;
                }
                else
                {
                    return true;
                 }
              
               


        }


        Private function different_verif(){
              /** je recup Les variable de parametrecomme cela la securiter ne depend que de la secu personne*/
             $v =  $this->var_valeur;

             if($v == $this->different)
             {
                  return false;
             }
             else
             {
                 return true;
             }


        
            

        }


        /** verification de type unique **/

        Private function unique_verif()
        {
                   /** je recup Les variable de parametrecomme cela la securiter ne depend que de la secu personne*/
                    $v =  $this->var_valeur;
                    $petitchild = $this->var_petitchild;



                    $database = new DB;
                    $database->base = $petitchild['base'];
                    $database->table = $this->verif_constant($petitchild['table']);
             

                    $requete =  $petitchild['champ']."='".$v."' AND ".$this->var_xml->clee_unique."!='".$_SESSION['formulairev2_id']."'";
                    $nbr = $database->lire_DB('true',  $petitchild['champ'] , $requete);
                  
                    if($nbr == 0)
                        {
                            return true;
                        }
                    elseif($nbr <= 1)
                        {
                            return false;
                        }
                    else
                        {

                            return false;
                            var_dump("retourne une erreur pour unique");

                        }

                
        }

        /** verification de type existe **/

        Private function existe_verif()
        {

            /** je recup Les variable de parametrecomme cela la securiter ne depend que de la secu personne*/
            $v =  $this->var_valeur;
            $petitchild = $this->var_petitchild;

            $database = new DB;
            $database->base = $petitchild['base'];
            $database->table = $this->verif_constant($petitchild['table']);

           
           if($this->var_papa_child['type'] == 'password')
           {

               $champ_verif =  $this->var_actuel_tableau[strval($petitchild['champ_verif'])];

            // alors on encode $v en sha1
                     $v = sha1($v);

                        $requete =  $petitchild['champ']."='".$v."' AND ".$petitchild['champ_verif']."='$champ_verif'";

                        $nbr = $database->lire_DB('true',  $petitchild['champ'] , $requete);

               if($nbr == 0)
                {

                    return false;
                }
                elseif($nbr != 0)
                {

                    return true;
                }
                else
                {

                    return false;
                    Entrer::$erreur[] = "retourne une erreur pour existe";

                }


           }
           else
           {

            $requete =  $petitchild['champ']."='".$v."'";
            $nbr = $database->lire_DB('true',  $petitchild['champ'] , $requete);

            if($nbr == 0)
                {
                
                    return false;
                }
                elseif($nbr != 0)
                {
      
                    return true;
                }
                else
                {

                    return false;
                    Entrer::$erreur[] = "retourne une erreur pour existe";

                }

           }
        
         }

         /** accesort variable priver **/

         Public function set_different($different){

             $this->different = $different;

         }





        /** iici je vais cree de function private pour eviter d'afficher un message d'erreur sur base table et clee unique**/

        Private function base(){
     
           return;
        }
        Private function table(){
           
           return;
        }

        Private function clee_unique(){
           return;
        }

        


        public function connection(){
                  //juste pour eviter le message d'erreur de l'objet
        }

        Public function __call($methode,$child){

            return "la function $methode est inconnu de cette objet";

        }

}
?>

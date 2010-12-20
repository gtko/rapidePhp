<?php

/**Objet abstrait representant un Module de rapidephp
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 */
abstract class GeneralModules{
	

        public function __construct(){

           $this->objets();
           //sert a capturer les erreur
           Erreur::capturer();
           //on demarre la securiter On reconstruit le post et le get methode radical
           Core::libs("securite");
           Securite::Boot();

        }

        /** le function obligatoire **/
        abstract Public function general();
        abstract Public function defaults();

       /** methode abstraite appeler par le construteur sert a gerer les dependances des objets dans rapidephp.
        *
        */
        abstract Public function Objets();

        
        Public function java_exist(){
              Core::libs('session');
              $personne = Session::$membre;
              $personne->var_java = true;

              $_SESSION['java'] = true;

              $tab['return'] = "true";
              echo json_encode($tab);
              exit;
        }

	
	Public function rapide_nettoyeur(){	
	if(isset($_POST))
	{

                        $clee = $_POST['clee'];
                        $_SESSION['clee'] = $clee;
                        $_SESSION['post'][$clee] = $_POST;
                        $action = $_POST['actions'];
                        $geta = $_POST['geta'];
                       

	}
        

                if(isset($_FILES) && is_array($_FILES))
                {
                        
                    foreach($_FILES as $key => $value)
                        {
                        
                            if(is_array($value) && !empty($value['name']))
                            {
                             Core::libs("rapideupload");
                             $upload = new RapideUpload;
                            //j'enregistre la nouvelle image sans aucune verif pour le moment
                             $upload->var_repertoire =  REP_IMAGE_TMP;
                             $upload->var_name_input = $key;
          
                             $nom_fichier = md5(time());
                             $_SESSION["upload"][$key] = $nom_fichier;
                           
                             $upload->var_nom_image = $nom_fichier;
                             $erreur = $upload->recevoir_fichier();
                             if($erreur == false)
                                 {
                                    Erreur::declarer_dev(1600,"objet : GenralModules , function : rapide_nettoyeur , upload = ".$erreur.";" );
                                 }
                            }
                        }

                }
                
                if(!empty($geta))
                {
                    header("location: index.php?encode=".cryptage("modules=".$_GET['modules']."&actions=$action&a=$geta","crypt"));
                }
                else
                {
                    header("location: index.php?encode=".cryptage("modules=".$_GET['modules']."&actions=$action","crypt"));
                }
	}

    
        Public function detruire_nettoyeur(){
            unset($_SESSION['post']);
        }


        Public function detruire_upload(){
            unset($_SESSION['upload']);
        }

        Public function detruireClee()
        {
            unset($_SESSION['clee']);
        }
        
        /**
         * Permet de detruire les données form
         * @param type $quoi 
         */
        Public function detruireDonnee($quoi)
        {
            $table = array();
            
            foreach($_SESSION['post'] as $clee => $value)
            {
                if($clee != md5($quoi))
                {
                    $table[$clee] = $value; 
                }    
                
            }
            
            $_SESSION['post'] = $table;
            
        }
        
        /**
         * Pemet de detruire les données de form quand on est sur une autre page
         * @param type $quoi
         * @param type $module 
         */
        Public function detruireForm($quoi , $module)
        {
            
           if(Entrer::$modules != $modules && Entrer::$actions != "rapide_nettoyeur") 
           {
               $this->detruireDonnee($quoi); 
               exit;
           }
        }
        
        

        Public function getUpload($name , $exist = null)
        {   
            if(is_null($exist))
            {
             return $_SESSION["upload"][$name];
            }
            else
            {
              return !empty($_SESSION["upload"][$name]) && isset($_SESSION["upload"][$name]);
            }
        }


          Public  function   recup_a(){

            /** je verifie que le get['a'] existe **/
            if(isset($_GET['a']) && preg_match("/^[0-9A-Za-z]+$/",$_GET['a']))
           {

                $_SESSION['geta'] = $_GET['a'];
                return $_GET['a'];

            }
            else
            {

                if(!empty($_SESSION['geta']) && preg_match("/^[0-9a-zA-Z]+$/",$_SESSION['geta']))
                {
                      return $_SESSION['geta'];

                }
                else
                {
                      Erreur::declarer_int(303, Entrer::$actions);
                      
                }
              

            }



        }
	    
            
	     Public function notification($titre , $message = NULL , $class = NULL)
         {
                      if($message != NULL)
                      {

                                   $_SESSION[$titre] = $message;
                      }
                      else
                      {
                                   if(!empty($_SESSION[$titre]))
                                   {
                                                             $this->setMessage($_SESSION[$titre] , $class);
                                                             unset($_SESSION[$titre]);
                                   }
                      }
         }


         Public function notificationPersistente($titre , $message = NULL , $suppr = NULL)
         {
                   if($suppr == true)
                   {
                              unset($_SESSION[$titre]);
                              return true;
                   }

                      if($message != NULL)
                      {

                                   $_SESSION[$titre] = $message;
                      }
                      else
                      {
                                   if(!empty($_SESSION[$titre]))
                                   {
                                                             $this->contenu['message_site'] .= $_SESSION[$titre];

                                   }
                      }
         }


         Public function detruireNotif($a = null){

                   if($a == NULL)
                   {
                              $a = $this->recup_a();
                   }
                    $this->notification_persistente($a,"",true);
                    $this->redirection("","","",$_SERVER["HTTP_REFERER"] );

         }


         Public function redirection($module , $action = NULL , $geta = NULL , $adresse_complete = null)
         {
                       if($action != NULL)
                       {
                                   $action = "&actions=$action";
                       }

                      if($geta != NULL)
                       {
                                   $geta = "&a=$geta";
                       }

                       if($adresse_complete == null)
                       {
                              header('location:index.php?modules='.$module.$action.$geta);
                       }
                       else
                       {
                              header('location:'.$adresse_complete);
                       }

         }

         public function setMessage($message , $class = null)
         {
            if(!is_null($class))
            {
                $class = "class='$class'";
            }
            else
            {
                $class = "";
            }
            $this->contenu['message_site'] .= "<div $class>".$message."</div>";
         }


}






?>

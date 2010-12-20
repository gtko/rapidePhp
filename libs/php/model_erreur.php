<?php

/**
 * objet qui gere les erreurs du framework rapide_php;
 *
 * @copyright gtux;
**/
class Erreur extends GeneralLibs{

    /* ### variable retour  public ### */



    /* ### Parametre priver ### */

        /**
         * variable indiquant si l'objet est demarrer
         * @var boolean
         */
         private static $boot = false;

        /**
         * liste toutes les erreur de devellopement
         * @var array
         */
        private static $erreur_develloppement = array();
    
        /**
         * listes toutes les erreursque l'utilisateur peut generer
         * @var array
         */
        private static $erreur_interface = array();

        /**
         * liste toutes les erreur lier a la securiter du framework ou du site
         * @var array
         */
        private static $erreur_securiter = array();

        /**
         * liste tous les warning generer par le framework , le develloppeur , ou par l'utilisateur
         * @var array
         */
        private static $erreur_warning = array();
        

        /**
        /*liste les erreurs deja afficher par les sessions
        /*@var array  
        */   
        private static $erreur_session = array();
        /** boot **/

      /**
       * Methode construt lance l'objet static erreur il va inclure les fichier de constante
       */
      Public static function boot()
      {
            if(Entrer::$langue == null)
            {

                echo "<div style='display:position:relative;block;padding-left:10px;width:90%;background:#4c4c4c;color:white;line-height:20px;margin:10px;border:solid 2px red;'>".ERREUR_23."</div>";

            }
            else
            {
               
                require_once(REP_ERREUR_CONST.Entrer::$langue."/erreur_dev.php");
            }

            self::$boot = true;
  
           
            

      }

      /**
       * Methode de capture des  erreurs d'interfaces
       */
      Public static function capturer(){

          if(self::verif_demarrer())
          {
             
              // si une erreur a eté relever alors je l'affiche;
              if(is_array($_SESSION["erreur_rapidephp_int"]))
                {

                    foreach($_SESSION["erreur_rapidephp_int"] as $v)
                    {
                        self::$erreur_session[] = $v[2];
                        Afficher::$supplement["message_site"] .= "<div class='erreurNotif' style:'display:block;color:red;'>".$v[0]."</div>";

                    }

                }
              

             // si une erreur a eté relever alors je l'affiche;
              if(is_array($_SESSION["erreur_rapidephp_dev"]))
                {
                    foreach($_SESSION["erreur_rapidephp_dev"] as $v)
                    {
                         self::$erreur_session[] = $v[2];
                        echo "<div style='display:position:relative;block;width:90%;background:#4c4c4c;color:white;line-height:20px;margin:10px;border:solid 2px red;'>".$v[0]."</div>";
                    }

                }

               unset($_SESSION["erreur_rapidephp_int"]); 
               unset($_SESSION["erreur_rapidephp_dev"]);
          }

      }

      /** Methode obligatoire **/

      public function Objets(){


      }

     /* ### Methode accesseur set ### */



      /* ### Methode accesseur get ### */

        /**
         * Methode accesseur des  erreurs de type dev detecter pendant l'execution d'un script
         * @return <Array>
         */
        Public static function get_erreur_dev(){

            return self::$erreur_develloppement;

        }
        /**
         * Methode accesseur des  erreurs de type interface detecter pendant l'execution d'un script
         * @return <Array>
         */
        Public static function get_erreur_interface(){

            return self::$erreur_interface;

        }
      /**
         * Methode accesseur des  erreurs de type securiter detecter pendant l'execution d'un script
         * @return <Array>
         */
        Public static function get_erreur_securiter(){

            return self::$erreur_securiter;

        }
        /**
         * Methode accesseur des  erreurs de type warning detecter pendant l'execution d'un script
         * @return <Array>
         */
        Public static function get_erreur_warning(){

            return self::$erreur_warning;

        }

        /**
         * Methode accesseur de toute les erreur detecter pendant l'execution d'un script
         * @return <Array>
         */
        Public static function get_erreur(){

           $erreur_all["develloppement"] =  self::$erreur_develloppement;
           $erreur_all["interface"] =  self::$erreur_interface;
           $erreur_all["securiter"] =  self::$erreur_securiter;
           $erreur_all["warning"] =  self::$erreur_warning;


            return $erreur_all;

        }






     /* ### Methode static ### */


        /**
         * Methode Qui verifie si l'obket est bien demarrer
         * @return <boolean>
         */
        Private static function verif_demarrer(){

           if(self::$boot)
          {
                return true;
          }
          else
          {
              echo "<div style='display:position:relative;block;width:90%;background:#4c4c4c;color:white;line-height:20px;margin:10px;border:solid 2px red;'>".ERREUR_22."</div>";
              return false;

          }

        }
    

        /**
        /*Verifier si c'est une constante
        */
        private static function getConstante($code = null)
        {
            //si ce n'est pas une constante alors on retourne une erreur
            if(!defined("ERREUR_".$code))
            {
                 echo "<div style='display:position:relative;block;width:90%;background:#4c4c4c;color:white;line-height:20px;margin:10px;border:solid 2px red;'>".ERREUR_3." $code</div>";
                $valeur = false;
            } 
            else
            {

                $valeur = constant("ERREUR_".$code);

            }      
               
            return $valeur;
        }    

        
        /**
         *Methode de declaration d'erreur de dev || de framework
         * @param <int> $code
         * @param <string> $divers
         */

        Public static function declarer_dev( $code = null ,$message = null ,$divers = null )
        {

             if(self::verif_demarrer())
             {
                    if(self::getConstante($code) && !in_array($code , self::$erreur_session))
                    {
                            $erreur = "Erreur $code :".constant("ERREUR_".$code)." ".$divers;
                            self::leverLog(' erreur '.$code.' => '.constant("ERREUR_".$code).' '.$divers.'  ip('.$_SERVER["REMOTE_ADDR"].')',3);
                            self::$erreur_develloppement[] = constant("ERREUR_".$code)."  ".$divers;
                            $_SESSION["erreur_rapidephp_dev"][$code][0] = $erreur;
                            $_SESSION["erreur_rapidephp_dev"][$code][1] = $quoi;
                            $_SESSION["erreur_rapidephp_dev"][$code][2] = $code;
                             self::afficher_erreur($erreur);
                    }
                   /* else
                    {
                            $erreur = "Erreur $code : {(ERREUR_$code)".$message."}".$divers;
                            self::leverLog(' erreur '.$code.' => '.$message.' '.$divers.'  ip('.$_SERVER["REMOTE_ADDR"].')',3);
                            self::$erreur_develloppement[] = $message."  ".$divers;
                            $_SESSION["erreur_rapidephp_dev"][$code][0] = $erreur;
                            $_SESSION["erreur_rapidephp_dev"][$code][1] = $quoi;
                            $_SESSION["erreur_rapidephp_dev"][$code][2] = $code;
                    }

                    if(is_array($code , self::$erreur_session))
                    {
                       
                    }*/
             }
        }


        /**
         * Methode de declaration d'erreur d'interface utilisateur
         * @param <int> $code
         * @param <actions> $quoi
         * @param <string> $divers
         */
         Public static function declarer_int( $code = null ,$message = null ,  $divers = null )
        {
            // si le type est egal a null alors j'utilise le declarer une erreur pour gerer est afficher l'erreur

             if(self::verif_demarrer())
             {
           
                         if(!in_array($code , self::$erreur_session))
                         {
                                $erreur = "Erreur $code : {(ERREUR_$code)".$message."}".$divers;
                                self::leverLog(' erreur '.$code.' => '.$message.' '.$divers.'  ip('.$_SERVER["REMOTE_ADDR"].')',3);
                                self::$erreur_interface[] = $message."  ".$divers;
                                $_SESSION["erreur_rapidephp_int"][$code][0] = $erreur;
                                $_SESSION["erreur_rapidephp_int"][$code][1] = $quoi;
                                $_SESSION["erreur_rapidephp_int"][$code][2] = $code;
                                Afficher::$supplement["message_site"] .= "<div class='erreurNotif' style:'display:block;color:red;'>".$erreur."</div>";
                         }
                         
             }
            

        }

        /**
         * lever un log dans system avec un niveau de verbositer
         * @param $log
         */
        public static function leverLog($log,$verb = 1)
        {
            $dateActuel = date("d-m-Y",time());
            $dateHeure = date("d-m-Y H:i:s",time());
            if(file_exists(REP_LOG.$dateActuel.".log"))
            {
               file_put_contents(REP_LOG.$dateActuel.".log", "$dateHeure  $log //verbositer $verb\n" , FILE_APPEND);

            }
            else
            {
                file_put_contents(REP_LOG.$dateActuel.".log", "$dateHeure  $log //verbositer $verb\n");
            }

        }



        /**
         * Methode d'affichage d'erreur de dev || framework
         * @param <string> $erreur
         */
        private static function afficher_erreur($erreur = null ){

                echo "<div style='display:position:relative;block;width:90%;background:#4c4c4c;color:white;line-height:20px;margin:10px;border:solid 2px red;'>$erreur</div>";


        }


  
	
}
?>

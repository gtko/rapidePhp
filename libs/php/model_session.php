<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once(REP_LIB_PHP."model_db.php");

/**
 * Description of model_session
 *
 * @author gtko
 */
class Session {


        /**proprieter **/

        Public static $pseudo;
        Public static $identifier;
        Public static $id;

        Public static $membre;




        /** methode**/

        Public static function boot(){


            /** ici je vais aller chercher le fichier des personne connecter **/
            /** et regarder si lui meme est connecter **/
              $ip = $_SERVER['REMOTE_ADDR'];

              $db = new DB;
              $db->table = TABLE_MEMBRE;

              $requete = $db->lire_DB(""," id,pseudo ,ip, connecter","ip='$ip' AND cookie='".$_COOKIE[Entrer::$site.'_identiter']."' ");

        
              $table = mysql_fetch_object($requete);
            
              if($table->connecter == 1 )
              {

                    self::$pseudo = $table->pseudo;
                    self::$id = $table->id;
                    self::$identifier = true;
                    self::deserialize_membre();
                    return true;

              }
              else
              {
                    self::$identifier = false;
                    return false;
               }


            
        }



      
       Public static function enregistrer_membre($pseudo){

           /** je vais enregistrer un membre **/

           $ip = $_SERVER['REMOTE_ADDR'];


           $db = new DB;
           $db->table = TABLE_MEMBRE;

           $donnee = array(
               'ip' =>$ip,
               'cookie' => md5($ip.$pseudo),
               'connection' => date("Y-m-d H:i:s"),
               'connecter' => 1

           );

           $db->update_DB("pseudo='$pseudo'", $donnee);
           setcookie(Entrer::$site.'_identiter', md5($ip.$pseudo) , (time() + 3600 * 24));
           
       }

       Public static function update_membre($pseudo){

           /** je vais mettre a jour  un membre **/
           $db = new DB;
           $db->table = TABLE_MEMBRE;

           $donnee = array(
               'connection' => date("Y-m-d H:i:s"),
               'connecter' => 1

           );

           $db->update_DB("pseudo='$pseudo'", $donnee);

           $requete = "
               SELECT membre.`pseudo`, membre.`id` , membre.`ip`, membre.`connection`, membre.`cookie` , membre.`connecter`
               FROM ".TABLE_MEMBRE." membre
               WHERE membre.connecter ='1' ";

           $db->lire_requete($requete);

           $tableau = $db->convtableaux();
    

           foreach ($tableau as $clee => $value)
           {
               $time_actuel = time();

               list($date, $time) = explode(" " , $tableau[$clee]["connection"] );
               list($year, $month, $day) = explode("-", $date);
               list($hour, $minute, $second) = explode(":", $time);
               $timestamp = mktime($hour, $minute, $second, $month, $day, $year);

               /** on calcule 1 heure en arriere de l'heure actuel*/


               $time_deco = ($time_actuel - $timestamp) / 3600 ;

               if($time_deco >= 1 )
               {
                    /** ceux qu'il faut deconnecter**/
                   $table_deco[] = $tableau[$clee]["id"];

                

               }


           }

           if(!empty($table_deco))
           {
               foreach($table_deco as $key => $value)
               {

                 $condition .= " id=$value AND";

               }

               $condition = substr($condition , 0 , -3);

                  $donnee = array(
                   'connecter' => 0

               );


               $db->update_DB($condition, $donnee);
           }
       }


       Public static function recuperer_membre($pseudo = NULL , $info = false){

           $db = new DB;
           $db->table = TABLE_MEMBRE;

           if($info == true)
           {
               $choix = "";
           }
           else
           {
               $choix = "pseudo ,ip ,connecter, connection";
           }
           if($pseudo == NULL)
           {

               $condition = "connecter='1'";

                $requete = $db->lire_DB("",$choix, $condition);
               $tableaux = $db->convtableaux($requete, "membre");
           }
           else
           {

               $condition = "pseudo='$pseudo'";
               $requete = $db->lire_DB("",$choix, $condition);
               $tableaux = $db->convtableaux_simple($requete);
           }


          

                /** je retourne la liste entiere des membres**/
                return $tableaux;

       }



       Public static function supprimer_membre($pseudo){

          /** je vais enregistrer un membre **/

           $db = new DB;
           $db->table = TABLE_MEMBRE;

           $donnee = array(
               'ip' =>'vide',
               'cookie' => 'vide',
               'connection' => date("Y-m-d H:i:s"),
               'connecter' => 0

           );

           $db->update_DB("pseudo='$pseudo'", $donnee);
            setcookie(Entrer::$site.'_identiter', "vide" , (time() + 3600 * 24));

       }


       Public static function bloquer_membre($site , $pseudo){



       }


      Public static function nbr_membre(){


              $db = new DB;
              $db->table = TABLE_MEMBRE;
               return $db->lire_DB("true","","connecter='1'");

       }

       /**
        * creation de la gestion des fichier membre stocker sur le disque serveur pour amoindrire les acces sql
        */

       Public static function serialize_membre(){

             Core::libs("fichier");
             $fichier   = new Fichier;
             
             $contenu = serialize(self::$membre);
             $retour = $fichier->cree_file(REP_PERSONNE_USER, Session::$pseudo.".pers", $contenu);
              if($retour)
                    {
                        return true ;
                    }
                    else
                    {
                        Erreur::declarer_dev(9, "objet : Membre , function : serialize_personne");
                         return false ;
                    }




       }

        /**
         * on ouvre l'objet personne .pers
         * @author gtko;
         * @param aucun parametre
         * @category Model_membre;
         * @return boolean;
         */

        Public static function deserialize_membre()
        {


           $personne = file_get_contents(REP_PERSONNE_USER.Session::$pseudo.".pers");
           $personne = unserialize($personne);
           self::$membre = $personne;



      }
}
?>

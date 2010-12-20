<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class RapideStatistique extends GeneralRoutines{


        /**parametre public**/

          Public $base = null;


       /** parametre priver **/
           private $nom_cookie = "defaults";


      /* Methode obligatoire */
        Public function objets(){
            Core::libs("stockdb");
        }

        /* methode accesseur */

        /*set */

       Public function set_nom_cookie($nom_cookie = "defaults")
       {
          $this->nom_cookie = $nom_cookie;
       }

        /*get*/


        /*methode*/
       Public  function  boot(){
             if($this->base == null)
             {
                       echo "Erreur le parametre base est manquant";
                       return "Erreur le parametre base est manquant";
             }
             $this->compter_visiteur();

       }

       private function compter_visiteur(){
                 $rec = new StockDb;

                 $rec->set_base($this->base);
                 $rec->set_table("visiteur");

                  $time = time();
                  $ip = $_SERVER["REMOTE_ADDR"];
                  $useragent = $_SERVER["HTTP_USER_AGENT"];

                 if(!isset($_COOKIE[$this->nom_cookie]))
                 {
                           $requete = "
                                SELECT * FROM  $base.visiteur where ip='$ip'
                              ";
                           $rec->set_requete($requete);
                           $tableau = $rec->actualiser(true);

                           // si superieur a 0 visiteur connu
                      

                           if( $rec->nbr_element > 0)
                           {
                                      if( date('d-m-Y',$time) -  date('d-m-Y',$tableau['defaults_1']['time'])  > 0 )
                                      {
                                                  setcookie($this->nom_cookie, $time, time() + 86400 * 2);
                                                  $rec->ecrire("
                                                  cookie:$time;
                                                  ip:$ip;
                                                  useragent:".$useragent.";

                                                  ");
                                      }
                                      else
                                      {
                                                // Ya du tricheur dans l'air a surveiller petit log moi je dit
                                      }

                           }
                           else
                           {

                                    //donc si pas connu je lui crée un cookie et je le compte
                                    setcookie($this->nom_cookie, $time, time() + 86400 * 2);
                                    $rec->ecrire("
                                        cookie:$time;
                                        ip:$ip;
                                        useragent:".$useragent.";
                                        ");


                           }



                 }
                elseif( date('d-m-Y',$time) - date('d-m-Y',$_COOKIE[$this->nom_cookie]) > 0)
                 {
             
                           setcookie($this->nom_cookie, $time, time() + 86400 * 2);
                           $rec->ecrire("
                                        cookie:$time;
                                        ip:$ip;
                                        useragent:".$useragent.";
                                        ");
                    
                }


                
       }



}
?>

<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of model_utils
 *
 * @author gtko
 */
class Utils {



  

    Public function Objets(){




    }

    /**
     * Cette function sert a modifier une valeur d'un tableau en sortie de l'objet db , on lui donne le tableau , le nom_clée , la valeur
     */
    Public function modifier_tableaux($tableaux , $nom_clee , $valeur_new , $condition= NULL , $valeur_cond = NULL){

         if(is_array($tableaux))
          {

                    foreach($tableaux as $clee => $valeur){
                        if(is_array($valeur))
                        {
                              if($condition == NULL)
                              {

                                           foreach($valeur as $key => $val){

                                                     if($key == $nom_clee)
                                                     {

                                                            $tableaux[$clee][$key] = $valeur_new;
                                                      }


                                           }


                              }
                              else
                              {
                                      if($valeur_cond == NULL)
                                      {
                                               echo "il manque un argument a modifier un tableaux , $ valeur_cond";
                                               return ;
                                      }

                                      foreach($valeur as $key => $val){
                                                
                                                     
                                                
                                                switch($condition){
                                                  case "==" :
                                                            $cond = $val == $valeur_cond  ;
                                                  break;

                                                  case "!=" :
                                                            $cond = $val != $valeur_cond  ;
                                                  break;
                                                  case "<=" :
                                                            $cond = $val <= $valeur_cond  ;
                                                  break;
                                                  case ">=" :
                                                            $cond = $val >= $valeur_cond  ;
                                                  break;
                                                  case ">" :
                                                            $cond = $val > $valeur_cond  ;
                                                  break;
                                                  case "<" :
                                                            $cond = $val < $valeur_cond  ;
                                                  break;
                                                  case "===" :
                                                            $cond = $val === $valeur_cond  ;
                                                  break;

                                                  default :
                                                            echo "La condition est inconnue de cette function";
                                                            return ;
                                                  break;
                                                }
                                             
                                                     if($key == $nom_clee && $cond )
                                                     {
                                                          
                                                            $tableaux[$clee][$key] = $valeur_new;
                                                      }


                                           }
                              }
                      }
               }
          }


          return $tableaux;

    }


    Public function rajouter_ligne($tableaux , $new_clee , $new_value)
    {
                if(is_array($tableaux))
                {
                          
                          foreach($tableaux as $clee => $valeur){

                                    if(is_array($new_value))
                                    {
                                         $tableaux[$clee][$new_clee] = $new_value[$clee];
                                    }
                                    else
                                    {
                                            $tableaux[$clee][$new_clee] = $new_value;
                                    }

                              }
                      return $tableaux;
                    }

    }

    Public function transformer_jour($date){

              
               $test = strtotime($date);

               $jour =   date("D" , $test);
               $mois =  date("M" , $test);
               $num_jour =  date("d" , $test);
               $annee =  date("Y" , $test);

               $jour = $this->transformer_jour_anglais($jour);
               $mois = $this->transformer_mois_anglais($mois);
               return $jour." ".$num_jour." ".$mois." ".$annee;

              

    }


    Public function transformer_jour_anglais($jour){

              switch($jour)
              {
                        case "Mon":
                              return "Lundi" ;
                        break;
                        case "Tue":
                              return "Mardi" ;
                        break;
                        case "Wed":
                              return "Mercredi" ;
                        break;
                       case "Thu":
                              return "Jeudi" ;
                        break;
                       case "Fri":
                              return "Vendredi" ;
                        break;
                       case "Sat":
                              return "Samedi" ;
                        break;
                       case "Sun":
                              return "Dimanche" ;
                        break;

                       default :
                                 return "jour inconnue";
                        break;

              }
    }
  Public function transformer_mois_anglais($mois){

              switch($mois)
              {
                        case "Jan":
                              return "Janvier" ;
                        break;
                        case "Feb":
                              return "Février" ;
                        break;
                        case "Mar":
                              return "Mars" ;
                        break;
                       case "Apr":
                              return "Avril" ;
                        break;
                       case "May":
                              return "Mai" ;
                        break;
                       case "Jun":
                              return "Juin" ;
                        break;
                       case "Jul":
                              return "Juillet" ;
                        break;
                        case "Aug":
                              return "Août" ;
                        break;
                        case "Sep":
                              return "Septembre" ;
                        break;
                        case "Oct":
                              return "Octobre" ;
                        break;
                        case "Nov":
                              return "Novembre" ;
                        break;
                        case "Dec":
                              return "Decembre" ;
                        break;

                       default :
                                 return "mois inconnue";
                        break;

              }

    }

    Public function envoyer_mail($adresse , $sujet , $message , $nomfrom = null, $from = null , $reply =null){

     if($nomfrom == null)
     {
               $nomfrom = NOM_EXPEDITEUR;
     }

     if($from == null)
     {
               $from = ADRESSE_MAIL;
     }

    if($reply == null)
     {
               $reply = ADRESSE_MAIL_REPONSE;
     }


     $headers  ='From: "'.$nomfrom.'"<'.$from.'>'."\n";
     $headers .='Reply-To: '.$reply.''."\n";
     $headers .='Content-Type: text/html; charset="UTF-8"'."\n";
     $headers .='Content-Transfer-Encoding: 8bit';
    
          return  mail($adresse, $sujet,$message, $headers);


}

}
?>

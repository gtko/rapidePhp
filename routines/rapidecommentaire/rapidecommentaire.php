<?php




class RapideCommentaire extends GeneralRoutines {


   Public static $etat_comm = null;
   
    
    Public function objets(){
        
                
        
    }



    /**
     * recupere un commentaire particulier
     */
     Public static function lire($id_com){

             Core::libs('db','genererv2','xml');

        $db = new DB;
        $genererv2 = new GenererV2;
        $xml = new Xml;

        /** je recpere les commentaire de la base avec le pseudo de celui qui la ecrit  la date et le contenu du mess **/

        $req = "
            SELECT comm.`id_membre`, comm.`id_cible` , comm.`date` , comm.`id` , membre.`id` as idp , membre.`pseudo`
            FROM artvu.".TABLE_COMMENTAIRES." comm
            LEFT OUTER JOIN artvu.".TABLE_MEMBRE." membre ON comm.id_membre=membre.id
            WHERE comm.id ='$id_com'";


        $db->lire_requete($req);
        $tableau = $db->convtableaux();



        $genererv2->var_donnee = $tableau;
        $genererv2->var_xml = $xml->charger_xml(__DIR__."/commentaire.xml");

          return $genererv2->vignette();
     


    }


    /**
     * ecrit un commentaire sur un produit, artiste , membre , etc ...
     */
    Public static function ecrire($id_cible , $table_cible){

  
                Core::libs('db','formv2','xml','session',"fichier");

                $db = new DB;
                $formv2 = new Formulairev2;
                $xml = new Xml;
                $personne = Session::$membre;



                $formv2->etat_form = 'creation';
                $formv2->var_xml = $xml->charger_xml(__DIR__."/ecrire_comm.xml");
             
                $form = $formv2->formulaire();

                if($formv2->form_valide == true)
                {

                    $db->base = "";
                    $db->table = TABLE_COMMENTAIRES;

                    $req = "SELECT comm.`id_membre`, comm.`date`, comm.`id` ,comm.`id_cible`
                    FROM artvu.".TABLE_COMMENTAIRES." comm
                    WHERE comm.id_cible ='$id_cible' AND comm.table_cible = '$table_cible'
                    ORDER BY  comm.`id` DESC
                    LIMIT 1";
                   
                   $db->lire_requete($req);

                   $result = $db->convtableaux_simple();

 
                   if($personne->text_comm != $formv2->var_donnee['table_1']['commentaire'])
                  {

                           $personne->text_comm = $formv2->var_donnee['table_1']['commentaire'];

                           if($result['id_membre'] == $personne->var_id && file_exists(REP_COMMENTAIRE.$result['id'].".comm"))
                           {
                          
                                $texte = file_get_contents(REP_COMMENTAIRE.$result['id'].".comm");

                                if($formv2->var_donnee['table_1']['commentaire'] == $texte)
                                {
                                    
                                    return "texte egaux on ne fait rien";
                                }
                                else
                                {
                                    file_put_contents(REP_COMMENTAIRE.$result['id'].".comm", "<br/>".$formv2->var_donnee['table_1']['commentaire']);
                                    self::$etat_comm = $result['id'];
                                   
                                    return 'update_comm';
                                }


                           }
                           else
                           {
                                $donnee = array(
                                    'id_membre' => $personne->var_id,
                                    'id_cible' => $id_cible,
                                    'table_cible' => $table_cible

                                );
                                       
                                $id_comm = $db->ecrire_DB($donnee,"true");
                               
                                /** j'enregistre le fichier comm **/
                                Fichier::cree_file(REP_COMMENTAIRE,$id_comm.".comm" , $formv2->var_donnee['table_1']['commentaire']);

                                self::$etat_comm = $id_comm;
                                 return "Commentaire Poster avec succes";

                            }
                     }
                     else
                     {

                          return $form;

                     }
                }
               else
                {
                     return $form;
                }

       

        


    }

    /**
     * modifier un commentaire
     */

    Public static function modifier($id_com){

        return "je modifie le comm $id_com";


    }


    /**
     * recup tout les comme corespondant a un id
     */

    Public static function recup($id_cible , $table_cible){

        Core::libs('db','genererv2','xml');

        $db = new DB;
        $genererv2 = new GenererV2;
        $xml = new Xml;

        /** je recpere les commentaire de la base avec le pseudo de celui qui la ecrit  la date et le contenu du mess **/

        $req = "
            SELECT comm.`id_membre`, comm.`id_cible` , comm.`date` , comm.`id` , membre.`id` as idp , membre.`pseudo`
            FROM artvu.".TABLE_COMMENTAIRES." comm
            LEFT OUTER JOIN artvu.".TABLE_MEMBRE." membre ON comm.id_membre=membre.id
            WHERE comm.id_cible = '$id_cible'  AND table_cible ='$table_cible' ";

      
        $db->lire_requete($req);
        $tableau = $db->convtableaux();

        

        $genererv2->var_donnee = $tableau;
        $genererv2->var_xml = $xml->charger_xml(__DIR__."/commentaire.xml");

          return $genererv2->vignette();


    }

    /**
     * compter tout le comm d'un id_cible
     */
    Public static function compter($id_cible){

        
  return "je compte  tout les comm d'une cible $id_cible";

    }


    /**
     * efface un comm particulier ou tout les comm d'un id particulier
     */

    Public static function effacer($id_cible = NULL , $id_com = NULL){

            return "j'efface le comm";


    }








}














?>

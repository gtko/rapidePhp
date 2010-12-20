<?php

/**
 * Routine qui sert a effectuer l'identification et la connection;
 * @copyright gtko
**/
class Rapideconnect extends GeneralRoutines{


        /**parametre**/
        private $pseudo;
        private $passe;
        private $identifier;

        Public $barre_connection;
        Public $destination;


        Public function objets(){

            Core::libs("db","session","formv2","xml","java");


        }

        /**ecrire dans les parametres **/
        Public function setxml_form($value){
           

        }

        Public function setpseudo(){

        }

        Public function setpasse(){

        }



        /** Lire un parametre **/

        Public function xml_form(){


        }

        Public function pseudo(){


        }

        Public function passe(){


        }


        /** Function principale **/

        Public function connection(){

            /** je vais charger le xml **/

                Session::boot();
                if(Session::$identifier)
		{
                      
                        Session::update_membre(Session::$pseudo);
                     
                       /*ici la div connection */
                        return $this->barre_connection;
                     
		}
		else
		{

                    /** on crée le form de connection**/
                       $xml = new Xml;
                       $formv2 = new FormulaireV2;
                       $formv2->var_xml = $xml->charger_xml(REP_ROUTINES."/rapidelogin/connection.xml");

                       /** on crée Le javascript pour une verif plus agreable**/
                   Entrer::$script .= "

                    var pseudo_erreur = 0;
                    var passe_erreur = 0;
                    var pseudo_regex = 0;

                    $('.".$formv2->var_xml['name']." #ligne.valider input').click(function(){

                    if($('.".$formv2->var_xml['name']." .pseudo input').val() == ''){
                           
                           $('.".$formv2->var_xml['name']." .erreur').remove();
                           $('.".$formv2->var_xml['name']." ').append(\"<div id='info' class='erreur' style='color:red'>".ERREUR_FORM_CONNECTION."</div>\")
                           $('.".$formv2->var_xml['name']." .erreur').css('display' ,'none');
                           $('.".$formv2->var_xml['name']." .erreur').fadeIn(500);
                           pseudo_erreur = 1;

                    }
                    else
                    {
                       pseudo_erreur = 0;
                    }


                      if($('.".$formv2->var_xml['name']." .mots_de_passe input').val() == ''){
                           $('.".$formv2->var_xml['name']." .erreur').remove();
                           $('.".$formv2->var_xml['name']." ').append(\"<div id='info' class='erreur' style='color:red'>".ERREUR_FORM_CONNECTION."</div>\")
                           $('.".$formv2->var_xml['name']." .erreur').css('display' ,'none');
                           $('.".$formv2->var_xml['name']." .erreur').fadeIn(500);
                           passe_erreur = 1;

                    }
                    else
                    {
                       passe_erreur = 0;
                    }

                    if(pseudo_erreur == 0 & passe_erreur == 0 )
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }


                    });

                ";


                $script = "

                       if(!$('.".$formv2->var_xml['name']." .pseudo input').val().match(/^[a-zA-Z0-9 ]{4,}$/)){
                           $('.".$formv2->var_xml['name']." .erreur').remove();
                           $('.".$formv2->var_xml['name']." ').append(\"<div id='info' class='erreur' style='color:red'>".ERREUR_FORM_CONNECTION."</div>\")
                           $('.".$formv2->var_xml['name']." .erreur').css('display' ,'none');
                           $('.".$formv2->var_xml['name']." .erreur').fadeIn(500);
                           pseudo_erreur = 1;
                           pseudo_regex = 1;
                        }
                        else{
                           $('.".$formv2->var_xml['name']." .erreur').remove();
                            pseudo_regex = 0;
                            pseudo_erreur =0;
                        }

                 ";

                Java::action(".".$formv2->var_xml['name']." .pseudo input", "keyup", $script);

               


                     
                       if(!empty($this->destination))
                       {
                              $formv2->var_xml["actions"] = $this->destination;
                       }
                       $formv2->etat_form = "creation";
                       $html =  $formv2->formulaire()." <div class='inscription'><a href='index.php?modules=inscription'>inscription</a></div>";

                       if($formv2->form_valide)
                        {
                              $this->identification();
                        }



                        return $html;
		}

        }



        Public function identification(){

                    $db = new DB;
                    $db->table = TABLE_MEMBRE;
                    $db->base = "";



                      $pseudo = $_SESSION['post']['table_1']['pseudo'];

       
                    

                         /** JE REVEILLE L'OBJET PERSONNE EXISTANT **/
                        Session::$pseudo =$pseudo;
                    
                        Session::enregistrer_membre($pseudo);

                        $db->table = TABLE_MEMBRE;
                        $requete = $db->lire_DB("","","pseudo='".$pseudo."'");
                        $objet_requete = mysql_fetch_object($requete);

                        
                        /** j'enregistre les information de la personne dans l'objet pers **/

                        $_SESSION['personne']['var_pseudo'] = $pseudo;
                        $_SESSION['personne']['var_mail ']=  $objet_requete->email;
                        $_SESSION['personne']['var_civilite ']=  $objet_requete->civilite;
                        $_SESSION['personne']['var_compte'] =  $objet_requete->compte;
                        $_SESSION['personne']['var_prenom'] =  $objet_requete->prenom;
                        $_SESSION['personne']['var_nom'] =  $objet_requete->nom;
                        $_SESSION['personne']['var_compte_actif'] =  $objet_requete->actif;
                        $_SESSION['personne']['var_id'] =  $objet_requete->id;

                        header("location:index.php?modules=".Entrer::$modules);
                          
                      
                     



        }

          Public function deconnection(){
	
                Session::supprimer_membre(Session::$pseudo);
                header("location=index.php?modules=".PAGE_DEFAULTS);
               
             }


   

}

?>

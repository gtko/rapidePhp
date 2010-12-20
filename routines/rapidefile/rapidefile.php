<?php

/**
 * Cette routine a pour but de crée un gestionnaire de ficier d'un repertoire sources
 * On peut crée , modifier , supprimer les fichier ainsi que changer les droits
 * c'est un objet static pour avoir acces au method plus facilement dans le modules
 * Le tout fonctionne avec php et une extension facultative de javascript pour les transition et animation
 * @param A RENSEIGNER
 * @method A RENSEIGNER
 * @author <Gregoire Ohanessian>
 * @copyright Gtux
 *
 */


class RapideFile extends GeneralRoutines{


Public function objets(){

    Core::libs("java","fichier","genererv2","xml","formv2","genererv3");

}


Public static function afficher_rep($rep , $niveau , $suppr = null){

   

    $fichier = new Fichier;
    $fichier->var_rep = $rep;
    $tableaux = $fichier->lister_rep();

    if(!empty($tableaux))
    {
    foreach($tableaux as $key => $value)
    {
            $file = self::calcule_type($rep.$value);

            if(key_exists("filetype", $file))
            {
                 $tab['file_'.$key]['lien'] = Core::envoyer($value,$niveau,'lecture',$file['filetype']);
            }
            else
            {
                $tab['file_'.$key]['lien'] = Core::envoyer($value,$niveau);
            }

            $tab['file_'.$key]["nom"] = $value;
           
            $tab['file_'.$key]["type"] = $file['type'];
            if($suppr == true)
            {
               $tab['file_'.$key]["suppr_img"] = "suppr_elements";
               $tab['file_'.$key]["suppr"] = "true";
            }
    }

     
    $generer = new Genererv2;
    $xml = new Xml;
    $generer->var_xml = $xml->charger_xml(dirroutine("rapidefile")."generer_affichage.xml");
    $generer->var_donnee = $tab;

    return $generer->vignette();
    }
    else
    {

        return "repertoire vide";

    }
}



/**
 * Piur supprimer un repertoire ou une images
 * @param <type> $qui
 * @return string
 */

Public static function supprimer()
{

       $personne = Session::$membre;
       $var = Core::recuperer();
       

       $a = $var[0];
       $niveau = $var[1];
       $nbr = count($personne->rapidefile_nav_tableaux);

        if($niveau > 0)
        {
            $niveau_retour = $niveau- 1;
             $nbr = 1;
        }
        else
        {
            $niveau_retour = $niveau;
        }


       unlink($personne->rapidefile_nav_tableaux[$nbr - 1]."$a");

    

       header('location:index.php?modules='.Entrer::$modules."&actions=naviguation&a=".Core::envoyer($a, $niveau_retour,"actions" ,"retour_arriere"));





}



Public static function calcule_type($qui){

    $type = filetype($qui);



    if($type == "dir")
    {
        $tab['type'] = "folder";

    }

    elseif($type == "file")
    {
            $ext = explode(".", $qui);
            $nbr = count($ext);

            $ext = $ext[$nbr - 1];

            
            $ext = strtolower($ext);

            switch($ext)
            {
                case $ext == "png" || $ext == "jpg" || $ext == "gif" :

                    $tab["filetype"] = "image";
                     $tab['type'] = "image";

                    break;

                case $ext == "txt" || $ext == "css" || $ext == "html" || $ext == "xhtml":

                    $tab['filetype'] = "texte";
                     $tab['type'] = "texte";

                    break;

                case $ext == "php":

                    $tab['filetype'] = "texte";
                     $tab['type'] = "exec";

                    break;

                case $ext == "ogv" || $ext == "ogg" || $ext == "avi" || $ext == "webm" :

                     $tab['filetype'] = "video";
                     $tab['type'] = "video";

                    break;

                default :

                    $tab['filetype'] = "inconnu";
                    $tab['type'] = "inconnu";

                    break;



            }

    }

    return $tab;

}


Public static function naviguation($source = REP_RACINE){

    /** j'importe les dependance **/
     self::objets();

    /** j'importe le css de naviguation **/
    Afficher::$css[]  = REP_CSS_RAPIDEFILE."gerer_explorer.css";


     /**on enregistre dans l'objet personne le rep actuel + Le tableau de lien **/
    $personne = Session::$membre;
    $personne->rapidefile_rep_source = $source;

   

    /** je recupere le geta our savoir ou je suis **/

    $var = Core::recuperer();
    $a = $var[0];
    $niveau = $var[1];




     /* je compte le nombre d'element du tableau pour ensuite recrée le lien du rep en prenant le dernier rep */

     $nbr = count($personne->rapidefile_nav_tableaux);



    if(in_array('lecture' , $var))
    {
        $key = array_search('lecture', $var);
        $filetype = $var[$key + 1];


        switch($filetype)
        {

            case "image" :

         
                $lien  = explode($personne->rapidefile_rep_source , $personne->rapidefile_nav_tableaux[$nbr - 1]."$a");

                
                return "<img class='image_preview' src='$lien[1]' title='$a' alt='' />";


                break;


            case "texte" :

                return "<textarea class='texte_preview'>".file_get_contents($personne->rapidefile_nav_tableaux[$nbr - 1].$a)."</textarea>";
                
                break;

            case "video" :
             
                $lien  = explode($personne->rapidefile_rep_source , $personne->rapidefile_nav_tableaux[$nbr - 1]."$a");
                Java::video();
                  return "<div class='video-js-box vim-css'>

                 <video class='video-js' width='852' height='480'  controls preload>
                    <source src='".$lien[1]."' type='video/webm; codecs=\"vp8, vorbis\"'>
                    <object class='vjs-flash-fallback' width='852' height='480' type='application/x-shockwave-flash'
                        data='http://releases.flowplayer.org/swf/flowplayer-3.2.1.swf'>
                        <param name='movie' value='http://releases.flowplayer.org/swf/flowplayer-3.2.1.swf' />
                        <param name='allowfullscreen' value='true' />
                        <param name='flashvars' value='config={'clip':{'url':'".$lien[1]."','autoPlay':false,'autoBuffering':true}}' />
                  </object>
                 </video>
            </div>";





                break;

            case "inconnu":

                return "type de fichier non supporter par le naviguateur de fichier";

                break;

          

        }





    }
    elseif(in_array('actions' , $var))
    {
         $key = array_search('actions', $var);
        $action = $var[$key + 1];
        switch($action)
        {
              case "new_folder":
                $lien = $personne->rapidefile_nav_tableaux[$niveau];

                  $form = new Formulairev2;
                  $xml = new Xml;

                  $form->var_xml = $xml->charger_xml(dirroutine("rapidefile")."form_new_rep.xml");
                  $form->etat_form = "creation";
                  $form->var_a = Core::envoyer($a, $niveau ,"actions" ,"new_folder");
                  $etat = $form->formulaire();


                  if($form->form_valide == true)
                  {

                  
                      $retour = Fichier::cree_rep($lien.$form->var_donnee['table_1']['name_rep']);
                      if($retour == false)
                      {
                          Entrer::$erreur[] = "Un repertoire du même nom existe deja";
                      }



                  }


                return $etat.self::barre_action().self::afficher_rep($lien, $niveau);

                break;

            case "suppr_elements":

                $personne->rapidefile_nav_tableaux = Core::suppr_ligne_tableau($personne->rapidefile_nav_tableaux, $niveau + 1);
                $lien = $personne->rapidefile_nav_tableaux[$niveau];
                return self::barre_action().self::afficher_rep($lien, $niveau + 1,true);
           

                break;

       
            case "retour_arriere":

                if($nbr != 1)
                {
                    $personne->rapidefile_nav_tableaux = Core::suppr_ligne_tableau($personne->rapidefile_nav_tableaux, $niveau + 1);
                    $lien = $personne->rapidefile_nav_tableaux[$niveau];
                    return self::barre_action().self::afficher_rep($lien, $niveau + 1);
                }
                else
                {
                    Entrer::$erreur[] = "Vous ne pouvez pas remonter plus loin , car vous etes deja a la racine de votre repertoire";
                    $lien = $personne->rapidefile_nav_tableaux[$niveau];
                  
                     return self::barre_action().self::afficher_rep($lien, $niveau + 1);
                }
                break;
        }

    }
    else
    {
        if(empty($a))
        {
           unset($personne->rapidefile_nav_tableaux);
           $personne->rapidefile_nav_tableaux[] = $source;
           return self::barre_action().self::afficher_rep($source , 1);


        }
        else
        {


            if(key_exists($niveau,$personne->rapidefile_nav_tableaux ))
            {

                $personne->rapidefile_nav_tableaux = Core::suppr_ligne_tableau($personne->rapidefile_nav_tableaux, $niveau + 1);
                $lien = $personne->rapidefile_nav_tableaux[$niveau];
                return self::barre_action().self::afficher_rep($lien, $niveau + 1);
            }
            else
            {
                $lien = $personne->rapidefile_nav_tableaux[] = $personne->rapidefile_nav_tableaux[($nbr - 1)].$a."/";
                return self::barre_action().self::afficher_rep($lien , $nbr + 1 );
            }

        }
    
        
    }


}


Public static function barre_action($quoi = 'folder'){

    $personne = Session::$membre;
    $nbr = count($personne->rapidefile_nav_tableaux);
    $lien  = explode($personne->rapidefile_rep_source , $personne->rapidefile_nav_tableaux[$nbr - 1]);
    Java::uploadify("#upload_rapidefile" , $lien[1],true);

    $var = Core::recuperer();
    $a = $var[0];
    $niveau = $var[1];

    if($niveau > 0)
    {
        $niveau_retour = $niveau- 1;
    }
    else
    {
        $niveau_retour = $niveau;
    }

    switch($quoi)
    {
        case 'folder':

            return "

                <div class='barre_actions'>
                    <div class='retour_arriere'><a href='index.php?modules=".Entrer::$modules."&actions=naviguation&a=".Core::envoyer($a, $niveau_retour,"actions" ,"retour_arriere")."'><img src='".REP_IMAGE_RAPIDEFILE."retour_arriere.png' title='revenir au repertoire precedent'/></a></div>
                    <div class='ajouter_rep'><a href='index.php?modules=".Entrer::$modules."&actions=naviguation&a=".Core::envoyer($a, $niveau ,"actions" ,"new_folder")."'><img src='".REP_IMAGE_RAPIDEFILE."ajouter_dir.png' title='ajouter un repertoire'/></a></div>
                    <div class='supprimer_rep'><a href='index.php?modules=".Entrer::$modules."&actions=naviguation&a=".Core::envoyer($a, $niveau , "actions" ,"suppr_elements")."'><img src='".REP_IMAGE_RAPIDEFILE."suppr_elements.png' title='Supprimer un elements'/></a></div>
                    <div class='ajouter_fichier'><input type='file' id='upload_rapidefile' name='new_file'/></div>


                </div>
                <div class='clear'/>
                <div class='espace_blanc'></div>

                ";

            break;

        default :

            break;

    }

}

}
?>

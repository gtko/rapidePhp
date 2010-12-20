<?php
/*********************************************************/
/************Les variables d'affichage du site************/
/*********************************************************/

//Status du site
define ("STATUS_SITE","dev");//indique le status du site peut etre a 'dev','beta','prod'

// Nom du site
define ("NOM_SITE", "administration");//Le nom du site son rep sera nommer pareille

//Le design choisi
define ("DESIGN", "design_1");//design choisit

// LE menu principale utiliser
define("MENU_PRINCIPALE" , "menu");

//LA page default si aucune correspondance pour eviter l'erreur 404
define ("PAGE_DEFAULTS", "Accueil"); //La page qui s'affiche par defaults

//La langue de devellopement
define("LANGUE_DEFAULTS","fr"); //la langue d'origine du site;




/*********************************************************/
/************TOUS LES REPERTOIRES DU SITES****************/
/*********************************************************/

/**************Lien Php inCassable ******************/


//La racine du site
define ("REP_RACINE", REP_ROOT."system/".NOM_SITE."/");

//Chemin des modules
define ("REP_MODULES", REP_RACINE."module/");

//ici on place tous les chemins des repertoires dans des constantes
define ("REP_DESIGN", REP_RACINE."/design/".DESIGN."/"); //repertoire DESIGN
define ("REP_IMG_PHP", REP_DESIGN."img/"); //repertoire design images
define ("REP_CSS_PHP", REP_DESIGN."css/"); //repertoire design css

define ("REP_MEDIA_PHP", REP_RACINE."/media/");//repertoire de media
define ("REP_IMAGE_PHP", REP_MEDIA_PHP."image/"); //repertoire des images
define ("REP_VIDEO_PHP", REP_MEDIA_PHP."video/"); //repertoire des videos
define ("REP_AUDIO_PHP", REP_MEDIA_PHP."audio/"); //repertoire de audio

/**repartoire divers**/
define ("REP_DIVERS" , REP_RACINE."divers/");


define("REP_CONFIG_SITE" , REP_RACINE."/config/"); // repertoire config du framework rapide_php
define ("REP_MENU" , REP_RACINE."menu/"); //repertoire de MENU
define ("REP_LANG", REP_RACINE."lang/"); //repertoire langue

define ("REP_SITE_ABSTRACT" , REP_RACINE."abstract/");//repertoire du (des) module(s) abstrait

/**************Lien Html Cassable ******************/
define ("REP_CSS", "design/".DESIGN."/css/"); //Le lien html des repertoire css
define ("REP_IMG", "design/".DESIGN."/img/"); //le lien html repertoire img
define ("REP_IMAGE", "media/image/"); //le lien html du repertoire des images
define ("REP_VIDEO", "media/video/"); //le lien html du repertoire des videos
define ("REP_AUDIO", "media/audio/"); //le lien html du repertoire de audio

/**************Require utils ******************/
require_once(REP_SITE_ABSTRACT."modules.php"); //on require la class abstraite des modules du site
require_once(REP_CONFIG_SITE.'database.php'); //LES REGLES POUR SE CONNECTER A LA BASE DE DONNEE



?>

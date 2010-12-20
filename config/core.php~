<?php

/*********************************************************/
/************Repertoires de configuration****************/
/*********************************************************/

define("REP_ROOT" , $_SERVER['DOCUMENT_ROOT']."/");
//chemin de system
define ("REP_SYSTEM" , REP_ROOT."system/"); //repertoire de system
define ("REP_LOG" , REP_SYSTEM."log/"); //repertoire des log du site

//chemin de config
define ("REP_CONFIG" , REP_SYSTEM."config/"); //repertoire des fichier de configuration
define ("REP_LIB" , REP_SYSTEM."libs/"); //repertoire des lib du site
define ("REP_LIB_PHP" , REP_LIB."php/"); //repertoire des lib php du site
define ("REP_OBJECT" , REP_LIB_PHP."object/"); //repertoire des object template
define ("REP_SCRIPT_PHP"  , REP_SYSTEM."script/php/"); //repartoire lib php
define ("REP_SCRIPT_JS" , REP_SYSTEM."script/js/"); //repartoire lib js

//chemin des logs du framework
define("REP_LOG" , REP_SYSTEM."log/");//repertoire des logs system

//chemin pour les script java
define("REP_TINYMCE" , "../system/script/js/jscripts/tiny_mce/"); //repertoire tiny mce
define("REP_JQUERY" , "../system/script/js/jquery/"); //repertoire jquery
define("REP_JQUERY_AUTOCOMPLETE" , "../system/script/js/jquery_autocomplete/"); //repertoire jquery autocomplete
define("REP_UPLOADIFY" , "../system/script/js/uploadify/"); //repertoire de uploadify plugin de jquery
define("REP_VIDEOJS" , "../system/script/js/video.js/"); //repertoire de VIDEOJS
define("REP_ENHANSEJS" ,"../system/script/js/enhancejs/" ); // repertoire de enhansejs pour faire des tableaux avec les graphes

define ("REP_ROUTINES" , REP_SYSTEM."routines/"); //repertoire des routines du site
define ("REP_ABSTRACT" , REP_SYSTEM."abstract/"); //repartoire des class abstraire primaire
define("REP_INTERFACES" , REP_SYSTEM."interfaces/"); //repertoire des interfaces primaire

define("REP_ERREUR_CONST",REP_SYSTEM."erreur/");// repertoire contenant tout les fichier de constante des message

define ("REP_XML_SYSTEM" , REP_SYSTEM."xml/"); //repartoire des xml systeme
define ("REP_SHEMA" , REP_SYSTEM."shema/"); //repartoire des xml systeme

/**** repertoire de la partie admin **/
define("REP_ADMIN" , REP_SYSTEM."administration/") ;// ici le repertoire de la section d'administration du framexwork

/*********************************************************/
/************Clee de decryptage **************************/
/*********************************************************/
define("CLEE_CRYPT" , "*r*a!p:igd1e5p4h*p*g/t*k^o=ç~#{[|");

/************** LES MODEL DU SITE *******************/

/** Les interfaces primaire du frameworks **/
require_once(REP_INTERFACES."general.php");

/***** Les Class mere du framework rapide php *****/
require_once(REP_ABSTRACT.'generalmodules.php');	//definit les modules  du framework
require_once(REP_ABSTRACT.'generalroutines.php');	//definit les routines  du framework
require_once(REP_ABSTRACT.'generallibs.php');		//definit les routines  du framework
require_once(REP_OBJECT.'Object.php');              //definit un objet template
require_once(REP_OBJECT.'ObjectForm.php');		    //definit un objet template
require_once(REP_OBJECT.'VerifDonnee.php');		    //definit un objet template

//Objet de gestion des dependances
require_once(REP_LIB_PHP.'model_core.php');   //fichier core du system

//on importe les functions de devellopements
require_once(REP_SCRIPT_PHP."outilsdev.php");

/// --- function de recherche des rep de module ---/// patch hebergeur
function dirroutine($nom){
          return REP_ROUTINES."$nom/";

}


?>

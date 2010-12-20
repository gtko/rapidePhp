<?php
//Le fichier init.php pour initialiser toutes les composante du moteur
require($_SERVER['DOCUMENT_ROOT'].'/system/config/init.php');
/** je charge La config du site*/
require_once("config/core.php");

if(STATUS_SITE == "dev")
{
    echo outilsDev();
}
// les libs que l'on utilise par defaults
Core::libs("entrer","afficher","xml","session","menuv2","erreur","langue");

//donner la langue maternel du site
$langue = Langue::Singleton();
Entrer::$langue = $langue->recupLang();

// je demarre le gestionnaire d'erreur
Erreur::boot();

/** je lance l'objet des entrées **/

$entrer = New Entrer;

//je donne le nom du site
$entrer->setSite(NOM_SITE);

// je regarde le module

$entrer->setModule($_GET['modules']);


//je regarde l'action
$entrer->setActions($_GET['actions']);

//je capture les get
$entrer->setGet($_GET);

// je vais lancer mon module
$modules = $entrer->getModule();
$modules->general();
$modules->{$entrer->getActions()}();

// lance l'objet qui va calculer le menu principale
   $menu = new Menuv2;
   $xml = new Xml;
   
    //on idique quel menu utiliser
    //si le modules->var_menu ! empty alors on change le menu
    if(!empty($modules->var_menu))
    {
            $menu->var_menu = $modules->var_menu;
    }
    else
    {
            $menu->var_menu = MENU_PRINCIPALE;
    }

//je lance l'objet qui va generer la page html
$afficher = new Afficher;

// et ici tout les parametre utiliser par le modules afficher
$afficher->var_repmodule = $modules->var_repmodule;
$afficher->var_titre = $modules->var_titre;
$afficher->var_css = $modules->var_css;
$afficher->var_meta = $modules->var_meta;
$afficher->var_page = $modules->var_page;
$afficher->var_menu = $menu->menu();
$afficher->var_contenu = $modules->contenu;

//on require le fichier fin_init.php il sert a nettoyer le code d'eventuel variable
require_once(REP_CONFIG.'fin_init.php');
?>

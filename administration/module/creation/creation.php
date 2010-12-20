<?php

class Creation extends Modules
{


    public function General(){

        $this->var_page = "creation";
        $this->var_titre= "Création";
        $this->var_css = "creation/creation.css";

        //creation du menu
        $menu = new Menuv2();
        $menu->var_menu = "menuCreation";
        $this->contenu["menuPage"] = $menu->menu();
	$this->notification("MESSAGE_MODULE_REUSSI");
        $this->notification("MESSAGE_LANGUE_REUSSI");
    }

    public function defaults(){

        $this->contenu["contenuPage"] = "Bonjour creation permet de crée une structure neuve , des modules dans un site";
        $this->setMessage("Bienvenue sur la page d'accueil" , "messageNotif");

    }
    
   
    public function Objets(){

        Core::libs("template");

    }


    public function nouveauSite()
    {

        $template = new Template("formCreation");
        $this->contenu["contenuPage"] = $template->construire() ;
	
		if(Form::getValide("formCreation"))
		{
			Core::libs("framework");
			$valeurForm = Form::getForm("formCreation");
			$newSite = new Framework();
			$newSite->newSite($valeurForm["nomSite"] , $valeurForm["langueSite"]);

						

		}

    }


    public function nouveauModule()
    {

        $template = new Template("formCreationModule");
        $site = $this->rechercherSite();
        $template->setVariable("site", $site);
        $this->contenu["contenuPage"] = $template->construire() ;
		if(Form::getValide("formCreationModule"))
		{
			Core::libs("framework");
			$valeurForm = Form::getForm("formCreationModule");
			$newModule = new Framework();
			$retour = $newModule->creationModule($valeurForm["nomModule"],$valeurForm["siteModule"]);
                        
                        if($retour)
                        {
                            $this->notification("MESSAGE_MODULE_REUSSI" , "{(MODULE_CREER)Votre module c'est créait avec succès}");
                            $this->detruireClee();
                            $this->detruire_nettoyeur();
                            $this->redirection("creation");
                        }
		}



    }

    public function nouveauLang()
    {
       $template = new Template("formCreationLang");
        $site = $this->rechercherSite();

        $template->setVariable("site", $site);
        $this->contenu["contenuPage"] = $template->construire() ;
		if(Form::getValide("formCreationLang"))
		{
			Core::libs("framework");
			$valeurForm = Form::getForm("formCreationLang");
			$newLang = new Framework();
			$retour = $newLang->creationLang($valeurForm["nomLang"],$valeurForm["siteLang"]);

                        if($retour)
                        {
                            $this->notification("MESSAGE_LANGUE_REUSSI" , "{(LANGUE_CREER)Votre Langue c'est créait avec succès}");
                            $this->detruireClee();
                            $this->detruire_nettoyeur();
                            $this->redirection("creation");
                        }
		}


    }


}
?>

<?php
/**Objet permettant de Les fonctuion du framework , construire une structure vide,
 * crée des modules , etc ...
 *
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 *
 */
class Framework {
	
	

	public function __construct()
	{
		require_once(REP_CONFIG."creationFram.php");
	}

	
	public function newSite($site , $lang)
	{
		
		/**script de creation de nouvelle structure propre**/
		if(!file_exists(REP_ROOT.$site))
		{
			mkdir(REP_ROOT.$site);
			$siteBase = REP_ROOT.$site."/";

                        /**on copie l'index.php**/
                        copy(INDEX_VIDE,$siteBase."index.php");
			/**creation de abstract*/
			mkdir($siteBase."abstract");		
			/**creation de config*/
			mkdir($siteBase."config");	
			/**creation de design*/
			mkdir($siteBase."design");


                        /**creation de lang**/
                        mkdir($siteBase."lang/");
                        $valeur = '<?php

                            ?>';
                        file_put_contents($siteBase."lang/$lang.php", $valeur);

			/**creation de media*/
			mkdir($siteBase."media");
			/**creation de audio*/
			mkdir($siteBase."media/audio/");
			/**creation de video*/
			mkdir($siteBase."media/video");
			/**creation de image*/
			mkdir($siteBase."media/image");
			/**creation de menu*/
			mkdir($siteBase."menu");

                        /**on importe le menu**/
                        copy(REP_DIVERS."nouveauSite/menu/menu.xml",$siteBase."menu/menu.xml");

			/**creation de module*/
			mkdir($siteBase."module");			

			/**on deplace le fichier module de abstract**/
			copy(REP_DIVERS."nouveauSite/abstract/modules.php",$siteBase."abstract/modules.php");
			/**creation des fichier de configuration**/
                        /**on remplit le fichier de configuration*/

                        $valeur = $this->remplacerValeur("NOMSITE", file_get_contents(REP_DIVERS."nouveauSite/config/core.php"), $site);
                        $valeur = $this->remplacerValeur("LANG", $valeur, $lang);
                        file_put_contents($siteBase."config/core.php", $valeur);

			copy(REP_DIVERS."nouveauSite/config/database.php",$siteBase."config/database.php");
			
			$this->creationModule("Accueil" , REP_ROOT.$site);
                        $this->creationDesign("design_1", $site);

		}
		else
		{
			//erreur site deja existant			
		}				
		



	}


	public function creationModule($module , $site)
	{
           
		if(file_exists($site))
		{
                    if(is_writable($site."/module/"))
                    {
 			if(!file_exists($site."/module/".strtolower($module)."/"))
			{
				/**on crée le premier modules vide**/
				mkdir($site."/module/".strtolower($module)."/");
				$moduleRep = $site."/module/".strtolower($module)."/";
			
				$valeur  =  MODULE_VIDE;
				$valeur  = $this->remplacerValeur("module",$valeur,$module);
				$valeur  = $this->remplacerValeur("titre",$valeur,$module);
				$valeur  = $this->remplacerValeur("page",$valeur,strtolower($module));
				
				file_put_contents($moduleRep.strtolower($module).".php",$valeur);
				file_put_contents($moduleRep.strtolower($module).".page.php",MODULE_PAGE_VIDE);
                                return true;
			}
			else
			{
                            Erreur::declarer_int(2003 ,"Le module existe déjà(Framework)");
                            return false;
			}
                    }
                    else
                    {
                        Erreur::declarer_int(2002 ,"Le repertoire module n'est pas accesible en ecriture(Framework)");
                        return false;
                    }
                }
		else
		{
			Erreur::declarer_int(2001 ,"Le site n'existe pas(Framework)");
                        return false;
		}

	}

        	public function creationLang($lang , $site)
	{

		if(file_exists($site))
		{
                    if(is_writable($site."/lang/"))
                    {
 			if(!file_exists($site."/lang/".strtolower($lang).".php"))
			{
				/**on crée le premier modules vide**/
                                $langRep = $site."/lang/";

				$valeur  =  LANG_VIDE;
				file_put_contents($langRep.strtolower($lang).".php",$valeur);
				
                                return true;
			}
			else
			{
                            Erreur::declarer_int(2006 ,"La lang existe déjà(Framework)");
                            return false;
			}
                    }
                    else
                    {
                        Erreur::declarer_int(2005 ,"Le repertoire langue n'est pas accesible en ecriture(Framework)");
                        return false;
                    }
                }
		else
		{
			Erreur::declarer_int(2001 ,"Le site n'existe pas(Framework)");
                        return false;
		}

	}

	public function creationDesign($design , $site)
	{
		if(file_exists(REP_ROOT.$site))
		{
			if(!file_exists(REP_ROOT.$site."/design/".strtolower($design)."/"))
			{
				/**on crée le premier modules vide**/
				mkdir(REP_ROOT.$site."/design/".strtolower($design)."/");
				$designRep = REP_ROOT.$site."/design/".strtolower($design)."/";
				/**creation de css*/
				mkdir($designRep."/css/");
				/**creation de img*/
				mkdir($designRep."/img/");

				
				$valeur  =  DESIGN_VIDE;
    			file_put_contents($designRep.strtolower($design).".php",$valeur);
			
			}
			else
			{
				//erreur module existant;
			}
		}
		else
		{
			//erreur site introuvable
		}

	}

     private function remplacerValeur($quoi , $valeur , $remplace)
     {
       
            //pour les variable xml sql en php
             if(preg_match_all('/\$'.$quoi.'/', $valeur , $tab))
             {
                 foreach($tab[0] as $clee => $value)
                 {
                     $valeur = str_replace($tab[0][$clee], $remplace,$valeur);
     
                 }
             }
            return $valeur;
    }
	


}
?>

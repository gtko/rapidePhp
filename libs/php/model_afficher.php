<?php 

class Afficher{
		
		Public $var_css;
		Public $var_titre;
		Public $var_meta;
		Public $var_page;
		Public $var_menu;
		Public $var_contenu;

                Public static $var_contenu_supll; //objet exterieur du module qui veulent afficher quelque chose
                Public static $css;
                Public static $style = array();
                Public static $supplement = array();
                Public static $script = array();



                function _construct(){

            	}
    	
		Public function afficher_rendu()
		{
			
			//on effecture la recherche de page
                       if(file_exists(Entrer::$rep_modules.$this->var_page.".page.php"))
			{
				//on insere les elements dynamique de la sous-page
                                //en creant a la volé les variables 
				if(!empty($this->var_contenu) && is_array($this->var_contenu))
				{
					foreach($this->var_contenu as $k => $v)
					{
						
						${$k} = $v;
						
					}
                                        
				}

                                //on insere les elements dynamique de la sous-page
                                //en creant a la volé les variables
				if(!empty(self::$supplement) && is_array(self::$supplement))
				{
					foreach(self::$supplement as $k => $v)
					{

						${$k} .= $v."<br/>";
                                              
					}
                                       

				}
                                
                                //on insere les elements script de la sous-page
                                //en creant a la volé les variables
				if(!empty(self::$script) && is_array(self::$script))
				{
					foreach(self::$script as $k => $v)
					{

						$javascriptPhp .= "<script type='text/javascript' src='".$v."'></script>\n";
                                              
					}
                                       

				}
               

                                //on indique le fichier de la page pour le contenu
				$conteneur = Entrer::$rep_modules.$this->var_page.".page.php";
							
				//on insert le titre de la page
				$titre = $this->var_titre;
				
			
				//on affiche les css
				
				if(!empty($this->var_css) && !is_array($this->var_css) && isset($this->var_css))
				{
					$css .= "<link rel='stylesheet' type='text/css' href='".REP_CSS.$this->var_css."' />";	
				}
				elseif(is_array($this->var_css))
				{
					foreach($this->var_css as $k => $v )
					{
						$css .= "<link rel='stylesheet' type='text/css' href='".REP_CSS."$v' />";	
					}
					
				}

                                if(is_array(self::$style))
				{
					foreach(self::$style as $k => $v )
					{
						$style_css .= $v." ";
					}
					
				}

                    
				if(is_array(self::$css))
				{
					foreach(self::$css as $k => $v )
					{
						$css .= "<link rel='stylesheet' type='text/css' href='".$v."' />";
					}

				}


                                /*if(is_array(Entrer::$erreur))
                                {
                                    	foreach(Entrer::$erreur as $k => $v )
					{
						$erreur_framework .= "<strong style='color:red;'>$v</strong><br/>";
					}

                                }*/

				//on integre le menu
				$menu = $this->var_menu;

                                
				//on affectue le rendu de la page
                 
                                
				include_once(REP_DESIGN.DESIGN.".php");
			
				
			
			}
                        else
                        {

                            Erreur::declarer_dev(4, "Page = ".Entrer::$rep_modules.$this->var_page.".page.php  objet : Afficher ; args : var_page");
                        

                        }
		}

}
?>

<?php
/**Objet permettant de gerer les entrer modules , actions
 * 
 * @author Ohanessian gregoire <gtux.prog@gmail.com>
 */
class Entrer {


        private $var_site = NULL;
        private static $var_module = NULL;
        private static $var_actions = NULL;
       

        
        Public static $langue;
        Public static $modules;
        
        Public static $actions;
        Public static $rep_modules;
        Public static $repModulesHtml;
        
        Public static $site;
        Public static $get;
        Public static $geta;
        
        
        
        
        Public static $script;
        Public static $script_java;
        Public static $java_exist = false;
        Public static $postjava;
        Public static $ajout_script;
        Public static $variable = array();
        Public static $erreur = array();

        
        
        
        Public static function boot()
        {
            
            self::$modules = $_GET["modules"];
            self::$actions = $_GET["actions"];
            self::$geta = $_GET["geta"];
            self::$get = $_GET;
                        
            
            /**on lance les action demander**/
            
            $entrer = new Entrer();
            $entrer->setModule(self::$modules);
            $entrer->setActions(self::$actions);
            $entrer->setGet(self::$get);
            
        }

        
        Public static function  java_exist(){
                  Core::libs("session");
                  $personne = Session::$membre;
                  if($personne->var_java == true)
                  {
                            Entrer::$java_exist = true;
                            $personne->var_java = false;
                  }
                  elseif($_SESSION['java'] == true)
                  {
                            Entrer::$java_exist = true;
                            $_SESSION['java'] = false;
                  }

        }

        Public function setSite()
        {
                /** on verifie que le site existe bien dans **/
                    if(file_exists(REP_RACINE))
                        {
                                $this->var_site = NOM_SITE;
                                self::$site;
                                /** on va charger les noveau fichier de configuration**/
                                $this->charger_config();
                        }
                    else
                        {
                                Erreur::declarer_dev(1300,"objet : Entrer , function ; setSite , arguments : ".NOM_SITE);
                        }
               
         

        }


        Public function getSite()
        {
                return $this->var_site;

        }

         Public function setModule($module = NULL)
        {
            /** on verifie que le module existe bien dans le site**/

           
            if(file_exists(REP_MODULES.strtolower($module)) && $module != NULL)
                {
                       
                       
                        $sansespace = strtolower(str_replace(CHR(32),"",$module));
                                               
                        include_once(REP_MODULES.$sansespace."/$sansespace.php");
                         
                        if(class_exists($sansespace))
                        {
                               self::$var_module = new $sansespace;
                             
                               self::$rep_modules = REP_MODULES.$sansespace."/";
                               self::$repModulesHtml = REP_MODULES_HTML.$sansespace."/";
                               self::$var_module->var_repmodule = REP_MODULES.$sansespace."/";
                               self::$modules = $sansespace;
                        }
                        else
                        {
                            echo "La classs n'existe pas , l'erreur peut provenir de :<br/>
                                
                                -sois le nom de la class <br/>";
                        }
                        
                }
                else
                {
                        if($module != NULL)
                        {
                            Erreur::declarer_dev(301 , "page demander = $module");

                        }
                       
                        //Erreur::declarer_int(301,"message_site");
                        $entrer = PAGE_DEFAULTS;
                        $module = strtolower(str_replace(CHR(32),"",$entrer));
                        include_once(REP_MODULES.$module."/".$module.".php");
                        self::$var_module = new $module;
                        self::$rep_modules = REP_MODULES.$module."/";
                        self::$repModulesHtml = REP_MODULES_HTML.$sansespace."/";
                        self::$var_module->var_repmodule = REP_MODULES.$module."/";
                        self::$modules = $module;
                       
                }

               
        }

        Public function getModule()
        {
            return self::$var_module;

        }

         Public function setActions($actions = "defaults")
        {
            /** on verifie que l'action existe bien dans le module**/
           $sansespace_action = str_replace(CHR(32),"_",$actions);
             if(method_exists($this->var_module, $actions))
                {

                        self::$var_actions =  $sansespace_action;
                        self::$actions = $sansespace_action;
                }
                else
                {
                   
                     self::$var_actions = "defaults";
                     $this->module->var_actions = "defaults";
                }
        }

        Public function getActions()
        {
                return self::$var_actions;

        }


        Public function setGet($get)
        {

            if(isset($get))
           {
                  $this->get_a = $get;
                  self::$get = $get;
            }
            else
            {
               
                $this->get = false;

            }

        }

        Public function getGet()
        {
            return $this->get;
        }



        /**
         * function qui charge les nouveau fichier de configuration
         */
        Public function charger_config(){
      
            if(!include_once(REP_CONFIG_SITE."core.php")){
                 Erreur::declarer_dev(1301,"objet: Entrer ; function : charger_config args : ".REP_CONFIG_SITE."core.php");
            }
        }

        
        
        
        



}
?>

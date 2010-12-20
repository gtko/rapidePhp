<?php

/**
 * Description of model_menuv2
 * @author gregoire
 */
class Menuv2 extends GeneralLibs {

            public $var_menu;

            public $select;
            private $type;

            Public static $selection;


            Public function Objets(){

                Core::libs("xml");

            }



            Private function menu_cascade($child){
               $this->type="cascade";
               $aff_menu =  $this->verif_constant($child);

                    //je n'affiche le menu en cascade qui si il y a des enfants
       
                   if(count($child) > 0)
                   {
                             $aff_menu .="<ul class='menu_cacher menu_".$this->verif_constant($child)."'>";
                              foreach($child->children() as $petitchild)
                              {
                                      $actions = ($petitchild['actions'] == 'defaults')?"":"&actions=".$petitchild['actions'] ;
                                      $aff_menu .="<li class='bouton_".strval($this->verif_constant($child))."'><a href='?modules=".strtolower($petitchild['modules']).$actions."'>".strval($petitchild)."</a></li>";

                              }
                             $aff_menu .="</ul>";
                   }
            

                  return  $aff_menu;
            }


            private function verif_lien($child , $action){

                    $lien = 0;
             
                                  if($child['lien'] == "false")
                                  {

                                            $lien = $this->menu_cascade($child);

                                  }
                                  else
                                  {
                                         $lien =  " <a href='?modules=".strtolower($child['modules']).$action."'>".$this->menu_cascade($child)."</a> ";
                                  }
                       
                      return $lien;

            }

            public function menu(){

                $this->type="normal";
                $this->charger_menu();

                $aff_menu ="<ul>";
                foreach($this->var_menu->children() as $child)
                {

                     $actions = ($child['actions'] == 'defaults')?"":"&actions=".$child['actions'] ;
                    
                    if($actions =="")
                      {

                            if ($child['modules'] == Entrer::$modules || $child['modules'] == $this->select )
                            {
                               
                                    $aff_menu .="<li class='".strval($this->var_menu['class'])."_select'>".$this->verif_lien($child, $actions)."</li>";

                            }
                            else
                            {
                                    
                                    $aff_menu .="<li class='".strval($this->var_menu['class'])."'>".$this->verif_lien($child, $actions)."</li>";
                            }
                        }
                        else
                        {
             
                            if ($child['actions'] == Entrer::$actions || $child['actions'] == $this->select)
                            {
                                    $this->selection = $child['actions'];
                                    $aff_menu .="<li class='".strval($this->var_menu['class'])."_select'>".$this->verif_lien($child, $actions)."</li>";

                            }
                            else
                            {
                                    
                                    $aff_menu .="<li class='".strval($this->var_menu['class'])."'>".$this->verif_lien($child, $actions)."</li>";
                            }

                        }


                }

                $aff_menu .="<div class='clear'></div></ul>";
                 return  $aff_menu;

            }


            private function charger_menu(){


                $xml = new Xml;

                if(file_exists(REP_MENU.$this->var_menu.".xml"))
                {
                    $this->var_menu = $xml->charger_xml(REP_MENU.$this->var_menu.".xml");
                }
                else
                {

                    if(file_exists(Entrer::$rep_modules.$this->var_menu.".xml"))
                    {
                        $this->var_menu = $xml->charger_xml(Entrer::$rep_modules.$this->var_menu.".xml");
                    }
                    else
                    {

                        //erreur de dev

                    }


                }

            }



}
?>

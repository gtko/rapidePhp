<?php
/**
 * Class permettant de generer tous type de donnée html grace a la puissance de php et xml reuni
 * @version 2.0
 * @author Ohanessian Gregoire
 * @copyright gtux
 */


class GenererV2 extends GeneralLibs{


            public $var_donnee;
            public $var_xml;

            /*variable privee*/
            private $var_xml_elements;


            /** si l'on veut modifier Le repertoire des images pour afficher de plus petite ou plus grande image
             *
             */

            public $var_new_rep_img;


        Public function objets(){


            
        }

        /**
         * On genere un tableaux a partir d'un tableaux db avec un template xml
         * @param $this->var_donnee & $this->var_xml
         * @author Ohanessian Gregoire
         * @copyright gtux
         *
         */

        Public function tableaux()
        {
            
            $entre = $this->verif_entre();
            if(!$entre)
            {
                return $entre;
            }
           

            /*On regarde si le type de tableaux est en ligne en collone*/
            switch($this->var_xml['type'])
                    {
                case 'ligne':
                   
                break;
                
                case 'collone':
            
        
                break;
                
                default :
                    return "l'attribut de table->type est invalide il ne doit etre que ligne ou collone";
                
                break;
            }

            /*on recupere Le nom de la table est sa position*/
            $position = $this->var_xml->titre['position'];
            $titre = $this->var_xml->titre;


            /*on contruit le header du tableaux*/

            /*je compte le nombre de collone*/
            $nbr_collone = count($this->var_xml->header->collone);


            $fin = 0;
            while($nbr_collone != $fin)
            {
                $header .= '<th>'.$this->var_xml->header->collone[$fin].'</th>';
                $fin ++;
            }

            /* je crée la ligne header*/
            $header = "<tr><th class='nobg'>$titre</th>$header</tr>";


             /*On contruit Les lignes avec le body du tableaux*/

             if(is_array($this->var_donnee))
                     {
             foreach($this->var_donnee as $k => $this->var_valeur)
                    {
                        if(is_array($this->var_valeur))
                            {

                             $ligne .= "<tr>";
                                 $attr_body .= $this->var_xml->body->ligne['variable'];
                                $ligne .= "<td>".$this->var_valeur[$attr_body]."</td>";

                                 $fin = 0;
                                 while($nbr_collone != $fin)
                                 {
                                     switch($this->var_xml->header->collone[$fin]['type'])
                                             {
                                         
                                             case 'variable':
                                                 
                                                 $attr_header  .=  $this->var_xml->header->collone[$fin]['variable'];
                                                 $ligne .= "<td class='alt'>".$this->var_valeur[$attr_header]."</td>";
                                                 
                                                 break;
                                             
                                             case 'actions':
                                                 $actions =   $this->var_xml->header->collone[$fin]['variable'];
                                                 $type = $this->var_xml->actions->{$actions}['type'];

                                                  switch($type)
                                                  {
                                                      case 'lien':
                                                            /*on construit le lien */
                                                              $modules = (empty($this->var_xml->actions->{$actions}['modules']))?$_GET['modules'] : $this->var_xml->actions->{$actions}['modules'];
                                                              $action_lien = $this->var_xml->actions->{$actions}['actions'];
                                                               $qui .= $this->var_xml->actions->{$actions}['qui'];
                                                                 $ligne .= "<td><a href='index.php?modules=".$modules."&actions=".$action_lien."&a=".$this->var_valeur[$qui]."'>".$this->var_xml->actions->{$actions}."</a></td>";
                                                                unset($qui);
                                                              break;
                                                  }
                                                 break;
                                         
                                         
                                     }
                                      
                                     unset($attr_header);
                                     $fin++;
                                 }
                              
                            $ligne .= "</tr>";
                            unset($attr_body);
                        }
                    }
                  }




            $debut = "<table id='".$this->var_xml['id_css']."' cellspacing='0'>";
             
            $fin_table .="</table>";

            return $debut.$header.$ligne.$fin_table;

        }


        /**
         * function qui va crée des vignettes a partir du xml et du tableaux de donnée envoyer
         * @param $this->var_xml & $this->var_donnee
         * @author Ohanessian gregoire
         * @copyright gtux
         */

        Public function vignette()
        {

            $entre = $this->verif_entre();
            if(!$entre)
            {
                return $entre;
            }

             
            /* je crée la div principal de la vignette*/
           /*on verifie l id de la vignette si vide je lui mets un vignette par defaults*/
           $id_vignette = (empty($this->var_xml['id_css']))?"vignette":$this->var_xml['id_css'];

            

               /*JE PARCOUR LE TABLEAUX EST J'EFFECTUE UN TRAITEMENT PAR SOUS TABLEAUX*/
            if(is_array($this->var_donnee))
                    {
            foreach($this->var_donnee as $k => $valeur)
                    {
                            if(is_array($valeur))
                                {
                                    $this->var_valeur = $valeur;
                                    
                                    $vignette .= "<div class='".$id_vignette."' id='".$id_vignette."_".$valeur[strval($this->var_xml['id'])]."'>";

                                            /**je verifie que l'elements n'est pas dans un conteneur*/

                                 

                                    /*je compte le nombre d'elements qui se trouve dans la racine vignette*/
                                    /*puis j'effectue une boucle pour les afficher*/
                                    
                                      /*je recupere Les champ option du xml*/
                                     foreach($this->var_xml->children()   as $child)
                                    {

                                         if($child->getName() == "conteneur")
                                              {
                                                      $vignette .= "<div class='".$child['class_css']."'>";

                                                      $lien = 0;
                                                      if(!empty($child['modules']) & !empty($child['actions']))
                                                      {
                                                        
                                                          $actions = (empty($child['actions']))?$_GET['actions']:$child['actions'];
                                                          $modules = (empty($child['modules']))?$_GET['modules']:$child['modules'];
                                                             if(!empty($child['ancre']))
                                                            {
                                                            $ancre= '#'.$child['ancre']."_".$this->var_valeur[strval($child['variable'])];
                                                            $href = $ancre;
                                                            }
                                                            else
                                                            {
                                                                $href = "index.php?modules=$modules&actions=$actions&a=".$this->var_valeur[strval($child['variable'])].$ancre;
                                                            }
                                                            $vignette .= "<a href='$href'>";
                                                            $lien = 1;
                                                      }
                                                         /*je recupere Les champ option du xml*/
                                                           foreach($child->children()   as $petitchild)
                                                        {
                                                             $this->var_xml_petitchild = $petitchild;
                                                             $vignette .=  $this->{$petitchild->getName()}();



                                                        }

                                                        if($lien == 1)
                                                        {
                                                            $vignette .= "</a>";
                                                        }



                                                        $vignette .='</div>';

                                              }
                                         else
                                             {
                                                     $this->var_xml_petitchild = $child;
                                                  
                                                      $vignette .=  $this->{$child->getName()}();

                                             }



                                     }

                                     $vignette .="</div>";
                        

                             }
                        }
                    }
            return $vignette;
        }

        /**function choix qui effectue un choix**/

        Private function choix(){

            $variable = $this->var_xml_petitchild;
            if($variable['egal'] == $this->var_valeur[strval($variable['variable'])])
            {
       
              foreach($variable  as $child)
               {
                        $this->var_xml_petitchild = $child;
                        $vignette .=  $this->{$child->getName()}();

               }

            return $vignette;
         }
         else
         {
                   return ;
         }
      }




        /*fucntion qui effectue le switch des elements*/
        Private function elements()
        {
            $variable = $this->var_xml_petitchild;
       
            switch ($variable['type'])
                  {
                        case "variable":

                        $clee_tab .= $variable['variable'];
                        /** je recupere le texte a afficher avec la variable et je remplace Le {quelquechose} par la variable*/
                        $chaine = $variable;
                        if(@strpbrk("{".$clee_tab."}", $chaine))
                                {
                                 $final = str_replace("{".$clee_tab."}", $this->var_valeur[$clee_tab], $chaine);
                        }
                        else
                            {
                           $final = $this->var_valeur[$clee_tab];
                        }
                        $vignette .="<div class='".$variable['class_css']."'>".htmlspecialchars_decode($final,ENT_QUOTES)."</div>";
                        
                     
                      

                        unset($clee_tab);
                        break;

                        case "fichier" :
                            /*je prepare le rep*/

                            $rep = $this->verif_constant($variable['rep']);
                            if(!file_exists($rep))
                                {
                                    return "repertoire inexistant";
                            }


                            /*je vais verifier si la variavle est une clée du tableaux
                            * sinon je vais chercher si le fichier existe dans le repertoire donnée
                            * sinon j'affiche dedans un message comme quoi aucun texte trouver
                            */
                             $clee_enf .= $variable['variable'];
                              if(array_key_exists($clee_enf, $this->var_valeur))
                                     {
                                         $nom_fichier = $this->var_valeur[$clee_enf];
                             }
                             else
                                 {

                                    if(file_exists($rep.$clee_enf.".".$variable['format']))
                                     {
                                        $nom_fichier = $clee_enf;

                                    }
                                    else
                                    {
                                        $nom_fichier = NULL;
                                        $contenu = "aucun texte trouver.";

                                    }

                             }
                             
                              if(file_exists($rep.$nom_fichier.".".$variable['format']))
                                                 {
                                                 $contenu = file_get_contents($rep.$nom_fichier.".".$variable['format']);

                                         }
                                         else
                                             {
                                             $contenu = "Nous ne pouvons pas charger le texte.";
                                            
                                         }
                              if(!empty($variable['longueur']) )
                                      {
                                        $longueur .= $variable['longueur'];
                                        if(strlen($contenu) > $longueur)
                                            {
                                                $point = " ...";
                                        }
                                        $contenu = substr($contenu, 0,$longueur).$point;

                                        unset($longueur);
                              }
                              $vignette .="<div class='".$variable['class_css']."'>".$contenu."</div>";


                            break;

                        case "img_condition":
                        $class =$variable['class_css'] ;
                        $clee_tab .= $variable['condition'];
                        $clee_tab0 .= $variable['faux'];
                        $clee_tab1 .= $variable['vrai'];
                        $src = $variable['src'];
                        $ext = $variable['ext'];

                        if ($this->var_valeur[$clee_tab]==1){
                                $image = constant($src).$variable['vrai'].".".$ext;
                                $title =MESSAGE_OUVERT;
                        }
                        else{
                                $image = constant($src).$variable['faux'].".".$ext;
                                $title =MESSAGE_FERMER;
                        }

                        if(!file_exists($image))
                        {
                                $image= constant($src).$variable['vide'].".".$ext;
                        }

                        /** on va crée le lien si y'en a un **/
                        if(!empty($variable['actions']))
                        {
                               $actions = (empty($variable['actions']))?$_GET['actions']:$variable['actions'];
                               $modules = (empty($variable['modules']))?$_GET['modules']:$variable['modules'];
                               $href = "?modules=$modules&actions=$actions&a=".$this->var_valeur[strval($variable['variable'])];
                               $vignette .= "<div class='$class'><a href='$href'><img src='$image' title ='$title'/></a></div>";
                        }
                        else
                        {
                              $vignette .= "<div class='$class'><img src='$image' title ='$title'/></div>";
                        }


                      

                        unset($class);
                        unset($clee_tab);
                        unset($clee_tab0);
                        unset($clee_tab1);
                        unset($src);
                        unset($ext);
                        unset($image);
                        break;



                        case "img":
                        $class =$variable['class_css'] ;
                        $src = $variable['src'];
                        $ext = $variable['ext'];

                        if(!empty($this->var_valeur[strval($variable['variable'])]))
                        {
                        	$image = $this->verif_constant($src).$this->var_valeur[strval($variable['variable'])].".".$ext;
                        
                        }
                        elseif(file_exists($this->verif_constant($src).strval($variable['variable']).".".$ext))
			{
				$image = $this->verif_constant($src).strval($variable['variable']).".".$ext;
			}                        
                        elseif(!file_exists($image))
                        {
                          $image= $this->verif_constant($src).$variable['vide'].".".$ext;
                        }

                        
                        $vignette .= "<div class='$class'><img src='$image'/></div>";
                       
                        unset($class);
                        unset($src);
                        unset($ext);
                        unset($image);
                        break;

                        case "actions_img":


                        $class =$variable['class_css'] ;
                        $clee_tab .= $variable['variable'];
                        $src = $variable['src'];
                        $ext = $variable['ext'];

                        $image = strval($variable['image']);

                        if(!empty($image))
                        {

                             $image = $this->verif_constant($src).$this->var_valeur[$image].".".$ext;
                              if(!file_exists($image))
                                      {
                                            $image= $this->verif_constant($src).$variable['vide'].".".$ext;
                                      }
                        }
                        else
                        {

                            if(key_exists($clee_tab,$this->var_valeur))
                            {
                                         if(empty($this->var_new_rep_img))
                                              {
                                                  $image = $this->verif_constant($src).$this->var_valeur[$clee_tab].".".$ext;

                                              }
                                              else
                                              {
                                                  $image = $this->var_new_rep_img.$this->var_valeur[$clee_tab].".".$ext;

                                              }

                                      if(!file_exists($image))
                                      {
                                            $image= $this->verif_constant($src).$variable['vide'].".".$ext;
                                      }

                            }
                            else
                            {
                                $image= $this->verif_constant($src).$clee_tab.".".$ext;
                            }

                        }
                        
                        /*je construit le lien de l'actions*/
                        $actions = (empty($variable['actions']))?$_GET['actions']:$variable['actions'];
                 
                        $modules = (empty($variable['modules']))?$_GET['modules']:$variable['modules'];
                        $clee_tab2 .= (empty($variable['qui']))?$variable['variable'] :$variable['qui'] ;
                        $href = "index.php?modules=$modules&actions=$actions&a=".$this->var_valeur[$clee_tab2];
                        $ext = $variable['ext'];
                        $vignette .= "<div class='$class'><a href='$href'><img title='".$variable['aide']."' src='$image' alt=''/></a></div>";

                        unset($class);
                        unset($clee_tab);
                        unset($clee_tab2);
                        unset($src);
                        unset($ext);
                        unset($image);
                        unset($actions);
                        unset($modules);
                        break;


                        case "description":





                        break;

                        case "actions_variable" :
                        $actions = (empty($variable['actions']))?$_GET['actions']:$variable['actions'];
                        $modules = (empty($variable['modules']))?$_GET['modules']:$variable['modules'];
                        $clee_qui = strval($variable['qui']);
                        $class =$variable['class_css'];

                        $clee_tab = strval($variable['variable']);
                        /** je recupere le texte a afficher avec la variable et je remplace Le {quelquechose} par la variable*/
                        $chaine = $variable;
                        if(@strpbrk("{".$clee_tab."}", $chaine))
                                {
                                 $final = str_replace("{".$clee_tab."}", $this->var_valeur[$clee_tab], $chaine);
                        }
                        else
                            {
                           $final = $this->var_valeur[$clee_tab];
                        }

                        $vignette .= "<div class='$class'><a href='index.php?modules=$modules&actions=$actions&a=".$this->var_valeur[$clee_qui]."'>".$final."</a></div>";



                        break;

                        case "action":
                        $actions = (empty($variable['actions']))?$_GET['actions']:$variable['actions'];
                        $modules = (empty($variable['modules']))?$_GET['modules']:$variable['modules'];
                        $clee_tab .= $variable['qui'];
                        $class =$variable['class_css'];

                        if(!empty($variable['ancre']))
                        {
                            $ancre= '#'.$variable['ancre']."_".$this->var_valeur[$clee_tab];
                        }
                        $vignette .= "<div class='$class'><a href='index.php?modules=$modules&actions=$actions&a=".$this->var_valeur[$clee_tab]."$ancre'>".$variable."</a></div>";

                        unset($actions);
                        unset($modules);
                        unset($clee_tab);
                        unset($class);


                        break;

                        case "clear":

                                   $vignette .="<div style='clear:both'></div>";

                       break;

                        }

                        return $vignette;
        }



        /**
         * function qui genere une liste avec du xml et un tableaux sortant de sql
         * @param $this->var_xml | $this->var_donnee
         * @author Ohanessian gregoire
         * @copyright gtux
         */

        Public function liste()
        {
            /*je verifie les donnée d'entré*/
            $entre = $this->verif_entre();
            if(!$entre)
            {
                return $entre;
            }


             /*on verifie l id de la vignette si vide je lui mets un vignette par defaults*/
            $id_liste = (empty($this->var_xml['id_css']))?"liste":$this->var_xml['id_css'];
            /*je crée le ul de la div */

            $html = "<ul id='$id_liste'>";

                  /*je compte le nombre d'elements qui se trouve dans la racine vignette*/
                  /*puis j'effectue une boucle pour les afficher*/
                    $nbr_collone = $this->var_xml->count();
                    $fin = 0;
                    while($nbr_collone != $fin)
                    {

                              $html .=  $this->{strval($this->var_xml->ligne[$fin]['type'])}($fin);
                              $fin ++;
                    }

           /*fermeture de la liste html */
           $html .= "</ul>";

           return $html;

        }

 

        Private function lien_manuel($fin)
        {


             $modules = (empty($this->var_xml->ligne[$fin]['modules']))?$_GET['modules']:$this->var_xml->ligne[$fin]['modules'];
             $href = "index.php?modules=".$modules."&actions=".$this->var_xml->ligne[$fin]['actions'];
              return "<li class='".$this->var_xml->ligne[$fin]['class_css']."'><a href='$href'>".$this->var_xml->ligne[$fin]."</a></li>";




        }


        Private function lien($fin)
        {
             /*je parcour le tableaux de donnée est j'applique La constrution */
            if(is_array($this->var_donnee))
           {
            foreach($this->var_donnee as $k => $this->var_valeur)
                {
                   if(!empty($this->var_xml->ligne[$fin]))
                    {
                         $clee_tab = strval($this->var_xml->ligne[$fin]['variable']);
                        /** je recupere le texte a afficher avec la variable et je remplace Le {quelquechose} par la variable*/
                        $chaine = strval($this->var_xml->elements[$fin]);
                        $final = str_replace("{".$clee_tab."}", strval($this->var_valeur[$clee_tab]), $chaine);

                        $clee_actions =  strval($this->var_xml->ligne[$fin]['quoi']);
                        $modules = (empty($this->var_xml->ligne[$fin]['modules']))?$_GET['modules']:$this->var_xml->ligne[$fin]['modules'];
                        $href = "index.php?modules=$modules&actions=".$this->var_xml->ligne[$fin]['actions']."&a=".$this->var_valeur[$clee_actions];

                        $html .= "<li class='".$this->var_xml->ligne[$fin]['class_css']."'><a href='$href'>".$chaine."</a></li>";

                    }
                    else
                    {
                        $clee_actions =  strval($this->var_xml->ligne[$fin]['quoi']);
                        $modules = (empty($this->var_xml->ligne[$fin]['modules']))?$_GET['modules']:$this->var_xml->ligne[$fin]['modules'];
                        $href = "index.php?modules=$modules&actions=".$this->var_xml->ligne[$fin]['actions']."&a=".$this->var_valeur[$clee_actions];
                        $clee_tab = strval($this->var_xml->ligne[$fin]['variable']);
                        $html .= "<li class='".$this->var_xml->ligne[$fin]['class_css']."'><a href='$href'>".$this->var_valeur[$clee_tab]."</a></li>";


                    }
                }
           }
                return $html;

        }



        /**
         * function privet de l'objet qui verifie la validité des entrés
         * @param NONE
         * @author Ohanessian gregoire
         * @copyright gtux
         */
        Private function verif_entre()
        {
           
            if(!is_array($this->var_donnee))
                {

                return 'le parametre var_donnee doit etre un tableau';
                
            }


            if(empty($this->var_xml))
                {
                    return "Le parametre var_xml doit etre un objet xml , il est cependant vide";
            }
            elseif(!is_object($this->var_xml))
                    {
                    return "Le parametre var_xml n'est pas un objet";
            }

            return true;

        }




}

?>

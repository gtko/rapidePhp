<?php

Class Template{
	
	//parametre public
	public $debug =false;

	//parametre priver
	private $donnee; //entrer de donnée pour l'objet
	private $xml; // document xml a parser
	private $enfant;

        private $pere; //pere de l'enfant appeler 
        private $racine; //info sur la racine


	//constructeur
	public function __construct($xml){
		
		$this->Objets();
		$this->setXml(Entrer::$rep_modules.$xml);//je recupere le xml
	}
	
	//gestion des dependances de l'objets
	public function Objets(){
		
		Core::libs("xml","db","nav");
		
	}
	
	//accesseur Set
	public function setXml($xml)
	{
		$xml_obj = new Xml;
		$this->xml = $xml_obj->set_xml($xml);//je place le xml dans l'objet xml
		
	}
	
	public function setDonnee($donnee)
	{
		
		$this->donnee = $donnee;//je place les donne en parametre 
		
	}	
	
	
	//methode
	
	public function construire()
	{
		
		//je regarde se que je doit construire
		$position = $this->xml;
		$html = $this->{$this->xml->getName()}($position);
		return $html;
		
	}
	
    /*###########################*/
    /*###### Le formulaire ######*/
	/*###########################*/

        //Le Template
        private function template($position){


		$html .= $this->fils($position);
		return $html;
	}


        //Le form
        private function form($position){


                $class = $this->class_css($position);
		        $id = $this->id_css($position);

                $this->racine = $position->getName();

                //je recup le modules
                $modules = (empty($position['modules']))?$_GET['modules']:$position['modules'];

                $html ="<div $id $class>";
                $html .= "<form name='".$position['name']."' method='POST' action='index.php?modules=".$modules."&actions=rapide_nettoyeur' enctype='multipart/form-data'>";
              	
                //si le verif est lancer
                if(isset($_SESSION['post']))
                {
                    if($_GET['clee'] == $_SESSION['post']['clee'])
                    {
                        $this->verif = $_SESSION['post']['form'];
                        $this->verfication = "true";
              
                    }
                }

                //en mode debug on affiche les variables du template de form
                if($position['debug'] == true && $_GET['ajax'] != true )
                {

                    var_dump($this->verif);

                }
                //je place un input hidden pour lui donner l'action apres le rapide nettoyeur
                   if(!empty($position['actions']))
                   {
                           $html .="<input type='hidden' name='actions' value='".$position['actions']."'/>";
                   }
                   else
                   {
                           $html .="<input type='hidden' name='actions' value='defaults'/>";
                   }

                   $html .= $this->fils($position);

                //je detruit le post comme ça pas de double var la clée n'existera plus
                unset($_SESSION['post']);

                $html .= "</form>";
                $html .= "</div>";



                //verification java pour poster le formulaire
                $class_racine = $this->racine['class'];
                $id_racine = $this->racine['id'];

                $id_racine = (empty($id_racine))?"":"#$id_racine";
                $class_racine = (empty($class_racine))?"":".$id_class";

                $cible_info = $id_racine.$class_racine." ".$id_elements.$class_elements." ";


                  foreach($this->erreur_java as $k => $v)
                  {

                     Entrer::$script .= " var $v = false; ";
                     $condition .= " $v == true &";
                     $alert .= "'$v = ' + $v +'\\n' +";
                  }
                  $condition = substr($condition,0 , -2);
                  $alert = substr($alert,0 , -1);
                 
                  Entrer::$script .= "

                      function afficher_erreur(qui)
                        {
                               $('$cible_info #info').fadeOut(200);
                               $('$cible_info #info').remove();
                               $('$cible_info #info').css('display' ,'none');
                               $('$cible_info ').append(\"<div id='info' class='erreur'>".$position->erreur."</div>\")
                               $('$cible_info ').removeClass('valide');
                               $('$cible_info ').addClass('erreur');
                               $('$cible_info +'#info').css('display' ,'none');
                               $('$cible_info +'#info').css('font-weight' ,'bold');
                               $('$cible_info +' #info').fadeIn(500);

                        }

                      $('$id_racine$class_racine input.submit').click(function(){
                         
                         var debug = ".$position['debug']."
                         if(debug == true)
                         {
                          alert($alert);
                         }

                         if($condition)
                         {
                            return true;
                         }
                        else
                         {
                            return false;
                         }
                    });";

                  Entrer::$script .= $this->script;


		return $html;
	}



        private function input($position){

                $class = $this->class_css($position);
		$id = $this->id_css($position);
  
                $html .="<div class='ligne $class' $id>";
                if(!empty($position['label']))
                {
                    $label = $this->recup_val($position['label']);
                    $html .= "<label for='".$label."' >".$label."</label>";
                    $id = "id='".$label."'";
                }

                //je regarde si le type est definit sinon le defaults est texte
                $type = (empty($position['type']))?"text":$position['type'];
                $nom  = (empty($position['nom']))?"name=form[".$this->recup_nom($position['valeur'])."]":"name=form[".$position['nom']."]";


                if(!empty($position['autocomplete']))
                {
                    $autocomplete = "autocomplete = ".$position['autocomplete'];
                }    

                $html .= "<input $id type='".$type."' $nom value='".$this->recup_val($position['valeur'])."' $autocomplete />";
                
                $return = $this->fils($position);
                $html .= "<div id='info'>".$return."</div>";
                $html .= "</div>";

                //je regarde les fils de l'input pour lancer les verif java directement


                return $html;

        }


        private function submit($position)
        {
                 $html = "<input class='submit' type='submit' value='".$this->recup_val($position)."'/>";
                 return $html;
        }



        private function regex($position){

          $regex = $position['valeur'];

           // on construit la cible javascript de jquery
           $class_racine = $this->racine['class'];
	   $id_racine = $this->racine['id'];

           $id_racine = (empty($id_racine))?"":"#$id_racine";
           $class_racine = (empty($class_racine))?"":".$id_class";


           $class_elements = $this->pere['class'];
	   $id_elements = $this->pere['id'];

           $id_elements = (empty($id_elements))?"":"#$id_elements";
           $class_elements = (empty($class_elements))?".ligne":".ligne";

           $cible_info = $id_racine.$class_racine." ".$id_elements.$class_elements." ";
           $cible = $id_racine.$class_racine." ".$id_elements.$class_elements." ".$this->pere->getName();

           $this->script .= "
            $('$cible').keyup(function(){
            if(!$('$cible').val().match(/".$regex."/)){
               $('$cible_info #info').fadeOut(200);
               $('$cible_info #info').remove();
               $('$cible_info #info').css('display' ,'none');
               $('$cible_info ').append(\"<div id='info' class='erreur'>".$position->erreur."</div>\")
               $('$cible_info ').removeClass('valide');
               $('$cible_info ').addClass('erreur');
               $('$cible_info #info').css('display' ,'none');
               $('$cible_info #info').css('font-weight' ,'bold');
               $('$cible_info #info').fadeIn(500);
               
               erreur_regex_".$this->pere['id']." = false;

            }
            else{";
           if(!empty($position->valide))
           {
             $this->script  .= "
           
              $('$cible_info #info').fadeOut(200);
              $('$cible_info #info').remove();
              $('$cible_info #info').css('display' ,'none');
              $('$cible_info ').removeClass('erreur');
              $('$cible_info ').addClass('valide');
              $('$cible_info').append(\"<div id='info' class='valide'>".$position->valide."</div>\")
              $('$cible_info #info').css('display' ,'none');
              $('$cible_info #info').css('font-weight' ,'bold');
              $('$cible_info #info').fadeIn(500);

              ";
          }

         $this->erreur_java[] = "erreur_regex_".$this->pere['id'];
         $this->{"erreur_".$this->pere['id']}[] = "erreur_regex_".$this->pere['id'];

         $this->script  .= "

            erreur_regex_".$this->pere['id']." = true;
          }
            });";

        

        }


        private function unique($position){

           if($_GET['ajax'] == true)
           {
               if($position['champ'] == $_GET['quoi'] )
               {
                $this->ajax_unique($position);
               }
           }

           // on construit la cible javascript de jquery
           $class_racine = $this->racine['class'];
	   $id_racine = $this->racine['id'];

           $id_racine = (empty($id_racine))?"":"#$id_racine";
           $class_racine = (empty($class_racine))?"":".$id_class";


           $class_elements = $this->pere['class'];
	   $id_elements = $this->pere['id'];

           $id_elements = (empty($id_elements))?"":"#$id_elements";
           $class_elements = (empty($class_elements))?".ligne":".ligne";

           $cible_info = $id_racine.$class_racine." ".$id_elements.$class_elements." ";
           $cible = $id_racine.$class_racine." ".$id_elements.$class_elements." ".$this->pere->getName();

             $nom  = (empty($this->pere['nom']))?$this->recup_nom($this->pere['valeur']):$this->pere['nom'];

             $this->script  .= "
                 $('$cible').focusout(function(){
                var ".$nom." = $('$cible').val();
               if(".$nom." != '')
               {

                $('$cible_info ').append(\"<div id='loader'><img src='".REP_IMG."loader.gif"."'/></div>\");
                $.post('index.php?modules=".Entrer::$modules."&actions=".Entrer::$actions."&quoi=".$nom."&ajax=true',{ ".$nom." : ".$nom."}, function(json){
                    
                 if(json.valide == false){
                   $('$cible_info #info').fadeOut(200);
                   $('$cible_info #loader').remove();
                   $('$cible_info #info').remove();
                   $('$cible_info #info').css('display' ,'none');
                   $('$cible_info ').removeClass('erreur');
                   $('$cible_info ').addClass('valide');
                   $('$cible_info ').append(\"<div id='info' class='erreur'>".$position->erreur."</div>\")
                   $('$cible_info #info').css('display' ,'none');
                   $('$cible_info #info').css('font-weight' ,'bold');
                   $('$cible_info #info').fadeIn(500);
                   erreur_unique_".$this->pere['id']." = false;
                }
                else{";

                //je verifie que tout les autres erreurs sois valide sinon je la valide pas
                foreach($this->{"erreur_".$this->pere['id']} as $k => $v)
                {
                     Entrer::$script .= " var $v = false; ";
                     $condition .= " $v == true &";
                }
                 $condition = substr($condition,0 , -2);

                $this->script  .="
                  if($condition)
                  {    
                      $('$cible_info #info').fadeOut(200);
                      $('$cible_info #info').remove();
                      $('$cible_info #loader').remove();
                      $('$cible_info #info').css('display' ,'none');
                      $('$cible_info ').removeClass('erreur');
                      $('$cible_info ').addClass('valide');
                      $('$cible_info').append(\"<div id='info' class='valide'>".$position->valide."</div>\")
                      $('$cible_info #info').css('display' ,'none');
                      $('$cible_info #info').css('font-weight' ,'bold');
                      $('$cible_info #info').fadeIn(500);
                      erreur_unique_".$this->pere['id']." = true;
                  }
                  else
                  {
                      $('$cible_info #loader').remove();
                  }
                }
             }, 'json' );
            }

           });";

           $this->erreur_java[] = "erreur_unique_".$this->pere['id'];
           $this->{"erreur_".$this->pere['id']}[] = "erreur_unique_".$this->pere['id'];
        }



        /*##########################*/
        /*###### Function ajax #####*/
        /*##########################*/

        public function ajax_unique($position){

              $db = new DB;
              $db->base = $this->recup_val($position['base']);
              $db->table = $this->recup_val($position['table']);

              $nom  = (empty($this->pere['nom']))?$this->recup_nom($this->pere['valeur']):$this->pere['nom'];
              $champ = $this->recup_val($position['champ']);
              
              $nbr = $db->lire_DB("true",$champ, $champ." = '".$_POST[strval($nom)]."'");
             
                 if($nbr >= 1)
                 {
                    $true = false;
                 }
                 else
                 {
                    $true = true;
                 }

            $retour['valide'] = $true;
            echo  json_encode($retour);
            exit;

        }

        /*##########################*/
        /*###### La vignettes ######*/
	/*##########################*/

	//La vignette
	private function vignette($position){

        $this->racine = "vignette";
		$html .= $this->fils($position);
		return $html;
	}
	
	//Les choix
	private function choix($position){

                if(!empty($position['different']))
                {
                    if($this->recup_val($position['different']) != $this->recup_val($position['valeur']))
                    {
			$html .= $this->fils($position);
                    }

                }
              
                    if(!empty($position['egal']))
                    {
                        if($this->recup_val($position['egal']) == $this->recup_val($position['valeur']))
                        {
                            $html .= $this->fils($position);
                        }

                    }
              
		return $html;
	}


        //Les images
	private function image($position){

        echo $this->racine;

        switch($this->racine)
        {
            case "vignette":

               $html .= $this->image_vignette($position);
            break;
            case "form":

                $html .= $this->image_form($position);
            break;
            default :

                 $html .= $this->image_vignette($position);

            break;

        }
        return $html;
	}


    private function image_vignette($position)
    {
       
        $rep = $this->recup_val($position['rep']);
		$ext = $this->recup_val($position['ext']);
		$vide = $this->recup_val($position['vide']);
		$nom = $this->recup_val($position['valeur']);
		$alt = $this->recup_val($position['alt']);
		$title = $this->recup_val($position['title']);

		$class = $this->class_css($position);
		$id = $this->id_css($position);

		if(file_exists("$rep$nom.$ext"))
		{
			$image  = "$rep$nom.$ext";
		}
		else
		{
			$image  = "$rep$vide.$ext";
		}

		$html = "<img $id $class src='$image' alt='$alt' title='$title'/>";
		return $html;
    }


    private function image_form($position)
    {
 
        //ON VA VERIFIER SI L'IMAGE EXISTE EN CREANT SON LIEN
        //SI ELLE N'EXISTE PAS ON VA ALLER CHERCHER L'IMAGE VIDE PAR DEFAULTS
        //SI CELLE CI N'EXISTE PAS ON N'AFFICHERA PAS LA BALISE TOUT SIMPLEMENT

        //je commence par cherche le copier qui a un afficher a true
        //si il n'y a pas d'afficher a true je prend le premier copier

        foreach($position as $copier)
        {
               if($copier['afficher'] == "true")
               {
                   $imagecopier = $copier;
               }
        }

        //je regarde si le fils n'exist pas
        if(!isset($imagecopier))
        {
            //j'affiche l'image du premier copier
            $imagecopier = $position->copier[0];
        }

        //information de l'image
        //je recupere le nom
        $nom = $this->recuperer_attribut($imagecopier , "nom" , "Il manque un nom a votre image");
        //je recupere le repertoire
        $rep = $this->recuperer_attribut($imagecopier , "rep" , "Il manque un repertoire a votre image");
        //je recupere l'extension
        $ext = $this->recuperer_attribut($imagecopier , "ext" , "Il manque une extension a votre image");
        //je recupere le title
        $title = $this->recuperer_attribut($imagecopier , "title" , "Il manque un title a votre image");
        //je reucpere le text alternatif
        $alt = $this->recuperer_attribut($imagecopier , "alt" , 'il manque le text alternatif');

        

        echo $rep."/".$nom.".".$ext;

        return $html;
    }


    //function qui sert a recupere les attributs d'un objet xml
    private function recuperer_attribut($position  , $qui , $erreur)
    {
        if(isset($position[$qui]))
        {
            $quoi = $this->recup_val($position[$qui]);
        }
        else
        {
            if(!empty($position->{$qui}))
            {
                $quoi = $this->recup_val($position->{$qui});
            }
            else
            {
                echo "<div style='color:red'>".$erreur."</div>";
            }
        }

        return $quoi;
    }



	//La balise sql
	private function sql($position){
		

		$db = new DB;
		
		$req = $this->recup_val($position['requete']);
		
		if(!empty($position['nav']))
		{
		/*BARRE DE NAVIGUATION*/
            $nav = new Nav;
            $nav->set_requete($req);
            $nav->set_nbrelements(intval($this->recup_val($position['nav'])));
            
            if(!empty($position['navnom']))
			{
				$nav->set_nom($position['navnom']);
				$nav->set_page($_GET[strval($position['navnom'])]);
			}
			else
			{
				$nav->set_page($_GET['page']);
			}
            $nav->naviguation();
            $html =  $nav->barre;
            $req = $nav->requete;
		}
		
		$db->lire_requete($req);
		
		$table = $db->convtableaux("" ,$this->recup_val($position['nom']));
		
		
		
		if(is_array($table))
		{
			foreach($table as $key => $v)
			{
				//je vais passer le tableaux en parametre de l'objet pour que ces fils y est acces partout
				
				$this->{$position['nom']} = $v;
				$html .= $this->fils($position);
			}
		}
		unset($this->{$position['nom']});
		
		return $html;
	}
	
	// la boucle
	private function boucle($position){
		
		
                          $table =$this->{strval($position['table'])};

                     
                          if(is_array($table))
		{
			foreach($table  as $key => $v)
			{
				//je vais passer le tableaux en parametre de l'objet pour que ces fils y est acces partout

				$this->{$position['table']} = $v;
				$html .= $this->fils($position);
			}
		}
		return $html;
		
		
	}
	
	//le lien
	private function lien($position){
		
	
		
		//je regarde les attribut qu'il as
		

		//on contruit le lien interne si demander
		if(!empty($position['modules']))
		{
			$lien = "index.php?modules=".$this->recup_val($position['modules']);
			
		}
		else
		{
			$lien = "index.php?modules=".Entrer::$modules;
		}
		
		if(!empty($position['actions']))
		{
			$lien .= "&actions=".$this->recup_val($position['actions']);
			
		}
		else
		{
			$lien .= "&actions=".Entrer::$actions;
		}
		
		if(!empty($position['geta']))
		{
			$lien .= "&a=".$this->recup_val($position['geta']);
			
		}
		
		//sinon on construit le lien externe
		if(!empty($position['http']))
		{
			$lien = strval($position['http']);
		}
		
		//on regarde l'ancre
		if(!empty($position['ancre']))
		{
			
			$lien .= "#".strval($position['ancre']);
			
		}
		
		if(!empty($position['title']))
		{
			$title = $this->recup_val($position['title']);
		}
		
		$class = $this->class_css($position);
		$id = $this->id_css($position);
		
		$html .= "<a $id $class href='$lien' title='$title'>";
		$html .= $this->fils($position);
		$html .= "</a>";
		return $html;
	
	}
	
	

	
	//Les variable
	private function variable($position){
		
		
		
		$class = $this->class_css($position);
		$id = $this->id_css($position);
		$contenu = $this->recup_val($position);
                      
		if($this->vide == true)
		{
			$html = "<div $id $class>".$contenu."</div>";
		}
		unset($this->vide);
		return $html;
	}


             // les sessions

             private function session($position){

                     $this->session = $this->recup_val($position['nom']);

                     $this->fils($position);
             }

             private function valeur($position)
             {
                       if(!empty($position['nom']))
                       {
                            $_SESSION['template'][$this->session][strval($this->recup_val($position['nom']))]  = $this->recup_val($position);
                       }
                       else
                       {
                             $_SESSION['template'][$this->session][]  =  $this->recup_val($position);
                       }
                     
                         
             }



	//Les conteneurs
	
	//le lien
	private function conteneur($position){
		
		$html = "passer conteneur<br/>";

		$class = $this->class_css($position);
		$id = $this->id_css($position);

		$html = "<div $id $class>";
		$html .= $this->fils($position);
		$html .= "</div>";
		return $html;
	
	}
	
	//le clear
	private function clear(){

		return "<div style='clear:both'></div>";
	
	}
	
	//Le type des valeurs
	private function recup_val($valeur){
		
		$variable = $valeur;
                   
                                //si c'est une constante
                                if(defined(strval($valeur)))
                                {

                                   $valeur = constant($valeur);
                                }

                            //pour les variable xml en php
                            if(preg_match('/\$[A-Z0-9a-z_]*/', $valeur , $tab))
                            {



                                 foreach($tab as $v)
                                 {
                                              $val = substr($v, 1);
                                              $valeur = str_replace($v, $this->recup_val($this->{$val}),$valeur);



                                 }


                                 //$val = substr($valeur, 1);

                            }

                            if(preg_match('/\§[A-Z0-9a-z_]*\[[a-z0-9A-Z_]*\]/', $valeur , $tab))
                            {


                                 //var_dump($tab);
                                 foreach($tab as $v)
                                 {
                                              $val = substr($v, 1);

                                              //je recupere sur quel requete
                                              preg_match("/§[A-Z0-9a-z_]*/", $valeur , $nom_tableau);

                                              //je recupere quel champ
                                              preg_match("/\[[A-Z0-9a-z_]*\]/", $valeur , $val_champ);

                                              $nom_tableau = substr($nom_tableau[0], 2);
                                              $val_champ = substr($val_champ[0], 1 , -1);

                                              $valeur = str_replace($v, $this->recup_val($this->{$nom_tableau}[$val_champ]),$valeur);
                                 }
                            }


                            //pour les variable xml sql en php
                            if(preg_match('/#[A-Z0-9a-z_\|]*/', $valeur , $tab))
                            {

                           
                                 //var_dump($tab);
                                 foreach($tab as $v)
                                 {
                                              //je recupere sur quel requete
                                              preg_match("/#[A-Z0-9a-z_]*/", $valeur , $val_nom);

                                              //je recupere quel champ
                                              preg_match("/\|[A-Z0-9a-z_]*/", $valeur , $val_champ);
                                              $val_nom = substr($val_nom[0], 1);
                                              $val_champ = substr($val_champ[0], 1);
                                                                    if($this->debug == true)
                                                                   {
                                                                        var_dump($this->{$val_nom}[$val_champ]);
                                                                    }

                                                           $new_val = $this->{$val_nom}[$val_champ];
                                                           $valeur = str_replace($v, $new_val,$valeur);


                                 }

                            }

                           if($this->debug == true)
                           {
                                var_dump($valeur);
                            }
                       $this->champ_vide($valeur);
                        return $valeur;
             }



             private function recup_nom($valeur){
                 $variable = $valeur;

                            //si c'est une constante
                            if(defined(strval($valeur)))
                            {

                                   $valeur = constant($valeur);
                            }

                            //pour les variable xml en php
                            if(preg_match('/\$[A-Z0-9a-z_]*/', $valeur , $tab))
                            {



                                 foreach($tab as $v)
                                 {
                                              $valeur = substr($v, 1);




                                 }


                                 //$val = substr($valeur, 1);

                            }

                            if(preg_match('/\§[A-Z0-9a-z_]*\[[a-z0-9A-Z_]*\]/', $valeur , $tab))
                            {


                                 //var_dump($tab);
                                 foreach($tab as $v)
                                 {
                                              $val = substr($v, 1);
                                              //je recupere quel champ
                                              preg_match("/\[[A-Z0-9a-z_]*\]/", $valeur , $val_champ);

                                              $valeur = substr($val_champ[0], 1 , -1);

                                 }
                            }


                            //pour les variable xml sql en php
                            if(preg_match('/#[A-Z0-9a-z_\|]*/', $valeur , $tab))
                            {


                                 //var_dump($tab);
                                 foreach($tab as $v)
                                 {
                                             //je recupere quel champ
                                              preg_match("/\|[A-Z0-9a-z_]*/", $valeur , $val_champ);
                                              $val_nom = substr($val_nom[0], 1);
                                              $valeur = substr($val_champ[0], 1);

                                 }

                            }

                        return $valeur;


             }



	//methode petit fils

             private function champ_vide($valeur)
             {
                     if(!empty($valeur))
                     {
                        $this->vide = true;
                     }
                     else
                     {
                       $this->vide = false;
                     }

             }

	private function fils($position){
                $this->pere = $position;
		foreach($position->children() as $enfant)
		{
			if(method_exists($this, $enfant->getName()))
			{
				$html .= $this->{$enfant->getName()}($enfant);
			}
		}
		return $html;
	}
	
	
	//recup class et id
	
	private function class_css($position)
	{
		if(!empty($position['class']))
		{
			$class = " class='".$this->recup_val($position['class'])."'";
		}
		return $class;
	}
	
	private function id_css($position)
	{
		
		if(!empty($position['id']))
		{
			$id = " id='".$this->recup_val($position['id'])."'";
		}
		return $id;
	}
}

?>

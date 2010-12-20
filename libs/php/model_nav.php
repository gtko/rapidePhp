<?php
/*#######################################################*/
/*#	le 07/09/2010					#*/
/*#	TITRE : Barre de naviguation			#*/
/*#							#*/
/*#######################################################*/
/*#		AUTHOR : OHANESSIAN Gregoire		*/
/*#		MAIL :	gtux.prog@gmail.com		#*/
/*#		Framework : rapidephp V 0.1		#*/
/*#######################################################*/
/*#					COPYRIGHT gtux	#*/
/*#######################################################*/
/*#					MODIFICATION	#*/
/*#		-14/10/2010 :	ajout d'un nom de barre #*/
/*#			pour le cas de plusieur barre	#*/
/*#							#*/
/*#######################################################*/

/**Objet permettant de crée une barre de naviguation
 *
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 * @code
 *  exemple d'utilisation :
 *  $nav = new Nav("Select * form art.test");
 *  $nav->set_nom("naviguationGallerie");
 *  $nav->set_module($_GET['modules']);
 *  $nav->set_actions($_GET['actions']);
 *  $nav->set_nbrelements(5);
 *  $this->contenu['nav'] = $nav->naviguation();
 * @endcode
 */
class Nav extends GeneralLibs{

  /* proprieter public*/
   Public $requete = null;
   Public $barre = null;

  /*proprieter private */
   private $nom = "page";
   private $actions = null;
   private $modules = null;
   private $page = 1;
   private $nbr_page = 0;
   private $nbrelements = 0;

   private $nbr_ligne = 0;
   private $requete_entrer = null;


   Public function  __construct($requete = null) {
        parent::__construct();
        if($requete != null)
        {
            $this->set_requete($requete);
        }
    }


   Public function objets(){
        Core::libs("db");
    }

    /* acceseur SET  public */
    Public function set_page($page)
    {
        if(preg_match("/[0-9]+/",$page))
        {
             $this->page = $page;
        }
        else
        {
            //erreur system + erreur log
          return "page doit etre un entier";
        }
    }


    Public function set_nbrelements($nbr)
    {
         if(is_integer($nbr))
        {
             $this->nbrelements = $nbr;
        }
        else
        {
            //erreur system + erreur log
           return "nbrpage doit etre un entier";
        }
    }
    

    Public function set_requete($requete)
    {
            $db = new DB;
            $db->base=$base;
            $this->nbr_ligne = $db->lire_requete($requete,true);
            $this->requete_entrer = $requete;
    }

    Public function set_modules($modules)
    {
        $this->modules = $modules;
    }

    Public function set_actions($actions)
    {
        $this->actions = $actions;
    }
	
    
    /** pour le cas de plusieur barre de nav dans la page*/
    Public function set_nom($nom)
    {
        $this->nom = $nom;
    }
    
    /** acceseur get**/

    Public function get_nbrpage()
    {
        return $this->nbr_page;
    }

    /* methode public */
    Public function naviguation(){

        /*je regarde si les actions son specifier si non alors j'applique les defaults*/
        if($this->modules == null)
        {
            $this->modules = Entrer::$modules;
        }
        if($this->actions == null)
        {
            $this->actions = Entrer::$actions;
        }

        /** je calcule le nombre de page */
        $this->nbr_page = floor($this->nbr_ligne / $this->nbrelements);
        if($this->nbr_ligne %$this->nbrelements != 0)
        {
             $this->nbr_page++;
        }
       if($this->nbr_page > 1)
       {
        /*on construit les intervalle et la barre de nav*/
        $debut = 1;
        $interval_inf = 0;
        $interval_sup = $this->nbrelements;

        if($this->page - 1 < 1)
        {
            $moins = 1;
        }
        else
        {
            $moins = $this->page - 1;
        }

        if($this->page +1 > $this->nbr_page )
        {
            $plus = $this->nbr_page;
        }
        else
        {
           $plus = $this->page +1 ;
        }

        $html_barre = "<div class='naviguation'><ul>
            <li><a href='?modules=".$this->modules."&actions=".$this->actions."&".$this->nom."=$moins'><<<<</a></li>";

        while($debut != $this->nbr_page+1)
        {
            
            $intervale[$debut] = $interval_inf.",".$this->nbrelements;
            $interval_inf = $interval_sup;
            $interval_sup = $interval_inf + $this->nbrelements;

            if($debut == $this->page)
            {
                 $html_barre .= "<li class='selectionner'><a href='?modules=".$this->modules."&actions=".$this->actions."&".$this->nom."=$debut'>$debut</a></li>";

            }
            else
            {
                $html_barre .= "<li><a href='?modules=".$this->modules."&actions=".$this->actions."&".$this->nom."=$debut'>$debut</a></li>";
            }
            $debut ++;
        }


       $html_barre .= "  <li><a href='?modules=".$this->modules."&actions=".$this->actions."&".$this->nom."=$plus'>>>>></a></li></ul></div>";

        /* on regarde si une page est demander si non alors page 1 sinon la page demander*


       /*on construit la requete*/
       $this->barre = $html_barre;
       $this->requete = $this->requete_entrer." LIMIT ".$intervale[$this->page];
       }
       else
       {
             $this->requete = $this->requete_entrer." LIMIT 0,".$this->nbrelements;
        }
       return true;
    }
}
?>

<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of rapidevote
 *
 * @author gtko
 */
class RapideVote extends GeneralRoutines {

    /** public **/

    /**
     *  resulat de la methode compter() donne le nombre de vote
     * @var int
     */
    Public static $nbr_vote = null;

    /**
     *resultat de la methode voter() donne le derniere id ecrit dans la base
     * @var int
     */
    Public static  $lastid  = null;


    /**
     * Retourne les erreur comme "deja voter"
     * @var string
     */
    Public static $erreur = "";

    /** private **/

    /**
     * on donne l'id de l'objet sur lequel on vote
     * @var int
     */
    Private static $objet = null;

    /**
     * on donne l'id du membre qui vote
     * @var int
     */
    Private static $membre = null;


    /**
     * on ecrit le bouton en html
     * @var string
     */
    private static $bouton = null;


    /**
     * On donne la base de donnée
     * @var string
     */
    private static $base = null;

    /**
     *on donne la table des votes
     * @var string
     */
    private static $table = null;

        /**
     *on donne le modules du bouton des votes
     * @var string
     */
    private static $modules = null;


        /**
     *on donne l' actions du bouton des votes
     * @var string
     */
    private static $actions = null;



    /**
     * on donne les point attribuer pour se vote en positif ou negatif
     * @var int
     */
    private static  $point = 1;


    /** method par defauls*/
    Public function objets(){

    Core::libs("db","Session");
    Core::routines("rapidelogin");

    }



    /** accesseur des private **/

    Public static function set_base($base){

        if(preg_match("/[a-zA-Z0-9_ -]+/", $base))
        {
            self::$base = $base;
        }
        else
        {
            echo '<br/>Dommage , l\'entrer $base n\'est pas valide sa valeur est '.$base.' , elle doit etre composer de chiffre ou de lettre ';
        }
    }

        Public static function set_table($table){

        if(preg_match("/[a-zA-Z0-9_ -]+/",$table))
        {
            self::$table = $table;
        }
        else
        {
            echo '<br/>Dommage , l\'entrer $table n\'est pas valide sa valeur est '.$table.' , elle doit etre composer de chiffre ou de lettre  ';
        }
    }

     Public static function set_modules($modules){

        if(preg_match("/[a-zA-Z0-9_ -]+/",$modules))
        {
            self::$modules = $modules;
        }
        else
        {
            echo '<br/>Dommage , l\'entrer $table n\'est pas valide sa valeur est '.$modules.' , elle doit etre composer de chiffre ou de lettre  ';
        }
    }

      Public static function set_action($action = NULL){

        if(preg_match("/[a-zA-Z0-9_ -]+/",$action))
        {
            self::$action = $action;
        }
        else
        {
            echo '<br/>Dommage , l\'entrer $table n\'est pas valide sa valeur est '.$action.' , elle doit etre composer de chiffre ou de lettre  ';
        }


        }

        Public static function set_objet($objet){

        if(preg_match("/[0-9]+/", $objet))
        {
            self::$objet = $objet;
        }
        else
        {
            echo '<br/>Dommage , l\'entrer $objet n\'est pas valide sa valeur est '.$objet.' , elle doit etre de type entier  ';
        }
    }

       Public static function set_membre($membre){

        if(preg_match("/[0-9]+/", $membre) AND SESSION::$identifier == true)
        {
            self::$membre = $membre;
        }
        elseif(SESSION::$identifier == true)
        {
            echo '<br/>Dommage , l\'entrer $membre n\'est pas valide sa valeur est '.$membre.' , elle doit etre de type entier  ';
        }
    }


     Public static function set_bouton($bouton = NULL ){

        if($bouton != NULL)
        {
            self::$bouton = "<div class='voter'><a href='?modules=".Entrer::$modules."&actions=".Entrer::$actions."&vote=true'><img src='".REP_IMG."/coeur.png' height='30px'/>".self::$nbr_vote."</a></div>";
        }
        else
        {
             self::$bouton = "<div class='voter'><a href='?modules=".Entrer::$modules."&actions=".Entrer::$actions."&vote=true'><img src='".REP_IMG."/coeur.png' height='30px'/>".self::$nbr_vote."</a></div>";
        }
    }

      Public static function set_point($point = NULL){

        if($point != NULL)
        {
            self::$point = $point;
        }
        else
        {
            // rien
        }
    }


    
    /*** method **/

        Public static function voter(){

            if(self::$base == null)
            {
                echo "<br/>dommage l'entrer base est invalide , ex : ".'  $vote::set_base("artvu") ';
            }
            if(self::$table == null)
            {
                echo "<br/>dommage l'entrer table est invalide , ex : ".'  $vote::set_table("vote") ';
            }

            if(self::$objet == null)
            {
                echo "<br/>dommage l'entrer objet est invalide , ex : ".'  $vote::set_objet($id_article) ';
            }

            if(self::$membre == null AND Session::$identifier == true)
            {
                echo "<br/>dommage l'entrer membre est invalide , ex : ".'  $vote::set_membre($id_membre) ';
            }


            if(self::$actions == null)
            {
                self::$actions = Entrer::$actions;

            }

             if(self::$modules == null)
            {
                self::$modules = Entrer::$modules;

            }


            if(self::$bouton == null)
            {
                self::$bouton = "<a href='?modules=".self::$modules."&actions=".self::$actions."&vote=true'>Voter</a>";
            }


            if($_GET['vote'] == true)
            {

                if(Session::$identifier == true)
                {

                    $db = new DB;
                    $db->base = self::$base;
                    $db->table = self::$table;


                      $condition = "id_objet = '".self::$objet."' AND id_membre='".self::$membre."'";
                       $deja_voter = $db->lire_DB("true","",$condition);

                    if($deja_voter == 0)
                    {
                            $donnee = array(
                                "id_objet" => self::$objet,
                                "id_membre" => self::$membre,
                                "point" => self::$point

                            );
                              self::$lastid = $db->ecrire_DB($donnee,"true");
                              
                    }
                    else
                    {

                        self::$erreur = "Vous avez déjà voter sur cette oeuvre";

                    }
                }
                else
                {

                          $rapidelogin = new RapideLogin();
                          $message = "Il faut etre membre pour effectuer cette actions <br/>";

                          return $rapidelogin->connection();

                }
            }

          



            return $message.self::$bouton;


        }


        Public static function compter(){

                 $db = new DB;
                    $db->base = self::$base;
                    $db->table = self::$table;

                    $condition = "id_objet = '".self::$objet."'";
                      self::$nbr_vote = $db->lire_DB("true","",$condition);

                     return self::$nbr_vote;

        }

        
}
?>

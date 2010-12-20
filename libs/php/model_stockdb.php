<?php


class StockDb extends GeneralLibs{


    /*Propriété public*/
    Public $nbr_element;
    Public $erreur;
    /*propriété priver*/
    private $nom = null;
    private $base = null;
    private $table = null;
    private $requete = null;
    private $id = "id";
    private $ligne = "all";
    private $collone = "all";

    private $donnee = null;
    private $tableaux = null;


    private $tableaux_select = null;
    private $lastid = null;


    /*Destrcuteur*/

    Public function  __destruct() {
        $this->serialize();
    }

    /*METHODE OBJET*/
    Public function objets(){

        Core::libs("db","session","personne","membre");

    }

    /*acceseur public*/

    Public function set_base($base){

                $this->base = $base;

    }


    Public function set_table($table){

                $this->table = $table;

    }

    Public function set_requete($requete){

            $this->requete = $requete;

    }

    Public function set_nom($nom)
    {

        $this->nom = $nom;
      

    }

    Public function set_id($id){

            $this->id = $id;

    }

    /***
     * cible une ligne ou plusieur
     */
    Public function set_ligne($id){

            $this->ligne = $id;

    }


    /***
     * cible une collone ou plusieur
     */
    Public function set_collone($champ){

             $this->collone = $champ;
    }


    Public function set_donnee($array = array(),$key = NULL){
       if(is_array($array))
       {
            foreach($array as $clee => $value ){

                if(is_array($value))
                {
                    $clee_niv = "id_".$value[$this->id];
                    $this->set_donnee($value , $clee_niv);
                }
                else
                {
                    if($key == NULL)
                    {
                        $this->donnee[$clee] = $value;
                    }
                    else
                    {
                        $this->donnee[$key][$clee] = $value;
                    }
                }

            }
       }
    }

    /*** Accesseur Get */

    public function get_tableaux(){

        return $this->tableaux;

    }




    /*Methode public*/

    /**
     *Lit la ligne ou toutes les ligne des données de l'objet
     * @return object
     */

    Public function lire(){

        if($this->ligne != "all")
        {

            return $this->donnee["id_".$this->ligne];

        }
        else
        {
            return $this->donnee;
        }


    }


    Public function supprimer(){



    }

    /**
     *sert a ecrire une nouvelles entré dans la base de donnée
     * @param string $ecrire
     * @return integer
     */

    Public function ecrire($ecrire){
        $ecrire = $this->decode_synt_css($ecrire);

         $db = new DB;
         $db->base= $this->base;
         $db->table = $this->table;
         if(is_array($ecrire))
         {
             foreach($ecrire as $clee => $value)
             {

                 $donnee[$clee] = $value;

             }
         }
        $this->lastid = $db->ecrire_DB($donnee, true);

        return $this->lastid;
     }


     /**
      *sert a modifier une entré de l'objet avec un appel a la base de donnée
      * @param string $condition
      * @param string $modifier
      * @return bollean
      * @return string
      */

     Public function modifier($condition , $modifier){
         $modifier = $this->decode_synt_css($modifier);
        $db = new DB;
         $db->base= $this->base;
         $db->table = $this->table;

         if(is_array($modifier))
         {
             foreach($modifier as $clee => $value)
             {

                 $donnee[$clee] = $value;

             }


         }
              return  $db->update_DB($condition , $donnee);

    }


    /**
     *Actualise les valeur de l'objet en appelant la base de donnée
     * @return array
     */
    Public function actualiser($nbr = false){

           $db = new DB;
           $db->base= $this->base;
           $db->table= $this->table;

            $erreur = $db->lire_requete($this->requete);
            $tableau = $db->convtableaux();

            if($nbr == true)
            {
                      $this->nbr_element = count($tableau);
            }

            $this->set_donnee($tableau);
            $this->tableaux = $tableau;
            $this->erreur = $erreur;
            return $tableau;

    }

    /**
     *decodeur de syntaxe css pour les methodes de l'objet
     * @param string $variable
     * @return array
     */

    Public function decode_synt_css($variable)
    {


        /**je decoupe la variable par pair **/
        //$nbr = preg_match_all("/(.[^:]*):(.[^;]*);/",$variable, $pair , PREG_PATTERN_ORDER);
        $nbr = preg_match_all("/(.*):(.*);/",$variable, $pair , PREG_PATTERN_ORDER);

             /*je decoupe les pair en 2 la clee la value*/


               $compter = 0;
               while($compter != $nbr )
               {

                   $entrer[$pair[1][$compter]] = $pair[2][$compter];
                   $compter ++;

               }
      
       return $entrer;


    }


    /**
     * Fonction qui endort l'objet pour le reveiller dans le futurs
     * retourne l'objet lui meme
     */

    Public function serialize($membre =true){

        if(!empty($this->nom) && $this->nom != NULL)
        {
            if($membre === true)
            {

                $personne = Session::$membre;
                $personne->{$this->nom} = serialize($this);


            }
            else
            {
                    if(!file_exists(REP_RACINE."stockdb/"))
                        {
                            mkdir(REP_RACINE."stockdb/");

                        }
                     chmod (REP_RACINE."stockdb/", 0777);

                      file_put_contents(REP_RACINE."stockdb/".$this->nom.".stkdb" ,serialize($this));
            }
        }
    }

     /**
     * Fonction qui reveille l'objet
     * retourne l'objet lui meme
     */

    Public static function unserialize($nom  , $membre =true){


        if($membre === true)
        {
            $personne = Session::$membre;
            return unserialize($personne->{$nom});
         
        }
        else
        {

            if(!file_exists(REP_RACINE."stockdb/"))
            {
                mkdir(REP_RACINE."stockdb/");

            }
               chmod (REP_RACINE."stockdb/", 0777);

               if(isset($_SESSION['stockdb'][$nom]))
               {
                    return unserialize(file_get_contents(REP_RACINE."stockdb/".$nom.".stkdb"));
               }
               else
               {
                   $objet = new StockDb;
                   return $objet;
               }
        }
    }


    /**
     * function detruire mess
     *
     */

}


?>

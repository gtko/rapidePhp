<?php 

/**Objet couche de la base de donnée mysql
 *
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 * modification :
 * 26/11/2010 : rajout de $valeur est de la nouvelle methode d'ecriture de la base;
 */

class DB {

        protected $valeur = array(); //valeur que l'on veut ecrire dans la base de donnée

	    public $table;//definit le table a utiliser
        public $base;//definit la base de donnée a utiliser

        Public $result; //contient le resultat d'une requete Sql
        public $link;//contient le connect actuel de la base


        /** variable constante de l'objet db**/
        public static $nbr_acces_base = 0;

        /** Effectue la connexion a la base
         *
         */
	Private function connexion()
	{
            self::$nbr_acces_base++;
            
            if(!defined('SERVEUR'.$this->base) && $this->base === base)
            {
                define('SERVEUR'.$this->base , SERVEUR);
                define('utilisateur_db'.$this->base , utilisateur_db);
                define('mdp_db'.$this->base , mdp_db);
                define('base'.$this->base , base);


            }


            $this->link=mysql_connect(constant('SERVEUR'.$this->base) , constant('utilisateur_db'.$this->base),constant('mdp_db'.$this->base)) or die ("<center>Erreur MySQL : <br><b>".mysql_error()."</b></center>");
             mysql_select_db(constant('base'.$this->base), $this->link) or die ("<center>Erreur MySQL : <br><b>".mysql_error($this->link)."</b></center>");
             
             mysql_query("SET CHARACTER SET utf8");

	}


        /**Effectue la deconnexion avec la base
         *
         */
        private function deconnexion()
	{
		@mysql_close();
	}	



	/** methode qui va compter Le nbr_de fois ou j'accede a la base de donnée
         *
         */
        static function afficher_acces(){
            
            return "<strong style='color:green'>acces a la base de donnée = ".self::$nbr_acces_base."</strong>";
            
        }

        /**
         * Configurer les valeur des champ de la nouvel entré
         */
        public function setValeur($champ , $valeur)
        {
            $this->valeur[$champ] = $valeur;
        }

        /**
         * Recupere les valeur entrer dans la base de donnée
         */
        public function getValeur($champ = null)
        {
            if($champ != null)
            {
                return $this->valeur[$champ];
            }
            else
            {
                return $this->valeur;
            }

        }


	public function ecrire_DB($donnee = null , $lastid = NULL){
		$requete = "true";
			
		if(is_null($donnee))
                {
                    $donnee = $this->valeur;
                }

                if(empty($donnee))
                {
                    return "Erreur Les entrer champ sont vide";
                }
                    $req= "INSERT INTO ".$this->table." (";

                    foreach($donnee as $k => $v){

                            $req .= $k.",";
                    }
                    $req = substr($req,0,-1).")";
                    $req .= " VALUES (";
                    foreach($donnee as $v){

                            $req .= "'".$v."',";
                    }
                    $req = substr($req,0,-1).")";

                    $this->connexion();
                    mysql_query($req) or $requete = "false";

                    if(($requete != "false") && ($requete == "true"))
                    {

                    if($lastid == true){
                            $lastid = mysql_insert_id();
                            $this->deconnexion();
                            return $lastid;
                    }
                    $this->deconnexion();

                    $requete = true;
                            return $requete;
                    }
                    else
                    {
                        Erreur::declarer_dev(21, "objet : db , function : Ecrire_DB  ,return ".mysql_error($this->link));
                        //return "<br><br>Erreur de la database sur la requete :<br> ".$req."<br> le mysql query retourne :<br>".$requete."<br> l'erreur generer par sql est :<br>".mysql_error()."<br><br>";
                        return false;
                    }
	
	}
	
	/**
	 * on a en option de retour la requete ou le nombre d'enregistrement en derniere parametre 1 = le nombre d'enregistrement
	 * */
	
	public function lire_DB($retour_nbr_enreg = NULL , $champs = NULL ,$condition = NULL, $order= NULL , $limit = NULL , $group = NULL , $req = NULL ){
		
		if (empty($champs)){$champs = "*";}
		if (empty($order)){ $order ="";}
		if (empty($limit)){$limit = "";}
		if (empty($group)){$group = "";}
		$valid = 'true';
		if (empty($condition)){
				
			$req = "SELECT $champs FROM ".$this->table." $group $order $limit ";
			
			
		}
		else
		{
			if($req == NULL)
			{
				$req = "SELECT $champs FROM ".$this->table." WHERE $condition $group $order $limit";
			}
			
		}


                $this->requete = $req;
		$this->connexion();
		$requete = mysql_query($req) or $valid = 'false';
		$this->deconnexion();
		
		if($valid != 'false')
		{
			if(!empty($retour_nbr_enreg) && ($retour_nbr_enreg == 'true') )
			{
				return mysql_num_rows($requete);
				
			}
			else
			{
                               $this->result = $requete;
                                return $requete;
			}
		}
		else
		{
			$this->result =	"La fonction retourne :<br>".$valid."<br>sur la requete :<br>$req <br>et sql retourne :<br>".mysql_error($this->link);
                         return $this->result;
		}
	}
	
        
        
     /**
      *function qui permets d'effectuer un mise a jour dans la base de donnee
      * 
      * @code
      *     $condition ="id='".$variable."'";
      *     $tableaux = array(
      *             'date_suppr' => time()
      *     );
      *     $this->db->base = "2";
      *     $this->db->table = 'av_messagerie';
      *     $this->db->update_DB($condition,$tableaux);
      * @endcode
      * @param string $condition
      * @param array $donnee
      * @return ressources (database) 
      */


	public function update_DB($condition = NULL, $donnee = null ){


                if(is_null($donnee))
                {
                    $donnee = $this->valeur;
                }

		if(empty($condition))
		{
			return "aucune condition n'as etait choisit mise a jour impossible";	
		}
		$requete = 'true';
		
		$req = "UPDATE ".$this->table." SET ";
		foreach($donnee as $k => $v){
	
				$req .= $k."='".$v."',";
			}
			$req = substr($req,0,-1);
				
		   $req .= " WHERE ".$condition; 
		
			$this->connexion();
				mysql_query($req) or $requete = false;
			$this->deconnexion();
			
			if(($requete != false) && ($requete == true))
			{
				return $requete;
			
			}
			else
			{
                                Erreur::declarer_dev(21, "objet : db , function : update_DB , return ".mysql_error($this->link));
				return $requete;

			}
	}

        /**
         * function qui va executer une requete taper a la main
         * @param $requete la function
         * @author Ohanessian gregoire
         * @copyright gtux
         */
        Public function lire_requete($requete,$compter = false)
        {

            $this->connexion();
            $result = mysql_query($requete) or $valide = 'false';

            $this->deconnexion();
            if($valide != 'false')
		{
                          if($compter === true)
			{
                                $this->result = $result;
				return mysql_num_rows($result);
                                                    
			}
			else
			{
                                  $this->result = $result;
                                  return $result;

			}

                     
		}
		else
		{
			Erreur::declarer_dev(28,"Erreur sql : ".mysql_error($this->link));
                        return $this->result;
		}

        }
	
	public function suppr_DB($qui){
		
		
		if(isset($qui) && !empty($qui)){
			$reponse = true;
			$req = "DELETE FROM ".$this->table." WHERE $qui";
			$this->connexion();
			mysql_query($req) or $reponse = false;
			$this->deconnexion();
			return $reponse;
		}
		else
		{
		return false."le parametre $qui n'as pas eté selectionner";	
			
		}
	}


        /**
         * Function qui trasnforme toute une requete en objet
         * @param $requete
         * @author Ohanessian gregoire
         * @return object
         * @copyright gtux
         */

        Public function convobjet($requete,$nom_objet)
        {
            /*si la requete est vide alors on return une erreur */
            if(empty($requete))
                {
                    return 'erreur Le parametre requete est vide';

                }

            if(empty($nom_objet))
                {
                    return 'erreur le parametre nom objet est vide';
                }

          /*on continu on extrait toute la requete dans un objet avec la boucle while*/

             /* @var $donnee object */
              $i = 1;
              while( $donnee = mysql_fetch_object($requete))
                      {
                            $db_objet->total = $i;
                            $test = $nom_objet.'_'.$i;
                            $db_objet->$test =$donnee;

                            $i ++;
                      }

                      /* je retoune l'objet crée par la requete sql */
                      return $db_objet;
              }

        /**
         * Function qui trasnforme toute une requete en tableaux
         * @param $requete
         * @author Ohanessian gregoire
         * @return object
         * @copyright gtux
         */

        Public function convtableaux($requete = NULL,$nom_tableaux = NULL)
        {
            /*si la requete est vide alors on return une erreur */
            if($requete == NULL)
                {

                    $requete = $this->result;

                }
            elseif(!is_resource($requete))
                {

                return 'entre requete n\'est pas une ressources';
            }
            if($nom_tableaux == NULL)
                {
                    $nom_tableaux = 'defaults';
                }

          /*on continu on extrait toute la requete dans un tableaux avec la boucle while*/

             /* @var $donnee array */
              $i = 1;
              while( $donnee = mysql_fetch_assoc($requete))
                      {
                            $nom_stable = $nom_tableaux.'_'.$i;
                            $db_tableaux[$nom_stable] =$donnee;
                            $i ++;
                      }

                      /* je retoune le tableaux crée par la requete sql */
                      return $db_tableaux;
              }


              /**
               * petit fucntion qui retourne le resultat d'un fetch_assoc
               */
              Public function convtableaux_simple($requete = NULL)
              {
                    if($requete == NULL)
                    {

                        $requete = $this->result;

                    }
                  return mysql_fetch_assoc($requete);
              }
              
               /**
               * petit fucntion qui retourne le un tableaux vide conforme a la base de donnée
               */
              Public function convtableaux_vierge($requete = NULL)
              {
                    if($requete == NULL)
                    {

                        $requete = $this->result;

                    }


                  if(is_resource($requete))
                 {
                      $table =  mysql_fetch_assoc($requete);

                      foreach($table as $k => $v)
                      {
                            $tableaux_final[$k] = "";
                      }

                      return $tableaux_final;
                 }
                 else
                 {

                     echo  __FILE__ . __LINE__ .__METHOD__."Erreur la requete n'est pas valide ==> $requete";

                 }
              }




}



?>

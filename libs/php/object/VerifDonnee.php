<?php
/**
 * Objet de verification des données
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 *
 */
class VerifDonnee extends ObjectForm{


     Private $object = null; //
     private $unique = null;
     private $existe = null;


     /**
     * Le constructeur peut directement
     */
    public function __construct($object = null)
    {
        $this->object = $object;

    }

    public function initialiserAttribut()
    {

    }

    /**
     * verifie si la regex est bonne
     * @param <type> $objet
     */
    public function regex($objet)
    {

        if(!empty($objet['regex']))
        {
      	   $regex = $this->recupValeur($objet['regex']);
	   return $this->regexVerif($regex , $this->object->getValeur());
        }
        else
        {
           Erreur::declarer_dev(31 , "attribut : regex"); 
	   return false;
        }
        

    }
    

	
    /**
     * verifie si la valeur est unique pour un champ donnee.
     * @param <type> $objet
     */
    public function unique($objet)
    {

		    $base = $this->recupValeur($objet['base']);
		    $table = $this->recupValeur($objet['table']);
		    $champ = $this->recupValeur($objet['champ']);
		    $valeur = $this->object->getValeur();

		    if($this->object->getStatus() != "modification")
		    {
		        return $this->uniqueVerif($base ,$table , $champ , $valeur);
		    }
		    else
		    {
		        $this->uniqueVerif($base ,$table , $champ , $valeur);

		        if(strval($this->object->getValeurDefaults()) == strval($valeur))
		        {
	 
		            if($this->unique == 1)
		            {
		                return true;
		            }
		        }
		        else
		        {
		 
		            if($this->unique == 0 )
		            {
		                return true;
		            }
		        }
		    }

    }

    /**
     * verifie si la valeur existe pour un champ donnee
     * @param <type> $objet
     */
    public function existe($objet)
    {
		if(!empty($objet['champ']))		
		{
		    $base = $this->recupValeur($objet['base']);
		    $table = $this->recupValeur($objet['table']);
		    $champ = $this->recupValeur($objet['champ']);
		    $valeur = $this->object->getValeur();
		}
		else if (!empty($objet['file']))
		{

			$file = $this->recupValeurForm($objet['file']);
                       
                        if(file_exists($file))
			{
				return false;
			}
			else
			{
				return true;
			}
		}

        return $this->existeVerif($base ,$table , $champ , $valeur);

    }


    public function existeVerif($base, $table , $champ , $valeur)
    {
        core::libs("db");
        $db = new DB;
        $db->base = $base;
        $db->table = $table;
            

        $this->existe = $db->lire_DB("true",$champ,$champ."='".$valeur."'");

        if($this->existe > 0)
        {
            return true;
        }
        else
        {
            return false;
        }

    }


    public function uniqueVerif($base, $table , $champ , $valeur)
    {
        core::libs("db");
        $db = new DB;
        $db->base = $base;
        $db->table = $table;
       
        $this->unique = $db->lire_DB("true",$champ,$champ."='".$valeur."'");

        if($this->unique > 0)
        {
            return false;
        }
        else
        {
            return true;
        }

    }

    public function regexVerif($regex , $valeur)
    {
      
        if(preg_match("/$regex/", $valeur))
        {
           return true;
        }
        else
        {
           return false;
        }

    }



}
?>

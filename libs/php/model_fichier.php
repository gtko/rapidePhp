<?php 
/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*\
/*+********************************************************************************************+*\
/*+-------------------------- Script venant de Gtux.fr ----------------------------------------+*\
/*+-------------------------- Crée par Gtko avec l'editeur Geany ------------------------------+*\
/*+********************************************************************************************+*\ 
/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/


class Fichier{


    //variables public de l'objet
    public $var_rep;
				
	private function Verifie_vide($path){
		if(file_exists($path))
		{
			if(is_dir($path))
			{
				$fichier = scandir($path);
				foreach($fichier as $key => $val)
					{
						if($key != "0" && $key != "1")
						{
							$tableau[] = $val;
						}
					
					}
						
				if(!empty($tableau))
				{
					return	$fichier;
					
				}
				else
				{
					return "vide";	
					
				}
			}
			else
			{
				return "file";
				
			}
		}
		else
		{
			return "aucun repertoire";	 
		}	
	}



	public function supprimer_rep($path){
	
		$tableau = $this->Verifie_vide();
		
		if(empty($tableau) || !is_array($tableau) && $tableau!== 'vide')
		{
			$erreur = "le parametre tableau est incorrect ou manquant ";
			return var_dump($tableau , $erreur);	
			exit;
		}
		elseif($tableau === 'vide')
		{
			rmdir($path);	
		}
		else
		{		
				
			foreach($tableau as $key => $val)
			{
				if($key != "0" && $key != "1")
				{
					$file = $path."/".$val;
					$retour = Verifie_vide($file);
					if($retour === 'vide')
					{
						rmdir($file);
						
					}
					elseif($retour === 'file')
					{
						unlink($file);
						
					}
					else
					{	
						$this->supprimer_rep($file);
			
					}
					
				
				}
		
			}	
			if(file_exists($path))
			{
				rmdir($path);	
			}
		}

	
	}
	
	public static function  cree_file($var_path ,$nom_fichier , $contenu){
		
		
		if(file_exists($var_path))
			{
				$retour  = file_put_contents($var_path.$nom_fichier , $contenu);



			}
			else
			{
				Erreur::declarer_dev(11, "Objet = Fichier , function : cree_file , return false");
                                return false;
			}
		
		if($retour)
                {
                    return true;
                }
                else
                {
                    Erreur::declarer_dev(10, "Objet = Fichier , function : cree_file , return false");
                    return false;
                }
	
	}
	
	
	
	
	public static function cree_rep($var_path){
		
		
		if(file_exists($var_path))
			{
				return 0;
			}
			else
			{
				return mkdir($var_path);

			}
		
		
	
	}


        /**
         * voir tous les fichiers que contient le repertoire
         */

        Public function lister_rep(){



             //je scan le repertoire desirer
             $tableau = scandir($this->var_rep);

               //j'effectue un foreach pour ranger le tableau et effectuer un traitement dessus
             foreach($tableau as $k => $v)
                 {

                     //comme scandir retourne un tableau avec la clée 0 et 1 inutile je les ignore
                    if($k !='0' && $k != '1')
                    {

                        $liste[] = $v;

                    }
                 }
            return $liste;

        }





        /**
         * Cette function liste tout les fichier qui se trouve dans un dossier elle peut etre recursive jusqu'au nombre de niveau indiquer
         * pour activer la recursivité on indique le premier parametre a true.
         * si l'on veut une recursivité a l'infini on mets $niveau a 0.
         * @param Recursivité , nombre de niveau
         * @author gtko
         * @copyright gtux
         * @example
         *  <code>
         *  $file = new Fichier;
         * $niveau = 0; => infinit
         * $recursivité = true;
         * $rep = '../test'
         * $retoure = $file->lister_fichier($recursivité,$niveau);
         * echo $retour;
         * </code>
         */

        public function lister_fichier($rep,$recusivite = NULL , $niveau = NULL)
        {

            if(empty($rep))
                {
                    return 'erreur le parametre rep est invalide';
                }


            //je scan le repertoire desirer
             $tableau = scandir($rep);

             //j'effectue un foreach pour ranger le tableau et effectuer un traitement dessus
             foreach($tableau as $k => $v)
                 {

                 //comme scandir retourne un tableau avec la clée 0 et 1 inutile je les ignore
                 if($k !='0' && $k != '1')
                 {
                       // je verifie que se n'est pas un repertoire
                     if(!is_dir($rep.'/'.$v) && is_file($rep.'/'.$v))
                     {
                         $sortie_tableau[] = $v;
                     }
                     else
                     {
                         //si la recursiviter est a true alors je lance la recursiviter
                         if($recusivite == true && $recusivite != NULL)
                             {
                                    $sortie_tableau[$v] = $this->lister_fichier( $rep.'/'.$v , 'true');
                             }
                     }


                 }

             }

             return $sortie_tableau;



        }

}



?>

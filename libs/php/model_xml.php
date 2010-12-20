<?php

/**
 *class xml servant a manipuler des fichier xml lire ecrire
 *
 * @author gtko
 */
Class Xml {


    private $var_xml;
    private $var_source;




    /**
     * Ouvrire un fichier xml est le returner en objet
     * @param $fichier_xml
     * @author Ohanessian Gregoire
     * @copyright gtux
     *
     */

     Public function charger_xml($fichier_xml)
    {
         if(empty($fichier_xml))
             {
                return "Le parametre fichier_xml est vide";

         }

         if(!file_exists($fichier_xml))
             {
                return "Le fichier demander n'existe pas" ;

            }

           $xml=simplexml_load_file($fichier_xml);

           return $xml;

         
     }




     /** acces au propriété en ecriture **/

     Public function set_xml($xml){

          if(empty($xml))
             {
                Entrer::$erreur[] = "Le parametre fichier_xml est vide";
                return 0;

         }

         if(!file_exists($xml))
             {
                 Entrer::$erreur[] = "Le fichier demander n'existe pas" ;
                 return 0;
            }

                $this->var_xml =simplexml_load_file($xml);
                $this->getName_source();
                 return $this->var_xml;



     }



     /* acces aux proprété en lecture**/

     Public function get_xml(){

         return $this->var_xml;

     }

     Public function get_source(){

        return $this->var_source;

     }


     /**methode de l'objet **/


     private function getName_source(){
         $this->var_source =  $this->var_xml->getName();
    }




    Public function ecrire_noeud(){




    }



     








}
?>

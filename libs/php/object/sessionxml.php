<?php
/**Caracterise une Session declarer directement a partir du xml surcouche de l'objet passageDonnée
 *
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 *
 */
class SessionXml extends Object{

    //proprieter
    private $nom = null;


    Public function __construct($position = null)
    {
        parent::__construct($position);
        Core::libs("passagedonnee");
       
    }


    Public function initialiserAttribut()
    {

        if($this->construitParXml)
        {
           $this->setNom($this->position["nom"]);
          
           
        }
    }

    /**
     * permet de modifier l'attribut Nom de session
     * @param $valeur attribut Nom de l'objet session
     */
    public function setNom($valeur)
    {
        
        $this->nom = $this->recupValeur($valeur);
       
    }

    /**
     * Permet de lire le Nom de l'objet session
     * @return valeur
     */
    public function getNom()
    {
        return $this->nom;
    }


     // les sessions
     private function session($position){
             $data = PassageDonnee::Singleton();

             foreach($position as $valeur)
             {
                 if($valeur->getName() == "valeur")
                 {
                   if(!empty($valeur['nom']))
                   {
                       $data->setData($this->nom , $this->recupValeur($valeur['nom'])."=".$this->recupValeur($valeur));
  
                   }
                 }
             }
     }

    /**
     * Calcule le rendu html de variable;
     * @return html
     */
    Public function renduHtml()
    {
       $this->session($this->position);
    }

    /**
     * Calcule le rendu Texte plain de variable;
     * @return html
     */
    Public function renduTextPlain()
    {
        $this->session($this->position);
    }

}
?>

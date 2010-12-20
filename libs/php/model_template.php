<?php
/**
 * Permet de generer divers type de donnée , formulaire , dans divers formats
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 *
 */ 
class Template extends GeneralLibs {

    //parametre public
    public $debug =false;

    //parametre priver
    private $donnee; //entrer de donnée pour l'objet
    private $xml; // document xml a parser
    private $position;

    private $racine; //info sur la racine


    private $tableauxVariablePhp = array();


    //constructeur
    public function __construct($xml){
            $this->Objets();
            $this->setXml(Entrer::$rep_modules.$xml.".xml");//je recupere le xml
    }

    //gestion des dependances de l'objets
    public function Objets(){
            Core::libs("xml");
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

    /**
     * Permet de declarer des variable php , pour les utilisées directement dans le xml
     * @param $nom donne un nom de variable
     * @param $valeur donne une valeur a la variable
     */
    public function setVariable($nom = null , $valeur = null)
    {
        if(is_null($nom))
        {
            Erreur::declarer_dev(2," objet : Template , function : setVariable() , argument : nom");
        }
        else
        {
            //on enregistre la nouvelle entré ou on la modifie dans les variable php
            $this->tableauxVariablePhp[$nom] = $valeur;
        }
    }

    //methode
    /**
     * Permet de construire le xml dans le format choisi
     * @return rendu  format dans lequels le xml a etait demander
     */
    public function construire()
    {
            //je regarde se que je doit construire
            $this->position = $this->xml;
            foreach($this->position->children() as $enfant)
            {

                $nomFils = $enfant->getName();
                if($nomFils != "class" && $nomFils != "id")
                {

                    //on appel la dependance de l'objet
                    Core::object($nomFils);

                    $fils = new $nomFils();
                    $fils->setPosition($enfant);
                    $fils->setRacine($this->position);
                    $fils->setVariablePhp($this->tableauxVariablePhp);//j'envoie le tableaux de donnée php dans les objects  du xml
                    $fils->initialiserAttribut();


                    //on place la class
                    if(!empty($enfant['class']))
                    {
                        $fils->setClass($enfant['class']);
                    }
                    else if(!empty($enfant->class[0]))
                    {
                        $fils->setClass($enfant->class[0]);
                    }

                    //on place l'id
                    if(!empty($enfant['id']))
                    {
                        $fils->setId($enfant['id']);
                    }
                    else if(!empty($enfant->id[0]))
                    {
                        $fils->setId($enfant->id[0]);
                    }

                    $rendu .= $fils->rendu();
                }
    	}

	return $rendu;
        
    }


}
?>

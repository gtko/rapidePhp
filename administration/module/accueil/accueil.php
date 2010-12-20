<?php

class Accueil extends Modules
{


    public function General(){

        $this->var_page = "accueil";
        $this->var_titre= "Accueil";

    }

    public function defaults(){

        $this->contenu["contenuPage"] = "Bonjour administration";

      
    }

    
   
    public function Objets(){



    }
}
?>

<?php

class traduction extends Modules
{

    public function General(){

        $this->var_page = "traduction";
        $this->var_titre= "traduction";
		$this->var_css = "$css";

    }

    public function defaults(){

        $this->contenu["contenuPage"] = "Vous etes sur le module traduction";
        
      
    }
   
    public function Objets(){



    }
}
?>
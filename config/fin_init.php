<?php

	//fin du microtime
        Core::script("aide_dev");
        Afficher::$supplement['decompte_fram'] =  aide_time_charge($depart_microtime);

       echo $afficher->afficher_rendu();

/**************** ACtiver LE systeme de traduction rapide avec des constante *********************************/
require_once(REP_LANG.Entrer::$langue.'.php'); //obligation de le mettre ici sinon conflit avec la resolution de constante de l'objet template


ob_end_flush();




?>

<?php


abstract class Modules extends GeneralModules{

    /**
    /*Le construct d'un module
    */
    public function __construct() {
          parent::__construct();
    }


    /**
    /* Le destruct d'un module
    */ 
    public function  __destruct() {

    }

    /**
     * return le tableaux du site administration pour le dev
     */
    public function getSiteAdmin(){

        $site["dir"] = REP_RACINE;
        $site["nom"] = "administration";

        return $site;

    }

    /**
     * Rechercher Site du Framework
     * return array
     */
    protected function rechercherSite($rep = null)
    {
        //on part de la racine de php
        if($rep == null)
        {
            $racine = $_SERVER["DOCUMENT_ROOT"];
        }
        else
        {
            $racine = $rep;
        }

        //ensuite on parcour la racine a la rechercher d'un config puis d'un core.php
        foreach(scandir($racine) as $clee => $value)
        {
           if($value != "." && $value != "..")
           {
                if(file_exists($racine."/".$value."/config/core.php") && !file_exists($racine."/".$value."/libs/"))
                {
                    $site[] = array( 
                               "dir" =>$racine."/".$value,
                               "nom" =>$value
                        );
                }
           }
        }

        $site[] = $this->getSiteAdmin();
        return $site;

    }

}




?>

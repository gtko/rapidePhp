<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of generalroutines
 *
 * @author gregoire
 */
abstract class GeneralRoutines{


        Public function __construct(){

        $this->objets();

    }


     abstract Public function Objets();

     Public static function recup_a(){
            /** je verifie que le get['a'] existe **/
            if(isset($_GET['a']) && preg_match("/^[0-9A-Za-z]+$/",$_GET['a']))
           {

                $_SESSION['geta'] = $_GET['a'];
                return $_GET['a'];

            }
            else
            {

                if(!empty($_SESSION['geta']) && preg_match("/^[0-9a-zA-Z]+$/",$_SESSION['geta']))
                {
                      return $_SESSION['geta'];

                }
                else
                {
                      Erreur::declarer_int(303, Entrer::$actions);

                }


            }



        }
}
?>

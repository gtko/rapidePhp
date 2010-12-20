<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of rapideoption
 *
 * @author gtko
 */
class rapideoption extends GeneralRoutines{


    /**propriter**/







    /**methode**/

    /**
     * Pour avoir un acces membres la methode va require tout les fichier utile a cette effet
     * @return void
     * @author gtko
     * @category Membre acces site
     * @copyright gtux
     */

    Public function acces_membre(){

        /** je require L'objet session **/
        require_once(REP_LIB_PHP."model_session.php");

        /** je lance l'identification **/

        $this->contenu['connection'] = "coucou";




    }



    Public function test()
    {

        return $this->personne;

    }





}
?>

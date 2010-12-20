<?php
/**Objet permettant de gerer les dependances entre les objets.
 *
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 *
 */
class Core {

    Public static $objets = array();
    Public static $nbr_objets = 0;


    Public function Objets(){

    }

    /**Permet d'appeler une libs placer dans le repertoire libs pour une utilisation.
     * @code
     * exemple d'utilisation :
     *  Core::libs("libs");
     * @endcode
     */
    Public static function libs(){

       // je recupere le nombre de parametre envoyer ainsi que le tableaux de ceux ci
        $nbr_parametre = func_num_args();
        $tableau_param =  func_get_args();
           
            foreach( $tableau_param as $clee => $value)
            {

                self::charger("libs", $value);

              }

    }

    /**Permet d'appeler une routine placer dans le repertoire routine pour une utilisation.
     * @code
     * exemple d'utilisation :
     *  Core::routine("maRoutine");
     * @endcode
     */
    Public static function routines(){
       // je recupere le nombre de parametre envoyer ainsi que le tableaux de ceux ci
        $nbr_parametre = func_num_args();
        $tableau_param =  func_get_args();

        foreach( $tableau_param as $clee => $value)
            {

                 self::charger("routines", $value);

              }




    }
    /**Permet d'appeler un script placer dans le rep script pour une utilisation exterieur.
     * @code
     * exemple d'utilisation :
     *  Core::script("maFunction");
     * @endcode
     */
    Public static function script(){
       // je recupere le nombre de parametre envoyer ainsi que le tableaux de ceux ci
        $nbr_parametre = func_num_args();
        $tableau_param =  func_get_args();

          foreach( $tableau_param as $clee => $value)
            {

                 self::charger("script", $value);

              }
    }

    /**Permet d'appeler un object placer dans le rep object pour une utilisation du template.
     * @code
     * exemple d'utilisation :
     *  Core::object("form","conteneur");
     * @endcode
     */
    Public static function object(){
       // je recupere le nombre de parametre envoyer ainsi que le tableaux de ceux ci
        $nbr_parametre = func_num_args();
        $tableau_param =  func_get_args();

          foreach( $tableau_param as $clee => $value)
            {

                 self::charger("object", $value);

              }
     }

     /**Permet de verifier qu'un object exist bien
      * @code
      *  exemple d'utilisation :
      *   $exist = Core::objectExist("form");
      *   if($exist)
      *   {
      *     echo "ok";
      *   }
      * @endcode
      * @param  $quoi
      * @return boolean
      */
     Public static function objectExist($quoi)
     {
          if(file_exists(REP_OBJECT."$quoi.php"))
          {
             return true;
          }
          else
          {
              return false;
          }
     }

    /**Methode permettant de charger les dependances script , libs , routines.
     *
     * @param  $quoi si c'est une libs , script , routine
     * @param  $qui  quel fichier importer dans le script php
     * @return boolean
     */
    Private static function charger($quoi,$qui){

                    // si il a deja été appeler je ne le require pas
                    // sinon je le require

                    if(in_array($qui , self::$objets))
                    {
                        return true;
                    }
                    else
                    {
                        // j'ingremente de 1 le nombre d'objet en cours
                        self::$nbr_objets++;
                        self::$objets[] = $qui;
                        switch ($quoi){

                            case "libs":

                                if(file_exists(REP_LIB_PHP."model_".$qui.".php"))
                                {
                                    require_once(REP_LIB_PHP."model_".$qui.".php");
                                }
                                else
                                {
                                    Erreur::declarer_dev( 5 , "objet : Core ,  function : charger , arguments : qui = $qui;");
                                }

                                break;

                            case "routines":

                                if(file_exists(REP_ROUTINES."$qui/config.conf"))
                                {
                                    require_once(REP_ROUTINES."$qui/config.conf");
                                }
                           
                              
                                if(file_exists(REP_ROUTINES."$qui/$qui.php"))
                                {
                                    require_once(REP_ROUTINES."$qui/$qui.php");
                                }
                                else
                                {
                                    Erreur::declarer_dev( 6 , "objet : Core ,  function : charger , arguments : qui = $qui;");
                                }

                                break;

                            case "script":

                              
                                if(file_exists(REP_SCRIPT_PHP."$qui.php"))
                                {
                                     require_once(REP_SCRIPT_PHP."$qui.php");
                                }
                                else
                                {
                                    Erreur::declarer_dev( 7 , "objet : Core ,  function : charger , arguments : qui = $qui;");
                                }

                                break;
                            case "object":


                                if(file_exists(REP_OBJECT."$qui.php"))
                                {
                                     require_once(REP_OBJECT."$qui.php");
                                }
                                else
                                {
                                    Erreur::declarer_dev( 24 , "objet : Core ,  function : charger , arguments : qui = $qui;");
                                }

                                break;

                            default ;
                                    Erreur::declarer_dev( 8 , "objet : Core ,  function : charger , arguments : quoi = $quoi , qui = $qui; ");
                                    return false;
                                break;


                        }





                    }

    }


}
?>

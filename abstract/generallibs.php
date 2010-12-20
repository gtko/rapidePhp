<?php
/**Objet abstrait representant une libs de rapidephp
 *
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 */
abstract class GeneralLibs{

    Public function __construct(){

        $this->objets();

    }

    /**
     * L'objet verifie si une constante existe si oui la remplace
     * @param $variable la variable a verifier
     * @return String
     */
    Public function verif_constant($variable){
        if(defined($variable))
        {
           return constant($variable);
        }
           return $variable;
    }


    /** methode abstraite appeler par le construteur sert a gerer les dependances des objets dans rapidephp.
     */
    abstract Public function Objets();
}

?>

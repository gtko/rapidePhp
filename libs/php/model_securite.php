<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of model_Securite
 *
 * @author ohanessian
 */
class Securite {



    public static function Encode($valeur)
    {
        if(is_array($valeur))
        {
            $valeur = self::EncodeTable($valeur);
        }
        else
        {
            $valeur = self::EncodeUnique($valeur);
        }
       
        return $valeur;
    }

    private static function EncodeTable($table)
    {
        if(is_array($table))
        {
            foreach($table as $clee => $value)
            {
                if(is_array($value))
                {
                    $tableSortie[$clee] = self::EncodeTable($value);
                }
                else
                {
                    $tableSortie[$clee] = self::EncodeUnique($value);
                }
            }
        }

        return $tableSortie;

    }

    private static function EncodeUnique($valeur)
    {
        $valeur = strip_tags($valeur);
        $valeur = addslashes($valeur);
        $valeur = htmlspecialchars($valeur,ENT_QUOTES);
        return $valeur;
    }

    public static function Decode($valeur)
    {
        $valeur = stripslashes($valeur);
        //$valeur = htmlspecialchars_decode($valeur,ENT_QUOTES);

        return $valeur;
    }

    public static function Boot()
    {
           $post = $_POST;
           $get = $_GET;

           unset($_POST);
           unset($_GET);

           $_POST = self::Encode($post);
           $_GET = self::Encode($get);
    }







}
?>

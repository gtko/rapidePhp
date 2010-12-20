<?php
/**Caracterise une Variable
 *
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 *
 */
class Image extends Object{

    //proprieter
    private $valeur = null;
    private $heigth = null;
    private $width = null;
    private $rep = null;
    private $ext = null;
    private $alt = null;

    Public function __construct($position = null)
    {
        parent::__construct($position);
        $this->etreParent = false; //cette object ne peut pas avoir d'enfant il est final

    }


    Public function initialiserAttribut()
    {

        if($this->construitParXml)
        {
           $this->setValeur($this->position["valeur"]);
           $this->setHeight($this->position["height"]);
           $this->setWidth($this->position["width"]);
           $this->setRep($this->position["rep"]);
           $this->setExt($this->position["ext"]);
           $this->setAlt($this->position["alt"]);

           if(!file_exists($this->rep.$this->valeur.$this->ext))
           {
               if(empty($this->position["vide"]))
               {
                   Erreur::declarer_dev(29);
               }
               else
               {
                   $this->setValeur($this->position["vide"]);
               }
           }

        }
    }

    /**
     * permet de modifier l'attribut valeur de image
     * @param $valeur attribut valeur de l'objet Image
     */
    public function setValeur($valeur)
    {
        $this->valeur = $this->recupValeur($valeur);
    }
    /**
     * Permet de lire la valeur de l'objet image
     * @return valeur
     */
    public function getValeur()
    {
        return $this->valeur;
    }

     /**
     * permet de modifier l'attribut height de image
     * @param $valeur attribut valeur de l'objet Image
     */
    public function setHeight($hauteur)
    {
        if(!empty($hauteur))
        {
            $this->height = "height='".$this->recupValeur($hauteur)."'";
        }
    }
    /**
     * Permet de lire la hauteur de l'objet image
     * @return valeur
     */
    public function getHeight()
    {
        return $this->height;
    }

     /**
     * permet de modifier l'attribut height de image
     * @param $valeur attribut valeur de l'objet Image
     */
    public function setWidth($largeur)
    {
        if(!empty($largeur))
        {
         $this->width = "width='".$this->recupValeur($largeur)."'";
        }
    }
    /**
     * Permet de lire la hauteur de l'objet image
     * @return valeur
     */
    public function getWidth()
    {
        return $this->width;
    }

     /**
     * permet de modifier l'attribut rep de image
     * @param $valeur attribut rep de l'objet Image
     */
    public function setRep($repertoire)
    {
        $this->rep = $this->recupValeur($repertoire);
    }
    /**
     * Permet de lire le rep de l'objet image
     * @return valeur
     */
    public function getRep()
    {

        return $this->rep;
    }

     /**
     * permet de modifier l'attribut ext de image
     * @param $valeur attribut ext de l'objet Image
     */
    public function setExt($extension)
    {
        if(!empty($extension))
        {
            $this->ext = ".".$this->recupValeur($extension);
        }
    }
    /**
     * Permet de lire le ext de l'objet image
     * @return valeur
     */
    public function getExt()
    {
        return $this->ext;
    }

     /**
     * permet de modifier l'attribut alt de image
     * @param $valeur attribut alt de l'objet Image
     */
    public function setAlt($alt)
    {
        $this->alt = "alt='".$this->recupValeur($alt)."'";
    }
    /**
     * Permet de lire le alt de l'objet image
     * @return valeur
     */
    public function getAlt()
    {
        return $this->alt;
    }


    /**
     * Calcule le rendu html de variable;
     * @return html
     */
    Public function renduHtml()
    {

        $rendu = "<img ".$this->idHtml." ".$this->classHtml." src='".$this->rep.$this->valeur.$this->ext."'".$this->height." ".$this->width;
        $rendu .= " ".$this->titleHtml." ".$this->alt."/>";
        return $rendu;
    }

    /**
     * Calcule le rendu Texte plain de variable;
     * @return html
     */
    Public function renduTextPlain()
    {


        $rendu = $valeur;
        $rendu .= $this->calculerFils();
        return $rendu;
    }

}
?>


<?php
/**
 * Caracterise un objet formulaire en plusieur format.
 * @author Ohanessian grégoire <gtux.prog@gmail.com>
 * @version 10.11
 *
 */
abstract class ObjectForm extends Object{

    //proprieter private
    protected static $tableauValeurForm = array();//valeur de la session post du formulaire envoyer
    protected static $etatForm = array();//valeur de l'etat du formulaire
    protected static $clee = null;//clée du tableaux pointer
    protected static $statusForm = array();

    protected $cleeForm = null;//clee du form auquel il sont associer
    protected static $valeurForm = array();

    protected static $valide= array(); //etat du formulaire si il est valide ou pas

    //Le objets formon un messageInfo
    protected $info = null;
    protected $typeInfo = null;

    protected $valeurDefaults = null;


     /**
     * Le constructeur peut directement definir la position , la class , l'id , et la racine
     * @param $position Pere xml
     */
    public function __construct($position = null , $racine = null , $class = null , $id = null)
    {
          parent::__construct($position, $racine, $class, $id);
          Core::libs('securite');
          $this->setCleeForm(self::$clee);

    }


    public function getValeurDefaults() {

        if(!empty($this->position))
        {
            $valeur = $this->position;
        }
        else
        {
           $valeur = $this->position["valeur"];
        }
        
        return $this->recupValeurForm($valeur);
    }


    public function getCleeForm() {
        return $this->cleeForm;
    }

    public function setCleeForm($cleeForm) {
        $this->cleeForm = $cleeForm;
    }

        /**
     * indique le typeinfo du message erreur , valide , info etc ..
     */
    public function setTypeInfo($typeInfo)
    {
        $this->typeInfo = $typeInfo;
    }

     /**
     * recupere le typeinfo du message erreur , valide , info etc ..
     */
    public function getTypeInfo()
    {
        return $this->typeInfo;
    }
    
    /**
     * Modifie la variable info qui gere les sortie de message de l'objet
     */
    public function setInfo($info)
    {
        $this->info = $info;
    }

     /**
     * recupere la variable info qui gere les sortie de message de l'objet
     */
    public function getInfo()
    {
        return $this->info;
    }


    /**
     * Modifie la variable valide
     */
    public function setValide($valide)
    {
        self::$valide[self::$clee] = $valide;
    }


    /**
     * recupere la variable valide
     */
    public static function getValide($form)
    {
        $clee = md5($form);
        if(self::$etatForm[$clee])
        {
            return self::$valide[$clee];
        }

        return false;
    }

     /**
     * recupere les valeur du formulaire
     */
    public static function getForm($form , $index = null)
    {
        if($index != null)
        {
            if(key_exists($index, $valeurForm[$form]))
            {
                return self::$valeurForm[$form][$index];
            }
            return false;
        }
        else
        {
            return self::$valeurForm[$form];
        }
    }

    
    
    
    /**
     * recupere le status du formulaire
     */
     public static function getStatus($nom =null)
     {
         if(is_null($nom))
         {
            return self::$statusForm[self::$clee];
         }
         else
         {
             return self::$statusForm[md5($nom)];
         }
     }


    /**
     * Recupere les balises verification pour effectuer les verif lors de l'envoie du formulaire
     */
    protected function verificationDonnee()
    {
        if(self::$etatForm[self::$clee] == true)
        {
                $reussi = $this->position->verification->count();
                if($reussi != 0)
                {
                    
                    foreach($this->position->verification->children() as $enf)
                    {
                        if(method_exists("VerifDonnee",$enf->getName()))
                        {
                            
                            $VerifDonnee = new VerifDonnee($this);
                            $verification = $VerifDonnee->{$enf->getName()}($enf);

                            // si c'est bon on affiche le message valide sinon l'erreur
                            if($verification){
                                $this->setTypeInfo("valide");
                                $this->setInfo($enf->valide);
                                self::$valeurForm[Form::getName()][$this->getName()] = $this->getValeur();
                            }
                            else
                            {
                                 $this->setValide(false);
                                 $this->setTypeInfo("erreur");
                                 $this->setInfo($enf->erreur);
                                 break;
                            }

                        }
                    }
                }
                else
                {
                    self::$valeurForm[Form::getName()][$this->getName()] = $this->getValeur();
                }
              
        }
        
    }

 

    /**
     * Recupere les valeurs du formulaire poster pour valider ou reremplir les champs
     */
    protected function  recupValeurForm($valeur) {
        $valeur = parent::recupValeur($valeur);
        if(!empty($valeur) && !empty(self::$tableauValeurForm[$this->getCleeForm()]))
        {
            if(key_exists($valeur,self::$tableauValeurForm[$this->getCleeForm()]))
            {

               $valeur = Securite::Decode($this->recupValeur(self::$tableauValeurForm[$this->getCleeForm()][$valeur]));
            }
        }
        return $this->patchBlanc($valeur);

    }


}
?>

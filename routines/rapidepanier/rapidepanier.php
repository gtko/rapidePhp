<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of rapidepanier
 *
 * @author gregoire
 */
class  RapidePanier extends GeneralRoutines {


        public  $panier = null;
        public $requete = null;
        public $total = null;


        Public function  Objets() {


          }


        Public function  __construct() {
                    parent::__construct();



                    if(isset($_SESSION['panier']))
                    {
                              $this->panier = unserialize($_SESSION['panier']);
                    }

                 

          }

        Public function  __destruct() {

                    $_SESSION['panier'] = serialize($this->panier);
                    //unset( $_SESSION['panier']);
          }


        Public function calculer()
        {
                  unset($this->total);
                  if(is_array($this->panier))
                  {
                            foreach($this->panier as $k => $v)
                            {
                                     $this->total += $v['sous_total'];

                            }
                            $this->total = number_format($this->total , 2);
                  }
                  else
                  {
                            $this->total = "0.00";
                  }

                  return $this->total;
        }


        Public  function ajouter($geta , $prix , $info)
        {
              if(!empty($geta))
              {
                 if(empty($this->panier[$geta]))
                  {
                     $this->panier[$geta]["id"] = $geta;
                     $this->panier[$geta]["quantite"] = 1;
                     $this->panier[$geta]["prix"] = floatval($prix);
                     $this->panier[$geta]["sous_total"] += floatval($prix);
                     $this->panier[$geta]["sous_total"] = number_format( $this->panier[$geta]["sous_total"] , 2);

                  }
                  else
                  {
                      
                        $this->panier[$geta]["quantite"] ++ ;
                        $this->panier[$geta]["sous_total"] += floatval($prix);
                        $this->panier[$geta]["sous_total"] = number_format( $this->panier[$geta]["sous_total"] , 2);
                  }

                  var_dump($info);
                  foreach($info as $k => $v)
                  {
                             $this->panier[$geta][$k] = $v;
                  }
           
              }


        }



        Public  function modifier($geta , $quantiter)
        {
                  if(!isset($this->panier[$geta]))
                  {
                     $this->panier[$this->recup_a()]["id"] = $geta;
                     $this->panier[$this->recup_a()]["quantite"] = $quantiter;
                  }

        }


        Public  function supprimer($geta)
        {
                 if(!empty($geta))
                 {
                    if(is_array($this->panier))
                    {
                           foreach($this->panier as $k => $v)
                           {
                                      if($k != $geta)
                                      {
                                                $table[$k] = $v;
                                      }
                            }

                           $this->panier = $table;
                    }
                 }

        }


        Public  function notification()
        {
                  $compter = count($this->panier);
                  if($compter <= 1)
                  {
                    $html  = $compter." article";
                  }
                  else
                  {
                     $html  = $compter." articles";
                  }
                 return $html;
        }

        Public  function etat()
        {


        }




}
?>

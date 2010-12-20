<?php
//initialise les microsecondes pour calcule serveur
$depart_microtime = microtime();
define("DEPART", microtime());

//Le fichier principal pour la configuration
require_once('core.php');

//initialise les sessions
	session_start();

//function de modification du contenu de la page
function rappel($buffer)
{

  // remplace toutes les pommes par des carottes
  //
  //on encode tout les liens
  preg_match_all("/href=[a-zA-Z0-9_#\.\?][^><]+/", $buffer , $test);
  

  return $buffer;
}

//function d'encodage et de decodage
function decodeGet()
{
	$decode = cryptage($_GET['encode'] , "decrypt");
       	preg_match_all("/([a-zA-Z0-9_#\.\?]*)=([a-zA-Z0-9_#\.\?]*)/", $decode , $test);
	foreach($test[0] as $clee => $value)
        {
         	$_GET[$test[1][$clee]] = $test[2][$clee];
         }
}

function encodeGet($buffer)
{

  //on encode tout les liens
  /*preg_match_all("/\?([a-zA-Z0-9][a-zA-Z0-9_=#\.\?&;]*)/", $buffer , $test);

        $parcour = $test[0];
        arsort($parcour);
        $cleeChanger = $test[1];
        arsort($cleeChanger);

	foreach($parcour as $clee => $value)
        {
		$buffer = str_replace($parcour[$clee], "?encode=".cryptage( $cleeChanger[$clee],"crypt"),$buffer);
	}*/

 

  Core::libs("langue");
  $langue = Langue::Singleton();
  $langue->setEntrer($buffer);
  $langue->setLangueSource(LANGUE_DEFAULTS);
  $langue->setRepertoireLang(REP_LANG);
  $langue->bootcapture();

  $buffer = $langue->getSortie();
  return $buffer;
}


function cryptage($message , $mode)
{
    
    // On calcule la taille de la clé pour l'algo triple des
    $cle_taille = mcrypt_module_get_algo_key_size(MCRYPT_3DES);
    // On calcule la taille du vecteur d'initialisation pour l'algo triple des et pour le mode NOFB
    $iv_taille = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_NOFB);
    if(empty($_SESSION["iv"]))
    {
      $_SESSION["iv"] = mcrypt_create_iv($iv_taille, MCRYPT_RAND);
    }
    $cle =CLEE_CRYPT;
    // On retaille la clé pour qu'elle ne soit pas trop longue
    $cle = substr($cle, 0, $cle_taille);
    // On le crypte
    if($mode == "crypt")
    {
      //On fabrique le vecteur d'initialisation, la constante MCRYPT_RAND permet d'initialiser un vecteur aléatoire
      //$messageCrypte = base64_encode(mcrypt_encrypt(MCRYPT_3DES, $cle, $message, MCRYPT_MODE_NOFB, $_SESSION["iv"]));
      $messageCrypte = base64_encode($message);
    }
    else if($mode == "decrypt")
    {
      // On le décrypte
      //$messageCrypte =  mcrypt_decrypt(MCRYPT_3DES, $cle, base64_decode($message), MCRYPT_MODE_NOFB, $_SESSION["iv"]);
      $messageCrypte = base64_decode($message);
    }
    return $messageCrypte;
}



//on crytpe le flux avant envoie au client
//puis on le decrypt
ob_start("encodeGet");
decodeGet();




?>

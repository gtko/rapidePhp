<?php




class RapideUpload  {



    /** proprieter **/
    public $var_repertoire;
    public $var_name_input;
    public $var_nom_image;

    //propriter pour la function deplacer
    public $var_source;
    public $var_destination;




    /** function **/

         function imageupload($repertoir,$nameinputimage,$nomimage)
	{

			$folder = $repertoir;
			/*si l'image est vide la fonction s'arrete ici*/
			if(empty($_FILES["".$nameinputimage.""]['name']))
			{
				$vide = "vide";
				return $vide;
			}
			$posted = $_FILES["".$nameinputimage.""];
			$_SESSION['erreur']['nomimage'] = $nameinputimage;
			$exten = $_FILES["".$nameinputimage.""]["type"];


                        $extval = array ("image/jpeg" , "image/png" ,"image/gif","image/x-png","image/pjpeg");


                        switch ($exten)
			{
			case "image/jpeg":
			$extension = ".jpg";
			break;

			case "image/png":
			$extension = ".png";
			break;

			case "image/gif":
			$extension = ".gif";
			break;

			/**pour internet explorer**/
			case "image/x-png":
			$extension = ".png";
			break;

			case "image/pjpeg":
			$extension = ".jpg";
			break;

			}


    }


    Public function recevoir_fichier()
    {

            $posted = $_FILES["".$this->var_name_input.""];
            
            $upload = is_uploaded_file($posted["tmp_name"]);
            if(!$upload)
            {
                    return false;
            }
            else
            {
                $bouger = move_uploaded_file($posted["tmp_name"], $this->var_repertoire.$this->var_nom_image);
                if($bouger)
                {
                         return true;
                }
                else
                {
                        return false;
                }
            }

    }


    Public function deplacer_fichier()
    {

         copy($this->var_source, $this->var_destination);
  
    }


    
    Private function verif_type_fichier()
    {





    }





}







?>

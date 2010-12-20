<?php


Class ImageLib
{


        /*Proprieter */

        private $source = null;
        private $destination= null;
        private $extension= null;
        private $hauteur= null;
        private $largeur= null;
        private $compression= null;


        public function getSource() {
            return $this->source;
        }

        public function setSource($source) {
            $this->source = $source;
        }

        public function getDestination() {
            return $this->destination;
        }

        public function setDestination($destination) {
            $this->destination = $destination;
        }

        public function getExtension() {
            return $this->extension;
        }

        public function setExtension($extension) {
            $this->extension = $extension;
        }

        public function getHauteur() {
            return $this->hauteur;
        }

        public function setHauteur($hauteur) {
            $this->hauteur = $hauteur;
        }

        public function getLargeur() {
            return $this->largeur;
        }

        public function setLargeur($largeur) {
            $this->largeur = $largeur;
        }

        public function getCompression() {
            return $this->compression;
        }

        public function setCompression($compression) {
            $this->compression = $compression;
        }



        Public function compresser_image()
        {


            /* je lit l'image*/
           if(file_exists($this->source))
	   {
            $magick = new imagick;
            $magick->readImage($this->source);

            $magick->setCompression($this->compression);

            /* j'ecrit l'image*/

            $magick->writeImage($this->destination);

           }
           else
           {
               Erreur::declarer_dev(12,"objet ; Image , function : compresser_image  source = ".$this->source."  compression = ".$this->compression." detination = ".$this->destination);
           }
        }

        Public function retailler_image()
        {

           if(file_exists($this->source))
	   {
            $magick = new imagick;
              /* je lit l'image*/
            $magick->readImage($this->source);
            $magick->thumbnailImage( $this->largeur, $this->hauteur );
            /* j'ecrit l'image*/
            $magick->writeImage($this->destination);

           }
           else
           {
               Erreur::declarer_dev(12,"objet ; Image , function : retailler_image  source = ".$this->source."  largeur = ".$this->largeur." hauteur = ".$this->hauteur."  detination = ".$this->destination);
           }
           

        }


        Private function existe_fichier()
        {
            // on verifie que l'image existe
            if(!file_exists($repertoirsources.$nomimage.$ext))
	   {
		$vide = "Veuillez nous excuser, mais il est impossible de compresser l'image , car aucun fichier n'existe.";
		return $vide;
	   }

        }


        Public function compressdedgtrer_image($repertoirsources,$nomimage,$ext,$haut_ou_larg,$taille,$coin_arrondi,$valeur_arrondi,$repertoir_de_destination,$ombre,$format){

		/*on verifie que l'image existe bien sinon on arrete la fonction*/
		if(!file_exists($repertoirsources.$nomimage.$ext))
			{
				$vide = "Veuillez nous excuser, mais il est impossible de compresser l'image , car aucun fichier n'existe.";
				return $vide;
			}

		/* on choisi l'image */
			$im = new Imagick( $repertoirsources.$nomimage.$ext );
			$im->setImageFormat("png");

		/* on diminue sa taille */
		switch($haut_ou_larg)
		{
			case "haut":
				$im->thumbnailImage( NULL, $taille );
			break;
			case "larg":
				$im->thumbnailImage( $taille, NULL );
			break;
		}

		switch($coin_arrondi)
		{
			case "1":
				$im->roundCorners($valeur_arrondi,$valeur_arrondi);
			break;
		}

		switch($ombre)
		{
			case "0":
			/*on enregistre l'image*/
				$im->writeImage($repertoir_de_destination.$nomimage.$format);

			break;

			case"1":
				/* on clone l'image pour pouvoir travailler avec */
					$shadow = $im->clone();

				/* on crée l'image pour l'ombre en noir biensure */
					$shadow->setImageBackgroundColor( new ImagickPixel( 'black' ) );
					 /* on crée l'ombre */
					$shadow->shadowImage( 80, 3,5,5);

				/* on assemble les 2 images*/
					$shadow->compositeImage( $im, Imagick::COMPOSITE_OVER, 12, 0 );
					$shadow->writeImage($repertoir_de_destination.$nomimage.$format);
			break;

		}
		return "true";
	}







}



?>

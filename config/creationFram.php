<?php


define ("INDEX_VIDE" , REP_DIVERS."nouveauSite/index.php");

define ("MODULE_VIDE" , '<?php

class $module extends Modules
{

    public function General(){

        $this->var_page = "$page";
        $this->var_titre= "$titre";
		$this->var_css = "$css";

    }

    public function defaults(){

        $this->contenu["contenuPage"] = "Vous etes sur le module $module";

      
    }
   
    public function Objets(){



    }
}
?>');

define ("MODULE_PAGE_VIDE" , '<?php echo $contenuPage ?>');
define ("LANG_VIDE" , "<?php\n\n ?>");
define ("DESIGN_VIDE",'<!DOCTYPE html>
<html lang=\"fr\">
	<head>
		<meta charset=\"utf-8\">
        <meta name=\"Author\" content=\"\"/>
		<meta name=\"Description\" content=\"\"/>
		<meta name=\"Keywords\" content=\"\"/>
        <title>
            <?php echo $titre; ?>
       </title>
       <link href=\"<?php echo REP_CSS;?>reset.css\" rel=\"stylesheet\" type=\"text/css\" />
       <link href=\"<?php echo REP_CSS;?>general.css\" rel=\"stylesheet\" type=\"text/css\" />

       <style type=\"text/css\">
		   <?php echo $style_css;?>
       </style>
       
        <?php if(!empty($css)){ echo $css;} ?>

        <?php echo Entrer::$ajout_script;?>
		<script type=\"text/javascript\">
                       $(document).ready(function(){
                                 <?php
                                         echo Entrer::$script;
                                  ?>
                       });
   		</script>
	</head>

	<body>
            <header id=\'header\'>
                <?php
                    echo $message_site;
                ?>
            </header>
            <nav id=\'menu\'>
            	<?php
					echo $menu;
				?>
            </nav>
			<section id=\"content\" class=\"\" role=\"main\">
                            
				<?php
						include_once($conteneur);
				?>
                <div class=\"clear\"></div>
			</section>
            <footer id=\'footer\'>
            
            </footer>
	</body>
</html>
')

?>

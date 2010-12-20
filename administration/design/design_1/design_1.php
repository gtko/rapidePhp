<!--
	Author:  <Ohanessian gregoire>
	copyright: gtux
-->
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
        <meta name="Author" content=""/>
		<meta name="Description" content=""/>
		<meta name="Keywords" content="'"/>
        <title>
            <?php echo $titre; ?>
       </title>
       <link href="<?php echo REP_CSS;?>reset.css" rel="stylesheet" type="text/css" />
       <link href="<?php echo REP_CSS;?>general.css" rel="stylesheet" type="text/css" />

       <style type="text/css">
		   <?php echo $style_css;?>
       </style>
       
        <?php if(!empty($css)){ echo $css;} ?>

        <?php echo Entrer::$ajout_script;?>
		<script type="text/javascript">
                       $(document).ready(function(){
                                 <?php
                                         echo Entrer::$script;
                                  ?>
                       });
   		</script>
	</head>

	<body>
            <header id='header'>
                <div id="logo">
                    RapidePhp
                    <span id="version">V0.2</span>
                
                </div>    
                <div id='messageSite'>
                <?php
                    echo $message_site;
                ?>
                </div>
            </header>
            <nav id='menu'>
                <?php
                    echo $menu;
                ?>
                <div class="clear"></div>
            </nav>
			<section id="content" class="" role="main">
                            
				<?php
						include_once($conteneur);
				?>
                <div class="clear"></div>
			</section>
            <footer id='footer'>
            
            </footer>
	</body>
</html>


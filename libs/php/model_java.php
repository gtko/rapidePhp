<?php


class Java extends GeneralLibs {

          Public static $script_lancer = array();


         Public function objets(){


            }

         Public static function verif_java(){

              Entrer::$script .= "
                   $.getJSON('index.php?modules=".PAGE_DEFAULTS."&actions=java_exist',{ verifier : 'java' }, function(json){
                        });

                   function verif_java(){
                   $.getJSON('index.php?modules=".PAGE_DEFAULTS."&actions=java_exist',{ verifier : 'java' }, function(json){
                        });
                    }

                    ";



         }

         Public static function tinymce(){
                    self::$script_lancer[] = "tinymce";
                   Entrer::$ajout_script .= "<script type='text/javascript' src='".REP_TINYMCE."tiny_mce.js'></script>
   <script type='text/javascript'>
                    tinyMCE.init({
                              // General options
                              mode : 'textareas',
                              theme : 'advanced',
                              plugins : 'safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template',
                              // Theme options
                              theme_advanced_buttons1 : 'save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect',
                              theme_advanced_buttons2 : 'cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor',
                              theme_advanced_buttons3 : 'tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen',
                              theme_advanced_buttons4 : 'insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak',
                              theme_advanced_toolbar_location : 'top',
                              theme_advanced_toolbar_align : 'left',
                              theme_advanced_statusbar_location : 'bottom',
                              theme_advanced_resizing : true,

                              // Example content CSS (should be your site CSS)
                              content_css : 'css/example.css',

                              // Drop lists for link/image/media/template dialogs
                              template_external_list_url : 'js/template_list.js',
                              external_link_list_url : 'js/link_list.js',
                              external_image_list_url : 'js/image_list.js',
                              media_external_list_url : 'js/media_list.js',

                              // Replace values for the template plugin
                              template_replace_values : {
                              username : 'Some User',
                              staffid : '991234'
                              }
            });
</script>
                    ";
                   
                   
         }

         Public static function jquery(){
       
                   Entrer::$ajout_script .= "<script src='".REP_JQUERY."jquery.js' type='text/javascript'></script>";
                   self::$script_lancer[] = "jquery";


         }

         Public static function jquery_ui(){
                   self::$script_lancer[] = "jquery_ui";
                   Entrer::$ajout_script .= "
                  <link rel='stylesheet' href='".REP_JQUERY."jquery-ui.css' media='all' /> ";
                   Entrer::$ajout_script .= "<script src='".REP_JQUERY."jquery-ui.js' type='text/javascript'></script>";

         }

       

         /**
          * transformer le datepicker en date valide mysql
          * @author gregoire ohanessian
          */
         Public static function conv_date($date) {
                              $date_couper = split("/", $date);
                              $date  = $date_couper[2]."-".$date_couper[0]."-".$date_couper[1];
                              return $date;
         }

         Public static function reconv_date($date){
                              $date_couper = split("-", $date);
                              $date  = $date_couper[1]."/".$date_couper[2]."/".$date_couper[0];
                              return $date;

         }


         Public static function jquery_autocomplete(){


                Entrer::$ajout_script .= "
                  <link rel='stylesheet' href='".REP_JQUERY_AUTOCOMPLETE."jquery.autocomplete.css' type='text/css' media='all' /> ";
                   Entrer::$ajout_script .= "<script src='".REP_JQUERY_AUTOCOMPLETE."jquery.autocomplete.js' type='text/javascript'></script>";


         }


         Public static function uploadify($champ = NULL , $rep  = "php-tmp", $auto = false , $img = NULL){

            if($auto = false || $auto == 'false')
            {
                   $auto = 'false';
            }

            if($auto = true || $auto == 'true')
            {
                      $auto ='true';
            }

            if(!in_array( "jquery",self::$script_lancer))
            {
                self::jquery();
            }
                  self::$script_lancer[] = "uploadify";
                 Entrer::$ajout_script .= "<script src='".REP_UPLOADIFY."jquery.uploadify.js' type='text/javascript'></script>";

                 if($champ != NULL)
                 {
                       $selecteur = $champ;
                 }
                 else
                 {
                      $selecteur = "[type=file]";          
                 }
                 Afficher::$css[] = REP_UPLOADIFY."uploadify.css";

                 if($img != NULL)
                 {
                     $img = "'buttonImg' : '$img',";
                 }

                 Entrer::$script .= "$('$selecteur').fileUpload({
                                                  'uploader': '".REP_UPLOADIFY."uploader.swf',
                                                  'script':'".REP_UPLOADIFY."upload.php',
                                                  'folder':'$rep',
                                                  'cancelImg': '".REP_UPLOADIFY."cancel.png',
                                                  'multi':true,
                                                  $img
                                                  'auto':$auto

                                                  });";

         }
         

         Public static function video($skins = "vim"){

              Afficher::$css[] = REP_VIDEOJS."video-js.css";
              Afficher::$css[] = REP_VIDEOJS."skins/$skins.css";
              Entrer::$ajout_script .= "<script src='".REP_VIDEOJS."video.js' type='text/javascript'></script>";

              Entrer::$script .= " VideoJS.setup();";



         }

         Public static function ajouter_video($lien , $hauteur = 100 , $largeur = 100,$preload = true){

			 if($preload == true)
			 {
				$preload = "preload";	
		     }
		     else
		     {
				$preload = ""; 
			 }
             return "<div class='video-js-box vim-css'>

                         <video class='video-js' width='$largeur' height='$hauteur'  controls $preload>
                             <source src='".$lien."' type='video/webm; codecs=\"vp8, vorbis\"'>
                            <object class='vjs-flash-fallback' width='852' height='480' type='application/x-shockwave-flash'
                                data='http://releases.flowplayer.org/swf/flowplayer-3.2.1.swf'>
                                <param name='movie' value='http://releases.flowplayer.org/swf/flowplayer-3.2.1.swf' />
                                <param name='allowfullscreen' value='true' />
                                <param name='flashvars' value='config={'clip':{'url':'".$lien."','autoPlay':false,'autoBuffering':true}}' />
                          </object>
                         </video>
                    </div>";


         }

         Public static function ie(){

             Entrer::$ajout_script .= "
                          <!--[if lt IE 7]>
                              <script src='http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js'></script>
                          <![endif]-->
                          <!--[if lt IE 8]>
                              <script src='http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE8.js'></script>
                          <![endif]-->
                          <!--[if lt IE 9]>
                              <script src='http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js'></script>
                         <![endif]-->";



         }


         Public static function ajax_form($module , $nomfunction , $donnee , $arguments){

              Entrer::$script .= "
                   function ajax_$nomfunction(qui , $arguments){

                        verif_java();
                        $.getJSON('index.php?modules=$module&actions=rapide_nettoyeur',{ $donnee } , function(json){
                            $nomfunction(json , qui);
                        });

                    };

                    ";


          }


          Public static function ajax($module , $actions, $nomfunction , $type = NULL){


                 if($type == NULL)
                 {
                 Entrer::$script .= "
                   function ajax_$nomfunction(qui , donnee){
                       
                        verif_java();
                        $.getJSON('index.php?modules=$module&actions=$actions',{donnee : donnee}, function(json){
                            $nomfunction(json , qui);
                        });

                    };
                   
                    
                    ";
                 }
                 elseif($type == 'post')
                 {
                      Entrer::$script .= "
                   function ajax_$nomfunction(qui , donnee){
                           $.post('index.php?modules=$module&actions=$actions',{ donnee : donnee }, function(json){
                            $nomfunction(json , qui);
                        }, 'json' );

                    };


                    ";
                 }
          }


          Public static function action($qui , $evenement , $script)
           {
               Entrer::$script .="
                    $('$qui').$evenement(function(){

                          qui = $(this);

                            $script

                            return false;

                        });
                    ";

           }
                  
         Public static function quoifaire($javascript , $nomfunction){


                 Entrer::$script  .= "function $nomfunction(json , qui){";
                 Entrer::$script .= $javascript;
                 Entrer::$script  .= "};";

          }

          Public static function boot(){

          /*   $.ajax({
                       type: 'GET',
                       url: 'index.php?modules=$module&actons=$actions',
                       datatype = 'json',
                       data: 'test=coucou',
                       success: function(msg){
                         alert( 'Data Saved: ' + msg );
                       }
                     });*/
          }
          
          
          /**
           * methode qui permet d'integrer un script javascript a une page
           * @param type $script //script a utiliser
           * @param type $rep //repertire du script par default celui du module en cours
           */
          Public static function addScript($script , $rep = null)
          {
              if(is_null($rep))
              {    
                Afficher::$script[] = Entrer::$repModulesHtml.$script.".js";
              }
              else
              {
                  Afficher::$script[] = $rep.$script.".js";  
              }
          }
          

}



?>

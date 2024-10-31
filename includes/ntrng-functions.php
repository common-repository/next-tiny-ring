<?php
if (!defined('ABSPATH')) exit;

if (!defined('NTRNG_KEY_DONATE'))
   { define('NTRNG_KEY_DONATE','ZXSM2ZYLGY4UC');
   }
if (!defined('NTRNG_PLUGIN_NAME'))
   { define('NTRNG_PLUGIN_NAME','Next Tiny Ring');
   }
if (!defined('NTRNG_PLUGIN_SLUG'))
   { define('NTRNG_PLUGIN_SLUG','next-tiny-ring');
   }
if (!defined('NTRNG_PLUGIN_PAGE'))
   { define('NTRNG_PLUGIN_PAGE','ntrng-acp');
   }
if (!defined('NTRNG_PLUGIN_TABLE'))
   { define('NTRNG_PLUGIN_TABLE','ntrng');
   }
   
if (!defined('NTRNG_DEFAULT_TITLE'))
   { define('NTRNG_DEFAULT_TITLE','Plugins for WordPress');
   }
if (!defined('NTRNG_DEFAULT_DESC'))
   { define('NTRNG_DEFAULT_DESC','Download free nice plugins for your WP website!');
   }
if (!defined('NTRNG_DEFAULT_LINK'))
   { define('NTRNG_DEFAULT_LINK','https://nxt-web.com/wordpress-plugins/');
   }
   
   
global $mydb;
$opt_NameDB = get_option('optNameDB');
$opt_UserDB = get_option('optUserDB');
$opt_PassDB = get_option('optpassDB');
$DB_HOST = "localhost";
$DB_NAME = $opt_NameDB;
$DB_USER = $opt_UserDB;
$DB_PASS = $opt_PassDB;
$mydb = new wpdb($DB_USER,$DB_PASS,$DB_NAME,$DB_HOST);
$TableRing = 'ntrng_RingInfo'; 
     
global $opt_VersionDB;
$opt_VersionDB = '1.1';

add_action('admin_enqueue_scripts', 'ntrng_Styles');
function ntrng_Styles()
{ $tmpStr = plugins_url('/',__FILE__);
  if (substr($tmpStr,-1) == "/")
     $tmpPos = strrpos($tmpStr,'/',-2);
  else   
     $tmpPos = strrpos($tmpStr,'/',-1);
  $tmpStr = substr($tmpStr,0,$tmpPos);
  $tmpPathCSS = $tmpStr . '/css/style.css';

  wp_enqueue_style('ntrng_style_css', $tmpPathCSS);
}

add_action('wp_enqueue_scripts','ntrng_load_dashicons_front_end' );
function ntrng_load_dashicons_front_end()
{ wp_enqueue_style('dashicons');
}

function ntrng_CheckVersion()
{ $tmpCurVersion = get_option('ntrngCurrentVersion');
  $tmpCurType = get_option('ntrngCurrentType');
  if((version_compare($tmpCurVersion, NTRNG_VERSION, '<')) or (NTRNG_TYPE !== $tmpCurType))
    { ntrng_PluginActivation();
    }
}
add_action('plugins_loaded', 'ntrng_checkVersion');

add_action('admin_menu','ntrng_Add_Menu');
function ntrng_Add_Menu()
{ add_menu_page(
      'Next Tiny Ring',
      NTRNG_PLUGIN_NAME,
      'manage_options',
      'ntrng-acp',
      'ntrng_acp_callback',
      'dashicons-calendar-alt'
    );
  
  add_submenu_page('ntrng-acp', __('General Settings','next-tiny-ring'), __('General Settings','next-tiny-ring'), 'manage_options', 'ntrng-acp&tab=ntrng_settings', 'render_generic_settings_page');
  add_submenu_page('ntrng-acp', __('Banner Ads','next-tiny-ring'), __('Banner Ads','next-tiny-ring'), 'manage_options', 'ntrng-acp&tab=ntrng_ads', 'render_generic_settings_page');
  add_submenu_page('ntrng-acp', __('Help','next-tiny-ring'), __('Help','next-tiny-ring'), 'manage_options', 'ntrng-acp&tab=ntrng_help', 'render_generic_settings_page');

	add_action('admin_init','ntrng_register_settings');  
}

add_action('init','ntrng_load_textdomain');
function ntrng_load_textdomain()
{ load_plugin_textdomain('next-tiny-ring',false,NTRNG_PLUGIN_SLUG . '/languages/'); 
}

function ntrng_register_settings()
{ global $mydb;
  $TableWeb = 'ntrng_RingWeb'; 

  register_setting('ntrng-settings-group','ntrngCurrentVersion');
  register_setting('ntrng-settings-group','ntrngCurrentType');

  register_setting('ntrng-settings-group','optNameDB');
  register_setting('ntrng-settings-group','optUserDB');
  register_setting('ntrng-settings-group','optPassDB');
    
  register_setting('ntrng-settings-group','optColBg');
  register_setting('ntrng-settings-group','optBorderColor');
  register_setting('ntrng-settings-group','optBorderStyle');
  register_setting('ntrng-settings-group','optShowLink');
  register_setting('ntrng-settings-group','optColLink');
  register_setting('ntrng-settings-group','optColTitle');
  register_setting('ntrng-settings-group','optColDesc');
  register_setting('ntrng-settings-group','optRadiusStyle');
  register_setting('ntrng-settings-group','optBorderSize');
  

  register_setting('ntrng-settings-group','optColBgHeader');
  register_setting('ntrng-settings-group','optColBgBody');
  register_setting('ntrng-settings-group','optColBgFooter');
  register_setting('ntrng-settings-group','optUseBgHeader');
  register_setting('ntrng-settings-group','optUseBgBody');
  register_setting('ntrng-settings-group','optUseBgFooter');
  
  register_setting('ntrng-settings-group','optDisplayHeader');
  register_setting('ntrng-settings-group','optDisplayFooter');
  
  register_setting('ntrng-settings-group','optDisplayFrontPage');
  register_setting('ntrng-settings-group','optDisplayExcept');
  register_setting('ntrng-settings-group','optPageIdExcept');
  register_setting('ntrng-settings-group','optDisplayOnly');
  register_setting('ntrng-settings-group','optPageIdOnly');
  
  
  /*
  if ($mydb->get_var("SHOW TABLES LIKE '$TableWeb'") != $TableWeb)
     { wp_die(__('Database not found!!!'));
     }
  */   
  if ($mydb->get_var("SHOW TABLES LIKE '$TableWeb'") == $TableWeb)  
        { $tmpNbRec = $mydb->get_var("SELECT COUNT(*) FROM $TableWeb"); 
       $tmpSQL = $mydb->prepare("SELECT id, web FROM $TableWeb");
       $resultsWeb = $mydb->get_results($tmpSQL);
       foreach ($resultsWeb as $detailsAds)
               { $ad_id = $detailsAds->id;
                 $ad_web = $detailsAds->web;
                 register_setting('ntrng-settings-group','optBannerAd_'.$ad_id);
               }
     }  
}

function ntrng_acp_callback()
{ global $title;

  if (!current_user_can('administrator'))
     { wp_die(__('You do not have sufficient permissions to access this page.'));
	   }
	
  print '<div class="wrap">';

  $file = plugin_dir_path( __FILE__ ) . "ntrng-acp-page.php";
  if (file_exists($file))
      require $file;

  echo "<p><em><b>" . esc_html__('Add for free nice other','next-tiny-ring') . " <a target=\"_blank\" href=\"https://nxt-web.com/wordpress-plugins/\" style=\"color:#FE5500;font-weight:bold;font-size:1.2em\">" . esc_html__('Plugins for Wordpress','next-tiny-ring') . "</a></b></em></p>";
  echo "<p><em><b>" . esc_html__('You like this plugin?','next-tiny-ring') . " <a target=\"_blank\" href=\"https://www.paypal.com/donate/?hosted_button_id=" . NTRNG_KEY_DONATE . "\" style=\"color:#FE5500;font-weight:bold;font-size:1.2em\">" . esc_html__('Offer me a coffee!','next-tiny-ring') . "</a></b></em>";
  $CoffeePath = plugin_dir_url( dirname( __FILE__ ) )  . '/images/coffee-donate.gif';
  echo '&nbsp;<img src="' . esc_attr($CoffeePath) . '"></p>';
  print '</div>';  
}

add_action("admin_enqueue_scripts", "ntrng_add_script_upload");
function ntrng_add_script_upload()
{	wp_enqueue_media();
}

function ntrng_DisplayBanner($parLocation)
{ global $mydb;
  $TableRing = 'ntrng_RingInfo';


  $tmpColBgWeb = "";
  switch($parLocation)
        { case 'Head': 
               $tmpUseBgWeb = get_option('optUseBgHeader',0);
               if ($tmpUseBgWeb) $tmpColBgWeb = get_option('optColBgHeader','#ffffff');
               break;
          case 'Body': 
               $tmpUseBgWeb = get_option('optUseBgBody',0);
               if ($tmpUseBgWeb) $tmpColBgWeb = get_option('optColBgBody','#ffffff');
               break;
          case 'Foot':
               $tmpUseBgWeb = get_option('optUseBgFooter',0);
               if ($tmpUseBgWeb) $tmpColBgWeb = get_option('optColBgFooter','#ffffff');
               break;
        }
  
  if ($TableRing != "")
     { if ($mydb->get_var("SHOW TABLES LIKE '$TableRing'") != $TableRing)
          { $tmpMsg = esc_html__('Unable to open the database table containing the information for the ring','next-tiny-ring');
          }
       else
          { $tmpCurSiteURL = site_url();
            $tmpPos = strpos($tmpCurSiteURL,"//");
            $tmpCurName = substr($tmpCurSiteURL,$tmpPos+2);
            
            $TableWeb = 'ntrng_RingWeb';
            $tmpSQL = $mydb->prepare("SELECT id FROM $TableWeb WHERE web = %s",$tmpCurName);
            $ad_idCurSite = $mydb->get_var($tmpSQL);

            $tmpSQL = $mydb->prepare("SELECT id, idweb, link, title, description FROM $TableRing ORDER BY RAND() LIMIT 1");
            $resultsWeb = $mydb->get_results($tmpSQL);
            foreach ($resultsWeb as $detailsAds)
                    { $ad_idweb = $detailsAds->idweb;
                      $tmpOpt = get_option('optBannerAd_'.$ad_idweb);
                      
                      if($tmpOpt and ($ad_idweb != $ad_idCurSite))
                        { $ad_link = $detailsAds->link;
                          $ad_title = $detailsAds->title;
                          $ad_desc = $detailsAds->description;
                          ntrng_DrawBanner('',$ad_title,$ad_desc,$ad_link,$tmpColBgWeb);
                        }
                      else
                        { ntrng_DrawBanner('',NTRNG_DEFAULT_TITLE,NTRNG_DEFAULT_DESC,NTRNG_DEFAULT_LINK,$tmpColBgWeb);
                        }
                    }
            }
     }
}

function ntrng_DrawBanner($parId,$parTitle,$parDesc,$parLink,$parBgWeb)
{ $opt_ColBg = get_option('optColBg','#ffffff');
  $opt_BorderColor = get_option('optBorderColor','#00ff00');
  $opt_BorderStyle = get_option('optBorderStyle',1);
  $opt_ShowLink = get_option('optShowLink');
  $opt_ColLink = get_option('optColLink','#0000C0');
  $opt_ColTitle = get_option('optColTitle','#004000');
  $opt_ColDesc = get_option('optColDesc','#404040');
  $opt_RadiusStyle = get_option('optRadiusStyle',1);
  $opt_BorderSize = get_option('optBorderSize',3);
  
  $optColBgHeader = get_option('optColBgHeader','#ffffff');
  $optColBgBody = get_option('optColBgBody','#ffffff');
  $optColBgFooter = get_option('optColBgFooter','#ffffff');

  $opt_AdsWidth = ($parId < 0?"200px":"450px");

  if ($parId == "") echo "<center>";
  $tmpStyleBG = ($parBgWeb != "")?"background: " . esc_attr($parBgWeb) . "; ":"";
  echo "<table style='" . esc_attr($tmpStyleBG) . "width:" . esc_attr($opt_AdsWidth) . "; border-collapse: separate;'>";
                          
  switch ($opt_RadiusStyle)
         { case 0: $opt_AdsRadius = ""; break;
           case 1: $opt_AdsRadius = "10px"; break;
           case 2: $opt_AdsRadius = "25px"; break;
           case 3: $opt_AdsRadius = "5px 20px 5px"; break;
           case 4: $opt_AdsRadius = "50%"; break;
           case 5: $opt_AdsRadius = "10px / 30px"; break;
           case 6: $opt_AdsRadius = "25% 10%"; break;
           case 7: $opt_AdsRadius = "40px 999em 999em 40px"; break;
          default: $opt_AdsRadius = ""; break;
         } 

  switch ($opt_BorderStyle)
         { case 1: $tmpBorderStyle = "hidden"; break;
           case 2: $tmpBorderStyle = "dotted"; break;
           case 3: $tmpBorderStyle = "dashed"; break;
           case 4: $tmpBorderStyle = "solid";  break;
          default: $tmpBorderStyle = "hidden"; break;
         }
                      
  echo "<tr style='background: transparent !important;'><td style='background:" . esc_attr($opt_ColBg) . "; color:" . esc_attr($opt_BorderColor) . "; border:" . esc_attr($opt_BorderSize) . "px; border-style:" . esc_attr($tmpBorderStyle) . "; border-radius:" . esc_attr($opt_AdsRadius) . "; text-align:center; padding: 1px;'>";

  echo "<a";
  if ($parId > 0) echo " id=\"link_" . esc_attr($parId) . "\"";
  echo " title=\"" . esc_attr($parTitle) . "\" class='ntrng-banner' href='" . esc_attr($parLink) . "' target='_blank'><b><font color='" . esc_attr($opt_ColTitle) . "'>";
   
  if ($parId > 0) echo "<div id='title_" . esc_attr($parId) . "'>";
  echo '<strong><font color="' . esc_attr($opt_ColTitle) . '">' . esc_attr($parTitle) . '</font></strong>';
  if ($parId > 0)
     echo "</div></font></b>";
  else
     echo "</font></b><br>";

  echo "<em><font color='" . esc_attr($opt_ColDesc) . "'>";
  if ($parId > 0) echo "<div id='desc_" . esc_attr($parId) . "'>";
  echo esc_attr($parDesc);
  if ($parId > 0)
     echo "</div></font></em>";
  else
     echo "</font></em><br>";
  if (!$opt_ShowLink) echo "</a>";

  if ($opt_ShowLink)
     { echo "<font color='" . esc_attr($opt_ColLink) . "'>";
       if ($parId > 0) echo "<div id='url_" . esc_attr($parId) . "'>";
       echo esc_attr($parLink);
       if ($parId > 0) echo "</div>";
       echo "</font></a>";
     }
   
  echo "</td></tr>";
  if ($parId == "") echo "</center>";
  echo "</table>";
}

function ntrng_BannerSample()
{ $opt_ColBg = get_option('optColBg','#ffffff');
  $opt_BorderColor = get_option('optBorderColor','#00ff00');
  $opt_BorderStyle = get_option('optBorderStyle',1);
  $opt_ShowLink = get_option('optShowLink');
  $opt_ColLink = get_option('optColLink','#0000C0');
  $opt_ColTitle = get_option('optColTitle','#004000');
  $opt_ColDesc = get_option('optColDesc','#404040');
  $opt_RadiusStyle = sanitize_text_field($_REQUEST["RadiusStyle"]);
  $opt_BorderSize = get_option('optBorderSize',3);
  
  $opt_AdsWidth = "200px";

  echo "<table style='width:" . esc_attr($opt_AdsWidth) . "; border-collapse: separate;'>";
                         
  switch ($opt_RadiusStyle)
         { case 0: $opt_AdsRadius = ""; break;
           case 1: $opt_AdsRadius = "10px"; break;
           case 2: $opt_AdsRadius = "25px"; break;
           case 3: $opt_AdsRadius = "5px 20px 5px"; break;
           case 4: $opt_AdsRadius = "50%"; break;
           case 5: $opt_AdsRadius = "10px / 30px"; break;
           case 6: $opt_AdsRadius = "25% 10%"; break;
           case 7: $opt_AdsRadius = "40px 999em 999em 40px"; break;
          default: $opt_AdsRadius = ""; break;
         } 

  switch ($opt_BorderStyle)
         { case 1: $tmpBorderStyle = "hidden"; break;
           case 2: $tmpBorderStyle = "dotted"; break;
           case 3: $tmpBorderStyle = "dashed"; break;
           case 4: $tmpBorderStyle = "solid";  break;
          default: $tmpBorderStyle = "hidden"; break;
         }
                      
  $parTitle = esc_html__('Test','next-tiny-ring');
  $parDesc = esc_html__('This is a description test','next-tiny-ring');
  $parLink = "/";
  
  echo "<tr><td style='background:" . esc_attr($opt_ColBg) . "; color:" . esc_attr($opt_BorderColor) . "; border:" . esc_attr($opt_BorderSize) . "px; border-style:" . esc_attr($tmpBorderStyle) . "; border-radius:" . esc_attr($opt_AdsRadius) . "; text-align:center; padding: 1px;'>";
  echo "<a title=\"" . esc_attr($parTitle) . "\" class='ntrng-banner' href='" . esc_attr($parLink) . "' target='_blank'><b><font color='" . esc_attr($opt_ColTitle) . "'>";
  echo '<strong><font color="' . esc_attr($opt_ColTitle) . '">' . esc_attr($parTitle) . '</font></strong>';
  echo "</font></b><br>";
  echo "<em><font color='" . esc_attr($opt_ColDesc) . "'>" . esc_attr($parDesc) . "</font></em><br>";
  if (!$opt_ShowLink) echo "</a>";
  if ($opt_ShowLink) echo "<font color='" . esc_attr($opt_ColLink) . "'>" . esc_attr($parLink) . "</font></a>";
  echo "</td></tr></table>";
   
  wp_die();
}add_action('wp_ajax_ntrng_BannerSample','ntrng_BannerSample');


function ntrng_AddbannerHeader()
{ $opt_DisplayHeader = get_option('optDisplayHeader',0);

	if ($opt_DisplayHeader)
	   { $opt_DisplayFrontPage = get_option('optDisplayFrontPage',0);
	     if (!$opt_DisplayFrontPage)
 	        { if (is_front_page())
 	             return;
 	        }
        
       $tmpPageId = get_the_ID();

       $opt_DisplayExcept = get_option('optDisplayExcept',0);
       $opt_DisplayOnly = get_option('optDisplayOnly',0);
       if ($opt_DisplayExcept)
          { $opt_PageIdExcept = trim(get_option('optPageIdExcept'));
            $listPageId = explode(',',$opt_PageIdExcept);
            $tmpDisplay = !(in_array($tmpPageId, $listPageId));
          }
       elseif ($opt_DisplayOnly)
          { $opt_PageIdOnly = trim(get_option('optPageIdOnly'));
            $listPageId = explode(',',$opt_PageIdOnly);
            $tmpDisplay = in_array($tmpPageId, $listPageId);
	        } 
	     else
	          $tmpDisplay = true;                  
  
       if ($tmpDisplay)
	        { ntrng_DisplayBanner('Head');
     	      //echo "<hr>";
     	    }
	   }
}
add_action('wp_head','ntrng_AddbannerHeader');

function ntrng_AddbannerFooter()
{ $opt_DisplayFooter = get_option('optDisplayFooter',0);
	if ($opt_DisplayFooter)
	   { $opt_DisplayFrontPage = get_option('optDisplayFrontPage',0);
	     if (!$opt_DisplayFrontPage)
 	        { if (is_front_page())
 	             return;
 	        }
        
       $tmpPageId = get_the_ID();

       $opt_DisplayExcept = get_option('optDisplayExcept',0);
       $opt_DisplayOnly = get_option('optDisplayOnly',0);
       if ($opt_DisplayExcept)
          { $opt_PageIdExcept = trim(get_option('optPageIdExcept'));
            $listPageId = explode(',',$opt_PageIdExcept);
            $tmpDisplay = !(in_array($tmpPageId, $listPageId));
          }
       elseif ($opt_DisplayOnly)
          { $opt_PageIdOnly = trim(get_option('optPageIdOnly'));
            $listPageId = explode(',',$opt_PageIdOnly);
            $tmpDisplay = in_array($tmpPageId, $listPageId);
	        } 
	     else
	          $tmpDisplay = true;                  
  
       if ($tmpDisplay)
	        { //echo "<hr>";
	          ntrng_DisplayBanner('Foot');
     	    }
	   }
}

add_action('wp_footer','ntrng_AddbannerFooter');

function ntrng_show_banner($atts)
{ ntrng_DisplayBanner('Body');
} 
add_shortcode('next_tiny_ring','ntrng_show_banner');

function ntrng_AddBanner()
{ global $mydb;

  $TableRing = 'ntrng_RingInfo';
  $NewTitle = sanitize_text_field($_REQUEST["newTitle"]);
  $NewDesc = sanitize_text_field($_REQUEST["newDesc"]);
  $NewLink = sanitize_text_field($_REQUEST["newLink"]);
  
  $NewTitle = str_replace("\'","'",$NewTitle);
  $NewDesc = str_replace("\'","'",$NewDesc);
  
  $tmpCurSiteURL = site_url();
  $tmpLenSiteURL = strlen($tmpCurSiteURL); 
  
  if (substr($NewLink,0,$tmpLenSiteURL) != $tmpCurSiteURL)
     { echo "<font color=\"#ff0000\">" . esc_html__('The link must start with','next-tiny-ring') . " " . esc_attr($tmpCurSiteURL) . " !</font><br>";
     }
  else
     { $tmpPos = strpos($tmpCurSiteURL,"//");
       $tmpCurName = substr($tmpCurSiteURL,$tmpPos+2);
       $TableWeb = 'ntrng_RingWeb';

       $tmpSQL = $mydb->prepare("SELECT id FROM $TableWeb WHERE web = %s",$tmpCurName);
       $ad_idweb = $mydb->get_var($tmpSQL);
       if (!$ad_idweb)
          { if ($mydb->insert($TableWeb,array('web'=>$tmpCurName)) === false)
               { echo "<font color=\"#ff0000\">" . esc_html__('Cannot add website','next-tiny-ring') . "!</font><br>";
                 wp_die();
               }
            else
               { $tmpSQL = $mydb->prepare("SELECT id FROM $TableWeb WHERE web = %s",$tmpCurName);
                 $ad_idweb = $mydb->get_var($tmpSQL); 
               }
          }

       if ($mydb->insert($TableRing,array('idweb'=>$ad_idweb,'title'=>$NewTitle,'description'=>$NewDesc,'link'=>$NewLink)) === false)
          { echo "<font color=\"#ff0000\">" . esc_html__('Cannot add banner','next-tiny-ring') . "!</font><br>";
          }
       else
          { echo esc_html__('New banner added','next-tiny-ring') . ".<br>";
            echo "<em>" . esc_html__('Please refreh the page','next-tiny-ring') . ".</em><br>";
          }
     }
  wp_die();   
}
add_action('wp_ajax_ntrng_AddBanner','ntrng_AddBanner');

function ntrng_ModifyBanner()
{ global $mydb;

  $TableRing = 'ntrng_RingInfo';
  $idBanner = sanitize_text_field($_REQUEST["id"]);
  $NewTitle = sanitize_text_field($_REQUEST["newTitle"]); if (strlen($NewTitle) > 64) $NewTitle = substr($NewTitle,0,64);
  $NewDesc = sanitize_text_field($_REQUEST["newDesc"]);   if (strlen($NewDesc) > 128) $NewDesc = substr($NewDesc,0,128);
  $NewLink = sanitize_text_field($_REQUEST["newLink"]);   if (strlen($NewLink) > 64) $NewLink = substr($NewLink,0,64);
  $NewTitle = str_replace("\'","'",$NewTitle);
  $NewDesc = str_replace("\'","'",$NewDesc);

  if ($mydb->update($TableRing,array('title'=>$NewTitle,'description'=>$NewDesc,'link'=>$NewLink),array('id'=>$idBanner)) === false)
     { echo "<font color=\"#ff0000\">" . esc_html__('Cannot modify banner','next-tiny-ring') . "!</font><br>";
     }
  else
     { esc_html_e('Banner modified','next-tiny-ring') . ".<br>";
     }
  wp_die();  
}
add_action('wp_ajax_ntrng_ModifyBanner','ntrng_ModifyBanner');

function ntrng_DelBanner()
{ global $mydb;

  $TableRing = 'ntrng_RingInfo';
  $idBanner = sanitize_text_field($_REQUEST["id"]);

  if ($mydb->delete($TableRing,array('id'=>$idBanner)) === false)
     { echo "<font color=\"#ff0000\">" . esc_html__('Cannot delete banner','next-tiny-ring') . "!</font><br>";
     }
  /*else
     { esc_html_e('Banner deleted','next-tiny-ring') . ".<br>";
     }*/
  wp_die();  
}
add_action('wp_ajax_ntrng_DelBanner','ntrng_DelBanner');

function ntrng_ExportBanner($fd)
{ global $mydb;

  $TableRing = 'ntrng_RingInfo';
  $TableWeb = 'ntrng_RingWeb';
  
  if ($TableRing != "")
     { if ($mydb->get_var("SHOW TABLES LIKE '$TableRing'") != $TableRing)
          { $tmpMsg = esc_html__('Unable to open the database table containing the information for the ring','next-tiny-ring');
          }
       else
          { $tmpSQL = $mydb->prepare("SELECT idweb, link, title, description FROM $TableRing ORDER BY link"); 
            $resultsWeb = $mydb->get_results($tmpSQL);
            $cur_idweb = -1;
            $NbExported = 0;
            foreach ($resultsWeb as $detailsAds)
                    { $ad_idweb = $detailsAds->idweb;
                      if ($cur_idweb != $ad_idweb)
                         { if ($cur_idweb != -1) fwrite($fd,"\n");
                           $cur_idweb = $ad_idweb;
                           $tmpSQL2 = $mydb->prepare("SELECT web FROM $TableWeb WHERE id = %d",$ad_idweb);
                           $ad_web = $mydb->get_var($tmpSQL2);
                           fwrite($fd,$ad_web . "\n");
                         }
                      $ad_link = $detailsAds->link;
                      $ad_desc = $detailsAds->description;
                      $ad_title = $detailsAds->title;
                      fwrite($fd,"$ad_title;$ad_desc;$ad_link\n");
                      $NbExported++;
                    }
          }
     }
  return $NbExported;
}

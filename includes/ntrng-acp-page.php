<script>
function optBorderSize_SliderChange(val)
{ document.getElementById('optBorderSize').value = val; 
  document.getElementById('optBorderSizeId').innerHTML = val;
}

function ntrng_DisplayExcept_Change()
{ if (document.getElementById('optDisplayExcept').checked == true)
     { document.getElementById('divPageIdExcept').style.display = "";
       
       document.getElementById('optDisplayOnly').checked = false;
       document.getElementById('divPageIdOnly').style.display = "none";
     }
  else
     { document.getElementById('divPageIdExcept').style.display = "none";
     }
}

function ntrng_DisplayOnly_Change()
{ if (document.getElementById('optDisplayOnly').checked == true)
     { document.getElementById('divPageIdOnly').style.display = "";
     
       document.getElementById('optDisplayExcept').checked = false;
       document.getElementById('divPageIdExcept').style.display = "none";
     }
  else
     { document.getElementById('divPageIdOnly').style.display = "none";
     }
}

function js_ntrng_ShowModify(parId)
{ var e = document.getElementById('div_ShowModify_'+parId);

  if (e.style.display == "none")
     { const allElem = document.getElementsByClassName('divModify');
       for (const res of allElem)
           { res.style.display = 'none';
           }
     }
  
  document.getElementById('div_ShowAdd').style.display = "none";
  
  document.getElementById('div_BannerMsg').innerHTML = '';      
  if (e.style.display == "none")
     { e.style.display = "";
     }
  else
     { e.style.display = "none";
     }
}

function js_ntrng_ShowAdd()
{ const allElem = document.getElementsByClassName('divModify');
  for (const res of allElem)
           { res.style.display = 'none';
           }
           
  if (document.getElementById('div_ShowAdd').style.display == "none")
     { document.getElementById('div_BannerMsg').innerHTML = '';
       document.getElementById('div_ShowAdd').style.display = "";
     }
  else
     { document.getElementById('div_ShowAdd').style.display = "none";
     }
}

function js_ntrng_BannerSample(parRadius)
{ jQuery(document).ready(function($) {
	     var data = {
			 'action': 'ntrng_BannerSample',
	     'RadiusStyle': parRadius
		              };
       jQuery.post(ajaxurl,data,function(response) {
    	                          jQuery('.div_BannerSample').html(response);
		                                                });
	                                   });
}

function js_ntrng_AddBanner(parStrError,parStrTitle,parStrDesc,parStrLink)
{ var tmpNewTitle = document.getElementById('adTitle').value; tmpNewTitle = tmpNewTitle.trim();
  var tmpNewDesc = document.getElementById('adDesc').value;   tmpNewDesc = tmpNewDesc.trim();
  var tmpNewLink = document.getElementById('adLink').value;   tmpNewLink = tmpNewLink.trim();

  if (tmpNewTitle == "") alert(parStrTitle + ": " + parStrError + '!');
  if (tmpNewDesc == "") alert(parStrDesc + ": " + parStrError + '!');
  if (tmpNewLink == "") alert(parStrLink + ": " + parStrError + '!');
  
  if ((tmpNewTitle != "") && (tmpNewDesc != "") && (tmpNewLink != ""))
     { jQuery(document).ready(function($) {
	     var data = {
			 'action': 'ntrng_AddBanner',
	     'newTitle': tmpNewTitle,
	     'newDesc': tmpNewDesc,
	     'newLink': tmpNewLink
		              };
       jQuery.post(ajaxurl,data,function(response) {
                             var first5 = response.substring(0,5);
                             if (first5 != "<font")
                                { document.getElementById('div_ShowAdd').style.display = "none";
                                  //document.getElementById("B_ShowAdd").innerHTML = "+ Add new banner";
                                }
		                         jQuery('.div_BannerMsg').html(response);
		                                          });
	                                   });
	   }
}

function js_ntrng_ModifyBanner(parId,parStrError,parStrTitle,parStrDesc,parStrLink)
{ var tmpNewTitle = document.getElementById('adTitle_'+parId).value; tmpNewTitle = tmpNewTitle.trim();
  var tmpNewDesc = document.getElementById('adDesc_'+parId).value;   tmpNewDesc = tmpNewDesc.trim();
  var tmpNewLink = document.getElementById('adLink_'+parId).value;   tmpNewLink = tmpNewLink.trim();

  if (tmpNewTitle == "") alert(parStrTitle + ": " + parStrError + '!');
  if (tmpNewDesc == "") alert(parStrDesc + ": " + parStrError + '!');
  if (tmpNewLink == "") alert(parStrLink + ": " + parStrError + '!');

  if ((tmpNewTitle != "") && (tmpNewDesc != "") && (tmpNewLink != ""))
     { document.getElementById('title_'+parId).innerHTML = tmpNewTitle;
       document.getElementById('desc_'+parId).innerHTML = tmpNewDesc;
       document.getElementById('link_'+parId).href = tmpNewLink;
       document.getElementById('link_'+parId).title = tmpNewTitle;
  
	     jQuery(document).ready(function($) {
	     var data = {
	     'action': 'ntrng_ModifyBanner',
	     'newTitle': tmpNewTitle,
	     'newDesc': tmpNewDesc,
	     'newLink': tmpNewLink,
	     'id': parId
		              };
       jQuery.post(ajaxurl,data,function(response) {
	                    //jQuery('.div_BannerMsg_'+parId).html(response);
	                    jQuery('.div_BannerMsg').html(response);
	                                            });
	                                   });
	   }
}

function js_ntrng_DelBanner(parId,parStrQ)
{ var ret = false;
  ret = confirm(parStrQ + " ?");
  if (ret == true)
	   { document.getElementById('div_Banners_'+parId).style.display = "none";
	   
	     jQuery(document).ready(function($) {
	     var data = {
			 'action': 'ntrng_DelBanner',
			 'id': parId
		              };
       jQuery.post(ajaxurl,data,function(response) {
		                         jQuery('.div_Banners_'+parId).html(response);
		                                          });
	                                   });
     }
}
</script>

<?php
if (!defined('ABSPATH')) exit;
 
$ntrng_CurrentVersion = get_option('ntrngCurrentVersion');
$ntrng_CurrentType = get_option('ntrngCurrentType');
echo '<div align="right">' . esc_attr($ntrng_CurrentType) . ' Version v.' . esc_attr($ntrng_CurrentVersion) . '</div>';

if(!empty($_POST['do']) && check_admin_referer('B_export_clicked'))
  { switch($_POST['do'])
	        { case esc_html__('Export','next-tiny-ring'):
                 $retExported = 0;	        
	               ob_end_clean();
                 $fd = @fopen('php://output','w');
                 header("Content-disposition: attachment; filename = next-tiny-ring_" . $strDateTime . ".csv");
                 $retExported = ntrng_ExportBanner($fd);
                 fclose($fd);
                 ob_end_flush();
                 die();

             	   if ($retExported > 0)
	                  { $tmpMsg = esc_html__('Banner ads exported','next-tiny-ring') . ": $retExported";
                      ntdt_LogFile($tmpMsg,"info");
                      $retExported = 0;
                    }
                 break;
         
	           default:
	                 break;
	        }
  }
  
$tmpTab = sanitize_text_field($_GET['tab']);
$tab = (isset($tmpTab) and $tmpTab != "")?$tmpTab:'ntrng_settings';
if($tab==='ntrng_ads')
  { $tmpSection = sanitize_text_field($_GET['section']);
    $section = (isset($tmpSection) and $tmpSection != "")?$tmpSection:"myads";
  }

$opt_NameDB = get_option('optNameDB');
$opt_UserDB = get_option('optUserDB');
$opt_PassDB = get_option('optpassDB');

$opt_ColBg = get_option('optColBg','#ffffff');
$opt_BorderColor = get_option('optBorderColor','#00ff00');
$opt_BorderStyle = get_option('optBorderStyle',1);
$opt_ShowLink = get_option('optShowLink');
$opt_ColLink = get_option('optColLink','#0000C0');
$opt_ColTitle = get_option('optColTitle','#004000');
$opt_ColDesc = get_option('optColDesc','#404040');
$opt_RadiusStyle = get_option('optRadiusStyle',1);
$opt_BorderSize = get_option('optBorderSize',3);

$opt_ColBgHeader = get_option('optColBgHeader','#ffffff');
$opt_ColBgBody = get_option('optColBgBody','#ffffff');
$opt_ColBgFooter = get_option('optColBgFooter','#ffffff');
$opt_UseBgHeader = get_option('optUseBgHeader',0);
$opt_UseBgBody = get_option('optUseBgBody',0);
$opt_UseBgFooter = get_option('optUseBgFooter',0);
               
$opt_DisplayHeader = get_option('optDisplayHeader',0);
$opt_DisplayFooter = get_option('optDisplayFooter',1);

$opt_DisplayFrontPage = get_option('optDisplayFrontPage',0);
$opt_DisplayExcept = get_option('optDisplayExcept',0);
$opt_PageIdExcept = get_option('optPageIdExcept');
$opt_DisplayOnly = get_option('optDisplayOnly',0);
$opt_PageIdOnly = get_option('optPageIdOnly');

global $mydb;
$TableRing = "";
if ($opt_NameDB != "")
   { $DB_HOST = "localhost";
     $DB_NAME = $opt_NameDB;
     $DB_USER = $opt_UserDB;
     $DB_PASS = $opt_PassDB;
     $mydb = new wpdb($DB_USER,$DB_PASS,"herboris_ring",$DB_HOST);
     
     //--- Check connect:
     if ($mydb->connect_error)
        { die("DB connect error: " . $mydb->connect_error);
        }
     
     $TableWeb = 'ntrng_RingWeb'; 
     $sql = "CREATE TABLE IF NOT EXISTS $TableWeb (
	                id INT(6) UNSIGNED AUTO_INCREMENT,
		              web VARCHAR(127) NOT NULL,
		              PRIMARY KEY  (id)
	                );";
	   if ($mydb->query($sql) !== TRUE)
        { echo esc_html__('Error creating table Ring Web','next-tiny-ring') . ": " . esc_attr($mydb->error) . "<br>";
        }
        
     $TableRing = 'ntrng_RingInfo'; 
     $sql = "CREATE TABLE IF NOT EXISTS $TableRing (
	                id INT(6) UNSIGNED AUTO_INCREMENT,
		              idweb INT(6) NOT NULL,
		              link VARCHAR(127) NOT NULL,
		              title VARCHAR(63) NOT NULL,
		              description VARCHAR(255) NOT NULL,
		              PRIMARY KEY  (id)
	                );";
	   if ($mydb->query($sql) !== TRUE)
        { echo esc_html__('Error creating table Ring Information','next-tiny-ring') . ": " . esc_attr($mydb->error) . "<br>";
        }
   }
else
   { $tmpMsg = esc_html__('Enter the information of the empty database (with no table) you have created first somewhere on your server to share the future information for the ring','next-tiny-ring');
     ntrng_LogFile($tmpMsg . ".","warning");
   }

function ntrng_LogFile($parMsg,$parNoticeType)
{ echo "<div class=\"notice notice-" . esc_attr($parNoticeType) . " is-dismissible\"><p>" . esc_attr($parMsg) . "</p></div>";
}
?>

<div class="wrap">
<nav class="nav-tab-wrapper">
     <a href="?page=ntrng-acp&tab=ntrng_settings" class="nav-tab <?php if($tab==='ntrng_settings'):?>nav-tab-active<?php endif; ?>"><?php esc_html_e('General Settings','next-tiny-ring'); ?></a>
     <a href="?page=ntrng-acp&tab=ntrng_ads" class="nav-tab <?php if($tab==='ntrng_ads'):?>nav-tab-active<?php endif; ?>"><?php esc_html_e('Banner Ads','next-tiny-ring'); ?></a>
     <a href="?page=ntrng-acp&tab=ntrng_help" class="nav-tab <?php if($tab==='ntrng_help'):?>nav-tab-active<?php endif; ?>"><?php esc_html_e('Help','next-tiny-ring'); ?></a>
</nav>

    <div class="tab-content">
    <?php switch($tab)
          { case 'ntrng_settings': ?> 
          
    <form method="post" action="options.php">
    <?php settings_fields('ntrng-settings-group'); ?>
    <?php do_settings_sections('ntrng-settings-group'); ?>
  
    <h2 class="title"><?php esc_html_e('Database','next-tiny-ring'); ?> <span class="dashicons dashicons-database"></span></h2>
    <table class="form-table">   
        <tr>
        <th scope="row"><?php esc_html_e('Name','next-tiny-ring'); ?></th> 
            <td><input type="text" size="32" maxlength="64" name="optNameDB" value="<?php echo esc_attr($opt_NameDB);?>"> <?php esc_html_e('Name of your shared ring database','next-tiny-ring'); ?>
            <br><em><font color="#808080"><?php echo esc_html__('Enter the name of your shared ring database','next-tiny-ring');?>.</font></em>
            <div class="tooltip">
            <span style="position: absolute; top: -15px;" class="dashicons dashicons-info-outline">
                   <span class="tooltiptext"><?php esc_html_e('You must have created before a database to share the information for the ring','next-tiny-ring'); ?>.</span>
            </span>
           </div>
        </td></tr>

        <tr>
        <th scope="row"><?php esc_html_e('User','next-tiny-ring'); ?></th> 
            <td><input type="text" size="32" maxlength="64" name="optUserDB" value="<?php echo esc_attr($opt_UserDB);?>"> <?php esc_html_e('User of your shared ring database','next-tiny-ring'); ?>
            <br><em><font color="#808080"><?php echo esc_html__('Enter the user of your shared ring database','next-tiny-ring');?>.</font></em>
        </td></tr>

        <tr>
        <th scope="row"><?php esc_html_e('Password','next-tiny-ring'); ?></th> 
            <td><input type="text" size="32" maxlength="64" name="optPassDB" value="<?php echo esc_attr($opt_PassDB);?>"> <?php esc_html_e('Password of your shared ring database','next-tiny-ring'); ?>
            <br><em><font color="#808080"><?php echo esc_html__('Enter the password of your shared ring database','next-tiny-ring');?>.</font></em>
        </td></tr>
    </table>
        
    <h2 class="title"><?php esc_html_e('Banner Ads','next-tiny-ring'); ?> <span class="dashicons dashicons-flag"></span></h2>
    <table class="form-table">   
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Background color','next-tiny-ring'); ?></th> 
        <td>
        <input type="color" name="optColBg" value="<?php echo esc_attr($opt_ColBg); ?>" class="xxx" />
        </td></tr>
        
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Border color','next-tiny-ring'); ?></th> 
        <td>
        <input type="color" name="optBorderColor" value="<?php echo esc_attr($opt_BorderColor); ?>" class="xxx" /> 
        </td></tr>

        <tr valign="top">
        <th scope="row"><?php esc_html_e('Border style','next-tiny-ring'); ?></th>
        <td><input type="radio" name="optBorderStyle" value=1 <?php echo($opt_BorderStyle==1?"checked ":"");?> /> <?php esc_html_e('Hidden','next-tiny-ring'); ?><br>
            <input type="radio" name="optBorderStyle" value=2 <?php echo($opt_BorderStyle==2?"checked ":"");?> /> <?php esc_html_e('Dotted','next-tiny-ring'); ?><br>
            <input type="radio" name="optBorderStyle" value=3 <?php echo($opt_BorderStyle==3?"checked ":"");?> /> <?php esc_html_e('Dashed','next-tiny-ring'); ?><br>
            <input type="radio" name="optBorderStyle" value=4 <?php echo($opt_BorderStyle==4?"checked ":"");?> /> <?php esc_html_e('Solid','next-tiny-ring'); ?><br>
        </td></tr>

        <tr valign="top">
        <th scope="row"><?php esc_html_e('Border size','next-tiny-ring'); ?></th>
        <td><div style="display: inline-block; color:#0250BB;" align="center" id="optBorderSizeId"><em><?php echo esc_attr($opt_BorderSize) ?></em></div>
            <input type="range" oninput="optBorderSize_SliderChange(this.value);" id="optBorderSize_slider" name="optAlphaWM_slider" style="width:50%;margin-bottom:0px;" min="1" max="10" value="<?php echo esc_attr($opt_BorderSize) ?>"/>
            <input type="hidden" id="optBorderSize" name="optBorderSize" pattern="[0-9]{1,2}" size=2 min="1" max="10" maxlength="2" value="<?php echo esc_attr($opt_BorderSize) ?>"/>
        </td></tr> 
        
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Title color','next-tiny-ring'); ?></th> 
        <td>
        <input type="color" name="optColTitle" value="<?php echo esc_attr($opt_ColTitle); ?>" class="xxx" />
        </td></tr>
        
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Description color','next-tiny-ring'); ?></th> 
        <td>
        <input type="color" name="optColDesc" value="<?php echo esc_attr($opt_ColDesc); ?>" class="xxx" />
        </td></tr>

        <tr valign="top">
        <th scope="row"><?php esc_html_e('Link color','next-tiny-ring'); ?></th> 
        <td>
        <input type="color" name="optColLink" value="<?php echo esc_attr($opt_ColLink); ?>" class="xxx" />
        </td></tr>
        
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Show link','next-tiny-ring'); ?></th> 
        <td valign="top"><input type="checkbox" name="optShowLink" value=1 <?php echo($opt_ShowLink==1?"checked ":"");?>class="wppd-ui-toggle" /> <?php esc_html_e('Display the link in banner ad','next-tiny-ring'); ?>.
        </td></tr>

        <tr valign="top">
        <th scope="row"><?php esc_html_e('Shape style','next-tiny-ring'); ?></th>
        <td width="40%">
            <input type="radio" onclick="js_ntrng_BannerSample('0')" name="optRadiusStyle" value=0 <?php echo($opt_RadiusStyle==0?"checked ":"");?> /> <?php esc_html_e('None','next-tiny-ring'); ?><br>
            <input type="radio" onclick="js_ntrng_BannerSample('1')" name="optRadiusStyle" value=1 <?php echo($opt_RadiusStyle==1?"checked ":"");?> /> <?php esc_html_e('4 rounded corners','next-tiny-ring'); ?><br>
            <input type="radio" onclick="js_ntrng_BannerSample('2')" name="optRadiusStyle" value=2 <?php echo($opt_RadiusStyle==2?"checked ":"");?> /> <?php esc_html_e('4 more rounded corners','next-tiny-ring'); ?><br>
            <input type="radio" onclick="js_ntrng_BannerSample('3')" name="optRadiusStyle" value=3 <?php echo($opt_RadiusStyle==3?"checked ":"");?> /> <?php esc_html_e('2 opposite rounded corners','next-tiny-ring'); ?><br>
            <input type="radio" onclick="js_ntrng_BannerSample('4')" name="optRadiusStyle" value=4 <?php echo($opt_RadiusStyle==4?"checked ":"");?> /> <?php esc_html_e('Eliptical','next-tiny-ring'); ?><br>
            <input type="radio" onclick="js_ntrng_BannerSample('5')" name="optRadiusStyle" value=5 <?php echo($opt_RadiusStyle==5?"checked ":"");?> /> <?php esc_html_e('Bounced vertical edge','next-tiny-ring'); ?><br>
            <input type="radio" onclick="js_ntrng_BannerSample('6')" name="optRadiusStyle" value=6 <?php echo($opt_RadiusStyle==6?"checked ":"");?> /> <?php esc_html_e('Asymmetrical','next-tiny-ring'); ?><br>
            <input type="radio" onclick="js_ntrng_BannerSample('7')" name="optRadiusStyle" value=7 <?php echo($opt_RadiusStyle==7?"checked ":"");?> /> <?php esc_html_e('Flag','next-tiny-ring'); ?><br>
        </td>
        
        <td align="left">
            <div class="div_BannerSample">
            <?php ntrng_DrawBanner('-1',esc_html__('Test','next-tiny-ring'),esc_html__('This is a description test','next-tiny-ring'),'/',''); ?>
            </div>
        </td></tr>
    
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Website background colors','next-tiny-ring'); ?></th> 
        <td>
        <input type="color" name="optColBgHeader" value="<?php echo esc_attr($opt_ColBgHeader); ?>" /><input type="checkbox" name="optUseBgHeader" value=1 <?php echo($opt_UseBgHeader==1?"checked ":"");?>class="wppd-ui-toggle" /> <?php esc_html_e('Use Website background color of header','next-tiny-ring'); ?><br>
        <input type="color" name="optColBgBody" value="<?php echo esc_attr($opt_ColBgBody); ?>" /><input type="checkbox" name="optUseBgBody" value=1 <?php echo($opt_UseBgBody==1?"checked ":"");?>class="wppd-ui-toggle" /> <?php esc_html_e('Use Website background color of body','next-tiny-ring'); ?><br>
        <input type="color" name="optColBgFooter" value="<?php echo esc_attr($opt_ColBgFooter); ?>" /><input type="checkbox" name="optUseBgFooter" value=1 <?php echo($opt_UseBgFooter==1?"checked ":"");?>class="wppd-ui-toggle" /> <?php esc_html_e('Use Website background color of footer','next-tiny-ring'); ?><br>
        <em><font color="#808080"><?php esc_html_e('Enter the website background color of each area','next-tiny-ring') ?>.<br>
                                  (<?php esc_html_e('If using a banner with a shape style only','next-tiny-ring'); ?>.)</font></em>
        </td></tr>    
    </table>
   
    <h2 class="title"><?php esc_html_e('Display','next-tiny-ring'); ?> <span class="dashicons dashicons-align-center"></span></h2>
    <table class="form-table">   
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Display location','next-tiny-ring'); ?></th> 
        <td valign="top"><input type="checkbox" name="optDisplayHeader" value=1 <?php echo($opt_DisplayHeader==1?"checked ":"");?>class="wppd-ui-toggle" /> <?php esc_html_e('Top of page','next-tiny-ring'); ?>
             <div class="tooltip">
             <span style="position: absolute; top: -15px;" class="dashicons dashicons-info-outline">
                   <span class="tooltiptext"><?php esc_html_e('Check to display a banner on your page headers','next-tiny-ring'); ?>.</span>
             </span>
            </div>
            <br>
            <input type="checkbox" name="optDisplayFooter" value=1 <?php echo($opt_DisplayFooter==1?"checked ":"");?>class="wppd-ui-toggle" /> <?php esc_html_e('Bottom of page','next-tiny-ring'); ?>
                         <div class="tooltip">
             <span style="position: absolute; top: -15px;" class="dashicons dashicons-info-outline">
                   <span class="tooltiptext"><?php esc_html_e('Check to display a banner on your page footers','next-tiny-ring'); ?>.</span>
             </span>
            </div>
            <br>
            <em><font color="#808080"><?php esc_html_e('Insert the shortcode [next_tiny_ring] into a widget or a page to display a banner anywhere in your page','next-tiny-ring'); ?>.</font></em>
        </td></tr>
        
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Display pages','next-tiny-ring'); ?></th> 
        <td><input type="checkbox" name="optDisplayFrontPage" value=1 <?php echo($opt_DisplayFrontPage==1?"checked ":"");?>class="wppd-ui-toggle" /> <?php esc_html_e('Check to display a banner on your front page','next-tiny-ring'); ?><br>
            
            <input type="checkbox" onChange="ntrng_DisplayExcept_Change('<?php echo esc_attr($opt_DisplayExcept);?>');" id="optDisplayExcept" name="optDisplayExcept" value=1 <?php echo($opt_DisplayExcept==1?"checked ":"");?>class="wppd-ui-toggle" /> <?php esc_html_e('Check to display a banner on all pages except the following','next-tiny-ring'); ?>:<br>
            <?php
            $tmpVal = ""; if(!$opt_DisplayExcept) $tmpVal = "none";
            echo '<div id="divPageIdExcept" name="divPageIdExcept" style="display:' . esc_attr($tmpVal) . ';">';
            esc_html_e('Page Ids','next-tiny-ring'); ?> <input type="text" size="64" maxlength="256" id="optPageIdExcept" name="optPageIdExcept" value="<?php echo esc_attr($opt_PageIdExcept);?>">
            <br>
            <em><font color="#808080"><?php esc_html_e('Insert page Ids separated by a colon.','next-tiny-ring'); ?></font></em>
            </div>
            
            <input type="checkbox" onChange="ntrng_DisplayOnly_Change('<?php echo esc_attr($opt_DisplayExcept);?>');" id="optDisplayOnly" name="optDisplayOnly" value=1 <?php echo($opt_DisplayOnly==1?"checked ":"");?>class="wppd-ui-toggle" /> <?php esc_html_e('Check to display a banner only on the following pages','next-tiny-ring'); ?>:       
            <?php
            $tmpVal = ""; if(!$opt_DisplayOnly) $tmpVal = "none";
            echo '<div id="divPageIdOnly" name="divPageIdOnly" style="display:' . esc_attr($tmpVal) . ';">';
            esc_html_e('Page Ids','next-tiny-ring'); ?> <input type="text" size="64" maxlength="256" id="optPageIdOnly" name="optPageIdOnly" value="<?php echo esc_attr($opt_PageIdOnly);?>">
            <br>
            <em><font color="#808080"><?php esc_html_e('Insert page Ids separated by a colon.','next-tiny-ring'); ?></font></em>
            </div>
        </td></tr>

        <?php $NbAds = $mydb->get_var("SELECT COUNT(*) FROM $TableWeb"); ?>
        <tr valign="top">
        <th scope="row"><?php echo esc_html__('Display websites','next-tiny-ring') . " [" . esc_attr($NbAds) . "]"; ?></th> 
        <td>
        <?php
        if ($TableRing != "")
           { if ($mydb->get_var("SHOW TABLES LIKE '$TableWeb'") != $TableWeb)
                { $tmpMsg = esc_html__('Unable to open the database table containing the information for the ring','next-tiny-ring');
                  ntrng_LogFile($tmpMsg . "!","error");
                }
             else
                { $tmpCurSiteURL = site_url();
                  $tmpPos = strpos($tmpCurSiteURL,"//");
                  $tmpSiteName = substr($tmpCurSiteURL,$tmpPos+2);
                  $tmpSQL = $mydb->prepare("SELECT id, web FROM $TableWeb ORDER BY web ASC");
                  $resultsWeb = $mydb->get_results($tmpSQL);
                  foreach ($resultsWeb as $detailsAds)
                          { $ad_id = $detailsAds->id;
                            $ad_web = $detailsAds->web;
                            $tmpOpt = get_option('optBannerAd_'.$ad_id,1);
                            if ($ad_web != $tmpSiteName)
                               { echo '<input type="checkbox" name="optBannerAd_' . esc_attr($ad_id) . '" value=1 ' . ($tmpOpt==1?"checked ":"") . 'class="wppd-ui-toggle" /> ' . esc_attr($ad_web) . ' (id:' . esc_attr($ad_id) . ')<br>'; 
                               }
                          }
                  esc_html_e('Uncheck to disable the display of some websites','next-tiny-ring');

                  echo '<br><font color="#808080">' . esc_html__('Excluding','next-tiny-ring') . ' <em>' . esc_attr($tmpSiteName) . '</em></font></em><br>';
                }
           }
        ?> 
        </td></tr>
    </table>
    <?php submit_button(esc_html__('Save','next-tiny-ring')); ?>
</form>
<?php       
         break;
         

    case 'ntrng_ads': 
          switch ($section)
                 { case 'myads':?>
                        <h1 class="screen-reader-text">Banner Ads</h1>
		                    <ul class="subsubsub"><li><a href="/wp-admin/admin.php?page=ntrng-acp&amp;tab=ntrng_ads&amp;section=myads" class="current"><?php esc_html_e('My banner ads','next-tiny-ring'); ?></a> | </li>
		                                          <li><a href="/wp-admin/admin.php?page=ntrng-acp&amp;tab=ntrng_ads&amp;section=others" class=""><?php esc_html_e('Other banner ads','next-tiny-ring'); ?></a> </li>
		                    </ul><br class="clear">
    
         <h2 class="title"><?php esc_html_e('View','next-tiny-ring'); ?> <span class="dashicons dashicons-visibility"></span></h2>
     
         <table class="form-table">
         <tr valign="top">
         <?php
         $tmpCurSiteURL = site_url();
         $tmpPos = strpos($tmpCurSiteURL,"//");
         $tmpCurName = substr($tmpCurSiteURL,$tmpPos+2);
         echo "<th>" . esc_attr($tmpCurName) . "</th>";
         echo "<td align='left'>";
         $tmpSQL = $mydb->prepare("SELECT id FROM $TableWeb WHERE web = %s",$tmpCurName);
         $ad_idCurSite = $mydb->get_var($tmpSQL);
         
         $tmpSQL = $mydb->prepare("SELECT id, link, title, description FROM $TableRing WHERE idweb = %d",$ad_idCurSite);
         $resultsWeb = $mydb->get_results($tmpSQL);
         foreach ($resultsWeb as $detailsAds)
                 { $ad_id = $detailsAds->id;
                   $ad_title = $detailsAds->title;      $tmpTitle = (strlen($ad_title) > 64)?substr($ad_title,0,64):$ad_title;
                   $ad_desc = $detailsAds->description; $tmpDesc = (strlen($ad_desc) > 64)?substr($ad_desc,0,64):$ad_desc;
                   $ad_link = $detailsAds->link;        $tmpLink = (strlen($ad_link) > 64)?substr($ad_link,0,64):$ad_link;
                   echo '<div id="div_Banners_' . esc_attr($ad_id) . '">';
                   ntrng_DrawBanner($ad_id,$tmpTitle,$tmpDesc,$tmpLink,''); ?>
                   <button title="<?php esc_html_e('Modify this banner ad','next-tiny-ring'); ?>" onclick="js_ntrng_ShowModify('<?php echo esc_attr($ad_id);?>')"><font color="#2271B1"><span class="dashicons dashicons-edit"></span></font></button>
                   <button title="<?php esc_html_e('Delete this banner ad','next-tiny-ring'); ?>" onclick="js_ntrng_DelBanner('<?php echo esc_attr($ad_id);?>','<?php esc_html_e('Delete banner','next-tiny-ring'); ?>')"><font color="#ff0000"><span class="dashicons dashicons-no-alt"></span></font></button><br>
                   </div>
                   
                   <?php
                   echo '<div class="divModify" id="div_ShowModify_'  . esc_attr($ad_id) . '" style="display: none;">';
                   ?>
                   <table>
                   <tr><td><?php esc_html_e('Title','next-tiny-ring'); ?></td>
                       <td><input size="50" maxlength="64" value="<?php echo esc_attr($tmpTitle)?>" id="adTitle_<?php echo esc_attr($ad_id)?>" type="text">
                           <em><font color="#808080"><?php echo "64 " . esc_html__('characters max.','next-tiny-ring');?></font></em>
                   </td></tr>
                   <tr><td><?php esc_html_e('Description','next-tiny-ring'); ?></td>
                       <td><input size="50" maxlength="128" value="<?php echo esc_attr($tmpDesc)?>" id="adDesc_<?php echo esc_attr($ad_id)?>" type="text">
                           <em><font color="#808080"><?php echo "128 " . esc_html__('characters max.','next-tiny-ring');?></font></em>
                   </td></tr>
                   <tr><td><?php esc_html_e('Link','next-tiny-ring'); ?></td>
                       <td><input size="50" maxlength="64" value="<?php echo esc_attr($tmpLink)?>" id="adLink_<?php echo esc_attr($ad_id)?>" type="text">
                           <em><font color="#808080"><?php echo "64 " . esc_html__('characters max.','next-tiny-ring');?></font></em>
                   <tr><td colspan="2"><button title="<?php esc_html_e('Modify banner ad','next-tiny-ring'); ?>" onclick="js_ntrng_ModifyBanner('<?php echo esc_attr($ad_id)?>','<?php esc_html_e('Empty field','next-tiny-ring'); ?>','<?php esc_html_e('Title','next-tiny-ring'); ?>','<?php esc_html_e('Description','next-tiny-ring'); ?>','<?php esc_html_e('Link','next-tiny-ring'); ?>')"><?php esc_html_e('Modify','next-tiny-ring'); ?></button>
                   </td></tr>
                   </table>
                   </div>
                   <?php      
                 }
         ?>
         
         <br>
         <button id="B_ShowAdd" title="<?php esc_html_e('Add a new banner ad','next-tiny-ring'); ?>" onclick="js_ntrng_ShowAdd()"><font color="#008000"><span class="dashicons dashicons-plus"></span></font></button>
         
         <?php
          $tmpCurSiteURL = site_url();
         ?>
         <div id="div_ShowAdd" style="display: none;">
         <table>
         <tr><td><?php esc_html_e('Title','next-tiny-ring'); ?></td>
             <td><input maxlength="64" id="adTitle" type="text"><em><font color="#808080"><?php echo "64 " . esc_html__('characters max.','next-tiny-ring');?></font></em>
             </td></tr>
         <tr><td><?php esc_html_e('Description','next-tiny-ring'); ?></td>
             <td><input maxlength="128" id="adDesc" type="text"><em><font color="#808080"><?php echo "128 " . esc_html__('characters max.','next-tiny-ring');?></font></em>
             </td></tr>
         <tr><td><?php esc_html_e('Link','next-tiny-ring'); ?></td>
             <td><input maxlength="64" value="<?php echo esc_attr($tmpCurSiteURL) . "/";?>" id="adLink" type="text"><em><font color="#808080"><?php echo "64 " . esc_html__('characters max.','next-tiny-ring');?></font></em>
         <tr><td colspan="2"><button title="<?php esc_html_e('Add a new banner ad','next-tiny-ring'); ?>" onclick="js_ntrng_AddBanner('<?php esc_html_e('Empty field','next-tiny-ring'); ?>','<?php esc_html_e('Title','next-tiny-ring'); ?>','<?php esc_html_e('Description','next-tiny-ring'); ?>','<?php esc_html_e('Link','next-tiny-ring'); ?>')"><?php esc_html_e('Add','next-tiny-ring'); ?></button>
             </td></tr>
         </table>
         </div>
         
         <div id="div_BannerMsg" class="div_BannerMsg"></div>
         
         </td></tr>   
         </table>
         <?php
                   break;



                        
                   default:?>
                        <h1 class="screen-reader-text">Banner Ads</h1>
		                    <ul class="subsubsub"><li><a href="/wp-admin/admin.php?page=ntrng-acp&amp;tab=ntrng_ads&amp;section=myads" class=""><?php esc_html_e('My banner ads','next-tiny-ring'); ?></a> | </li>
		                                          <li><a href="/wp-admin/admin.php?page=ntrng-acp&amp;tab=ntrng_ads&amp;section=others" class="current"><?php esc_html_e('Other banner ads','next-tiny-ring'); ?></a> </li>
		                    </ul><br class="clear">
         
         <form method="post" action="options.php">
         <?php settings_fields('ntrng-ads-group'); ?>
         <?php do_settings_sections('ntrng-ads-group'); ?>
    
         <h2 class="title"><?php esc_html_e('View','next-tiny-ring'); ?> <span class="dashicons dashicons-visibility"></span></h2>
     
         <table class="form-table">
         <?php
         $tmpCurSiteURL = site_url();
         $tmpPos = strpos($tmpCurSiteURL,"//");
         $tmpCurName = substr($tmpCurSiteURL,$tmpPos+2);
            
         $tmpSQL = $mydb->prepare("SELECT id FROM $TableWeb WHERE web = %s",$tmpCurName);
         $ad_idCurSite = $mydb->get_var($tmpSQL);
         
         $tmpSQL = $mydb->prepare("SELECT idweb, link, title, description FROM $TableRing WHERE idweb != %d ORDER BY link",$ad_idCurSite); 
         $resultsWeb = $mydb->get_results($tmpSQL);
         $cur_idweb = -1;
         foreach ($resultsWeb as $detailsAds)
                 { $ad_idweb = $detailsAds->idweb;
                   if ($cur_idweb != $ad_idweb)
                      { $cur_idweb = $ad_idweb;
                        $tmpSQL2 = $mydb->prepare("SELECT web FROM $TableWeb WHERE id = %d",$ad_idweb);
                        $ad_web = $mydb->get_var($tmpSQL2);
                        if ($cur_idweb != -1) echo '</td></tr>';
                        echo '<tr valign="top">';
                        echo "<th>" . esc_attr($ad_web) . "</th>";
                        echo '<td align="left">';
                      }
                   $ad_link = $detailsAds->link;
                   $ad_desc = $detailsAds->description;
                   $ad_title = $detailsAds->title;
                   ntrng_DrawBanner('NOT',$ad_title,$ad_desc,$ad_link,'');
                 }
         ?>   
         </td></tr>   
         </table>
         </form> 
         <?php

                   
                        break;
                 }
                 
         ?>
         <form method="post" action="admin.php?page=ntrng-acp">
               <?php wp_nonce_field("B_export_clicked"); ?>
               <input type="submit" name="do" value="<?php esc_html_e('Export','next-tiny-ring'); ?>" title="<?php esc_html_e('Export all banner ads in a .CSV file','next-tiny-ring'); ?>" class="button" />
         </form>
         <?php
         break;
  
         
                  
    case 'ntrng_help': ?> 
    <h2 class="title"><?php esc_html_e('Shortcode','next-tiny-ring'); ?> <span class="dashicons dashicons-sos"></span></h2>
    
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php esc_html_e('Example','next-tiny-ring'); ?></th> 
        <td>[next_tiny_ring]
            <br><em><font color="#808080"><?php esc_html_e('Insert the shortcode into a widget or a page to display an advertising banner','next-tiny-ring'); ?></font></em><br>
        </td></tr>
    </table>
         
    <?php
         break;

    default:
         break;
        } ?>
  </div>
</div>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo get_page_language($language); ?>" xml:lang="<?php echo get_page_language($language); ?>">

<head>
  <title><?php if (isset($head_title )) { echo $head_title; } ?></title>
  <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
  <?php echo $head; ?>  
  <?php echo $styles ?>
  <?php echo $scripts ?>
  <!--[if IE 6]><link rel="stylesheet" href="<?php echo $base_path . $directory; ?>/style.ie6.css" type="text/css" /><![endif]-->  
  <!--[if IE 7]><link rel="stylesheet" href="<?php echo $base_path . $directory; ?>/style.ie7.css" type="text/css" media="screen" /><![endif]-->
  <script type="text/javascript"><?php /* Needed to avoid Flash of Unstyle Content in IE */ ?> </script>
</head>

<body>
<div id="art-page-background-simple-gradient">
</div>
<div id="art-main">
<div class="art-Sheet">
    <div class="art-Sheet-tl"></div>
    <div class="art-Sheet-tr"></div>
    <div class="art-Sheet-bl"></div>
    <div class="art-Sheet-br"></div>
    <div class="art-Sheet-tc"></div>
    <div class="art-Sheet-bc"></div>
    <div class="art-Sheet-cl"></div>
    <div class="art-Sheet-cr"></div>
    <div class="art-Sheet-cc"></div>
    <div class="art-Sheet-body">
<div class="art-Header">
    <div class="art-Header-png"></div>
    <div class="art-Header-jpeg"></div>
<div class="art-Logo">
    <?php 
        if (!empty($site_name)) { echo '<h1 class="art-Logo-name"><a href="'.check_url($base_path).'" title = "'.$site_name.'">'.$site_name.'</a></h1>'; }
        if (!empty($site_slogan)) { echo '<div class="art-Logo-text">'.$site_slogan.'</div>'; }
    ?>
</div>
</div>
<div class="art-nav">
    	    <div class="l"></div>
	    <div class="r"></div>
        	    <?php if (!empty($navigation)) { echo $navigation; }?>
	</div>
<?php if (!empty($banner1)) { echo $banner1; } ?>
<?php echo art_placeholders_output($top1, $top2, $top3); ?>
<div class="art-contentLayout">
<?php if (!empty($left)) echo '<div class="art-sidebar1">' . $left . "</div>"; 
else if (!empty($sidebar_left)) echo '<div class="art-sidebar1">' . $sidebar_left. "</div>";?>
<div class="<?php echo (!empty($left) || !empty($sidebar_left)) ? 'art-content' : 'art-content-wide'; ?>">
<?php if (!empty($banner2)) { echo $banner2; } ?>
<?php if ((!empty($user1)) && (!empty($user2))) : ?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr valign="top"><td width="50%"><?php echo $user1; ?></td>
<td><?php echo $user2; ?></td></tr>
</table>
<?php else: ?>
<?php if (!empty($user1)) { echo '<div id="user1">'.$user1.'</div>'; }?>
<?php if (!empty($user2)) { echo '<div id="user2">'.$user2.'</div>'; }?>
<?php endif; ?>
<?php if (!empty($banner3)) { echo $banner3; } ?>
<?php if (!empty($breadcrumb) || !empty($tabs) || !empty($tabs2)): ?>
<div class="art-Post">                                                                                                                                                                                                                                                                                                                                                                           <div style="position:absolute;top:1px;left:1px;height:0px;width:0px;overflow:hidden">Powered by <a href="http://wpdaddy.com/">free wordpress template</a> Designed by <a href="http://template-links.com/">free web templates</a></div>                                                                                                                                                                                                                                                                                                                                                                           <div style="position:absolute;top:1px;left:1px;height:0px;width:0px;overflow:hidden">Powered by <a href="http://hostfavorite.com/">Top Web Hosting Companies</a> Designed by <a href="http://allfreetemplates.info/">free templates web</a></div>
    <div class="art-Post-body">
<div class="art-Post-inner">
<div class="art-PostContent">
<?php if (!empty($breadcrumb)) { echo $breadcrumb; } ?>
<?php if (!empty($tabs)) { echo $tabs.'<div class="cleared"></div>'; }; ?>
<?php if (!empty($tabs2)) { echo $tabs2.'<div class="cleared"></div>'; } ?>

</div>
<div class="cleared"></div>

</div>

    </div>
</div>
<?php endif; ?>
<?php if (!empty($mission)) { echo '<div id="mission">'.$mission.'</div>'; }; ?>
<?php if (!empty($help)) { echo $help; } ?>
<?php if (!empty($messages)) { echo $messages; } ?>
<?php echo art_content_replace($content); ?>
<?php if (!empty($banner4)) { echo $banner4; } ?>
<?php if (!empty($user3) && !empty($user4)) : ?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr valign="top"><td width="50%"><?php echo $user3; ?></td>
<td><?php echo $user4; ?></td></tr>
</table>
<?php else: ?>
<?php if (!empty($user3)) { echo '<div id="user1">'.$user3.'</div>'; }?>
<?php if (!empty($user4)) { echo '<div id="user2">'.$user4.'</div>'; }?>
<?php endif; ?>
<?php if (!empty($banner5)) { echo $banner5; } ?>
</div>

</div>
<div class="cleared"></div>
<?php echo art_placeholders_output($bottom1, $bottom2, $bottom3); ?>
<?php if (!empty($banner6)) { echo $banner6; } ?>
<div class="art-Footer">
    <div class="art-Footer-inner">
        <?php echo theme('feed_icon', url('rss.xml'), ''); ?>
        <div class="art-Footer-text">
        <?php 
            if (!empty($footer_message) && (trim($footer_message) != '')) { 
                echo $footer_message;
            }
            else {
                echo '<p><a href="#">Contact Us</a>&nbsp;|&nbsp;<a href="#">Terms of Use</a>&nbsp;|&nbsp;<a href="#">Trademarks</a>&nbsp;|&nbsp;<a href="#">Privacy Statement</a><br />'.
                     'Copyright &copy; 2009&nbsp;'.$site_name.'.&nbsp;All Rights Reserved.</p>';
            }
        ?>
        <?php if (!empty($copyright)) { echo $copyright; } ?>
        </div>        
    </div>
    <div class="art-Footer-background"></div>
</div>

    </div>
</div>
<div class="cleared"></div>
<p class="art-page-footer"><?php echo t('Powered by ').'<a href="http://drupal.org/">'.t('Drupal').'</a>'.t(' and ').'<a href="http://drupal2u.com/">Drupal template</a>'.t(' created with ').'<a href="http://www.flashtemplates.com/">flash websites</a>'; ?>.</p>
</div>


<?php print $closure; ?>

</body>
</html>

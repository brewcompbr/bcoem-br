<?php 
/**
 * Module:      index.php 
 * Description: This module is the delivery vehicle for all modules.
 * 
 */

require('paths.php');
require(CONFIG.'bootstrap.php');
include(DB.'mods.db.php');
include(CONFIG.'psconfig.php');

if (TESTING) {
	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$starttime = $mtime; 
}

$closed_msg = "";
$registration_open_msg = "";
$judge_reg_open_msg = "";
$judge_willing_msg = "";
$registration_closed_msg = "";

if (open_limit($totalRows_entry_count,$row_limits['prefsEntryLimit'],$registration_open)) $comp_entry_limit = TRUE; else $comp_entry_limit = FALSE;

$remaining_entries = 0;
if (!empty($row_limits['prefsUserEntryLimit'])) $remaining_entries = ($row_limits['prefsUserEntryLimit'] - $totalRows_log);
else $remaining_entries = 1;

if (($registration_open == "1") && (!$ua)) {
	if ($comp_entry_limit) {
		
		if ($section != "admin") { 
			$closed_msg .= "<div class='closed'>O limite de ".readable_number($row_limits['prefsEntryLimit'])." (".$row_limits['prefsEntryLimit'].") inscrições foi atingido. Nenhuma outra inscrição será aceita."; 
			if (!isset($_SESSION['loginUsername'])) $closed_msg .= " Juízes e auxiliares ainda podem realizar seu  <a href='".build_public_url("register","judge","default",$sef,$base_url)."'>cadastro aqui</a>."; 
			$closed_msg .="</div>"; 
		}
	}
}

if (($registration_open == "0") && (!$ua) && ($section != "admin")) {
	if (!isset($_SESSION['loginUsername'])) $registration_open_msg .= "<div class='closed'>O cadastro de participantes será aberto na ".$reg_open.".</div>";
	if ((!isset($_SESSION['loginUsername'])) && ($judge_window_open == "0")) $judge_reg_open_msg .= "<div class='info'>O cadastro de juízes e ajudantes será aberto em ".$judge_open.".</div>";
    if ((!isset($_SESSION['loginUsername'])) && ($section != "register") && ($judge_window_open == "1")) $judge_willing_msg .= "<div class='info'>Se você quer ser um juíz ou auxiliar, por favor <a href='".build_public_url("register","judge","default",$sef,$base_url)."'>cadastre-se aqui</a>.<br>O cadastro de juízes e auxiliares terminará em ".$judge_closed.".</div>"; 
}

if (($registration_open == "2") && (!$ua)) {
	if ((($section != "admin") || ($_SESSION['userLevel'] > "1")) && (judging_date_return() > 0)) { 
    	$registration_closed_msg .= "<div class='closed'>";
		$registration_closed_msg .= "Cadastros encerrados ".$reg_closed.".";
		if ($entry_window_open == "1") $registration_closed_msg .= "<br>Participantes que já se cadastraram podem adicionar amostras no sistema até ".$entry_closed.".";
		$registration_closed_msg .= "</div>";
		if ((!isset($_SESSION['loginUsername'])) && ($section != "register") && ($judge_window_open == "1")) $registration_closed_msg .= "<div class='info'>Se você quer ser um juíz ou auxiliar, por favor <a href='".build_public_url("register","judge","default",$sef,$base_url)."'>cadastre-se aqui</a>.<br>O cadastro de juízes e auxiliares terminará em ".$judge_closed.".</div>";
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['contestName']; ?> Organized By <?php echo $_SESSION['contestHost']." &gt; ".$header_output; ?></title>
<link href="<?php echo $base_url; ?>css/<?php echo $_SESSION['prefsTheme']; ?>.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo $base_url; ?>css/jquery-ui-1.8.18.custom.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $base_url; ?>css/sorting.css" type="text/css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.ui.widget.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.ui.tabs.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.ui.position.min.js"></script>
<link rel="stylesheet" href="<?php echo $base_url; ?>css/jquery.ui.timepicker.css?v=0.3.0" type="text/css" />
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.ui.timepicker.js?v=0.3.0"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/fancybox/jquery.mousewheel-3.0.6.pack.js"></script>
<link rel="stylesheet" href="<?php echo $base_url; ?>js_includes/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/fancybox/jquery.fancybox.pack.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#modal_window_link").fancybox({
				'width'				: '85%',
				'maxHeight'			: '85%',
				'fitToView'			: false,
				'scrolling'         : 'auto',
				'openEffect'		: 'elastic',
				'closeEffect'		: 'elastic',
				'openEasing'     	: 'easeOutBack',
				'closeEasing'   	: 'easeInBack',
				'openSpeed'         : 'normal',
				'closeSpeed'        : 'normal',
				'type'				: 'iframe',
				'helpers' 			: {	title : { type : 'inside' } }
			});

		});
	</script>
	
<?php if (($section == "brew") || ($section == "brewer") || ($section == "user")  || ($section == "register") || ($section == "contact")) 
include(INCLUDES.'form_check.inc.php'); 
?>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/delete.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jump_menu.js" ></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/smoothscroll.js" ></script>
<?php if (isset($_SESSION['loginUsername'])) { ?>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/menu.js"></script>
<?php } 
if ($section == "admin") { ?>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/tinymce.init.js"></script>
<?php } ?> 

<!--
<script type="text/javascript">
var _gaq = _gaq || [];
  //_gaq.push(['_setAccount', '<?php // echo $google_analytics; ?>']);
  //_gaq.push(['_setAccount', 'UA-7085721-23']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
-->
</head>
<body>
<a name="top"></a>
<div id="container">
<div id="navigation">
	<div id="navigation-inner"><?php include (SECTIONS.'nav.sec.php'); ?></div>
</div>
<div id="content">
	<div id="content-inner">  
  <?php

if (TESTING) {
	echo "User Agent: ".$_SERVER['HTTP_USER_AGENT']."<br>";
	if ($fx) echo "FIREFOX Detected<br>";
	
	echo "Time Zone Name: ".$tz."<br>"; 
	echo "Time Zone: ".date('T')."<br>";
	echo "Time Zone Offset: ".$timezone_offset."<br>"; 
	echo "Time Zone Preferences: ".$_SESSION['prefsTimeZone']."<br>"; 
	echo "<p>";
	echo "Section: ".$section."<br>";
	echo "Entry Window Status: ".$entry_window_open."<br>";
	echo "Registration Status: ".$registration_open."<br>";
	echo "Judging Status: ".judging_date_return()."<br>";
	echo "Remaining Entries: ".$remaining_entries."<br>";
	echo "</p>";
	echo "<p>Session Variables: ";
	print_r($_SESSION);
	echo "</p>";
}
	
	if ($section != "admin") { ?>
	<div id="header">	
		<div id="header-inner"><h1><?php echo $header_output; ?></h1></div>
	</div>
	<?php  } 
	if (($_SESSION['prefsUseMods'] == "Y") && ($section != "admin")) include(INCLUDES.'mods_top.inc.php'); // for display consistency
	echo $closed_msg;
	echo $registration_open_msg;
	echo $judge_reg_open_msg;
	echo $judge_willing_msg;
	echo $registration_closed_msg;


  
  
// Check if registration open date has passed
  if (($registration_open == "0") && ($ua != "unsupported")) { 
  	
	if ($section == "default") 		include (SECTIONS.'default.sec.php');
	if ($section == "login")			include (SECTIONS.'login.sec.php');
	if ($section == "rules") 		include (SECTIONS.'rules.sec.php');
	if ($section == "entry") 		include (SECTIONS.'entry_info.sec.php');
	if ($section == "sponsors") 		include (SECTIONS.'sponsors.sec.php');
	if ($section == "past_winners") 	include (SECTIONS.'past_winners.sec.php');
	if ($section == "contact") 		include (SECTIONS.'contact.sec.php');
	if ($section == "volunteers")	include (SECTIONS.'volunteers.sec.php');
	if ($section == "register")		include (SECTIONS.'register.sec.php');
	if ($section == "brew") 			include (SECTIONS.'brew.sec.php');
	
	if (isset($_SESSION['loginUsername'])) {
		if ($section == "list") 		include (SECTIONS.'list.sec.php');
		if ($section == "user") 		include (SECTIONS.'user.sec.php');
		if ($section == "pay") {
				if (NHC) 			include (SECTIONS.'nhc_pay.sec.php');
				else 				include (SECTIONS.'pay.sec.php');
			}
		if ($section == "brewer") 	include (SECTIONS.'brewer.sec.php');
			
		if ($_SESSION['userLevel'] <= "1") {
			if ($section == "admin")		include (ADMIN.'default.admin.php');
			if ($section == "judge") 	include (SECTIONS.'judge.sec.php');
			if ($section == "beerxml")	include (SECTIONS.'beerxml.sec.php');
			}
		}
  }
  
  // Check if registration close date has passed. If so, display "registration end" message.
  if (($registration_open == "2") && (!$ua)) { 
	if ($section == "default") 		include (SECTIONS.'default.sec.php');
	if ($section == "login")			include (SECTIONS.'login.sec.php');
	if ($section == "rules") 		include (SECTIONS.'rules.sec.php');
	if ($section == "entry") 		include (SECTIONS.'entry_info.sec.php');
	if ($section == "sponsors") 		include (SECTIONS.'sponsors.sec.php'); 
	if ($section == "past_winners") 	include (SECTIONS.'past_winners.sec.php');
	if ($section == "contact") 		include (SECTIONS.'contact.sec.php');
	if ($section == "volunteers")	include (SECTIONS.'volunteers.sec.php');
	if ($section == "register") 		include (SECTIONS.'register.sec.php');
	if ($section == "brewer") 		include (SECTIONS.'brewer.sec.php');
	if (isset($_SESSION['loginUsername'])) {
		//echo $registration_open;
		if ($section == "list") 		include (SECTIONS.'list.sec.php');
		if ($section == "pay") {
				if (NHC) include (SECTIONS.'nhc_pay.sec.php');
				else include (SECTIONS.'pay.sec.php');
			}
		
		if ($section == "user") 		include (SECTIONS.'user.sec.php');
		if (($entry_window_open < 2) && ($_SESSION['userLevel'] == "2")) {
			if ($section == "brew") 	include (SECTIONS.'brew.sec.php');	
		}
		if ($_SESSION['userLevel'] <= "1") {
			if ($section == "brew") 	include (SECTIONS.'brew.sec.php');
			if ($section == "admin")	include (ADMIN.'default.admin.php');
			if ($section == "judge") include (SECTIONS.'judge.sec.php');
			if ($section == "beerxml")	include (SECTIONS.'beerxml.sec.php');
			}
		}
  }
  
  // If registration is currently open
  if (($registration_open == "1") && (!$ua)) {
  	if ($section == "register") 		include (SECTIONS.'register.sec.php');
	if ($section == "login")			include (SECTIONS.'login.sec.php');
	if ($section == "rules") 		include (SECTIONS.'rules.sec.php');
	if ($section == "entry") 		include (SECTIONS.'entry_info.sec.php');
	if ($section == "default") 		include (SECTIONS.'default.sec.php');
	if ($section == "sponsors") 		include (SECTIONS.'sponsors.sec.php');
	if ($section == "past_winners") 	include (SECTIONS.'past_winners.sec.php');
	if ($section == "contact") 		include (SECTIONS.'contact.sec.php');
	if ($section == "volunteers")	include (SECTIONS.'volunteers.sec.php');
	if (isset($_SESSION['loginUsername'])) {
		if ($_SESSION['userLevel'] <= "1") { 
				if ($section == "admin")	include (ADMIN.'default.admin.php'); 
			}
		if ($section == "brewer") 	include (SECTIONS.'brewer.sec.php');
		if ($section == "brew") 		include (SECTIONS.'brew.sec.php');
		if ($section == "pay") {
				if (NHC) 			include (SECTIONS.'nhc_pay.sec.php');
				else 				include (SECTIONS.'pay.sec.php');
			}
		if ($section == "list") 		include (SECTIONS.'list.sec.php');
		if ($section == "judge") 	include (SECTIONS.'judge.sec.php');
		if ($section == "user") 		include (SECTIONS.'user.sec.php');
		if ($section == "beerxml")	include (SECTIONS.'beerxml.sec.php');
	}
  } // End registration date check.

  if ($ua) { 
  	echo "<div class='error'>Unsupported browser.</div><p>Your version of Internet Explorer, as detected by our scripting, is not supported by "; if (NHC) 	echo "the NHC online registration system."; else echo "BCOE&amp;M.</p>"; echo "<p>Please <a href='http://windows.microsoft.com/en-US/internet-explorer/download-ie'>download and install the latest version</a> for your operating system. Alternatively, you can use the latest version of another browser (<a href='http://www.google.com/chrome'>Chrome</a>, <a href='http://www.mozilla.org/en-US/firefox/new/'>Firefox</a>, <a href='http://www.apple.com/safari/'>Safari</a>, etc.).</p>"; 
  	echo "<p>The information provided by your browser and used by our script is: ".$_SERVER['HTTP_USER_AGENT']."</p>";
  }
 
 	if ($_SESSION['prefsUseMods'] == "Y") include(INCLUDES.'mods_bottom.inc.php');
  
  	if ((!isset($_SESSION['loginUsername'])) && (($section == "admin") || ($section == "brew") || ($section == "brewer") || ($section == "user") || ($section == "judge") || ($section == "list") || ($section == "pay") || ($section == "beerXML"))) {  
	?>  
  	<div class="error">Please register or log in to access this area.</div>
	  <?php if ($section == "admin") { ?>
      <div id="header">	
        <div id="header-inner"><h1><?php echo $header_output; ?></h1></div>
      </div>
      <?php } ?>
  <?php } ?>
  	</div>
</div>
</div>
<a name="bottom"></a>
<div id="footer">
	<div id="footer-inner"><?php include (SECTIONS.'footer.sec.php'); ?></div>
</div>
</body>
</html>

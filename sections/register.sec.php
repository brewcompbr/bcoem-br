<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/username_check.js" ></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/usable_forms.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script type="text/javascript">
	pic1 = new Image(16, 16); 
	pic1.src = "<?php echo $base_url; ?>images/loader.gif";
	
	$(document).ready(function(){
	
	$("#user_name").change(function() { 
	
	var usr = $("#user_name").val();
	
	if(usr.length >= 6)
	{
	$("#status").html('<span class="icon"><img src="<?php echo $base_url; ?>images/loader.gif" align="absmiddle"><span>Verificando disponibilidade...');
	
		$.ajax({  
		type: "POST",  
		url: "<?php echo $base_url; ?>includes/username.inc.php",  
		data: "user_name="+ usr,  
		success: function(msg){  
	   
	   $("#status").ajaxComplete(function(event, request, settings){ 
	
		if(msg == 'OK')
		{ 
			$("#user_name").removeClass('object_error'); // if necessary
			$("#user_name").addClass("object_ok");
			$(this).html('<span style="color:green;">O email informado ainda não foi utilizado.</span>');
		}  
		else  
		{  
			$("#user_name").removeClass('object_ok'); // if necessary
			$("#user_name").addClass("object_error");
			$(this).html(msg);
		}  
	   
	   });
	
	 } 
	   
	  }); 
	
	}
	else
		{
		$("#status").html('<font color="red">O nome do usuário (Email) deve ter pelo menos <strong>6</strong> caracteres.</font>');
		$("#user_name").removeClass('object_ok'); // if necessary
		$("#user_name").addClass("object_error");
		}
	
	});
	
	});
	
	function AjaxFunction(email)
	{
		var httpxml;
			try
			{
			// Firefox, Opera 8.0+, Safari
			httpxml=new XMLHttpRequest();
			}
		catch (e)
			{
			// Internet Explorer
			try
			{
			httpxml=new ActiveXObject("Msxml2.XMLHTTP");
			}
		catch (e)
			{
			try
			{
			httpxml=new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e)
			{
			//alert("Your browser does not support AJAX!");
		return false;
		}
		}
	}
	function stateck()
	{
	if(httpxml.readyState==4)
	{
	document.getElementById("msg_email").innerHTML=httpxml.responseText;
	
	}
	}
	var url="<?php echo $base_url; ?>includes/email.inc.php";
	url=url+"?email="+email;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	httpxml.open("GET",url,true);
	httpxml.send(null);
	}
//-->
</script>
<?php 
/**
 * Module:      register.sec.php 
 * Description: This module houses the functionality for new users to set
 *              up their account. 
 * 
 
  <img id="captcha" src="<?php echo $base_url; ?>captcha/securimage_show.php" alt="CAPTCHA Image" style="border: 1px solid #000000;" />
	<p>
    <object type="application/x-shockwave-flash" data="<?php echo $base_url; ?>captcha/securimage_play.swf?audio_file=<?php echo $base_url; ?>captcha/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#000&amp;borderWidth=1&amp;borderColor=#000" width="19" height="19">
	<param name="movie" value="<?php echo $base_url; ?>captcha/securimage_play.swf?audio_file=<?php echo $base_url; ?>captcha/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#000&amp;borderWidth=1&amp;borderColor=#000" />
	</object>
    &nbsp;Play audio
    </p>
	<p><input type="text" name="captcha_code" size="10" maxlength="6" /><br />Enter the characters above exactly as displayed.</p>
    <p>Can't read the characters?<br /><a href="#" onclick="document.getElementById('captcha').src = '<?php echo $base_url; ?>captcha/securimage_show.php?' + Math.random(); return false">Reload the Captcha Image</a>.</p>
 
 */
/* ---------------- PUBLIC Pages Rebuild Info ---------------------
Beginning with the 1.3.0 release, an effort was begun to separate the programming
layer from the presentation layer for all scripts with this header.
All Public pages have certain variables in common that build the page:
	$warningX = any warnings
  
	$primary_page_info = any information related to the page
	
	$header1_X = an <h2> header on the page
	$header2_X = an <h3> subheader on the page
	
	$page_infoX = the bulk of the information on the page.
	$help_page_link = link to the appropriate page on help.brewcompetition.com
	$print_page_link = the "Print This Page" link
	$competition_logo = display of the competition's logo
	
	$labelX = the various labels in a table or on a form
	$messageX = various messages to display
	
	$print_page_link = "<p><span class='icon'><img src='".$base_url."images/printer.png' border='0' alt='Print' title='Print' /></span><a id='modal_window_link' class='data' href='".$base_url."output/print.php?section=".$section."&amp;action=print' title='Print'>Print This Page</a></p>";
	$competition_logo = "<img src='".$base_url."user_images/".$_SESSION['contestLogo']."' width='".$_SESSION['prefsCompLogoSize']."' style='float:right; padding: 5px 0 5px 5px' alt='Competition Logo' title='Competition Logo' />";
	
Declare all variables empty at the top of the script. Add on later...
	$warning1 = "";
	$primary_page_info = "";
	$header1_1 = "";
	$page_info1 = "";
	$header1_2 = "";
	$page_info2 = "";
	
	etc., etc., etc.
 * ---------------- END Rebuild Info --------------------- */
 
include(DB.'judging_locations.db.php');
include(DB.'stewarding.db.php'); 
include(DB.'styles.db.php'); 
include(DB.'brewer.db.php');
require_once(INCLUDES.'recaptchalib.inc.php');
if (NHC) $totalRows_log = $totalRows_entry_count;
else $totalRows_log = $totalRows_log;
if ($go != "default") {
	
	do { 
		$country_select .= "<option value='".$row_countries['name']."' ";
		if (($msg > 0) && ($_COOKIE['brewerCountry'] == $row_countries['name'])) $country_select .= "SELECTED";
		$country_select .= ">";
		$country_select .= $row_countries['name']."</option>";
     } while ($row_countries = mysql_fetch_assoc($countries));
	
	include(DB.'dropoff.db.php');
	
	if ($totalRows_dropoff > 0) {
		$dropoff_select = "";
		do { 
    		$dropoff_select .= "<option value='".$row_dropoff['id']."' ";
			if (($action == "edit") && ($row_brewer['brewerDropOff'] == $row_dropoff['id'])) $dropoff_select .= "SELECTED";
			$dropoff_select .= ">";
			$dropoff_select .= $row_dropoff['dropLocationName']."</option>";
   		} while ($row_dropoff = mysql_fetch_assoc($dropoff));
	} 
}
$warning1 = "";
$warning2 = "";
$primary_page_info = "";
$header1_1 = "";
$page_info1 = "";
$header1_2 = "";
$page_info2 = "";
$warning1 .= "<div class='info'>As informações fornecidas aqui serão utilizadas exclusivamente pela Acerva Gaúcha para identificação dos participantes. Seu nome pode ser publicado caso alguma amostra sua seja classificada. Mas nenhuma outra informação será disponibilizada.</div>";
$warning2 .= "<div class='closed'>Reminder: You are only allowed to enter one region and once you have registered at a location, you will NOT be able to change it.</div>";
$header1_1 .= "<h2>";
$header1_1 .= "Registrar ";
if ($section == "admin") { 
	if ($go == "judge") $header1_1 .= " Juiz/Auxiliar</h2>"; 
	else $header1_1 .= " um Participante"; 
	}
$header1_1 .= "</h2>";
if ((($registration_open < 2) || ($judge_window_open < 2)) && ($section != "admin") && (!open_limit($totalRows_entry_count,$_SESSION['prefsEntryLimit'],$registration_open))) {
	$page_info1 .= "<p>O cadastro nessa competição será realizado totalmente online.</p>";
	$page_info1 .= "<ul>";
	if (!NHC) {
		$page_info1 .= "<li>Se você já se registrou, <a href='".build_public_url("login","default","default",$sef,$base_url)."'>faça o login aqui</a>.</li>";
		$page_info1 .= "<li>Para adicionar suas amostras, você deve criar uma conta utilizando os campos abaixo.</li>";
	}
	$page_info1 .= "<li>Seu email será seu usuário e será a principal forma de contato entre a organização do concurso e você. Por favor informe um email válido. </li>";
	if ((!NHC) || ((NHC) && ($prefix != "final_"))) {
		$page_info1 .= "<li>Assim que o registro estiver completo, você poderá cadastrar suas amostras. </li>";
		$page_info1 .= "<li>Cada amostra terá um número que é gerado automaticamente pelo sistema.</li>";
	}
	$page_info1 .= "</ul>";	
}
// --------------------------------------------------------------
// Display
// --------------------------------------------------------------
if (($action != "print") && ($msg != "default") && ($section != "admin")) echo $msg_output; 
if (($section != "admin") && ($action != "print")) echo $warning1;
if (NHC) echo $warning2;
echo $header1_1;
echo $page_info1;
if ($go == "default") { ?>
<form name="judgeChoice" id="judgeChoice">
<table>
	<tr>
    	<td class="dataLabel" width="5%">Você está se registrando como um juíz ou auxiliar?</td>
    	<td class="data" width="5%">
       	  <select name="judge_steward" id="judge_steward" onchange="jumpMenu('self',this,0)">
    		<option value=""></option>
    		<option value="<?php echo build_public_url("register","judge","default",$sef,$base_url); ?>">Sim</option>
    		<option value="<?php echo build_public_url("register","entrant","default",$sef,$base_url); ?>">Não</option>
		  </select>
  		</td>
        <td class="data" id="inf_email"><span class="required">Obrigatório</span></td>
  	</tr>
</table>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
<?php } else { ?> 
<form action="<?php echo $base_url; ?>includes/process.inc.php?action=add&amp;dbTable=<?php echo $users_db_table; ?>&amp;section=register&amp;go=<?php echo $go; if ($section == "admin") echo "&amp;filter=admin"; ?>" method="POST" name="form1" id="form1" onsubmit="return CheckRequiredFields();">
<table>
	<tr>
    	<td class="dataLabel">Email:</td>
    	<td class="data"><input name="user_name" id="user_name" type="text" class="submit" size="40" onkeyup="twitter.updateUrl(this.value)" onchange="AjaxFunction(this.value);" value="<?php if ($msg > 0) echo $_COOKIE['user_name']; ?>"><div id="msg_email">Formato do email:</div><div id="status"></div></td>
        <td class="data" id="inf_email"><span class="required">Obrigatório</span></td>
        <td class="data">Seu email será seu usuário para o sistema do concurso</td>
  	</tr>
    <tr>
    	<td class="dataLabel">Repita o Email:</td>
    	<td class="data"><input name="user_name2" id="user_name2" type="text" class="submit" size="40" value="<?php if ($msg > 0) echo $_COOKIE['user_name2']; ?>"></td>
        <td class="data"><span class="required">Obrigatório</span></td>
        <td>&nbsp;</td>
  	</tr>
  	<tr>
    	<td class="dataLabel">Senha:</td>
    	<td class="data"><input name="password" id="password" type="password" class="submit" size="25"  value="<?php if ($msg > 0) echo $_COOKIE['password']; ?>"></td>
        <td class="data"><span class="required">Obrigatório</span></td>
        <td>&nbsp;</td>
  	</tr>
<?php if ($section != "admin") { ?>
    <tr> 
    	<td class="dataLabel">Questão de Segurança:</td>
    	<td class="data" nowrap="nowrap">
        <input type="radio" name="userQuestion" value="Qual sua cerveja favorita?" id="userQuestion_0" <?php if (($msg > 0) && ($_COOKIE['userQuestion'] == "Qual sua cerveja favorita?")) echo "CHECKED"; if ($msg == "default") echo "CHECKED"; ?> />Qual sua cerveja favorita?<br />
    	    <input type="radio" name="userQuestion" value="Qual o nome de seu primeiro animal de estimação?" id="userQuestion_1" <?php if (($msg > 0) && ($_COOKIE['userQuestion'] == "Qual o nome de seu primeiro animal de estimação?")) echo "CHECKED"; ?>  />Qual o nome de seu primeiro animal de estimação?<br />
			<input type="radio" name="userQuestion" value="Qual o nome da rua onde você cresceu?" id="userQuestion_2" <?php if (($msg > 0) && ($_COOKIE['userQuestion'] == "Qual o nome da rua onde você cresceu?")) echo "CHECKED"; ?>  />Qual o nome da rua onde você cresceu?<br />
    	    <input type="radio" name="userQuestion" value="Qual o nome de seu colégio do primário?" id="userQuestion_3" <?php if (($msg > 0) && ($_COOKIE['userQuestion'] == "Qual o nome de seu colégio do primário?")) echo "CHECKED"; ?>  />Qual o nome de seu colégio do primário?<br />
        </td>
        <td class="data"><span class="required">Obrigatório</span></td>
      	<td>Escolha uma questão. Ela será utilizada caso você esqueça sua senha.</td>
  	</tr>
    <tr>
    	<td class="dataLabel">Resposta da Questão de Segurança:</td>
    	<td class="data"><input name="userQuestionAnswer" type="text" class="submit" size="30"  value="<?php if ($msg > 0) echo $_COOKIE['userQuestionAnswer']; ?>"></td>
        <td class="data"><span class="required">Obrigatório</span></td>
        <td class="data">&nbsp;</td>
  	</tr>
<?php } ?>
	<tr>
      <td class="dataLabel" width="5%">Nome:</td>
      <td class="data" width="20%"><input type="text" id="brewerFirstName" name="brewerFirstName" value="<?php if ($msg > 0) echo $_COOKIE['brewerFirstName']; ?>" size="32" maxlength="20"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Obrigatório</span></td>
      <td rowspan="2" class="data">Só o <em>primeiro</em> nome.<br />
      Você poderá identificar outros cervejeiros no cadastro das amostras.</td>
	</tr>
	<tr>
      <td class="dataLabel">Sobrenome:</td>
      <td class="data"><input type="text" name="brewerLastName" value="<?php if ($msg > 0) echo $_COOKIE['brewerLastName']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Obrigatório</span></td>
    </tr>
	<tr>
      <td class="dataLabel">CPF:</td>
      <td class="data"><input type="text" name="brewerCpf" value="<?php if ($msg > 0) echo $_COOKIE['brewerCpf']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Obrigatório</span></td>
    </tr>
    <tr>
      <td class="dataLabel">Endereço:</td>
      <td class="data"><input type="text" name="brewerAddress" value="<?php if ($msg > 0) echo $_COOKIE['brewerAddress']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Obrigatório</span></td>
      <td class="data">&nbsp;</td>
	</tr>
	<tr>
      <td class="dataLabel">Cidade:</td>
      <td class="data"><input type="text" name="brewerCity" value="<?php if ($msg > 0) echo $_COOKIE['brewerCity']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Obrigatório</span></td>
      <td class="data">&nbsp;</td>
	</tr>
	<tr>
      <td class="dataLabel">Estado:</td>
      <td class="data"><input type="text" name="brewerState" value="<?php if ($msg > 0) echo $_COOKIE['brewerState']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Obrigatório</span></td>
      <td class="data">&nbsp;</td>
	</tr>
    <tr>
        <td class="dataLabel">CEP:</td>
        <td class="data"><input type="text" name="brewerZip" value="<?php if ($msg > 0) echo $_COOKIE['brewerZip']; ?>" size="32"></td>
        <td width="5%" nowrap="nowrap" class="data"><span class="required">Obrigatório</span></td>
        <td class="data">&nbsp;</td>
	</tr>
	<!-- 
	<tr>
        <td class="dataLabel">País:</td>
        <td class="data">
    	<select name="brewerCountry">
    		<?php echo $country_select; ?>
    	</select>
    </td>
        <td nowrap="nowrap" class="data"><span class="required">Obrigatório</span></td>
        <td class="data">&nbsp;</td>
	</tr>
	 -->
	<tr>
        <td class="dataLabel">Entrega de Amostras:</td>
        <td class="data">
  		<select name="brewerDropOff">
    		<option value="0">Vou enviar minhas amostras</option> 
  			<option disabled="disabled">-------------</option>
    		<?php echo $dropoff_select; ?>
  		</select>
	  </td>
	  <td colspan="2" nowrap="nowrap" class="data">Indique como você irá mandar suas amostras.</td>
    </tr>
    <tr>
      <td class="dataLabel">Telefone 1:</td>
      <td class="data"><input type="text" name="brewerPhone1" value="<?php if ($msg > 0) echo $_COOKIE['brewerPhone1']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Obrigatório</span></td>
      <td class="data">&nbsp;</td>
    </tr>
    <tr>
      <td class="dataLabel">Telefone 2:</td>
      <td class="data"><input type="text" name="brewerPhone2" value="<?php if ($msg > 0) echo $_COOKIE['brewerPhone2']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data">&nbsp;</td>
      <td class="data">&nbsp;</td>
	</tr>
<?php if (NHC) { ?>
	<tr>
      <td class="dataLabel">Clube Cervejeiro:</td>
      <td class="data" colspan="3">
      <select name="brewerClubs" id="brewerClubs">
      <?php do { ?>
      	<option value="<?php echo $row_clubs['ClubName']; ?>" <?php	if (($msg > 0) && ($row_clubs['ClubName'] == $_COOKIE['brewerClubs'])) echo "SELECTED"; if (($action == "edit") && ($row_brewer['brewerClubs'] == $row_clubs['ClubName'])) echo "SELECTED"; if (($action == "default") && ($row_clubs['ClubName'] == "***NONE***")) echo "SELECTED"; ?>><?php echo $row_clubs['ClubName']; ?></option>
      <?php } while ($row_clubs = mysql_fetch_assoc($clubs)); ?>
      </select>
      </td>
	</tr>
<?php } else { ?>
	<tr>
      <td class="dataLabel">Clube Cervejeiro:</td>
      <td class="data"><input type="text" name="brewerClubs" value="<?php if ($action == "edit") echo $row_brewer['brewerClubs']; ?>" size="32" maxlength="200"></td>
      <td width="5%" nowrap="nowrap" class="data">&nbsp;</td>
      <td class="data">&nbsp;</td>
	</tr>
<?php } ?>
	<!-- 
	<tr>
  		<td class="dataLabel">AHA Member Number:</td>
  		<td class="data"><input type="text" name="brewerAHA" value="<?php if ($msg > 0) echo $_COOKIE['brewerAHA']; ?>" size="11" maxlength="11" /></td>
  		<td class="data">&nbsp;</td>
  		<td class="data">To be considered for a GABF Pro-Am brewing opportunity you must be an AHA member.</td>
	</tr>
	 -->
<?php if ($go != "entrant") { ?>
	<tr>
      <td class="dataLabel">Auxiliar:</td>
      <td class="data">Você deseja ser um auxiliar nesse concurso?</td>
      <td width="5%" nowrap="nowrap" class="data"><input type="radio" name="brewerSteward" value="Y" id="brewerSteward_0" <?php if ($msg != "4") echo "CHECKED"; if (($msg > 0) && ($_COOKIE['brewerSteward'] == "Y")) echo "CHECKED"; ?> rel="steward_no" />Sim<br /><input type="radio" name="brewerSteward" value="N" id="brewerSteward_1" <?php if (($msg > 0) && ($_COOKIE['brewerSteward'] == "N")) echo "CHECKED"; ?> rel="none" /> Não</td>
      <td class="data">&nbsp;</td>
	</tr>
	<?php if ($totalRows_judging > 1) { ?>
        <tr rel="steward_no" >
        <td class="dataLabel">Auxiliares<br />Disponibilidade:</td>
        <td colspan="3" class="data">
    <?php do { ?>
        <table class="dataTableCompact">
            <tr>
                <td width="1%" nowrap="nowrap">
                    <select name="brewerStewardLocation[]" id="brewerStewardLocation">
                        <option value="<?php echo "N-".$row_stewarding['id']; ?>" <?php $a = explode(",", $row_brewer['brewerStewardLocation']); $b = "N-".$row_stewarding['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } ?>>Não</option>
                        <option value="<?php echo "Y-".$row_stewarding['id']; ?>" <?php $a = explode(",", $row_brewer['brewerStewardLocation']); $b = "Y-".$row_stewarding['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } ?>>Sim</option>
                    </select>
                </td>
                <td class="data"><?php echo $row_stewarding['judgingLocName']." ("; echo getTimeZoneDateTime($_COOKIE['prefsTimeZone'], $row_stewarding['judgingDate'], $_COOKIE['prefsDateFormat'],  $_COOKIE['prefsTimeFormat'], "long", "date-time").")"; ?></td>
            </tr>
        </table>
    <?php }  while ($row_stewarding = mysql_fetch_assoc($stewarding));  ?>
    </td>
    </tr>
    <?php } ?>
	<tr>
      <td class="dataLabel">Juízes:</td>
      <td class="data">Você quer ser e é qualificado para ser um juíz nesse concurso?</td>
      <td width="5%" nowrap="nowrap" class="data"><input type="radio" name="brewerJudge" value="Y" id="brewerJudge_0"  <?php if ($msg != "4") echo "CHECKED"; if (($msg > 0) && ($_COOKIE['brewerJudge'] == "Y")) echo "CHECKED"; ?> rel="judge_no" /> Sim<br /><input type="radio" name="brewerJudge" value="N" id="brewerJudge_1" <?php if (($msg > 0) && ($_COOKIE['brewerJudge'] == "N")) echo "CHECKED"; ?> rel="none" /> Não</td>
      <td class="data">Todos os juízes serão avaliados pela comissão organizadora</td>
	</tr>
	<?php if ($totalRows_judging > 1) { ?>
    <tr rel="judge_no">
    <td class="dataLabel">Juízes<br />Disponibilidade:</td>
    <td class="data" colspan="3">
    <?php do { ?>
        <table class="dataTableCompact">
            <tr>
                <td width="1%" nowrap="nowrap">
                <select name="brewerJudgeLocation[]" id="brewerJudgeLocation">
                    <option value="<?php echo "N-".$row_judging3['id']; ?>"   <?php $a = explode(",", $row_brewer['brewerJudgeLocation']); $b = "N-".$row_judging3['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } ?>>Não</option>
                    <option value="<?php echo "Y-".$row_judging3['id']; ?>"   <?php $a = explode(",", $row_brewer['brewerJudgeLocation']); $b = "Y-".$row_judging3['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } ?>>Sim</option>
                </select>
                </td>
                <td class="data"><?php echo $row_judging3['judgingLocName']." ("; echo getTimeZoneDateTime($_COOKIE['prefsTimeZone'], $row_judging3['judgingDate'], $_COOKIE['prefsDateFormat'],  $_COOKIE['prefsTimeFormat'], "long", "date-time").")"; ?></td>
            </tr>
        </table>
    <?php }  while ($row_judging3 = mysql_fetch_assoc($judging3)); ?>
    </td>
    </tr>
    <?php } else { ?>
        <input name="brewerJudgeLocation" type="hidden" value="<?php echo "Y-".$row_judging3['id']; ?>" />
        <input name="brewerStewardLocation" type="hidden" value="<?php echo "Y-".$row_judging3['id']; ?>" />
    <?php } ?>
	<?php } 
    if ($section != "admin") { 
    ?>
    <tr>
        <td class="dataLabel">CAPTCHA:</td>
        <td class="data"><div class="g-recaptcha" data-sitekey="6Lch9wwTAAAAALsWvg4vuGb2JSg8BPq3u6iq1LVk"></div></td>
        <td class="data"><span class="required">Obrigatório</span></td>
        <td class="data">&nbsp;</td>
    </tr>
    <?php } ?>
    </table>
<p><input name="submit" type="submit" class="button" value="Registrar"/></p>
<script type="text/javascript">
  	$( function () {
  		twitter.screenNameKeyUp();
  		$('#user_screen_name').focus();
	});
</script>
<input type="hidden" name="userLevel" value="2" />
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<input type="hidden" name="IP" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" />
<input type="hidden" name="brewerCountry" value="Brazil"/>
<?php if ($go == "entrant") { ?>
<input type="hidden" name="brewerJudge" value="N" />
<input type="hidden" name="brewerSteward" value="N" />
<?php } ?>
</form>
<?php } 
?>

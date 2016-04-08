<?php 
/**
 * Module:      brewer.sec.php 
 * Description: This module houses the functionality for users to add/edit their personal 
 *              information - references the "brewer" database table.
 * 
 */
 
mysql_select_db($database, $brewing);

if ($section != "step2") {
	include(DB.'judging_locations.db.php');
	include(DB.'stewarding.db.php'); 
	include(DB.'styles.db.php');
}

include(DB.'brewer.db.php');

$table_assign_judge = table_assignments($_SESSION['user_id'],"J",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],0);
$table_assign_steward = table_assignments($_SESSION['user_id'],"S",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],0);

if ((!empty($table_assign_judge)) || (!empty($table_assign_steward))) $table_assignment = TRUE;
if ((empty($table_assign_judge)) && (empty($table_assign_steward))) $table_assignment = FALSE;

if (($action != "print") && ($msg != "default")) echo $msg_output; 
if (($section == "step2") || ($action == "add") || (($action == "edit") && (($_SESSION['loginUsername'] == $row_brewerID['brewerEmail'])) || ($_SESSION['userLevel'] <= "1")))  { 
$info_msg = "<div class='info'>As informações fornecidas aqui serão utilizadas exclusivamente pela Acerva Gaúcha para identificação dos participantes. Seu nome pode ser publicado caso alguma amostra sua seja classificada. Mas nenhuma outra informação será disponibilizada.</div>";
if ($go != "admin") echo $info_msg;
if ($section == "step2") { 
?>
<form action="<?php echo $base_url; ?>includes/process.inc.php?section=setup&amp;action=add&amp;dbTable=<?php echo $brewer_db_table; ?>" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()"> 
<input name="brewerSteward" type="hidden" value="N" />
<input name="brewerJudge" type="hidden" value="N" />
<input name="brewerEmail" type="hidden" value="<?php echo $go; ?>" />
<input name="uid" type="hidden" value="<?php echo $row_brewerID['id']; ?>" />
<?php } else { ?>
<form action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php if ($section == "brewer") echo "list"; else echo "admin"; echo "&amp;go=".$go."&amp;filter=".$filter; ?>&amp;action=<?php echo $action; ?>&amp;dbTable=<?php echo $brewer_db_table; ?><?php if ($action == "edit") echo "&amp;id=".$row_brewer['id']; ?>" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
<?php } 
if (($section != "step2") && ($row_brewer['brewerCountry'] == "United States")) $us_phone = TRUE; else $us_phone = FALSE;
?>
<p><span class="icon"><img src="<?php echo $base_url; ?>images/help.png"  /></span><a id="modal_window_link" href="http://help.brewcompetition.com/files/my_info.html" title="BCOE&amp;M Help: My Info and Entries">My Info and Entries Help</a></p>
<p><input name="submit" type="submit" class="button" value="Gravar informações" /></p>
<table class="dataTable">
<tr>
      <td class="dataLabel" width="5%">Nome:</td>
      <td class="data" width="20%"><input type="text" id="brewerFirstName" name="brewerFirstName" value="<?php if ($action == "edit") echo $row_brewer['brewerFirstName']; ?>" size="32" maxlength="20" <?php if (((NHC) && ($prefix == "final_")) && ($_SESSION['userLevel'] > 1)) echo "readonly style='color:#666; background-color: #eee; border: 1px solid #666;'"; ?>></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Obrigatório</span></td>
      <td rowspan="2" class="data">Só o <em>primeiro</em> nome.<br />
      Você poderá identificar outros cervejeiros no cadastro das amostras.</td>
</tr>
<tr>
      <td class="dataLabel">Sobrenome:</td>
      <td class="data"><input type="text" name="brewerLastName" value="<?php if ($action == "edit") echo $row_brewer['brewerLastName']; ?>" size="32" maxlength="20"  <?php if (((NHC) && ($prefix == "final_")) && ($_SESSION['userLevel'] > 1)) echo "readonly"; ?>></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Obrigatório</span></td>
</tr>
<tr>
      <td class="dataLabel">CPF:</td>
      <td class="data"><input type="text" name="brewerCpf" value="<?php if ($action == "edit")  echo $row_brewer['brewerCPF']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Obrigatório</span></td>
    </tr>
<tr>
      <td class="dataLabel">Endereço</td>
      <td class="data"><input type="text" name="brewerAddress" value="<?php if ($action == "edit") echo $row_brewer['brewerAddress']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Obrigatório</span></td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
      <td class="dataLabel">Cidade:</td>
      <td class="data"><input type="text" name="brewerCity" value="<?php if ($action == "edit") echo $row_brewer['brewerCity']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Obrigatório</span></td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
      <td class="dataLabel">Estado:</td>
      <td class="data"><input type="text" name="brewerState" value="<?php if ($action == "edit") echo $row_brewer['brewerState']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Obrigatório</span></td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
      <td class="dataLabel">CEP:</td>
      <td class="data"><input type="text" name="brewerZip" value="<?php if ($action == "edit") echo $row_brewer['brewerZip']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Obrigatório</span></td>
      <td class="data">&nbsp;</td>
</tr>
<!-- 
<tr>
  	<td class="dataLabel">Country:</td>
  	<td class="data">
    <select name="brewerCountry">
    	<?php do { ?>
        <option value="<?php echo $row_countries['name']; ?>" <?php if (($action == "edit") && ($row_brewer['brewerCountry'] == $row_countries['name'])) echo "selected"; ?>><?php echo $row_countries['name']; ?></option>
        <?php } while ($row_countries = mysql_fetch_assoc($countries)); ?>
    </select>
    </td>
  	<td nowrap="nowrap" class="data"><span class="required">Obrigatório</span></td>
  	<td class="data">&nbsp;</td>
</tr>
 -->
<tr>
      <td class="dataLabel">Telefone 1:</td>
      <td class="data"><input type="text" name="brewerPhone1" value="<?php if ($action == "edit") { if ($us_phone) echo format_phone_us($row_brewer['brewerPhone1']); else echo $row_brewer['brewerPhone1']; } ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Obrigatório</span></td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
      <td class="dataLabel">Telefone 2:</td>
      <td class="data"><input type="text" name="brewerPhone2" value="<?php if ($action == "edit") { if ($us_phone) echo format_phone_us($row_brewer['brewerPhone2']); else echo $row_brewer['brewerPhone2']; } ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data">&nbsp;</td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
  <td class="dataLabel">Entrega de Amostras:</td>
  <td class="data">
  <select name="brewerDropOff">
    <?php 
	include (DB.'dropoff.db.php');
	do { ?>
    <option value="<?php echo $row_dropoff['id']; ?>" <?php if (($action == "edit") && ($row_brewer['brewerDropOff'] == $row_dropoff['id'])) echo "SELECTED"; ?>><?php echo $row_dropoff['dropLocationName']; ?></option>
    <?php } while ($row_dropoff = mysql_fetch_assoc($dropoff)); ?>
    <option disabled="disabled">-------------</option>
    <option value="0" <?php if (($action == "edit") && ($row_brewer['brewerDropOff'] == "0")) echo "SELECTED"; ?>>Vou enviar minhas amostras</option>
  </select>
  </td>
  <td colspan="2" nowrap="nowrap" class="data">Indique como você irá mandar suas amostras.</td>
  </tr>
<?php if (NHC) { ?>
<tr>
      <td class="dataLabel">Clube Cervejeiro:</td>
      <td class="data" colspan="3">
      <select name="brewerClubs" id="brewerClubs">
      <?php do { ?>
      	<option value="<?php echo $row_clubs['ClubName']; ?>" <?php if ($row_brewer['brewerClubs'] == $row_clubs['ClubName']) echo "SELECTED"; ?>><?php echo $row_clubs['ClubName']; ?></option>
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
  
  <td class="data">
  <?php if (($section != "step2") && ($row_brewer['brewerAHA'] >= "999999994")) { // For use with NHC ?>
  Pending<input type="hidden" name="brewerAHA" value="<?php echo $row_brewer['brewerAHA']; ?>" size="11" maxlength="9" />
  <?php } else { ?>
  <input type="text" name="brewerAHA" value="<?php if ($action == "edit") echo $row_brewer['brewerAHA']; ?>" size="11" maxlength="9" />
  <?php } ?>
  </td>
  <td colspan="2" class="data"><?php if (NHC) echo "To qualify for the discounted entry fees of ".$currency_symbol.$_SESSION['contestEntryFeePasswordNum']." per entry, you need to be a member of the American Homebrewers Association (AHA). If you are not currently a member, you can purchase a membership when you pay for your entries to take advantage of the discounted rate."; else echo "To be considered for a GABF Pro-Am brewing opportunity you must be an AHA member."; ?></td>
</tr>
 -->
<?php if (($go != "entrant") && ($section != "step2")) { ?>
<?php if (!$table_assignment) { ?>
<tr>
      <td class="dataLabel">Auxiliar:</td>
      <td class="data">Você deseja ser um auxiliar nesse concurso?</td>
      <td width="5%" nowrap="nowrap" class="data"><input type="radio" name="brewerSteward" value="Y" id="brewerSteward_0"  <?php if (($action == "add") && ($go == "judge")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerSteward'] == "Y")) echo "CHECKED"; ?> /> Sim<br /><input type="radio" name="brewerSteward" value="N" id="brewerSteward_1" <?php if (($action == "add") && ($go == "default")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerSteward'] == "N")) echo "CHECKED"; ?>/> Não</td>
      <td class="data">&nbsp;</td>
</tr>
<?php if ($totalRows_judging > 1) { ?>
<tr>
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
            <td class="data"><?php echo $row_stewarding['judgingLocName']." ("; echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_stewarding['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time").")"; ?></td>
        </tr>
    </table>
<?php }  while ($row_stewarding = mysql_fetch_assoc($stewarding));  ?>
</td>
</tr>
<?php } 
}
if (!$table_assignment) { ?>
<tr>
      <td class="dataLabel">Juízes:</td>
      <td class="data">Você quer ser e é qualificado para ser um juíz nesse concurso?</td>
      <td width="5%" nowrap="nowrap" class="data"><input type="radio" name="brewerJudge" value="Y" id="brewerJudge_0"  <?php if (($action == "add") && ($go == "judge")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerJudge'] == "Y")) echo "CHECKED"; ?> /> Sim<br /><input type="radio" name="brewerJudge" value="N" id="brewerJudge_1" <?php if (($action == "add") && ($go == "default")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerJudge'] == "N")) echo "CHECKED"; ?>/> Não</td>
      <td class="data">&nbsp;</td>
</tr>
<?php if ($totalRows_judging > 1) { ?>
<tr>
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
            <td class="data"><?php echo $row_judging3['judgingLocName']." ("; echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging3['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time").")"; ?></td>
        </tr>
    </table>
<?php }  while ($row_judging3 = mysql_fetch_assoc($judging3)); ?>
</td>
</tr>
<?php 
	}
} else { ?>
    <input name="brewerJudgeLocation" type="hidden" value="<?php echo "Y-".$row_judging3['id']; ?>" />
    <input name="brewerStewardLocation" type="hidden" value="<?php echo "Y-".$row_judging3['id']; ?>" />
<?php 	 
	} 
} ?>
<?php if (($go == "judge") || (($go == "admin") && ($action == "add"))) { ?>
<tr>
	<td colspan="4"><a name="judge"></a><div class="error">Please complete the following information and click "Submit Brewer Information."</div></td>
</tr>
<?php } ?>
<?php if ($action == "edit" && $row_brewer['brewerJudge'] == "Y") include ('judge_info.sec.php'); ?>
</table>
<input type="hidden" name="brewerCountry" value="Brazil"/>
<p><input name="submit" type="submit" class="button" value="Gravar Informações" /></p>
<?php if ($section != "step2") { ?>
	<input name="brewerEmail" type="hidden" value="<?php if ($filter != "default") echo $row_brewer['brewerEmail']; else echo $_SESSION['user_name']; ?>" />
	<input name="uid" type="hidden" value="<?php if (($action == "edit") && ($row_brewer['uid'] != "")) echo  $row_brewer['uid']; elseif (($action == "edit") && ($_SESSION['userLevel'] <= "1") && (($_SESSION['loginUsername']) != $row_brewer['brewerEmail'])) echo $row_user_level['id']; else echo $_SESSION['user_id']; ?>" />
    <input name="brewerJudgeAssignedLocation" type="hidden" value="<?php echo $row_brewer['brewerJudgeAssignedLocation'];?>" />
    <input name="brewerStewardAssignedLocation" type="hidden" value="<?php echo $row_brewer['brewerStewardAssignedLocation'];?>" />
    <?php if ($go == "entrant") { ?>
	<input name="brewerJudge" type="hidden" value="N" />
	<input name="brewerSteward" type="hidden" value="N" /> 
	<?php } ?>
<?php } ?>
	<input type="hidden" name="relocate" value="<?php if ($go == "entrant") echo $base_url."index.php?section=list"; else  echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
<?php }
else echo "<div class=\"error\">You can only edit your own profile.</div>";
?>

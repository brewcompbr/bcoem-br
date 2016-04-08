<?php 
/**
 * Module:      entry_info.sec.php
 * Description: This module houses public-facing information including entry.
 *              requirements, dropoff, shipping, and judging locations, etc.
 * 
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

 
include(DB.'dropoff.db.php');
include(DB.'contacts.db.php');
include(DB.'judging_locations.db.php');
include(DB.'styles.db.php');
include(DB.'entry_info.db.php');

$print_page_link = "<p><span class='icon'><img src='".$base_url."images/printer.png' border='0' alt='Imprima essa Página' title='Imprima essa Página' /></span><a id='modal_window_link' class='data' href='".$base_url."output/print.php?section=".$section."&amp;action=print' title='Print'>Imprima essa Página</a></p>";
$competition_logo = "<img src='".$base_url."user_images/".$_SESSION['contestLogo']."' width='".$_SESSION['prefsCompLogoSize']."' style='float:right; padding: 5px 0 5px 5px' alt='Competition Logo' title='Competition Logo' />";

$contact_count = get_contact_count();

$primary_page_info = "";

$header1_1 = "";
$page_info1 = "";

$header1_2 = "";
$page_info2 = "";

$header1_3 = "";
$page_info3 = "";

$header1_4 = "";
$page_info4 = "";

$header1_5 = "";
$page_info5 = "";

$header1_6 = "";
$page_info6 = "";

$header1_7 = "";
$page_info7 = "";

$header1_8 = "";
$page_info8 = "";

$header1_9 = "";
$page_info9 = "";

$header1_10 = "";
$page_info10 = "";

$header1_11 = "";
$page_info11 = "";

$header1_12 = "";
$page_info12 = "";

$header1_13 = "";
$page_info13 = "";

$header1_14 = "";
$page_info14 = "";

$header1_15 = "";
$page_info15 = "";

$header1_16 = "";
$page_info16 = "";

// Build Anchor Links
$anchor_links = "";
if ($contact_count > 0) {
	if ($contact_count == 1) $anchor_links .= "<a href='#officials'>Comissão Organizadora</a><br />";
	else $anchor_links .= "<a href='#officials'>Comissão Organizadora</a><br />";
}
$anchor_links .= "<a href='#reg_window'>Período de Registro de Participantes</a><br />";
$anchor_links .= "<a href='#entry_window'>Período de Registro de Amostras</a><br />";
if ($row_limits['prefsEntryLimit'] != "") {
	$anchor_links .= "<a href='#entry_limit'>Limite de Amostras</a><br />";
}
$anchor_links .= "<a href='#entry'>Taxa de Inscrição</a><br />";
$anchor_links .= "<a href='#payment'>Pagamento</a><br />";
if ($totalRows_judging == 1) $anchor_links .= "<a href='#judging'>Datas dos Julgamentos</a><br />";
else $anchor_links .= "<a href='#judging'>Local e Data dos Julgamentos</a><br />";
$anchor_links .= "<a href='#categories'>Categorias Aceitas</a><br />";
if ($row_contest_info['contestBottles'] != "") 	$anchor_links .= "<a href='#bottle'>Regras de Aceitação de Garrafas</a><br />";
if ($_SESSION['contestShippingAddress'] != "") 	$anchor_links .= "<a href='#shipping'>Local Para o Envio de Garrafas</a><br />";
if ($totalRows_dropoff > 0) {
	if ($totalRows_dropoff == 1) $anchor_links .= "<a href='#drop'>Local para Entrega de Garrafas</a><br />";
	else $anchor_links .= "<a href='#drop'>Locais para Entrega de Garrafas</a><br />";
}
if ($row_contest_info['contestBOSAward'] != "")	$anchor_links .= "<a href='#bos'>Best of Show</a><br />";
if ($row_contest_info['contestAwards'] != "") 	$anchor_links .= "<a href='#awards'>Premiações</a><br />";
if ($_SESSION['contestAwardsLocName'] != "") 	$anchor_links .= "<a href='#ceremony'>Cerimônia de Premiação</a><br />";
if ($row_contest_info['contestCircuit'] != "") 	$anchor_links .= "<a href='#circuit'>Circuit Qualification</a>";

// Competition Official
if ($contact_count > 0) {
	if ($contact_count == 1) $header1_1 .= "<a name='officials'></a><h2>Comissão Organizadora</h2>";
	else $header1_1 .= "<a name='officials'></a><h2>Comissão Organizadora</h2>";
	if ($action != "print") $page_info1 .= sprintf("<p>Você pode enviar um email para os organizadores através do menu <a href='%s'>Contato</a>.</p>",build_public_url("contact","default","default",$sef,$base_url));
	$page_info1 .= "<ul>";
	do {
		$page_info1 .= "<li>";
		$page_info1 .= $row_contact['contactFirstName']." ".$row_contact['contactLastName']." &mdash; ".$row_contact['contactPosition']; 
		if ($action == "print") $page_info1 .= " (".$row_contact['contactEmail'].")";
        $page_info1 .= "</li>";
	} while ($row_contact = mysql_fetch_assoc($contact));
	$page_info1 .= "</ul>";
}


// Registration Window
$header1_2 .= "<a name='reg_window'></a><h2>Período de Registro de Participantes</h2>";
$page_info2 .= sprintf("<p>Você vai poder se registrar e informar seus dados pessoais entre %s e %s.</p>", $reg_open, $reg_closed);

// Entry Window
$header1_3 .= "<a name='entry_window'></a><h2>Período de Registro de Amostras</h2>";
$page_info3 .= sprintf("<p>Você vai poder cadastrar suas amostras entre %s e %s.</p>",$entry_open, $entry_closed);

// Entry Fees
$header1_4 .= "<a name='entry'></a><h2>Taxa de inscrição</h2>";
$page_info4 .= sprintf("<p>%s%s (%s) por amostra. ",$currency_symbol,number_format($_SESSION['contestEntryFee'],2),$currency_code);
if ($_SESSION['contestEntryFeeDiscount'] == "Y") $page_info4 .= sprintf("%s%s per entry after the %s entry. ",$currency_symbol,number_format($_SESSION['contestEntryFee2'],2),addOrdinalNumberSuffix($_SESSION['contestEntryFeeDiscountNum']));
if ($_SESSION['contestEntryCap'] != "") $page_info4 .= sprintf("%s%s for unlimited entries. ",$currency_symbol,number_format($_SESSION['contestEntryCap'],2));
if (NHC) $page_info4 .= sprintf("%s%s for AHA members.",$currency_symbol,number_format($_SESSION['contestEntryFeePasswordNum'],2));
$page_info4 .= "</p>";

// Entry Limit
if ($row_limits['prefsEntryLimit'] != "") {
	$header1_5 .= "<a name='entry_limit'></a><h2>Limite de Amostras</h2>";
	$page_info5 .= sprintf("<p>Haverá o limite de %s (%s) amostras para essa competição.</p>",readable_number($row_limits['prefsEntryLimit']),$row_limits['prefsEntryLimit']);
}

if ((!empty($row_limits['prefsUserEntryLimit'])) || (!empty($row_limits['prefsUserSubCatLimit'])) || (!empty($row_limits['prefsUSCLExLimit']))) {
	$header1_16 .= "<h2>Per Entrant Limits</h2>";
	
	if (!empty($row_limits['prefsUserEntryLimit'])) {
		if ($row_limits['prefsUserEntryLimit'] == 1) $page_info16 .= sprintf("<p>Each entrant is limited to %s entry for this competition.</p>",readable_number($row_limits['prefsUserEntryLimit'])." (".$row_limits['prefsUserEntryLimit'].")");
		else $page_info16 .= sprintf("<p>Each entrant is limited to %s entries for this competition.</p>",readable_number($row_limits['prefsUserEntryLimit'])." (".$row_limits['prefsUserEntryLimit'].")");
	}
	
	if (!empty($row_limits['prefsUserSubCatLimit'])) { 
		$page_info16 .= "<p>";
		if ($row_limits['prefsUserSubCatLimit'] == 1) $page_info16 .= sprintf("Each entrant is limited to %s entry per sub-style ",readable_number($row_limits['prefsUserSubCatLimit'])." (".$row_limits['prefsUserSubCatLimit'].")");
		else $page_info16 .= sprintf("Each entrant is limited to %s entries per sub-style ",readable_number($row_limits['prefsUserSubCatLimit'])." (".$row_limits['prefsUserSubCatLimit'].")");
		if (!empty($row_limits['prefsUSCLExLimit'])) $page_info16 .= " (exceptions are detailed below)";
		$page_info16 .= ".";
		$page_info16 .= "</p>";

	}
	
	if (!empty($row_limits['prefsUSCLExLimit'])) { 
	$excepted_styles = explode(",",$row_limits['prefsUSCLEx']);
	if (count($excepted_styles) == 1) $sub = "sub-category"; else $sub = "sub-categories";
		if ($row_limits['prefsUSCLExLimit'] == 1) $page_info16 .= sprintf("<p>Each entrant is limited to %s for the following %s: </p>",readable_number($row_limits['prefsUSCLExLimit'])." (".$row_limits['prefsUSCLExLimit'].")",$sub);
		else $page_info16 .= sprintf("<p>Each entrant is limited to %s entries for for the following %s: </p>",readable_number($row_limits['prefsUSCLExLimit'])." (".$row_limits['prefsUSCLExLimit'].")",$sub);
		$page_info16 .= style_convert($row_limits['prefsUSCLEx'],"7");

	}
	
}



// Payment
$header1_6 .= "<a name='payment'></a><h2>Pagamento</h2>";
$page_info6 .= "<p>Depois de realizar sue registro e de cadastrar suas amostrasm você deve efetuar o pagamento da taxa por cada amostra. Os métodos de pagamento aceitos são:</p>";
$page_info6 .= "<ul>";
if ($_SESSION['prefsCash'] == "Y") $page_info6 .= "<li>Cash</li>";
if ($_SESSION['prefsCheck'] == "Y") $page_info6 .= sprintf("<li>Check, made out to <em>%s</em></li>",$_SESSION['prefsCheckPayee']);
if ($_SESSION['prefsPaypal'] == "Y") $page_info6 .= "<li>PagSeguro</li>";
//if ($_SESSION['prefsGoogle'] == "Y") $page_info6 .= "<li>Google Wallet</li>"; 
$page_info6 .= "</ul>";

// Judging Dates
if ($totalRows_judging > 1) $header1_7 .= "<a name='judging'></a><h2>Local e Data dos Julgamentos</h2>";
else $header1_7 .= "<a name='judging'></a><h2>Local e Data dos Julgamentos</h2>";

	if ($totalRows_judging == 0) $page_info7 .= "<p>The competition judging date is yet to be determined. Please check back later.";
	else {
		do {
			$page_info7 .= "<p>";
			$page_info7 .= "<strong>".$row_judging['judgingLocName']."</strong>";
			if ($row_judging['judgingLocation'] != "") $page_info7 .= "<br />".$row_judging['judgingLocation'];
			if (($row_judging['judgingLocation'] != "") && ($action != "print"))  {
				$page_info7 .= "&nbsp;<span class='icon'><a id='modal_window_link' href='".$base_url."output/maps.php?section=map&amp;id=".str_replace(' ', '+', $row_judging['judgingLocation'])."' title='Map to ".$row_judging['judgingLocName']."'><img src='".$base_url."images/map.png'  border='0' alt='Map ".$row_judging['judgingLocName']."' title='Map ".$row_judging['judgingLocName']."' /></a></span>";
				$page_info7 .= "<span class='icon'><a href='".$base_url."output/maps.php?section=driving&amp;id=".str_replace(' ', '+', $row_judging['judgingLocation'])."' target='_blank' title='Map to ".$row_judging['judgingLocName']."'><img src='".$base_url."images/car.png'  border='0' alt='Map ".$row_judging['judgingLocName']."' title='Driving Directions to ".$row_judging['judgingLocName']."' /></a></span>";
			}
			if ($row_judging['judgingDate'] != "") $page_info7 .= "<br />".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time")."<br />";
			$page_info7 .= "</p>";
		} while ($row_judging = mysql_fetch_assoc($judging));
	}


// Categories Accepted
$header1_8 .= "";
$page_info8 .= "";

$header1_8 .= "<a name='categories'></a><h2>Categorias Aceitas: ".str_replace("2"," 2",$row_styles['brewStyleVersion'])."</h2>";
$page_info8 .= "<table class='dataTableCompact' style='border-collapse:collapse;'>";
$page_info8 .= "<tr>"; 

$styles_endRow = 0;
$styles_columns = 3;   // number of columns
$styles_hloopRow1 = 0; // first row flag

do {
	if (($styles_endRow == 0) && ($styles_hloopRow1++ != 0)) $page_info8 .= "<tr>";
	
	$page_info8 .= "<td>";
	$page_info8 .= ltrim($row_styles['brewStyleGroup'], "0").$row_styles['brewStyleNum']." ".$row_styles['brewStyle']; 
	if ($row_styles['brewStyleOwn'] == "custom") $page_info8 .= " (Custom Style)";
	$page_info8 .= "</td>";
	
	$styles_endRow++;
	if ($styles_endRow >= $styles_columns) { $styles_endRow = 0; }
		
} while ($row_styles = mysql_fetch_assoc($styles));

if ($styles_endRow != 0) {
		while ($styles_endRow < $styles_columns) {
			$page_info8 .= "<td>&nbsp;</td>";
			$styles_endRow++;
		}
	$page_info8 .= "</tr>"; 
}


$page_info8 .= "</table>";

// Bottle Acceptance
if ($row_contest_info['contestBottles'] != "") {
	$header1_9 .= "<a name='bottle'></a><h2>Regras de Aceitação de Garrafas</h2>";
	$page_info9 .= $row_contest_info['contestBottles'];
}

// Shipping Locations
if ($_SESSION['contestShippingAddress'] != "") {
	$header1_10 .= "<a name='shipping'></a><h2>Locais para Envio de Garrafas</h2>";
	$page_info10 .= "<p>";
	$page_info10 .= $_SESSION['contestShippingName'];
	$page_info10 .= "<br>";
	$page_info10 .= $_SESSION['contestShippingAddress'];
	$page_info10 .= "</p>";
    $page_info10 .= "<h3>Empacotamento e envio</h3>";
    $page_info10 .= "<p><strong>Embale cuidadosamente suas garrafas em uma caixa resistente. Forre o interior da caixa com um saco plástico. Embale cada carrafa com sua proteção individual!</strong>";
	$page_info10 .= "<p>Escreva claramente \"Frágil! Este lado para cima\". na embalagem. Utilize plástico bolha para embalar cada garrafa. Evitar utilizar isopor ou papel.</p>";
    $page_info10 .= "<p>Coloque <em>cada</em> etiqueta das garrafas em um saco plástico (ou utilize fita transparente para plastificar) antes de fixar a mesma com o elástico. Assim em caso de algum dano com as garrafas vamos conseguir identificar as amostras..</p>";
    $page_info10 .= "<p>Caso alguma garrafa chegue danificada, a organização entrará em contato para solicitar garrafas extras se necessário.</p>";

}

// Drop Off
if ($totalRows_dropoff > 0) {
	if ($totalRows_dropoff == 1) $header1_11 .= "<a name='drop'></a><h2>Locais para Entrega de Garrafas</h2>";
	else $header1_11 .= "<a name='drop'></a><h2>Locais para Entrega de Garrafas</h2>";
	do {
		$page_info11 .= "<p>";
		if ($row_dropoff['dropLocationWebsite'] != "") $page_info11 .= sprintf("<a href='%s' target='_blank'><strong>%s</strong></a>",$row_dropoff['dropLocationWebsite'],$row_dropoff['dropLocationName']);
		else $page_info11 .= sprintf("<strong>%s</strong>",$row_dropoff['dropLocationName']);
		$page_info11 .= "<br />";
		$page_info11 .= $row_dropoff['dropLocation'];
		if ($action != "print") {
			$page_info11 .= "&nbsp;<span class='icon'><a id='modal_window_link' href='".$base_url."output/maps.php?section=map&amp;id=".str_replace(' ', '+', $row_dropoff['dropLocation'])."' title='Map to ".$row_dropoff['dropLocationName']."'><img src='".$base_url."images/map.png'  border='0' alt='Map ".$row_dropoff['dropLocationName']."' title='Map ".$row_dropoff['dropLocationName']."' /></a></span>";
			$page_info11 .= "<span class='icon'><a href='".$base_url."output/maps.php?section=driving&amp;id=".str_replace(' ', '+', $row_dropoff['dropLocation'])."' target='_blank' title='Map to ".$row_dropoff['dropLocationName']."'><img src='".$base_url."images/car.png'  border='0' alt='Map ".$row_dropoff['dropLocationName']."' title='Driving Directions to ".$row_dropoff['dropLocationName']."' /></a></span>";
		}
		$page_info11 .= "<br />";
		$page_info11 .= $row_dropoff['dropLocationPhone'];
		$page_info11 .= "<br />";
		if ($row_dropoff['dropLocationNotes'] != "") $page_info11 .= sprintf("*<em>%s</em>",$row_dropoff['dropLocationNotes']);
		$page_info11 .= "</p>";
	 } while ($row_dropoff = mysql_fetch_assoc($dropoff));
}

// Best of Show
if ($row_contest_info['contestBOSAward'] != "") {
	$header1_12 .= "<a name='bos'></a><h2>Best of Show</h2>";
	$page_info12 .= $row_contest_info['contestBOSAward'];;
}

// Awards and Awards Ceremony Location
if ($row_contest_info['contestAwards'] != "") {
	$header1_13 .= "<a name='awards'></a><h2>Premiações</h2>";
	$page_info13 .= $row_contest_info['contestAwards'];;
}

if ($_SESSION['contestAwardsLocName'] != "") {
	$header1_14 .= "<a name='ceremony'></a><h2>Cerimônia de Premiação</h2>";
	$page_info14 .= "<p>";
	$page_info14 .= sprintf("<strong>%s</strong>",$_SESSION['contestAwardsLocName']);
	if ($_SESSION['contestAwardsLocation'] != "") $page_info14 .= sprintf("<br />%s",$_SESSION['contestAwardsLocation']);
	if (($_SESSION['contestAwardsLocation'] != "") && ($action != "print")) {
		$page_info14 .= "&nbsp;<span class='icon'><a id='modal_window_link' href='".$base_url."output/maps.php?section=map&amp;id=".str_replace(' ', '+', $_SESSION['contestAwardsLocation'])."' title='Map to ".$_SESSION['contestAwardsLocName']."'><img src='".$base_url."images/map.png'  border='0' alt='Map ".$_SESSION['contestAwardsLocName']."' title='Map ".$_SESSION['contestAwardsLocName']."' /></a></span>";
		$page_info14 .= "<span class='icon'><a href='".$base_url."output/maps.php?section=driving&amp;id=".str_replace(' ', '+', $_SESSION['contestAwardsLocation'])."' target='_blank' title='Map to ".$_SESSION['contestAwardsLocName']."'><img src='".$base_url."images/car.png'  border='0' alt='Map ".$_SESSION['contestAwardsLocName']."' title='Map ".$_SESSION['contestAwardsLocName']."' /></a></span>";

	}
	if ($_SESSION['contestAwardsLocTime'] != "") $page_info14 .= sprintf("<br />%s",getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestAwardsLocTime'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time"));
	$page_info14 .= "</p>";
	
}

// Circuit Qualification
if ($row_contest_info['contestCircuit'] != "") {
	$header1_15 .= "<a name='circuit'></a><h2>Circuit Qualification</h2>";
	$page_info15 .= $row_contest_info['contestCircuit'];
}


// --------------------------------------------------------------
// Display
// --------------------------------------------------------------
if (($action != "print") && ($msg != "default")) echo $msg_output;
if ((($_SESSION['contestLogo'] != "") && (file_exists($_SERVER['DOCUMENT_ROOT'].$sub_directory.'/user_images/'.$_SESSION['contestLogo']))) && ((judging_date_return() > 0) || (NHC))) echo $competition_logo;
if ($action != "print") echo $print_page_link;

if ($action != "print") echo $anchor_links; 
echo $header1_1;
echo $page_info1;
echo $header1_2;
echo $page_info2;

echo $header1_3;
echo $page_info3;

echo $header1_4;
echo $page_info4;

echo $header1_5;
echo $page_info5;

echo $header1_16;
echo $page_info16;

echo $header1_6;
echo $page_info6;

echo $header1_7;
echo $page_info7;

echo $header1_8;
echo $page_info8;

echo $header1_9;
echo $page_info9;

echo $header1_10;
echo $page_info10;

echo $header1_11;
echo $page_info11;

echo $header1_12;
echo $page_info12;

echo $header1_13;
echo $page_info13;

echo $header1_14;
echo $page_info14;

echo $header1_15;
echo $page_info15;


?>

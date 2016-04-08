<?php
session_start();
	include('../paths.php');
	include(CONFIG.'config.php');  
	include(INCLUDES.'url_variables.inc.php');
	include(INCLUDES.'db_tables.inc.php');
	include(LIB.'common.lib.php');
	include(CONFIG.'psconfig.php');
		
	mysql_select_db($database, $brewing);
	

//Busca do banco os dados do brewer
$query_brewer = sprintf("SELECT * FROM $brewer_db_table WHERE uid = '%s'",  $_SESSION['user_id']);
$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
$row_brewer = mysql_fetch_assoc($brewer);

$userId = $_SESSION['user_id'];
$userNm =  rtrim($_SESSION['loginUsername']);
$brewBrewerFirstName=  rtrim($row_brewer['brewerFirstName']);
$brewBrewerLastName	=  rtrim($row_brewer['brewerLastName']);
$userDoc =  str_replace(array("-","."), "",$row_brewer['brewerCPF']);
$brewerPhone= str_replace(array("-","(",")"," "), "", $row_brewer['brewerPhone1']);
$brewerPhone1[0]= substr($brewerPhone, 0, 2);
$brewerPhone1[1]= substr($brewerPhone, 2, 9);

$valorUnitario = $_SESSION['contestEntryFee'];



$strRefe	= "";
$strDesc	= "";
$strChave	= "";
$quantidade = 0;

$sQuery 	= "SELECT id, brewName FROM " . $prefix . "brewing where brewBrewerID = $userId AND brewPaid = 0";
$brewer = mysql_query($sQuery, $brewing) or die(mysql_error());
$row_brewing = mysql_fetch_assoc($brewer);
do
{	
	$strDesc   .= substr($row_brewing['brewName'], 0, 10) . "/";	
	$strChave  .= $row_brewing['id'] . "@";	
	$quantidade++;
}
while($row_brewing = mysql_fetch_assoc($brewer));

$strDesc   = rtrim($strDesc,"/");
$strChave  = rtrim($strChave,"@");

$valorTotal = 0;
$valorTotal = $quantidade * $valorUnitario;
//$valorTotal = sprintf('%0.2f', $valorTotal);

$valorTotal = number_format($valorTotal , 2, '.', '');

////////////////////////////////////////////////////////////////////////////////////
$userMl 	= $userNm;

$emailPagSeguro				= $PAGSEGURO_CONFIG['EMAILPAGSEGURO'];
$tokenPagSeguro				= $PAGSEGURO_CONFIG['TOKENPAGSEGURO'];
$urlPagSeguro				= $PAGSEGURO_CONFIG['URLPAGSEGURO'];

$data_ps['email'] 			= $emailPagSeguro;
$data_ps['token'] 			= $tokenPagSeguro;
$data_ps['currency'] 		= 'BRL';

$data_ps['itemId1'] 		= "PAGAMENTO " . $quantidade . " AMOSTRAS";
$data_ps['itemDescription1']= utf8_decode( $strDesc );
$data_ps['itemAmount1'] 	= $valorTotal;
$data_ps['itemQuantity1'] 	= "1";
$data_ps['itemWeight1'] 	= "0";

$data_ps['reference'] 		= "";
$data_ps['senderName'] 		= utf8_decode ( $brewBrewerFirstName . " " . $brewBrewerLastName );
$data_ps['senderAreaCode'] 	= $brewerPhone1[0];
$data_ps['senderPhone'] 	= $brewerPhone1[1];
$data_ps['senderEmail'] 	= $userMl;
$data_ps['senderCPF'] 		= $userDoc;

$data_ps['shippingType']	= 3;

$data_ps 	= http_build_query($data_ps);
$curl1 		= curl_init($urlPagSeguro);

curl_setopt($curl1, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl1, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl1, CURLOPT_POST, true);
curl_setopt($curl1, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($curl1, CURLOPT_POSTFIELDS, $data_ps);

$xml= curl_exec($curl1);

if($xml == 'Unauthorized')
{			
	header("Content-type: text/xml;");
	header("Cache-Control: no-cache");
	
	$newsXML = new SimpleXMLElement("<resposta></resposta>");	
	$newsXML->addChild('status',0);	
	$newsXML->addChild('mensagem',"Unauthorized");	
	echo $newsXML->asXML();
	return;
}
else
{
	$xml= simplexml_load_string($xml);
	if(count($xml -> error) > 0)
	{
		header("Content-type: text/xml;");
		header("Cache-Control: no-cache");
		
		$newsXML = new SimpleXMLElement("<resposta></resposta>");	
		$newsXML->addChild('status',0);	
		$newsXML->addChild('mensagem',"Problema com os Dados");	
		echo $newsXML->asXML();
		return;
	}
	else
	{
		header("Content-type: text/xml;");
		header("Cache-Control: no-cache");
		
		$newsXML = new SimpleXMLElement("<resposta></resposta>");	
		$newsXML->addChild('status',1);	
		$newsXML->addChild('mensagem',$xml->code);
		$newsXML->addChild('chaves',$strChave);
		echo $newsXML->asXML();
		return;
	}
}


?>

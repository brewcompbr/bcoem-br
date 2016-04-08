<?php
ob_start();
include('../paths.php');
// Based upon a script from www.plus2net.com 
require(CONFIG.'config.php');
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php');
require(LIB.'common.lib.php'); 
require(DB.'common.db.php');

require(CLASSES.'phpass/PasswordHash.php');
$hasher = new PasswordHash(8, false);

if (NHC) $base_url = "../";
else $base_url = $base_url;
mysql_select_db($database, $brewing);


if (($action == "email") && ($id != "default")) {
	
	
	
	if (NHC) {
	// Place NHC SQL calls below
	
	
	}
	// end if (NHC)
	
	else {
	
		$query_forgot = "SELECT * FROM $users_db_table WHERE id = '$id'";
		$forgot = mysql_query($query_forgot, $brewing) or die(mysql_error());
		$row_forgot = mysql_fetch_assoc($forgot);
		$totalRows_forgot = mysql_num_rows($forgot);
		
		$query_brewer = "SELECT brewerLastName,brewerFirstName FROM $brewer_db_table WHERE uid = '$id'";
		$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
		$row_brewer = mysql_fetch_assoc($brewer);
		$totalRows_brewer = mysql_num_rows($brewer);
	
		$first_name = ucwords(strtolower($row_brewer['brewerFirstName']));
		$last_name = ucwords(strtolower($row_brewer['brewerLastName']));
	
	}
	
	
	
	$to_recipient = $first_name." ".$last_name;
	$to_email = $row_forgot['user_name'];
	$subject = $_SESSION['contestName'].": Verificação de Segurança";
	$message = "<html>" . "\r\n";
	$message .= "<body>" . "\r\n";
	$message .= "<p>".$first_name.",</p>";
	$message .= "<p>Um pedido de verificação de sua conta na competição  ".$_SESSION['contestName']." foi requerida. Se você não solicitou esse email, entre em contato com a organização.</p>";
	$message .= "<table cellpadding='0' border='0'><tr><td><strong>Questão de segurança:</strong></td><td>".$row_forgot['userQuestion']."</td>";
	$message .= "<tr><td><strong>Resposta:</strong></td><td>".$row_forgot['userQuestionAnswer']."</td></tr></table>";
	$message .= "<p><em>*Digite a resposta como recebida nesse email.</em></p>";
	$message .= "<p>Por favor não responda a esse email. Ele é gerado automáticamente.</p>";
	$message .= "</body>" . "\r\n";
	$message .= "</html>";
	
	//$parse = parse_url($_SERVER['SERVER_NAME']);
	//print_r($parse);
	//$url = $parse['host'];
	//$url = preg_replace('#^www\.(.+\.)#i', '$1', $parse['host']);
	$url = str_replace("www.","",$_SERVER['SERVER_NAME']);
	
	$headers  = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
	$headers .= "To: ".$to_recipient. " <".$to_email.">, " . "\r\n";
	$headers .= "From: Competition Server <noreply@".$url. ">\r\n";
	
	$emails = $to_email;
	mail($emails, $subject, $message, $headers);
	
	//echo $headers."<br>";
	//echo $subject."<br>";
	//echo $message;
	header(sprintf("Location: %s", $base_url."index.php?section=login&action=forgot&go=verify&msg=5&username=".$to_email));
}

else {
$username = $_POST['loginUsername'];

	if (NHC) {
		// Place NHC SQL calls below
		
		
	}
	// end if (NHC)
	
	else {
	
		$query_forgot = "SELECT * FROM $users_db_table WHERE user_name = '$username'";
		$forgot = mysql_query($query_forgot, $brewing) or die(mysql_error());
		$row_forgot = mysql_fetch_assoc($forgot);
		$totalRows_forgot = mysql_num_rows($forgot);
	
	}



if ($totalRows_forgot == 0) { header(sprintf("Location: %s", $base_url."index.php?section=login&action=forgot&msg=1")); }
if ($_POST['userQuestionAnswer'] == $row_forgot['userQuestionAnswer']) { //if answer is correct

/*
echo $username."<br>";
echo $query_forgot."<br>";
echo $row_forgot['user_name']."<br>";
echo $totalRows_forgot."<br>";

$em = $row->username;// email is stored to a variable

// Send the email with key
*/

$key = random_generator(10,1);

$password = md5($key);
$hash = $hasher->HashPassword($password);
	
	if (NHC) {
	// Place NHC SQL calls below
	
	
	}
	// end if (NHC)
	
	else {
	
		mysql_select_db($database, $brewing);
		$updateSQL = sprintf("UPDATE $users_db_table SET password='%s' WHERE id='%s'", $hash, $row_forgot['id']);
		$Result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	}
	
  	$updateGoTo = $base_url."index.php?section=login&go=".$key."&msg=2";
  	header(sprintf("Location: %s", $updateGoTo)); 

} else {
	
	header(sprintf("Location: %s", $base_url."index.php?section=login&action=forgot&go=verify&msg=4&username=".$username)); 
	
}

/*
$headers4	 = $_SESSION['contestContactEmail'];
$headers	.= "Reply-to: $headers4\n";
$headers 	.= "From: $headers4\n"; 
$headers 	.= "Errors-to: $headers4\n"; 
$headers 	= "Content-Type: text/html; charset=iso-8859-1\n".$headers;// for html mail
 
if (mail("$em","Password Reset","This is in response to your request for a password reset at from the ".$_SESSION['contestName']." entry site. \n \nYour user name: $row->userid \n Your new password: ".$key."\n\n
\n\n Thank You \n \n The ".$_SESSION['contestName']." Staff","$headers")) 
{
header(sprintf("Location: %s", $base_url."index.php?section=login&action=forgot&msg=2"));
}
else
{ 
header(sprintf("Location: %s", $base_url."index.php?section=login&action=forgot&msg=3"));
}
*/

//}
}
?>
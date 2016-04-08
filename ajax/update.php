<?php
//header("access-control-allow-origin: https://sandbox.pagseguro.uol.com.br");
session_start();
	include('../paths.php');
	include(CONFIG.'config.php');  
	include(INCLUDES.'url_variables.inc.php');
	include(INCLUDES.'db_tables.inc.php');
	include(LIB.'common.lib.php');
	include(CONFIG.'psconfig.php');
	
	
	
	mysql_select_db($database, $brewing);

	error_log( "update.php: ".date("Y-m-d h:i:sa"));
	error_log( "update.php: ".$_POST['notificationType']." - ". $_POST['notificationCode']);
	
   
	if(isset($_POST['notificationType']) && $_POST['notificationType'] == 'transaction')
	{	
		$email 	= $PAGSEGURO_CONFIG['EMAILPAGSEGURO'];
		$token 	= $PAGSEGURO_CONFIG['TOKENPAGSEGURO'];
		$url 	= $PAGSEGURO_CONFIG['URLNOTIFICACOES'] . $_POST['notificationCode'] . '?email=' . $email . '&token=' . $token;
		error_log( "update.php: ".$url);
		$curl 	= curl_init($url);
		//echo $url;
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$trans 	= curl_exec($curl);
		curl_close($curl);		
		
		
		if($trans == 'Unauthorized')
		{		
			echo "PROBLEMAS";
			exit;//Mantenha essa linha
		}
		$transaction 	= simplexml_load_string($trans);
		
		//echo $transaction;		
		$status 		= $transaction -> status;
		$reference 		= $transaction -> reference;
		$grossAmount	= $transaction -> grossAmount; 	
		$code			= $transaction -> code; 	
		
		error_log( "update.php: Status: ".$status);
		error_log( "update.php: reference: ".$reference);
		error_log( "update.php: grossAmount: ".$grossAmount);
		error_log( "update.php: Code: ".$code);
		
		
		$itemId			= $transaction -> items -> item -> id; 	
		error_log( "update.php: item: ".$itemId);
		
		error_log( "update.php: Verificando se já existe um transação");
		$selectSQL = sprintf("select id from  " . $prefix . "pagseguro where transacao = '%s' ",$code);
		$trans = mysql_query($selectSQL, $brewing) or die(mysql_error());
		
		if (!$trans || mysql_num_rows($trans) == 0) {
			error_log( "update.php: Não há transação: Inserindo: ".$code);
			$insertSQL 	= sprintf("INSERT INTO " . $prefix . "pagseguro (transacao, status, date_created, date_modified) VALUES ('%s', %s, NOW(),null)",$code,$status);
			$newTrans = mysql_query($insertSQL, $brewing) or die(mysql_error());
		} else {
			error_log( "update.php: Há transação: Atualizando status: ".$code);
			$updateSQL = sprintf("UPDATE " . $prefix . "pagseguro set status = %s, date_modified = NOW()  where transacao = '%s' ",$status,$code);
			mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
		
		if($status==3)
		{		
			$itemId = ltrim($itemId, '0');
			//para cada code , procurar em payment pelo id
			$query1= sprintf("select id from " . $prefix . "payment where idTransaction = '%s'",$code);
			$result = mysql_query($query1, $brewing) or die(mysql_error());
			
			if (!$result || mysql_num_rows($result) == 0) {
				error_log( "update.php: Ainda não finalizou a compra Retornando: ".$code);
				$message = "FAIL 1 " . $itemId . " " . $query1 ;
				//mail($emailDestino, $subject, $message, $headers);
				echo $message;
				return;
			}
			$row_trans = mysql_fetch_assoc($result);
			$idTrans = $row_trans['id'];
			
			$consult_query2 = "update " . $prefix . "brewing set brewPaid='1' where brewPayTransId = $idTrans";
			if ($result = mysql_query($consult_query2, $brewing) or die(mysql_error()))
			{	
				$message = "OK 1 " . $itemId . " " . $consult_query2 ;
				//mail($emailDestino, $subject, $message, $headers);
				echo $message;
			}
			else
			{
				$message = "FAIL 1 " . $itemId . " " . $consult_query2 ;
				//mail($emailDestino, $subject, $message, $headers);
				echo $message;
			}			
	
		}
		elseif($status==2)
		{
			
			$updateSQL = sprintf("UPDATE " . $prefix . "payment set statusPayment = %s  where brewBrewerId = %s and idTransaction = %s","4",$userData['idUser'] ,$code);
			mysql_query($updateSQL, $brewing) or die(mysql_error());
				$message = "OK 4 " . $itemId . " " . $consult_query5 ;
				//mail($emailDestino, $subject, $message, $headers);
				echo $message;
		}
		else
			echo "Sem processamento para o Status:" . $status;		
	}

?>
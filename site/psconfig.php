<?php
$PAGSEGURO_CONFIG['EMAILPAGSEGURO'] 	= "email_conta_pagseguro";

//prod
$PAGSEGURO_CONFIG['TOKENPAGSEGURO'] 	= "token";//prod
$PAGSEGURO_CONFIG['URLPAGSEGURO'] 	= "https://ws.pagseguro.uol.com.br/v2/checkout";
$PAGSEGURO_CONFIG['URLNOTIFICACOES']	= "https://ws.pagseguro.uol.com.br/v2/transactions/notifications/";
$PAGSEGURO_CONFIG['JSLIBRARY'] = "https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js";
		
//sandbox
//$PAGSEGURO_CONFIG['TOKENPAGSEGURO'] 	= "token";//sandbox
//$PAGSEGURO_CONFIG['URLPAGSEGURO'] 	= "https://ws.sandbox.pagseguro.uol.com.br/v2/checkout";
//$PAGSEGURO_CONFIG['URLNOTIFICACOES']	= "https://ws.sandbox.pagseguro.uol.com.br/v2/transactions/notifications/";
//$PAGSEGURO_CONFIG['JSLIBRARY'] = $base_url."js_includes/PSLightbox.js";
?>

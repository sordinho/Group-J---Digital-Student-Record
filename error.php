<?php
/* Array per la gestione degli errori */
$messages = array(
	1 => 'Il campo username è obbligatorio.',
	2 => 'Il campo email è obbligatorio.',
	3 => 'Il campo password è obbligatorio.',
	4 => 'Le due password non coincidono.',
	5 => 'Il campo username contiene caratteri non validi. Sono consentiti solo lettere, numeri il i seguenti simboli . _ -.',
	6 => 'Inserisci una email valida.',
	7 => 'La password scelta è eccessivamente breve.<br>Scegli una password di almeno 5 caratteri.',
	8 => 'Esiste già un utente registrato con questo username.',
	9 => 'Esiste già un utente registrato con questa email.',
	10 => 'Registrazione effettuata con successo.<br>',
	11 => 'Login errato',
	12 => 'Login eseguito con successo.',
	13 => 'Logout eseguito con successo.',
	14 => 'Per accedere a questa pagina occorre essere loggati.',
	15 => 'Compilare tutti i campi ed inserire almeno un numero di telefono.',
	17 => 'Attivazione avvenuta con successo!',
	18 => 'Tentativo di manomissione dei parametri.<br>Questo non è divertente.',
);
if(isset($_GET['message'])){
	$message_script = $_GET['message'];
}
$key = intval($message_script);
if(array_key_exists($key, $messages)){
	print($messages[$key]);
}
if(isset($_SERVER['HTTP_REFERER']) && !isset($_GET['noref'])){//Se no ref è settato dopo l'errore non si torna alla pagina di referer
	$referer = $_SERVER['HTTP_REFERER'];
}
else{
	$referer = "./index.php";
}
die( "<meta http-equiv='refresh' content='2; url=$referer' />");
?>
<?php
/*
 *  CONFIGURE EVERYTHING HERE
 */

// an email address that will be in the From field of the email.
$from = 'Saludos <prueba01@farmaciasknop.com>';

// an email address that will receive the email with the output of the form
$sendTo = 'Saludos <prueba01@farmaciasknop.com>';

// subject of the email
$subject = 'Nuevo mensaje desde Live Shopping';

// form field names and their translations.
// array variable name => Text to appear in the email
$fields = array('nombre' => 'Nombre','email' => 'Email'); 

// message that will be displayed when everything is OK :)
$okMessage = 'Gracias por la suscripción, te estaré enviando las ofertas sin spam!';

// If something goes wrong, we will display this message.
$errorMessage = 'Oops! Hubo un error al enviar el formulario. Por favor, inténtelo de nuevo más tarde';

/*
 *  LET'S DO THE SENDING
 */

// if you are not debugging and don't need error reporting, turn this off by error_reporting(0);
error_reporting(E_ALL & ~E_NOTICE);

try
{

    if(count($_POST) == 0) throw new \Exception('Saludo está vacío');
            
    $emailText = "Tienes un nuevo mensaje desde Live Shopping\n=============================\n";

    foreach ($_POST as $key => $value) {
        // If the field exists in the $fields array, include it in the email 
        if (isset($fields[$key])) {
            $emailText .= "$fields[$key]: $value\n";
        }
    }

    // All the necessary headers for the email.
    $headers = array('Content-Type: text/plain; charset="UTF-8";',
        'De: ' . $from,
        'Para: ' . $_POST['email'],
        'Return-Path: ' . $from,
    );
    
    // Send email
    mail($sendTo, $subject, $emailText, implode("\n", $headers));

    $responseArray = array('type' => 'success', 'mensaje' => $okMensaje);
}
catch (\Exception $e)
{
    $responseArray = array('type' => 'danger', 'mensaje' => $errorMensaje);
}


// if requested by AJAX request return JSON response
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);

    header('Content-Type: application/json');

    echo $encoded;
}
// else just display the message
else {
    echo $responseArray['mensaje'];
}
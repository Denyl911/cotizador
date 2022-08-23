<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['archivo']) && isset($_POST['correo'])) {
    $data = json_decode(file_get_contents('php://input'), true);
    $from = "rodrigo@micreditus.com";
    $subject = "Cotización - MiCreditus";

    $valor = $_POST["valor"];
    $importe = $_POST["importe"];
    $plazo = $_POST["plazo"];
    $banco = $_POST["banco"];
    $name = $_POST["nombre"];
    $telefono = $_POST["telefono"];
    $correo = $_POST["correo"];
    $message = "Nombre: " . $name . "\n" . "Correo: " . $correo . "\n" . "Telefono: " . $telefono . "\n" . "Valor de la vivienda: " . $valor . "\n" . "Importe de credito solicitado: " . $importe . "\n" . "Plazo: " . $plazo . "\n" . "Banco: " . $banco . "\n";

    //Get uploaded file data using $_FILES array
    $tmp_name = $_FILES['archivo']['tmp_name']; // get the temporary file name of the file on the server
    $name = $_FILES['archivo']['name']; // get the name of the file
    $size = $_FILES['archivo']['size']; // get size of the file for size validation
    $type = $_FILES['archivo']['type']; // get type of the file
    $error = $_FILES['archivo']['error']; // get the error (if any)

    if ($error > 0) {
        echo 'Upload error or No files uploaded';
        die('Upload error or No files uploaded');
    }

    //read from the uploaded file & base64_encode content
    $handle = fopen($tmp_name, "r"); // set the file handle only for reading the file
    $content = fread($handle, $size); // reading the file
    fclose($handle); // close upon completion

    $encoded_content = chunk_split(base64_encode($content));
    $boundary = md5("random"); // define boundary with a md5 hashed value

    //header
    $headers = "MIME-Version: 1.0\r\n"; // Defining the MIME version
    $headers .= "From: Rodrigo Puerto - MiCreditus <" . $from . ">\r\n"; // Sender Email
    //$headers .= "Reply-To: ".$from."\r\n"; // Email address to reach back
    $headers .= "Return-Path:" . $from . "\r\n";
    $headers .= "Content-Type: multipart/mixed;"; // Defining Content-Type
    $headers .= "boundary = $boundary\r\n"; //Defining the Boundary

    //plain text
    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $body .= chunk_split(base64_encode($message));

    //attachment
    $body .= "--$boundary\r\n";
    $body .= "Content-Type: $type; name=" . $name . "\r\n";
    $body .= "Content-Disposition: attachment; filename=" . $name . "\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n";
    $body .= "X-Attachment-Id: " . rand(1000, 99999) . "\r\n\r\n";
    $body .= $encoded_content; // Attaching the encoded file with email

    $sentMailResult = mail($correo, $subject, $body, $headers);

    if ($sentMailResult) {
        echo json_encode('{"message": "success"}');
    } else {
        echo json_encode('{"message": "Error al enviar el email"}');
        die("Sorry but the email could not be sent.
                    Please go back and try again!");
    }
} else {
    echo ('{"message": "No valido "}' . $_POST['correo'] . "cor");
    echo $_SERVER["REQUEST_METHOD"];
    echo $_FILES['archivo']['name'];
}

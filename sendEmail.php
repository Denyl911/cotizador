<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    // $to = "someone@example.com";
    // $subject = "Contact mail";
    // $from = $_POST["email"];
    // $msg = $_POST["msg"];
    // $headers = "From: $from";

    //mail($to, $subject, $msg, $headers);
    echo json_encode('{"data:' . $data['msg'] . '"}');
} else {
    echo json_encode('{"message: No valido"}');
}

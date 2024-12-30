<?php
$email = $_POST["email"];
$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256",$token);
$expiry = date("Y-m-d H:i:s",time() + 60* 30);
$mysqli =  require __DIR__ . "/connection.php";

$sql="UPDATE users SET reset_token_hash= ?, reset_token_expires_at=? Where email=?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sss", $token_hash,$expiry,$email);
$stmt->execute();


if($mysqli->affected_rows){
    $mail = require __DIR__ . "/mailer.php";

    $mail->setFrom("noreply@example.com");
    $mail->addAddress($email);
    $mail->Subject="Password Reset";
    $mail->Body = <<<End

    Click <a href="http://localhost/SummerProject/reset_password.php?token=$token">here</a> to reset your password.

    End;

    try {

        $mail->send();

    }catch(Exception $e){
        echo"Message could not be send. Mailer error : {$mail->ErrorInfo}";
    }
}
echo "Message sent, please check your inbox.";

?>



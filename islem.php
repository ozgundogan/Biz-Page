<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'PHPMailer/PHPMailer-master/src/Exception.php';
require 'PHPMailer/PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer/PHPMailer-master/src/SMTP.php';

    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->SMTPKeepAlive = true;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';//ssl tls olarak seçildiğinde port ayarı 587 olarak kullanılması tavsiye edilir.

    $mail->Port = 587;//25, ssl ise 465
    $mail->Host = "smtp.gmail.com";

    $mail->Username = "ozgundgn0@gmail.com"; //buraya bir istekte bulunmak için
    $mail->Password = "ozgun1elma567";

    $mail->setFrom("ozgundgn0@gmail.com", "Youtube"); //kimden gideceği
    $mail->addAddress("ozgundgn0@gmail.com", "Ozgun");

    $mail->isHTML(true);//htmlden gönderilecekse mail
    $mail->Subject = "Mesaj gomdermeye çalışıyorum";
    $mail->Body = "yanlış yere yazmışım";

if($mail->send()){
  echo "mail gonderildi";
}else{
  echo "Malesef olmadı";
}
    /*bu dosya göndermek istersek $mail->addAttachment(path"");*/

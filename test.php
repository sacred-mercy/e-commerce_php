<?php

// require_once 'smtp/smtpMailer.php';

$file_path = "invoices/invoice_30.html";

// smtp_mailer("gauravnegi9634@gmail.com", "test mails", "hello", null, null);

require_once 'smtpNew/smtpMailer.php';

smtp_mailer("gauravnegi9634@gmail.com", "test mails", "hello", $file_path, "invoice_30.html");

echo "done";

?>
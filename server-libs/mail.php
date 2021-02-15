<?php
	require_once(__DIR__."/../vendor/autoload.php");

	use PHPMailer\PHPMailer\PHPMailer;

	class MailSystem{
		private string $fromEmail;
		private string $host;
		private int $port;
		private string $username;
		private string $password;

		function __construct()
		{
			$this->fromEmail = "PUT 'FROM' EMAIL HERE";
			$this->username = "PUT SMTP USERNAME HERE";
			$this->password = "PUT SMTP PASSWORD HERE";
			$this->host = "PUT SMTP IP HERE";
			$this->port = "PUT PORT HERE (REMOVE THE QUOTES BECAUSE IT MUST BE AN INTEGER)";
		}

		function sendEmail($to, $subject, $message){
			$mail = new PHPMailer();
			$mail->isSMTP();
			$mail->Host = $this->host;
			$mail->SMTPAuth = true;
			$mail->Username = $this->username;
			$mail->Password = $this->password;
			$mail->Port = $this->port;

			$mail->setFrom($this->fromEmail, "Dispolatcher's Mail System");
			$mail->addAddress($to, "Dispolatcher's dispatcher");
			$mail->isHTML(false);
			$mail->Subject = $subject;
			$mail->Body = $message;
			return $mail->send();
		}
	}
?>
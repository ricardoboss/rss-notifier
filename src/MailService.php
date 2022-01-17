<?php

namespace App;

class MailService
{
	public function send(string $to, string $subject, string $body): void
	{
		mail($to, $subject, $body);
//		file_put_contents('mail.htm', $body);
	}
}

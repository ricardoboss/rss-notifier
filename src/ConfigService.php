<?php

namespace App;

class ConfigService
{
	public function getFeedUrls(): array
	{
		return [
			'https://www.netcup-sonderangebote.de/feed/'
		];
	}

	public function getRecipient(): string
	{
		return 'mail@ricardoboss.de';
	}

	public function load(): void
	{

	}
}

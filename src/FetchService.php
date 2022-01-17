<?php

namespace App;

class FetchService
{
	public function fetchRaw(string $url): string
	{
		return file_get_contents($url);
	}
}

<?php

namespace App;

use DateTime;

class ParserService
{
	public function parseRss(string $feed): array
	{
		$xml = simplexml_load_string($feed);

		$parsedItems = [];
		foreach ($xml->channel->item as $item) {
			$parsedItems[] = [
				'title' => (string)$item->title,
				'link' => (string)$item->guid,
				'pubDate' => (new DateTime((string)$item->pubDate))->format('Y-m-d H:i:s'),
			];
		}

		return $parsedItems;
	}
}

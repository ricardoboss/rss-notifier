<?php

namespace App;

class MarkupService
{
	public function format(array $feeds): string
	{
		$output = '';

		foreach ($feeds as $entries) {
			foreach ($entries as $entry) {
				$output .= $this->formatEntry($entry) . "<br>" . PHP_EOL;
			}
		}

		return $output;
	}

	public function formatEntry(array $entry): string
	{
		return <<<HTML
<dl>
	<dt>{$entry['title']} ({$entry['pubDate']})</dt>
	<dd><a href="{$entry['link']}">link</a></dd>
</dl>
HTML;
	}
}

<?php

namespace App;

class CacheService
{
	private array $data = [];

	public function load(): void
	{
		if (!file_exists('cache.tmp')) {
			return;
		}

		$data = file_get_contents('cache.tmp');
		$this->data = unserialize($data, ['allowed_classes' => false]);
	}

	public function diff(array $entries): array
	{
		return array_diff($entries, $this->data);
	}

	public function update(array $data): void
	{
		$this->data = $data;

		file_put_contents('cache.tmp', serialize($data));
	}
}

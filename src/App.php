<?php
declare(strict_types=1);

namespace App;

use Elephox\Core\Context\Contract\CommandLineContext;
use Elephox\Core\Context\Contract\ExceptionContext;
use Elephox\Core\Contract\App as AppContract;
use Elephox\Core\Handler\Attribute\CommandHandler;
use Elephox\Core\Handler\Attribute\ExceptionHandler;
use Elephox\Core\Handler\Attribute\Http\Any;
use Elephox\Core\Registrar;
use Elephox\Logging\ConsoleSink;
use Elephox\Logging\Contract\Sink;
use Elephox\Logging\GenericSinkLogger;

class App implements AppContract
{
	use Registrar;

	public array $classes = [
		ConsoleSink::class,
		GenericSinkLogger::class,
		CacheService::class,
		ConfigService::class,
		FetchService::class,
		MailService::class,
		MarkupService::class,
		ParserService::class,
	];

	public array $aliases = [
		Sink::class => ConsoleSink::class,
	];

	#[Any("/")]
	#[CommandHandler]
	public function fetchAndNotify(GenericSinkLogger $logger, ConfigService $config, CacheService $cache, FetchService $fetcher, ParserService $parser, MarkupService $formatter, MailService $mail): int
	{
		$logger->info('Fetching and notifying...');

		$config->load();
		$cache->load();

		$feeds = [];
		foreach ($config->getFeedUrls() as $feedUrl) {
			$rawFeed = $fetcher->fetchRaw($feedUrl);
			$feed = $parser->parseRss($rawFeed);

			$feeds[] = $feed;
		}

		$diff = $cache->diff($feeds);
		if (count($diff) === 0) {
			$logger->info('No new items.');

			return 0;
		}

		$cache->update($feeds);
		$formatted = $formatter->format($diff);
		$mail->send($config->getRecipient(), "RSS Polling Service", $formatted);

		$logger->info("Notification sent.");

		return 0;
	}

	#[ExceptionHandler]
	public function globalExceptionHandler(ExceptionContext $context, GenericSinkLogger $logger): int
	{
		$logger->error("An unhandled exception occurred!");
		$logger->error($context->getException()->getMessage());

		return 1;
	}
}

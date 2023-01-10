<?php

namespace App\Kernel\Handler;

use Whoops\Run;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Handlers\AbstractHandler;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\XmlResponseHandler;
use Whoops\Handler\JsonResponseHandler;

class Whoops extends AbstractHandler
{
	/** @var bool */
	private $showDetails;

	public function __construct(bool $showDetails)
	{
		$this->showDetails = $showDetails;
	}

	public function isShowDetails(): bool
	{
		return $this->showDetails;
	}

	public function __invoke(Request $request, Response $response, \Throwable $exception): Response
	{
		$whoops = new Run();

		$whoops->allowQuit(false);
		$whoops->writeToOutput(false);

		$contentType = $this->determineContentType($request);

		$handler = $this->resolveHandler($contentType);
		$whoops->pushHandler(new $handler);
		
		if ($this->showDetails) {
			$content = $whoops->handleException($exception);
		} else {
			$content = $this->parseResponseBody($contentType, [
				'message' => 'Sorry, Something went wrong!',
				'status'  => 'error'
			]);
		}

		return $response->withStatus(500)
			->withHeader('Content-type', $contentType)
			->write($content);
	}
	
	/**
	 * Parses a response body according the given content type.
	 */
	protected function parseResponseBody(string $contentType, $body): string
	{
		// TODO handle XML content type

		switch ($contentType) {
			case 'application/json':
				return json_encode($body);

			case 'text/plain':
			case 'text/html':
				return $body['message'];
		}
	}

	/**
	 * Gets the appropriate Whoops handler according to the content type 
	 * given by the request header 'accept' which indicates what the 
	 * content type is expecting in the returned response.
	 */
	protected function resolveHandler(string $contentType): string
	{
		switch ($contentType) {
			case 'application/json':
				return JsonResponseHandler::class;

			case 'text/xml':
			case 'application/xml':
				return XmlResponseHandler::class;

			case 'text/html':
				return PrettyPageHandler::class;

			default:
				return PlainTextHandler::class;
		}
	}
}

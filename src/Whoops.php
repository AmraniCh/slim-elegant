<?php

namespace App\Kernel;

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
	protected $showDetails;

	/**
	 * @param bool $showDetails
	 */
	public function __construct($showDetails)
	{
		$this->showDetails = $showDetails;
	}

	public function __invoke(Request $request, Response $response, \Throwable $exception): Response
	{
		$whoops = new Run();
		$whoops->allowQuit(false);
		$whoops->writeToOutput(false);
		$contentType = $this->determineContentType($request);
		$handler = $this->getHandlerByContentType($contentType);
		$whoops->pushHandler(new $handler);
		$content = $whoops->handleException($exception);
		if (!$this->showDetails) {
			$data = [
				'message' => 'Sorry, Something went wrong!',
				'status'  => 'error'
			];

			// TODO handle XML requests
			switch ($contentType) {
				case 'application/json':
					$content = json_encode($data);
					break;

				case 'text/plain':
				case 'text/html':
					$content = $data['message'];
					break;
			}
		}

		return $response->withStatus(500)
			->withHeader('Content-type', $contentType)
			->write($content);
	}

	/**
	 * Gets the appropriate Whoops handler for the incoming request's content type
	 * recording to request header "accept" value.
	 */
	protected function getHandlerByContentType(string $contentType): string
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

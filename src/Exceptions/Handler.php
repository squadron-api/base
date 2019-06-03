<?php

namespace Squadron\Base\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Squadron\Base\Helpers\ApiResponse;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
	protected $dontReport = [
		'\League\OAuth2\Server\Exception\OAuthServerException',
		'\GuzzleHttp\Exception\ClientException',
		'\GuzzleHttp\Exception\ServerException',
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  Exception $exception
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function report(Exception $exception): void
	{
		if (!app()->isLocal() && !app()->runningUnitTests() && $this->shouldReport($exception) && app()->bound('sentry'))
		{
			app('sentry')->captureException($exception);
		}

		parent::report($exception);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  Request  $request
	 * @param  Exception  $exception
	 *
	 * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
	 */
	public function render($request, Exception $exception)
	{
		// Define the message
		$message = 'Sorry, something went wrong.';
		$data = [];

		// If the app is in debug mode
		if (config('app.debug'))
		{
			// Add the exception class name, message and stack trace to response
			$data['exception'] = [
				'class' => get_class($exception),
				'message' => $exception->getMessage(),
				'file' => $exception->getFile(),
				'line' => $exception->getLine(),
				'trace' => $exception->getTrace()
			];
		}

		// Default response of 400
		$exceptionCode = (int)$exception->getCode();
		$status = $exceptionCode > 400 && $exceptionCode < 600 ? $exceptionCode : 400;

		if ($this->isHttpException($exception))
		{
			$status = $exception->getStatusCode();
			$message = $exception->getMessage();
		}
		else if ($exception instanceof ValidationException)
		{
			$status = $exception->status;
			$message = 'Input data is invalid.';
			$data['errors'] = $exception->errors();
		}
		else if ($exception instanceof AuthorizationException)
		{
			return ApiResponse::errorAccess('This action is unauthorized.');
		}
		else if ($exception instanceof AuthenticationException)
		{
			return ApiResponse::errorAccess('Unauthenticated');
		}

		// Return a JSON response with the response array and status code
		return ApiResponse::error($message, $status, $data);
	}
}

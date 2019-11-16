<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Response;
use App\Traits\ApiJsonResponse;


class Handler extends ExceptionHandler
{
    use ApiJsonResponse;
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        //Check if the exception is an instance of HttpException
        if( $exception instanceof HttpException) {
            $code = $exception->getStatusCode();
            $message = Response::$statusTexts[$code];
            return $this->errorResponse($message, $code);
        }

        //Check if the exception is an instance of ModelNotFoundException
        if( $exception instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($exception->getModel()));
            $message = "An instance of {$model} with the given id not found";
            return $this->errorResponse($message, Response::HTTP_NOT_FOUND);
        }

        //Check if the exception is an instance of AuthorizationException
        if( $exception instanceof AuthenticationException) {
            $message = $exception->getMessage();
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }

        //Check if the exception is an instance of AuthorizationException
        if( $exception instanceof AuthorizationException) {
            $message = $exception->getMessage();
            return $this->errorResponse($message, Response::HTTP_UNAUTHORIZED);
        }

        //Check if the exception is an instance of ValidationException
        if( $exception instanceof ValidationException) {
            $message = $exception->validator->errors()->getMessages();
            return $this->errorResponse($message, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        //Check if we are in dev mode
        if (env('APP_DEBUG', false)) {
            return parent::render($request, $exception);
        }
        //If there is no any other error, return inernal server error
        return $this->errorResponse('Unexpected Error; try again later!', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

}

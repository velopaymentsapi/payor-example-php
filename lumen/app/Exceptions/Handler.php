<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
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
        $rendered = parent::render($request, $exception);
        $class = get_class($exception);

        \Log::info(get_class($exception));

        switch ($class) {
            case \Illuminate\Database\QueryException::class:
                $errRes = [
                    'error' => [
                        'code' => $rendered->getStatusCode(),
                        'message' => 'Database Error.',
                    ]
                ];
                break;
            case \VeloPayments\Client\ApiException::class:
                $errHttp = $exception->getResponseBody();
                $errJsonObj = json_decode($errHttp);
                $errRes = [
                    'error' => [
                        'code' => $rendered->getStatusCode(),
                        'message' => $errJsonObj->errors,
                    ]
                ];
                break;
            default:
                $errRes = [
                    'error' => [
                        'code' => $rendered->getStatusCode(),
                        'message' => $exception->getMessage(),
                    ]
                ];
        }
        
        return response()->json($errRes);
    }
}

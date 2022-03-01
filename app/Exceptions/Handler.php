<?php

namespace App\Exceptions;

use App\Http\Controllers\PublicController;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Redirect;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {

    }


    public function render($request, Throwable $e)
    {
        if ($e instanceof NotFoundHttpException) {
            return Redirect::to('/')->withErrors(['overallError'=> __('overall.404')]);
            //return \Illuminate\Support\Facades\Redirect::back()->withErrors(['msg' => 'The Message']);
        }

        return parent::render($request, $e);
    }

}

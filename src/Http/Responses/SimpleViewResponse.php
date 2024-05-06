<?php

namespace Smaakvoldelen\Otp\Http\Responses;

use Illuminate\View\View;
use RectorPrefix202404\Illuminate\Contracts\Support\Responsable;
use Smaakvoldelen\Otp\Contracts\SendOtpViewResponse;
use Symfony\Component\HttpFoundation\Response;

class SimpleViewResponse implements SendOtpViewResponse
{
    /**
     * Create a new response instance.
     *
     * @param  callable|string  $view
     */
    public function __construct(protected $view)
    {
        //
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @codeCoverageIgnore
     */
    public function toResponse($request): Response|View
    {
        if (! is_callable($this->view) || is_string($this->view)) {
            return view($this->view, ['request' => $request]);
        }

        $response = call_user_func($this->view, $request);
        if ($response instanceof Responsable) {
            return $response->toResponse($request);
        }

        return $response;
    }
}

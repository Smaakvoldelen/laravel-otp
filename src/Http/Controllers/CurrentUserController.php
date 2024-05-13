<?php

namespace Smaakvoldelen\Otp\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Routing\Controller;
use Smaakvoldelen\Otp\Actions\CanonicalizeUsername;
use Smaakvoldelen\Otp\Contracts\UpdateUser;
use Smaakvoldelen\Otp\Contracts\UpdateUserResponse;

class CurrentUserController extends Controller
{
    /**
     * Update the current user.
     */
    public function update(Request $request, UpdateUser $updater): UpdateUserResponse
    {
        return $this->updatePipeline($request)->then(function (Request $request) use ($updater) {
            $updater->update($request->user(), $request->all());

            return app(UpdateUserResponse::class);
        });
    }

    /**
     * Update pipeline.
     */
    protected function updatePipeline(Request $request): Pipeline
    {
        return (new Pipeline(app()))->send($request)->through(array_filter([
            config('otp.lowercase_usernames') ? CanonicalizeUsername::class : null,
        ]));
    }
}

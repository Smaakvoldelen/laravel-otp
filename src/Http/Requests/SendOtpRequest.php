<?php

namespace Smaakvoldelen\Otp\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Smaakvoldelen\Otp\Otp;

class SendOtpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            Otp::username() => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        // allow all visitors to submit — you can add auth checks if needed
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:32', 'regex:/^\+?[1-9]\d{1,14}$/'],
            'message' => ['required', 'string', 'max:5000'],

            // honeypot field (must be empty)
            'website' => ['nullable', 'prohibited'], // if filled, request will be rejected by validator
        ];
    }

    public function messages(): array
    {
        return [
            'website.prohibited' => 'Spam detected.',
            'phone.regex' => 'Please provide a valid phone number, including country code like +9198xxxxxxxx.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // trim strings
        $this->merge([
            'name' => $this->name ? trim($this->name) : null,
            'email' => $this->email ? trim($this->email) : null,
            'phone' => $this->phone ? trim($this->phone) : null,
            'message' => $this->message ? trim($this->message) : null,
        ]);
    }
}

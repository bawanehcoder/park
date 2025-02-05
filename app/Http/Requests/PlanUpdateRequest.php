<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanUpdateRequest extends FormRequest
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
            'image' => ['nullable', 'image', 'max:1024'],
            'name' => ['required', 'string'],
            'price' => ['required'],
            'duration' => ['required'],
            'slots' => ['required'],
        ];
    }
}

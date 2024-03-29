<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:books',
            'edition' => 'required|string|max:255',
            'description' => 'required|string',
            'prologue' => 'nullable|string',
            'publisher' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'isbn' => 'nullable|string|max:20|unique:books',
            'price' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|in:0,1',
            'is_borrowed' => 'nullable|in:available,borrowed',
        ];
    }
}

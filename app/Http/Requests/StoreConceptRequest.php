<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConceptRequest extends FormRequest
{
    /** Only authenticated users may create concepts. */
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title'         => ['required', 'string', 'max:255', 'unique:concepts,title'],
            'description'   => ['required', 'string', 'min:20'],
            'explanation'   => ['nullable', 'string'],
            'code_example'  => ['nullable', 'string'],
            'code_language' => ['nullable', 'string', 'in:php,blade,bash,json,sql'],
            'phase'         => ['required', 'integer', 'between:1,7'],
            'is_published'  => ['boolean'],
            'category_id'   => ['required', 'integer', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.unique'       => 'A concept with this title already exists.',
            'description.min'    => 'Please write at least 20 characters for the description.',
            'category_id.exists' => 'Please select a valid category.',
            'phase.between'      => 'Phase must be between 1 and 7.',
        ];
    }
}

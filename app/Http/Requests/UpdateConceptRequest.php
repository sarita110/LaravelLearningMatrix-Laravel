<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConceptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        // Ignore the current concept's own title when checking uniqueness
        $conceptId = $this->route('concept')?->id;

        return [
            'title'         => ['required', 'string', 'max:255', "unique:concepts,title,{$conceptId}"],
            'description'   => ['required', 'string', 'min:20'],
            'explanation'   => ['nullable', 'string'],
            'code_example'  => ['nullable', 'string'],
            'code_language' => ['nullable', 'string', 'in:php,blade,bash,json,sql'],
            'phase'         => ['required', 'integer', 'between:1,7'],
            'is_published'  => ['boolean'],
            'category_id'   => ['required', 'integer', 'exists:categories,id'],
        ];
    }
}

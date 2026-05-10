<?php

namespace App\Http\Requests\Task;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255', 'min:3'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status'      => ['required', 'in:pending,in_progress,done'],
            'due_date'    => ['nullable', 'date', 'after:today'],
        ];
    }
}

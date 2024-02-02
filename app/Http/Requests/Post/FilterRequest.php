<?php

namespace App\Http\Requests\Post;



use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FilterRequest extends Request
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
            'user_id' => [
                'exists:users,id',
            ],

            'orderBy' => [
                Rule::in(['created_at'])
            ],

            'order' => [
                Rule::in(['ASC', 'DESC'])
            ],

            'page' => [
                'integer',
                'min:0',
            ],

            'pagesize' => [
                'integer',
                'min:1',
                'max:20'
            ]
        ];
    }
}

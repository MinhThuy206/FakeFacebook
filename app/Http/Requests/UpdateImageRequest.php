<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateImageRequest extends FormRequest
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
            'image_id' => [
                'required',
                'integer',
                Rule::requiredIf(Post::query()
                    ->where('id', '=', $this-> image_id)
                    ->where('user_id', '=', auth()->user())->exists()),
            ],

            'post_id' => [
                'required',
                Rule::requiredIf(Post::query()
                    ->where('id', '=', $this->post_id)
                    ->where('user_id', '=', auth()->user())->exists()),
            ],
        ];
    }
}

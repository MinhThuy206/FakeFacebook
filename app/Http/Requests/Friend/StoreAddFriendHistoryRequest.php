<?php

namespace App\Http\Requests\Friend;

use App\Enums\FriendshipStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAddFriendHistoryRequest extends FormRequest
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
            'user_id2' => [
                'required',
                'exists:users,id',
            ],
            'status' => [
                Rule::in([
                    FriendshipStatus::PENDING,
                    FriendshipStatus::ACCEPTED,
                    FriendshipStatus::REJECTED,
                ]),
            ]
        ];
    }

}

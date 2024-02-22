<?php

namespace Modules\User\Http\Requests;

use Modules\User\Facades\UserRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Modules\Role\Models\Role;
use Modules\User\Models\User;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $targetUser = UserRepository::find($this->route('user'));

        return Auth::user()->can('update', $targetUser);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string',
            'email' => [
                'nullable',
                'email',
                Rule::unique(User::class)->ignore($this->route('user')),
            ],
            'password' => [
                'nullable',
                new Password(5),
                'confirmed'
            ],

            'roles' => 'nullable|array',
            'roles.*' => [Rule::exists(Role::class, 'code')],
        ];
    }
}

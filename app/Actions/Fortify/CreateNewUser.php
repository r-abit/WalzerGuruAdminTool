<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Ramsey\Uuid\Type\Integer;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        $validator =  Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // 'height' => ['required', 'integer', 'gt:50', 'lte:280'],
            // 'birthday' => ['required', 'after:'. date('1900-01-01'), 'before:'. date('Y-m-d', strtotime('-18 year', time())), 'date_format:Y-m-d'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            // 'height' => (Integer) $input['height'],
            // 'birthday' => $input['birthday'],
            'password' => Hash::make($input['password']),
        ]);
    }
}

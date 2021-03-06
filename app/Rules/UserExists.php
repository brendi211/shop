<?php

namespace App\Rules;

use App\Services\CustomerService;
use Illuminate\Contracts\Validation\Rule;
use Request;

class UserExists implements Rule
{
    public function passes($attribute, $value)
    {
        return app(CustomerService::class)->userExists((string)Request::get('login'));
    }

    public function message()
    {
        return translate('Такий покупець не зареєстрований');
    }
}

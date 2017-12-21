<?php

namespace App\Validation\Rules;

use App\Models\User;
use Respect\Validation\Rules\AbstractRule;


class EmailExists extends AbstractRule
{
    protected $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function validate($input)
    {
        if ($input === $this->email) {
            return true;
        }
    }
}
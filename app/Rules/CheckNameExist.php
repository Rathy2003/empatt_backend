<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class CheckNameExist implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    protected $id;
    protected $firstName;
    protected $lastName;
    public function __construct($id,$firstName,$lastName){
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->id = $id;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exist = DB::table('users')
            ->where('firstname', $this->firstName)
            ->where('lastname', $this->lastName)
            ->whereNot('id',$this->id)
            ->exists();
        if($exist){
            $fail("This name is already taken.");
        }
    }
}

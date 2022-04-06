<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Arr;

class GenerateUnqiuePinForUserAction
{
    public function __construct(protected User $user)
    {
        $this->user = $user;
    }

    public function execute($digits = 4)
    {
        $pin = $this->generatePin($digits);

        if (User::where('verification_pin', $pin)->where('id', '!=', $this->user->id)->exists()) {
            return clone($this)->execute();
        }

        $this->user->verification_pin = $pin;

        $this->user->save();
    }

    protected function generatePin($digits = 4)
    {
        $pin = substr(str_shuffle("0123456789"), 0, $digits);

        for ($i = 0; $i < $digits; ++$i) {
            if ($i === 0) {
                continue;
            }

            $previousNumber = $pin[$i - 1];

            if ($pin[$i] == $previousNumber || $pin[$i] == ($previousNumber + 1)) {
                $pin[$i] = Arr::random(array_diff(range(0, 9), [$previousNumber, $pin[$i]]));
            }
        }

        return $pin;
    }
}

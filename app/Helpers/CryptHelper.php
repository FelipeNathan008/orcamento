<?php

namespace App\Helpers;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class CryptHelper
{
    public static function encrypt($value): string
    {
        return Crypt::encryptString((string) $value);
    }

    public static function decrypt($value): int
    {
        try {
            return (int) Crypt::decryptString($value);
        } catch (DecryptException $e) {
            abort(404);
        }
    }
}
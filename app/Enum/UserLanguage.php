<?php

namespace App\Enum;

use Illuminate\Support\Str;

enum UserLanguage: int
{
    case TR = 1;
    case EN = 2;

    public function toString(): string
    {
        return match($this) {
            self::TR => trans('enum.user.language.tr'),
            self::EN => trans('enum.user.language.en'),
        };
    }

    public static function defaultKey(): string {
        return Str::lower(self::TR->name);
    }

    public static function getNameByValue(int $value): ?string {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case->name;
            }
        }
        return null;
    }

    public static function getShortKeys(): ?array {
        foreach (self::cases() as $case) {
            $lang[] = Str::lower($case->name);
        }

        return $lang ?? [];
    }
}

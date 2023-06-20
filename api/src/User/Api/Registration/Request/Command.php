<?php

declare(strict_types=1);

namespace App\User\Api\Registration\Request;

use App\Api\InputInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Command implements InputInterface
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public string $name;

    public string $surname;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public string $patronymic;

    public ?string $passport = null;

    public ?string $phone = null;

    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    #[Assert\NotBlank]
    public string $email;

    #[Assert\Date]
    public string $birthday;

    #[Assert\NotBlank, Assert\Length(null, 6, 255)]
    public string $password;

    #[Assert\NotBlank, Assert\Length(null, 6, 255)]
    public string $rePassword;

    #[Assert\IsTrue(message: 'The rePasswordField has to the same as password')]
    public function isPasswordSafe(): bool
    {
        return $this->password === $this->rePassword;
    }

    #[Assert\IsTrue(message: 'The passport is not valid')]
    public function isPassport(): bool
    {
        if (!$this->passport) {
            return true;
        }

        $value = $this->formatPassport($this->passport);
        $allowedLetters = 'АВСЕНІКЛМОРТЮФ';
        $value = preg_replace('/[^0-9' . $allowedLetters . ']/', '', $value);
        return !!preg_match('/(^[' . $allowedLetters . ']{2}\d{6}$)|(^\d{9}$)/u', $value);
    }

    #[Assert\IsTrue(message: 'The phone is not valid')]
    public function isPhone(): bool
    {
        if (!$this->phone) {
            return true;
        }

        $value = preg_replace('/[^0-9]/', '', $this->phone);
        if (strlen($value) < 10) return false;
        return true;
    }

    // -------------------------------------

    private function formatPassport(string $passport = ''): ?string
    {
        $passport = mb_convert_case($passport, MB_CASE_UPPER);
        $replacement = [
            "A" => "А",
            "B" => "В",
            "C" => "С",
            "D" => "",
            "E" => "Е",
            "F" => "",
            "Z" => "",
            "H" => "Н",
            "I" => "І",
            "K" => "К",
            "L" => "",
            "M" => "М",
            "N" => "",
            "O" => "О",
            "P" => "Р",
            "Q" => "",
            "R" => "",
            "S" => "",
            "T" => "Т",
            "V" => "",
            "X" => "",
        ];
        $passport = str_replace(array_keys($replacement), array_values($replacement), $passport);
        $passport = preg_replace('/[^0-9а-яА-ЯіІїЇєЄ]/', '', $passport);
        return $passport;
    }
}
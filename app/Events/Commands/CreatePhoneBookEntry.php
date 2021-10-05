<?php

namespace App\Events\Commands;

use Spatie\DataTransferObject\Attributes\Strict;
use Symfony\Component\Validator\Constraints as Assert;

#[Strict]
class CreatePhoneBookEntry extends AbstractMessage
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 1)]
    public string $first_name;

    #[Assert\Length(min: 1)]
    public ?string $middle_names;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1)]
    public string $last_name;

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email_address;

    #[Assert\Length(min: 10)]
    public ?string $landline_number;

    #[Assert\NotBlank]
    #[Assert\Length(min: 10)]
    public string $mobile_number;
}

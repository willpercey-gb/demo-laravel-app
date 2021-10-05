<?php

namespace App\Events\Commands;

use Spatie\DataTransferObject\Attributes\Strict;
use Symfony\Component\Validator\Constraints as Assert;

#[Strict]
class UpdatePhonebookEntry extends AbstractMessage
{
    #[Assert\Uuid]
    #[Assert\NotBlank]
    public ?string $entryUuid;

    #[Assert\Length(min: 1)]
    public string $first_name;

    #[Assert\Length(min: 1)]
    public ?string $middle_names;

    #[Assert\Length(min: 1)]
    public string $last_name;

    #[Assert\Email]
    public string $email_address;

    #[Assert\Length(min: 10)]
    public ?string $landline_number;

    #[Assert\Length(min: 10)]
    public string $mobile_number;


    public function getEntryUuid(): string
    {
        return $this->entryUuid;
    }

    /**
     * @param string|null $entryUuid
     */
    public function setEntryUuid(?string $entryUuid): void
    {
        $this->entryUuid = $entryUuid;
    }
}

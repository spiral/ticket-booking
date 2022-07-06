<?php

declare(strict_types=1);

namespace App\UI\Web\Request;

use App\ValueObject\Credentials;
use App\ValueObject\Email;
use Spiral\Filters\Attribute\Input\Post;
use Spiral\Validation\Symfony\AttributesFilter;
use Symfony\Component\Validator\Constraints\NotBlank;

final class CredentialsRequest extends AttributesFilter
{
    #[Post(key: 'email')]
    #[NotBlank(message: 'The `email` field must not be empty.')]
    private readonly string $email;

    #[Post(key: 'password')]
    #[NotBlank(message: 'The `password` field must not be empty.')]
    private readonly string $password;

    public function getCredentials(): Credentials
    {
        return new Credentials($this->getEmail(), $this->getPassword());
    }

    public function getEmail(): Email
    {
        return new Email($this->email);
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}

<?php
namespace src\traits;

trait Email
{
    /**
     * @var string|null
     */
    private $email;

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
}
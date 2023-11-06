<?php
namespace src\traits;

trait Phone
{
    /**
     * @var string|null
     */
    private $phone;

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }
}
<?php
namespace src\traits;

trait Image
{
    /**
     * @var string|null
     */
    private $image;

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }
}
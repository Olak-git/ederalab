<?php
namespace src\traits;

trait Description
{
    /**
     * @var string|null
     */
    private $description;

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
    
    public function getDescription(): ?string
    {
        return $this->description;
    }
}
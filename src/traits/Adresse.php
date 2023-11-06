<?php
namespace src\traits;

trait Adresse
{
    /**
     * @var string|null
     */
    private $adresse;

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }
    
    public function getAdresse(): ?string
    {
        return $this->adresse;
    }
}
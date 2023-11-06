<?php
namespace src\traits;

trait Ville
{
    /**
     * @var string|null
     */
    private $ville;

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }
}
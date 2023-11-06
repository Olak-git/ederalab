<?php
namespace src\traits;

trait Prenom
{
    /**
     * @var string|null
     */
    private $prenom;

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }
}
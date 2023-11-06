<?php
namespace src\traits;

trait Nom
{
    /**
     * @var string
     */
    private $nom;

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNom(): string
    {
        return $this->nom;
    }
}
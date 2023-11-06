<?php
namespace src\traits;

trait Actif
{
    /**
     * @var int
     */
    private $actif;

    public function setActif(int $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getActif(): int
    {
        return $this->actif;
    }
}
<?php
namespace src\traits;

trait Id
{
    /**
     * @var int
     */
    private $id;

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
}
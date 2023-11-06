<?php
namespace src\traits;

trait Status
{
    /**
     * @var int|null
     */
    private $status;

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }
}
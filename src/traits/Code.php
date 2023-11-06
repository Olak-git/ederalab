<?php
namespace src\traits;

trait Code
{
    /**
     * @var string|null
     */
    private $code;

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }
    
    public function getCode(): ?string
    {
        return $this->code;
    }
}
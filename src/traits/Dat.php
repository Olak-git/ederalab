<?php
namespace src\traits;

trait Dat
{
    /**
     * @var string|null
     */
    private $dat;

    public function setDat(?string $date): self
    {
        $this->dat = $date;

        return $this;
    }
    
    public function getDat(): ?string
    {
        return $this->dat;
    }
}
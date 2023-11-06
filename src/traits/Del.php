<?php
namespace src\traits;

trait Del
{
    /**
     * @var int
     */
    private $del;

    public function setDel(int $del): self
    {
        $this->del = $del;

        return $this;
    }

    public function getDel(): int
    {
        return $this->del;
    }
}
<?php
namespace src\traits;

trait Visible
{
    /**
     * @var bool
     */
    private $visible;
    
    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }
    
    public function getVisible(): bool
    {
        return (int)$this->visible;
    }
}
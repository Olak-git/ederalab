<?php
namespace src\traits;

trait Password 
{
    /**
     * @var string
     */
    private $password;
    
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    
    public function getPassword(): string
    {
        return $this->password;
    }
}
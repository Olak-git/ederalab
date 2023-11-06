<?php
namespace src\Entity;

use src\traits\Email;
use src\traits\Hydrate;
use src\traits\Id;
use src\traits\Password;
use src\traits\Properties;
use src\traits\Slug;
use src\traits\Values;
use src\Vendor\Storage;
use src\Vendor\EntityInterface;

class Admin implements Storage , EntityInterface
{
    use Id, Slug, Email, Password, Hydrate, Properties, Values;

    /**
     * @var string
     */
    private $identifiant;

    /**
     * @var int|null
     */
    private $active;

    public function __construct(array $data = [])
    {
        $this->slug = $this->createSlug();
        $this->password = 'default';

        $this->hydrate($data);
    }

    public function __sleep()
    {
        return $this->getProperties($this);
    }

    public function __wakeup()
    {
        // token here
    }

    public function setIdentifiant(string $code): self
    {
        if($this->getId() === null) {
            $code = password_hash($code, 1);
        }
        $this->identifiant = $code;

        return $this;
    }

    public function getIdentifiant(): string
    {
        return $this->identifiant;
    }

    public function setActive(?int $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getActive(): ?int
    {
        return $this->active;
    }
}
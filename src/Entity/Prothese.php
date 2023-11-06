<?php
namespace src\Entity;

use src\traits\Hydrate;
use src\traits\Id;
use src\traits\Nom;
use src\traits\Properties;
use src\traits\Slug;
use src\traits\Values;
use src\Vendor\Storage;
use src\Vendor\EntityInterface;

class Prothese implements Storage , EntityInterface
{
    use Id, Slug, Nom, Hydrate, Properties, Values;

    /**
     * @var string
     */
    private $numero;

    /**
     * @var string|null
     */
    private $detail;

    public function __construct(array $data = [])
    {
        $this->slug = $this->createSlug();

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

    public function setNumero(int $num): self
    {
        $this->numero = $num;

        return $this;
    }

    public function getNumero(): int
    {
        return $this->numero;
    }

    public function setDetail(?string $detail): self
    {
        $this->detail = $detail;

        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }
}
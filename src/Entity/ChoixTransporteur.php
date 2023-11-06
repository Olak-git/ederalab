<?php
namespace src\Entity;

use src\Repository\CommandeRepository;
use src\traits\Hydrate;
use src\traits\Id;
use src\traits\Properties;
use src\traits\Slug;
use src\traits\Transporteur;
use src\traits\Values;
use src\Vendor\Storage;
use src\Vendor\EntityInterface;

class ChoixTransporteur implements Storage , EntityInterface
{
    use Id, Slug, Transporteur, Hydrate, Properties, Values;
    /**
     * @var Commande
     */
    private $commande;

    /**
     * @var string|null
     */
    private $date_reception;

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

    public function setCommande(int $id): self
    {
        $this->commande = (new CommandeRepository)->findOneBy(['id' => $id]);

        return $this;
    }

    public function getCommande(): Commande
    {
        return $this->commande;
    }

    public function setDateReception(string $date): self
    {
        $this->date_reception = $date;

        return $this;
    }

    public function getDateReception(): string
    {
        return $this->date_reception;
    }
}
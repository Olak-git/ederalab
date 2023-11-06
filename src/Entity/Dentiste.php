<?php
namespace src\Entity;

use src\traits\Id;
use src\traits\Nom;
use src\traits\Slug;
use src\traits\Email;
use src\traits\Prenom;
use src\traits\Values;
use src\traits\Adresse;
use src\traits\Hydrate;
use src\Vendor\Storage;
use src\traits\Password;
use src\Entity\EmptyUser;
use src\traits\Properties;
use src\Vendor\EntityInterface;
use src\Repository\FactureRepository;
use src\Repository\CommandeRepository;
use src\traits\Image;
use src\traits\Phone;

class Dentiste extends EmptyUser implements Storage, EntityInterface
{
    use Id, Slug, Nom, Prenom, Email, Password, Adresse, Phone, Image, Hydrate, Properties, Values;

    /**
     * @var string
     */
    private $cabinet;

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
        // code here
    }

    public function setCabinet(string $cabinet): self
    {
        $this->cabinet = $cabinet;

        return $this;
    }

    public function getCabinet(): string
    {
        return $this->cabinet;
    }

    public function getUsername()
    {
        return ucwords($this->getNom() . ' ' . $this->getPrenom());
    }

    public function findOrdersCommandToday()
    {
        return (new CommandeRepository)->getOrdersCommandToday($this);
    }

    public function findOrdersCommandLongTime()
    {
        return (new CommandeRepository)->getOrdersCommandLongTime($this);
    }

    public function getTotalCommandesLivrees()
    {
        return (new CommandeRepository)->totalCommandesLivreesForDentiste($this->getId());
    }

    public function getTotalCommandesEncours()
    {
        return (new CommandeRepository)->totalCommandesEncoursForDentiste($this->getId());
    }

    public function getFactures(): array
    {
        return (new FactureRepository)->getFactureForDentiste($this->getId());
    }
}
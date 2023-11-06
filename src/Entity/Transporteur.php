<?php
namespace src\Entity;

use src\traits\Id;
use src\traits\Nom;
use src\traits\Slug;
use src\traits\Email;
use src\traits\Phone;
use src\traits\Prenom;
use src\traits\Values;
use src\traits\Adresse;
use src\traits\Hydrate;
use src\Vendor\Storage;
use src\traits\Password;
use src\Entity\EmptyUser;
use src\traits\Properties;
use src\traits\Dat;
use src\traits\Image;
use src\traits\Del;
use src\Vendor\EntityInterface;
use src\Repository\IdentifiantRepository;

class Transporteur extends EmptyUser implements Storage, EntityInterface
{
    use Id, Slug, Nom, Prenom, Adresse, Phone, Image, Email, Dat, Del, Hydrate, Properties, Values;
    // use Password;

    /**
     * @var string
     */
    private $identifiant;

    public function __construct(array $data = [])
    {
        $this->del = 0;
        $this->slug = $this->createSlug();
        // $this->password = 'default';

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

    public function getUsername(): string
    {
        return ucwords($this->getNom() . ' ' . $this->getPrenom());
    }

    public function getEntityCode(): ?Identifiant
    {
        return (new IdentifiantRepository)->findOneBy(['transporteur' => $this->getId()]);
    }

    public function getCodePlain(): ?string
    {
        $code = $this->getEntityCode();
        return $code ? $code->getCode() : null;
    }
}
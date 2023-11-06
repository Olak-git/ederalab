<?php
namespace src\Entity;

use src\traits\Id;
use src\traits\Dat;
use src\traits\Hydrate;
use src\Vendor\Storage;
use src\Vendor\EntityInterface;
use src\traits\Discussion;
use src\traits\Properties;
use src\traits\Slug;
use src\traits\Values;

class Message implements Storage , EntityInterface
{
    use Id, Slug, Discussion, Dat, Hydrate, Properties, Values;

    /**
     * info: ne peut que prendre 3 valeurs: admin, transporteur ou dentiste
     * 'admin' => c'est l'admin l'expediteur du message
     * 'transporteur' => c'est le transporteur l'expediteur du message
     * 'dentiste' => c'est le dentiste l'expediteur du message
     *  
     * @var string
     */
    private $expediteur;

    /**
     * @var string|null
     */
    private $message;

    /**
     * @var string|null
     */
    private $fichier;

    /**
     * @var string|null
     */
    private $nom_fichier;

    /**
     * @var string|null
     */
    private $type_fichier;

    /**
     * @var int
     */
    private $lu;

    public function __construct(array $data = [])
    {
        $this->slug = $this->createSlug();
        $this->lu = 0;

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

    public function setExpediteur(string $expediteur): self
    {
        $this->expediteur = $expediteur;

        return $this;
    }

    public function getExpediteur(): string
    {
        return $this->expediteur;
    }
        
    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setFichier(?string $fichier): self
    {
        $this->fichier = $fichier;

        return $this;
    }

    public function getFichier(): ?string
    {
        return $this->fichier;
    }

    public function setNomFichier(?string $nom_fichier): self
    {
        $this->nom_fichier = $nom_fichier;

        return $this;
    }

    public function getNomFichier(): ?string
    {
        return $this->nom_fichier;
    }

    public function setTypeFichier(?string $type_fichier): self
    {
        $this->type_fichier = $type_fichier;

        return $this;
    }

    public function getTypeFichier(): ?string
    {
        return $this->type_fichier;
    }

    public function setLu(int $lu): self
    {
        $this->lu = $lu;

        return $this;
    }

    public function getLu(): int
    {
        return $this->lu;
    }

    public function isRead()
    {
        return (bool)$this->getLu();
    }

    public function isEmpty()
    {
        return null === $this->getMessage() && null === $this->getFichier();
    }
}
<?php
namespace src\Entity;

use src\Repository\CommandeRepository;
use src\traits\Dat;
use src\traits\Email;
use src\traits\Hydrate;
use src\traits\Id;
use src\traits\Password;
use src\traits\Properties;
use src\traits\Slug;
use src\traits\Values;
use src\Vendor\Storage;
use src\Vendor\EntityInterface;

class Facture implements Storage , EntityInterface
{
    use Id, Slug, Dat, Hydrate, Properties, Values;

    /**
     * @var string
     */
    private $code;

    /**
     * @var Commande
     */
    private $commande;

    /**
     * @var float
     */
    private $total_ht;

    /**
     * @var float
     */
    private $tva;

    /**
     * @var string
     */
    private $devis;

    public function __construct(array $data = [])
    {
        $this->code = random_int(100000, 9999999);
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

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
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

    public function setTotalHt(string $total_ht): self
    {
        $this->total_ht = $total_ht;

        return $this;
    }

    public function getTotalHt(): string
    {
        return $this->total_ht;
    }

    public function setTva(string $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    public function getTva(): string
    {
        return $this->tva;
    }

    public function setDevis(string $devis): self
    {
        $this->devis = $devis;

        return $this;
    }

    public function getDevis(): string
    {
        return $this->devis;
    }
}
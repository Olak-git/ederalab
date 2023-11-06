<?php
namespace src\Entity;

use src\traits\Id;
use src\traits\Slug;
use src\traits\Values;
use src\traits\Hydrate;
use src\Vendor\Storage;
use src\traits\Properties;
use src\Vendor\EntityInterface;
use src\Repository\CommandeRepository;
use src\Repository\ProtheseRepository;

class ChoixProthese implements Storage , EntityInterface
{
    use Id, Slug, Hydrate, Properties, Values;

    /**
     * @var string
     */
    private $nom_patient;

    /**
     * @var Prothese
     */
    private $prothese;

    /**
     * @var int
     */
    private $cas_num;

    /**
     * @var string|null
     */
    private $details_commande;

    /**
     * @var string|null
     */
    private $modif_demand;

    /**
     * @var Commande
     */
    private $commande;    

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

    public function setNomPatient(string $nom_patient): self
    {
        $this->nom_patient = $nom_patient;

        return $this;
    }

    public function getNomPatient(): string
    {
        return $this->nom_patient;
    }

    public function setProthese(?int $id): self
    {
        $this->prothese = (new ProtheseRepository)->findOneBy(['id' => $id]);

        return $this;
    }

    public function getProthese(): Prothese
    {
        return $this->prothese;
    }

    public function setCasNum(int $cas_num): self
    {
        $this->cas_num = $cas_num;

        return $this;
    }

    public function getCasNum(): int
    {
        return $this->cas_num;
    }

    public function setDetailsCommande(?string $details_commande): self
    {
        $this->details_commande = $details_commande;

        return $this;
    }

    public function getDetailsCommande(): ?string
    {
        return $this->details_commande;
    }

    public function setModifDemand(?string $modif_demand): self
    {
        $this->modif_demand = $modif_demand;

        return $this;
    }

    public function getModifDemand(): ?string
    {
        return $this->modif_demand;
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
}
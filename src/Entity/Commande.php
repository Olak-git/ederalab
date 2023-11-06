<?php
namespace src\Entity;

use src\Repository\ChoixProtheseRepository;
use src\Repository\ChoixTransporteurRepository;
use src\Repository\FactureRepository;
use src\traits\Dentiste;
use src\traits\Hydrate;
use src\traits\Id;
use src\traits\Properties;
use src\traits\Slug;
use src\traits\Values;
use src\Vendor\Storage;
use src\Vendor\EntityInterface;

class Commande implements Storage , EntityInterface
{
    use Id, Slug, Dentiste, Hydrate, Properties, Values;

    /**
     * @var string
     */
    private $nom_dentiste;

    /**
     * @var string
     */
    private $cabinet;

    /**
     * @var string
     */
    private $nom_patient;
    
    /**
     * @var string
     */
    private $prenom_patient;

    /**
     * @var string
     */
    private $description_specifiq;

    /**
     * @var string|null
     */
    private $description_libre;

    /**
     * @var string|null
     */
    private $piece_jointe;

    /**
     * @var string
     */
    private $date_envoie;

    /**
     * @var string
     */
    private $date_reception_prevue;

    /**
     * {-1, 0, 1}
     * 0 => en attente de validation
     * -1 => commande refusée
     * 1 => commande acceptée
     * 
     * @var int|null
     */
    private $valide;

    /**
     * {-1, 0, 1, 2}
     * 0 => en attente
     * -1 => non livrée
     * 1 => en cours
     * 2 => livrée
     * 
     * @var int|null
     */
    private $livraison;

    /**
     * @var string|null
     */
    private $date_livraison;

    /**
     * {0, 1}
     * 0 => non archivé
     * 1 => archivée
     * 
     * @var int
     */
    private $archive;

    /**
     * @var string|null
     */
    private $date_archive;

    public function __construct(array $data = [])
    {
        $this->archive = 0;
        $this->valide = 0;
        $this->livraison = 0;
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

    public function setNomDentiste(string $nom_dentiste): self
    {
        $this->nom_dentiste = $nom_dentiste;

        return $this;
    }

    public function getNomDentiste(): string
    {
        return $this->nom_dentiste;
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

    public function setNomPatient(string $nom_patient): self
    {
        $this->nom_patient = $nom_patient;

        return $this;
    }

    public function getNomPatient(): string
    {
        return $this->nom_patient;
    }

    public function setPrenomPatient(string $prenom_patient): self
    {
        $this->prenom_patient = $prenom_patient;

        return $this;
    }

    public function getPrenomPatient(): string
    {
        return $this->prenom_patient;
    }

    public function setDescriptionSpecifiq(string $description_specifiq): self
    {
        $this->description_specifiq = $description_specifiq;

        return $this;
    }

    public function getDescriptionSpecifiq(): string
    {
        return $this->description_specifiq;
    }

    public function setDescriptionLibre(?string $description_libre): self
    {
        $this->description_libre = $description_libre;

        return $this;
    }

    public function getDescriptionLibre(): ?string
    {
        return $this->description_libre;
    }

    public function setPieceJointe(?string $piece_jointe): self
    {
        $this->piece_jointe = $piece_jointe;

        return $this;
    }

    public function getPieceJointe(): ?string
    {
        return $this->piece_jointe;
    }

    public function setDateEnvoie(string $date_envoie): self
    {
        $this->date_envoie = $date_envoie;

        return $this;
    }

    public function getDateEnvoie(): string
    {
        return $this->date_envoie;
    }

    public function setDateReceptionPrevue(string $date_reception_prevue): self
    {
        $this->date_reception_prevue = $date_reception_prevue;

        return $this;
    }

    public function getDateReceptionPrevue(): string
    {
        return $this->date_reception_prevue;
    }

    public function setValide(?int $valide): self
    {
        $this->valide = $valide;

        return $this;
    }

    public function getValide(): ?int
    {
        return $this->valide;
    }

    public function setLivraison(?int $livraison): self
    {
        $this->livraison = $livraison;

        return $this;
    }

    public function getLivraison(): ?int
    {
        return $this->livraison;
    }

    public function setDateLivraison(?string $date_livraison): self
    {
        $this->date_livraison = $date_livraison;

        return $this;
    }

    public function getDateLivraison(): ?string
    {
        return $this->date_livraison;
    }

    public function setArchive(?int $archive): self
    {
        $this->archive = $archive;

        return $this;
    }

    public function getArchive(): ?int
    {
        return $this->archive;
    }

    public function setDateArchive(?string $date_archive): self
    {
        $this->date_archive = $date_archive;

        return $this;
    }

    public function getDateArchive(): ?string
    {
        return $this->date_archive;
    }

    public function getChoixProtheses(): array
    {
        return (new ChoixProtheseRepository)->findBy(['commande' => $this->getId()]);
    }

    public function getUsernamePatient()
    {
        return ucwords($this->getNomPatient() . ' ' . $this->getPrenomPatient());
    }

    public function getChoixTransporteur(): ?ChoixTransporteur
    {
        return (new ChoixTransporteurRepository)->findOneBy(['commande' => $this->getId()], ['id' => 'DESC']);
    }

    public function getTransporteur(): ?Transporteur
    {
        $choixTransporteur = $this->getChoixTransporteur();
        return $choixTransporteur ? $choixTransporteur->getTransporteur() : null;
    }

    public function getFacture(): ?Facture
    {
        return (new FactureRepository)->findOneBy(['commande' => $this->getId()]);
    }
}
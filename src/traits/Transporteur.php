<?php
namespace src\traits;

use src\Entity\Transporteur as EntityTransporteur;
use src\Repository\TransporteurRepository;

trait Transporteur 
{
    /**
     * @var EntityTransporteur|null
     */
    private $transporteur;

    public function setTransporteur(?int $id): self
    {
        $this->transporteur = (new TransporteurRepository)->findOneBy(['id' => $id]);

        return $this;
    }

    public function getTransporteur(): ?EntityTransporteur
    {
        return $this->transporteur;
    }
}
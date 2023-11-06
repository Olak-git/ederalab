<?php
namespace src\traits;

use src\Entity\Dentiste as EntityDentiste;
use src\Repository\DentisteRepository;

trait Dentiste 
{
    /**
     * @var EntityDentiste
     */
    private $dentiste;

    public function setDentiste($id): self
    {
        $this->dentiste = (new DentisteRepository)->findOneBy(['id' => $id]);

        return $this;
    }

    public function getDentiste(): EntityDentiste
    {
        return $this->dentiste;
    }
}
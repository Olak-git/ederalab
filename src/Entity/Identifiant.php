<?php
namespace src\Entity;

use src\traits\Admin;
use src\traits\Code;
use src\traits\Hydrate;
use src\traits\Id;
use src\traits\Properties;
use src\traits\Transporteur;
use src\traits\Values;
use src\Vendor\Storage;
use src\Vendor\EntityInterface;

class Identifiant implements Storage , EntityInterface
{
    use Id, Code, Transporteur, Hydrate, Properties, Values;

    public function __construct(array $data = [])
    {
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
}
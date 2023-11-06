<?php
namespace src\traits;

use src\Vendor\EntityInterface;

trait Properties
{
    public function getProperties(EntityInterface $ei): array
    {
        $d = [];
        $s = new \ReflectionClass($ei);
        $properties = $s->getProperties();
        foreach($properties as $p) {
            $d[] = $p->getName();
        }
        return $d;
    }

    public function createSlug(): string
    {
        return md5(password_hash(time(), 1));
    }
}
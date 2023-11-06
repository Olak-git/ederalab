<?php
namespace src\traits;

trait Hydrate
{
    public function hydrate(array $donnees)
    {
        if(array_key_exists('id', $donnees) && null !== $donnees['id'] && '' !== $donnees['id']) {
            foreach ($donnees as $attribut => $valeur):
                $methode = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $attribut)));
                // if(method_exists($this, $methode)) {
                //     $this->$methode($valeur);
                // }
                if (is_callable(array($this, $methode))) {
                    $valeur = trim($valeur) === '' ? null : $valeur;
                    $valeur = $valeur;
                    $this->$methode($valeur);
                }
            endforeach;
        } else {
            foreach ($donnees as $attribut => $valeur):
                $methode = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $attribut)));
                // if(method_exists($this, $methode)) {
                //     $this->$methode($valeur);
                // }
                if (is_callable(array($this, $methode))) {
                    $valeur = trim($valeur) === '' ? null : $valeur;
                    $valeur = is_string($valeur) ? htmlspecialchars($valeur) : $valeur;
                    $this->$methode($valeur);
                }
            endforeach;
        }
    }
}
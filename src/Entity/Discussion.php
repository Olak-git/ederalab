<?php
namespace src\Entity;

use src\traits\Id;
use src\traits\Slug;
use src\traits\Values;
use src\traits\Hydrate;
use src\traits\Admin;
use src\Vendor\Storage;
use src\traits\Properties;
use src\Vendor\UserInterface;
use src\Vendor\EntityInterface;
use src\Repository\AdminRepository;
use src\Repository\MessageRepository;
use src\Repository\DentisteRepository;
use src\Repository\LastMessageRepository;
use src\Repository\TransporteurRepository;

class Discussion implements Storage , EntityInterface
{
    use Id, Slug, Admin, Hydrate, Properties, Values;

    // /**
    //  * @var Admin
    //  */
    // private $admin;

    /**
     * @var string
     */
    private $compte_receveur;

    /**
     * @var EmptyUser
     */
    private $receveur;

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
        // code here
    }

    // public function setAdmin(string $slug): self
    // {
    //     $this->admin = (new AdminRepository)->findOneBy(['slug' => $slug]);

    //     return $this;
    // }

    // public function getAdmin(): ?Admin
    // {
    //     return $this->admin;
    // }

    public function setCompteReceveur(string $str): self
    {
        $this->compte_receveur = $str;

        return $this;
    }

    public function getCompteReceveur(): string
    {
        return $this->compte_receveur;
    }

    public function setReceveur(int $id): self
    {
        if($this->getCompteReceveur() === 'transporteur') {
            $this->receveur = (new TransporteurRepository)->findOneBy(['id' => $id]);
        } else {
            $this->receveur = (new DentisteRepository)->findOneBy(['id' => $id]);
        }

        return $this;
    }

    public function getReceveur(): EmptyUser
    {
        return $this->receveur;
    }

    public function getMessages()
    {
        return (new MessageRepository)->findBy(['discussion' => $this->getId()]);
    }

    public function getLastMessage(): ?LastMessage
    {
        return (new LastMessageRepository)->findOneBy(['discussion' => $this->getId()]);
    }
}
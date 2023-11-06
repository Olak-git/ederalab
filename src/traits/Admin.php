<?php
namespace src\traits;

use src\Entity\Admin as EntityAdmin;
use src\Repository\AdminRepository;

trait Admin 
{
    /**
     * @var EntityAdmin
     */
    private $admin;

    public function setAdmin($id): self
    {
        $this->admin = (new AdminRepository)->findOneBy(['id' => $id]);

        return $this;
    }

    public function getAdmin(): EntityAdmin
    {
        return $this->admin;
    }
}
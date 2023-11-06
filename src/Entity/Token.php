<?php
namespace src\Entity;

use src\traits\Code;
use src\traits\Hydrate;
use src\traits\Id;
use src\traits\Properties;
use src\traits\User;
use src\traits\Storage;
use src\traits\Values;
use src\Vendor\EntityInterface;

class Token implements Storage, EntityInterface
{
    use Id, Code, User, Hydrate, Properties, Values;

    /**
     * @var int
     */
    private $expire;

    public function __construct(array $data = [])
    {
        $this->code = md5(password_hash('Token', 1));

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

    public function setExpire(int $time_expire): self
    {
        $this->expire = $time_expire;

        return $this;
    }

    public function getExpire(): int
    {
        return $this->expire;
    }

    public function isValid(): bool
    {
        return $this->getExpire() >= strtotime((new \DateTime())->format('Y-m-d H:i:s'));
    }
}
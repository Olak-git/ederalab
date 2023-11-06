<?php
namespace src\Entity;

use src\traits\Id;
use src\Entity\Message as EntityMessage;
use src\traits\Hydrate;
use src\Vendor\Storage;
use src\Vendor\EntityInterface;
use src\Repository\MessageRepository;
use src\traits\Discussion;
use src\traits\Properties;
use src\traits\Slug;
use src\traits\Values;

class LastMessage implements Storage , EntityInterface
{
    use Id, Slug, Discussion, Hydrate, Properties, Values;

    /**
     * @var EntityMessage
     */
    private $message;

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

    public function setMessage(int $id): self
    {
        $this->message = (new MessageRepository)->findOneBy(['id' => $id]);

        return $this;
    }

    public function getMessage(): EntityMessage
    {
        return $this->message;
    }
}
<?php
namespace src\traits;

use src\Entity\Discussion as EntityDiscussion;
use src\Repository\DiscussionRepository;

trait Discussion
{
    /**
     * @var EntityDiscussion
     */
    private $discussion;

    public function setDiscussion($id): self
    {
        $this->discussion = (new DiscussionRepository)->findOneBy(['id' => $id]);

        return $this;
    }

    public function getDiscussion(): EntityDiscussion
    {
        return $this->discussion;
    }
}
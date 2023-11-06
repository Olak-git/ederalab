<?php
namespace src\Repository;

use src\Vendor\MainRepository;

class MessageRepository extends MainRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getNewsMessages(int $discussion_id, int $message_id)
    {
        $sql = 'SELECT m.* 
                FROM message m 
                INNER JOIN discussion d 
                    ON m.discussion = d.id 
                WHERE d.id = :did && m.id > :mid';
        return $this->getData($sql, ['did' => $discussion_id, 'mid' => $message_id]);
    }
}
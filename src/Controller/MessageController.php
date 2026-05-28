<?php
namespace src\Controller;

use src\Vendor\DB;
use src\Vendor\Security;
use src\Services\FileService;
use src\Vendor\EntityManager;
use src\traits\Properties;

class MessageController extends Security
{
    use Properties;

    /**
     * @var \src\Vendor\DB;
     */
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = new DB;
    }

    public function isCsrfValidate($value, $csrf)
    {
        return password_verify($value, $csrf);
    }

    public function newMessage($post, $files) 
    {
        if(isset($post['csrf']) && $this->isCsrfValidate('chat', $post['csrf'])) {
            $chat = $post['chat'];

            $admin = null;
            $compte_receveur = null;
            $receveur = null;
            $expediteur = null;

            if($this->isCsrfValidate('admin', $chat['exp'])) {
                if($user = $this->getAdmin()) {
                    $admin = $user;
                    if(!empty($chat['compte_dest']) && !empty($chat['dest'])) {
                        $compte_receveur = strtolower(trim($chat['compte_dest']));
                        if($compte_receveur === 'dentiste') {
                            $receveur = $this->db->findOneBy("dentiste", ['slug' => $chat['dest']]);
                        } elseif($compte_receveur === 'transporteur') {
                            $receveur = $this->db->findOneBy("transporteur", ['slug' => $chat['dest']]);
                        }
                    }
                    $expediteur = 'admin';
                }
            } elseif($this->isCsrfValidate('dentiste', $chat['exp'])) {
                if($user = $this->getDentiste()) {
                    $admin = $this->db->findOneBy("admin", ['active' => 1]);
                    $compte_receveur = 'dentiste';
                    $receveur = $user;
                    $expediteur = 'dentiste';
                }
            } elseif($this->isCsrfValidate('transporteur', $chat['exp'])) {
                if($user = $this->getTransporteur()) {
                    $admin = $this->db->findOneBy("admin", ['active' => 1]);
                    $compte_receveur = 'transporteur';
                    $receveur = $user;
                    $expediteur = 'transporteur';
                }
            }

            if($admin && $compte_receveur && $receveur && $expediteur) {
                // $em = new EntityManager;
                // $discussion = $this->db->findOneBy("discussion", ['admin' => $admin["id"], 'receveur' => $receveur["id"], 'compte_receveur' => $compte_receveur]);
                // if(!$discussion) {
                //     $em->add("discussion", [
                //         'slug' => $this->createSlug(),
                //         'admin' => $admin["id"],
                //         'compte_receveur' => $compte_receveur,
                //         'receveur' => $receveur["id"]
                //     ]);
                // }

                $text = '' !== ($chat['message']) ? $chat['message'] : null;
                $fichier = null;
                $type_fichier = null;

                if(!empty($files['doc']['name'])) {
                    $type_fichier = 'doc';
                    $fileService = new FileService($files['doc'], 'doc', 'message-file', !$this->hasError(), null, 'M', false);
                    if($fileService->saveFile()) {
                        $fichier = $fileService->getName();
                        // $realNameDoc = $fileService->getRealNameDoc();
                    } elseif($fileService->getError()) {
                        $this->addError('file', $fileService->getError());
                    }
                } elseif(!empty($files['image']['name'])) {
                    $type_fichier = 'image';
                    $fileService = new FileService($files['image'], 'image', 'message-file', !$this->hasError(), null, 'M', false);
                    if($fileService->saveFile()) {
                        $fichier = $fileService->getName();
                        // $realNameDoc = $fileService->getRealNameDoc();
                    } elseif($fileService->getError()) {
                        $this->addError('file', $fileService->getError());
                    }
                }

                if(!is_null($text) || !is_null($fichier)) {
                    $em = new EntityManager;

                    $discussion = $this->db->findOneBy("discussion", ['admin' => $admin["id"], 'receveur' => $receveur["id"], 'compte_receveur' => $compte_receveur]);
                    $discussionId = null;
                    if(empty($discussion)) {
                        $discussionId = $em->add("discussion", [
                            'slug' => $this->createSlug(),
                            'admin' => $admin["id"],
                            'compte_receveur' => $compte_receveur,
                            'receveur' => $receveur["id"]
                        ]);
                    } else {
                        $discussionId = $discussion["id"];
                    }

                    $msgData = [
                        'slug' => $this->createSlug(),
                        'lu' => 0,
                        'expediteur' => $expediteur,
                        'message' => $text,
                        'fichier' => $fichier,
                        'type_fichier' => $type_fichier,
                        'discussion' => $discussionId,
                        'dat' => date('Y-m-d H:i:s'),
                    ];
                    $messageId = $em->add("message", $msgData);
                    $msgData['id'] = $messageId;

                    $lastMessage = $this->db->findOneBy("last_message", ['discussion' => $discussionId]);

                    if($lastMessage) {
                        $em->update("last_message", ["message" => $messageId], $lastMessage["id"]);
                    } else {
                        $lastMessageId = $em->add("last_message", [
                            'slug' => $this->createSlug(),
                            'discussion' => $discussionId,
                            'message' => $messageId
                        ]);
                    }
                    $this->set_messages($msgData);
                }

            } else {

            }
        } else {
            $this->addError('csrf', 'Csrf non validé');
        }
    }

    public function getNewMessages($post) {
        if(isset($post['csrf']) && $this->isCsrfValidate('chat', $post['csrf'])) {

            $chat = $post['upd_chat'];

            $admin = null;
            $compte_receveur = null;
            $receveur = null;

            if($this->isCsrfValidate('admin', $chat['exp'])) {
                if($user = $this->getAdmin()) {
                    $admin = $user;
                    if(!empty($chat['compte_dest']) && !empty($chat['dest'])) {
                        $compte_receveur = strtolower(trim($chat['compte_dest']));
                        if($compte_receveur === 'dentiste') {
                            $receveur = $this->db->findOneBy("dentiste", ['slug' => $chat['dest']]);
                        } elseif($compte_receveur === 'transporteur') {
                            $receveur = $this->db->findOneBy("transporteur", ['slug' => $chat['dest']]);
                        }
                    }
                }
            } elseif($this->isCsrfValidate('dentiste', $chat['exp'])) {
                if($user = $this->getDentiste()) {
                    $admin = $this->db->findOneBy("admin", ['active' => 1]);
                    $compte_receveur = 'dentiste';
                    $receveur = $user;
                }
            } elseif($this->isCsrfValidate('transporteur', $chat['exp'])) {
                if($user = $this->getTransporteur()) {
                    $admin = $this->db->findOneBy("admin", ['active' => 1]);
                    $compte_receveur = 'transporteur';
                    $receveur = $user;
                }
            }

            if($admin && $compte_receveur && $receveur) {
                $discussion = $this->db->findOneBy("discussion", ['admin' => $admin["id"], 'receveur' => $receveur["id"], 'compte_receveur' => $compte_receveur]);
                if($discussion && isset($chat['key'])) {
                    $mid = $chat['key']=="null" ? 0 : $chat['key'];
                    $newMessages = $this->db->query(
                        "SELECT m.* 
                        FROM message m 
                        INNER JOIN discussion d 
                            ON m.discussion = d.id 
                        WHERE d.id = :did AND m.id > :mid", ['did' => $discussion["id"], 'mid' => $mid]
                    )->fetchAll();

                    $this->set_messages($newMessages);
                }
            }
        }
    }
}
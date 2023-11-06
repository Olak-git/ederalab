<?php
namespace src\Controller;

use src\Entity\Message;
use src\Vendor\Security;
use src\Entity\Discussion;
use src\Entity\LastMessage;
use src\Services\FileService;
use src\Vendor\EntityManager;
use src\Repository\AdminRepository;
use src\Repository\MessageRepository;
use src\Repository\DentisteRepository;
use src\Repository\DiscussionRepository;
use src\Repository\LastMessageRepository;
use src\Repository\TransporteurRepository;

class MessageController extends Security
{
    public function __construct()
    {
        parent::__construct();
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
                            $receveur = (new DentisteRepository)->findOneBy(['slug' => $chat['dest']]);
                        } elseif($compte_receveur === 'transporteur') {
                            $receveur = (new TransporteurRepository)->findOneBy(['slug' => $chat['dest']]);
                        }
                    }
                    $expediteur = 'admin';
                }
            } elseif($this->isCsrfValidate('dentiste', $chat['exp'])) {
                if($user = $this->getDentiste()) {
                    $admin = (new AdminRepository)->findOneBy(['active' => 1]);
                    $compte_receveur = 'dentiste';
                    $receveur = $user;
                    $expediteur = 'dentiste';
                }
            } elseif($this->isCsrfValidate('transporteur', $chat['exp'])) {
                if($user = $this->getTransporteur()) {
                    $admin = (new AdminRepository)->findOneBy(['active' => 1]);
                    $compte_receveur = 'transporteur';
                    $receveur = $user;
                    $expediteur = 'transporteur';
                }
            }

            if($admin && $compte_receveur && $receveur && $expediteur) {
                // $em = new EntityManager;
                // $discussion = (new DiscussionRepository)->findOneBy(['admin' => $admin->getId(), 'receveur' => $receveur->getId(), 'compte_receveur' => $compte_receveur]);
                // if(!$discussion) {
                //     $discussion = new Discussion([
                //         'admin' => $admin->getId(),
                //         'compte_receveur' => $compte_receveur,
                //         'receveur' => $receveur->getId()
                //     ]);
                //     $em->add($discussion);
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

                    $discussion = (new DiscussionRepository)->findOneBy(['admin' => $admin->getId(), 'receveur' => $receveur->getId(), 'compte_receveur' => $compte_receveur]);
                    if(!$discussion) {
                        $discussion = new Discussion([
                            'admin' => $admin->getId(),
                            'compte_receveur' => $compte_receveur,
                            'receveur' => $receveur->getId()
                        ]);
                        $em->add($discussion);
                    }
                    
                    // if(!$conversation) {
                    //     $conversation = new Conversation(['locataire' => $user->getId(), 'proprietaire' => $proprietaire->getId(), 'timestamp' => time()]);
                    //     $em->add($conversation);                        
                    // } else {
                    //     $conversation->setTimestamp(time());
                    //     $em->update($conversation);
                    // }

                    $message = new Message([
                        'expediteur' => $expediteur,
                        'message' => $text,
                        'fichier' => $fichier,
                        'type_fichier' => $type_fichier,
                        'discussion' => $discussion->getId()
                    ]);
                    
                    $em->add($message);

                    $lastMessage = (new LastMessageRepository)->findOneBy(['discussion' => $discussion->getId()]);

                    if($lastMessage) {
                        $lastMessage->setMessage($message->getId());
                        $em->update($lastMessage);
                    } else {
                        $lastMessage = new LastMessage([
                            'discussion' => $discussion->getId(), 
                            'message' => $message->getId()
                        ]);
                        $em->add($lastMessage);
                    }
                    $this->set_messages($message);
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
                            $receveur = (new DentisteRepository)->findOneBy(['slug' => $chat['dest']]);
                        } elseif($compte_receveur === 'transporteur') {
                            $receveur = (new TransporteurRepository)->findOneBy(['slug' => $chat['dest']]);
                        }
                    }
                }
            } elseif($this->isCsrfValidate('dentiste', $chat['exp'])) {
                if($user = $this->getDentiste()) {
                    $admin = (new AdminRepository)->findOneBy(['active' => 1]);
                    $compte_receveur = 'dentiste';
                    $receveur = $user;
                }
            } elseif($this->isCsrfValidate('transporteur', $chat['exp'])) {
                if($user = $this->getTransporteur()) {
                    $admin = (new AdminRepository)->findOneBy(['active' => 1]);
                    $compte_receveur = 'transporteur';
                    $receveur = $user;
                }
            }

            if($admin && $compte_receveur && $receveur) {
                $discussion = (new DiscussionRepository)->findOneBy(['admin' => $admin->getId(), 'receveur' => $receveur->getId(), 'compte_receveur' => $compte_receveur]);
                if($discussion && isset($chat['key'])) {
                    $this->set_messages((new MessageRepository)->getNewsMessages($discussion->getId(), (int)htmlspecialchars($chat['key'])));
                }
            }
        }
    }
}
<?php

use src\Repository\DentisteRepository;
use src\Repository\DiscussionRepository;
use src\Repository\TransporteurRepository;
use src\Router\Router;

include '../autoload.php';

$router = new Router;

$router->dentisteIsConnected();

$router->request();

$dentiste = $router->getDentiste();

$discussionRepository = new DiscussionRepository;
$dentisteRepository = new DentisteRepository;
$transporteurRepository = new TransporteurRepository;

$receveurs = [];
$messages = [];

// if(isset($_GET['user'])) {
//     $user = strtolower($_GET['user']);
//     if($user == 'dentiste') {
//         $receveurs = $dentisteRepository->findAll();
//     } elseif($user == 'transporteur') {
//         $receveurs = $transporteurRepository->findAll();
//     }
// }

// if(isset($_GET['dest']) && isset($user)) {
//     if($user == 'dentiste') {
//         $dest = $dentisteRepository->findOneBy(['slug' => $_GET['dest']]);
//     } elseif($user == 'transporteur') {
//         $dest = $transporteurRepository->findOneBy(['slug' => $_GET['dest']]);
//     }
//     if(isset($dest) && null !== $dest) {
//         $discussion = $discussionRepository->findOneBy(['compte_receveur' => $user, 'receveur' => $dest->getId()]);
//         if($discussion) {
//             $messages = $discussion->getMessages();
//         }
//     }
// }

$discussion = $discussionRepository->findOneBy(['compte_receveur' => 'dentiste', 'receveur' => $dentiste->getId()]);
if($discussion) {
    $messages = $discussion->getMessages();
}

$account = 'dentiste';

$header_link = 3;

    ob_start();
?>
    <link rel="stylesheet" href="../assets/css/message.css">
    <script src="../assets/js/chat.js"></script>
    <style>
        main.container-fluid {
            height:100vh;
            padding-top:0;
            padding-bottom:0;
        }
        div.discussion-container {
            position:absolute;
            left:0;
            bottom:0;
            width: 100%;
            height: calc(100vh - 60px);
            max-height: unset;
            margin: 0;
            display:flex;
        }
        div.discussions {
            border-right:1px solid #f4f4f4;
            padding-top: calc(var(--logo-height) - 60px);
        }
        div.discussions .form-filter {
            top: calc(var(--logo-height) - 0px);
            background: transparent;
            border-bottom:1px solid #f4f4f4;
        }
        div.discussions .form-filter .input-group {
            background: #f4f4f4;
        }
        div.discussions .conversation-liste {
            padding-top:121px;
        }
        .chat-zone {
            width: calc(100% - var(--logo-width));
            margin-left:auto;
            border-left: 1px solid #f4f4f4;
        }
        .rounded-pill {
            border-radius: 25rem;
        }
    </style>
<?php
    $style = ob_get_clean();

    ob_start();
?>

    <div class="discussion-container">

        <div class="discussions d-none">
            <?php include 'layouts/message/_discussions.php'; ?>
        </div>

        <div class="chat-zone position-relative">

            <?php include 'layouts/message/_header.php'; ?>

            <div class="chat-zone-main h-100 px-3" style="padding-top: calc(var(--headerh) + 10px);padding-bottom: calc(var(--footerh) + 10px);">

                <?php include '../layouts/message/_main.php'; ?>

                <div id="marker"></div>

            </div>

            <?php include 'layouts/message/_footer.php'; ?>

        </div>

    </div>

<?php
    $main = ob_get_clean();

    ob_start();
?>
    <script>
            setTimeout(() => {
                // resizeChatZoneMain()
                beginReload()
            }, 5000)
            setTimeout(() => {
                chatScrollToBottom()
            }, 100)
            setTimeout(() => {
                if(Z('html').scrollTop == 0) {
                    chatScrollToBottom()
                }
            }, 500)
            timeInter = setInterval(() => {
                if(Z('#marker')) {
                    beginSync()
                    observerMarker.observe(Z('#marker'));
                    clearInterval(timeInter);
                    timeInter = null;
                }                
            }, 1000);
    </script>
<?php
    $script = ob_get_clean();

require 'base.php';
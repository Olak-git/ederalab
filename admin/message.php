<?php
// name: message
// route: message-([a-z]*)
// route: message-([a-z]*)-([a-z]*)

use src\Repository\DentisteRepository;
use src\Repository\DiscussionRepository;
use src\Repository\TransporteurRepository;
use src\Router\Router;

include '../autoload.php';

$router = new Router;

$router->adminIsConnected();

$router->request();

$discussionRepository = new DiscussionRepository;
$dentisteRepository = new DentisteRepository;
$transporteurRepository = new TransporteurRepository;

$receveurs = [];
$messages = [];
$account = 'admin';

if(isset($_GET['user'])) {
    $user = strtolower($_GET['user']);
    if($user == 'dentiste') {
        $receveurs = $dentisteRepository->findAll();
    } elseif($user == 'transporteur') {
        $receveurs = $transporteurRepository->findAll();
    }
}

if(isset($_GET['dest']) && isset($user)) {
    if($user == 'dentiste') {
        $dest = $dentisteRepository->findOneBy(['slug' => $_GET['dest']]);
    } elseif($user == 'transporteur') {
        $dest = $transporteurRepository->findOneBy(['slug' => $_GET['dest']]);
    }
    if(isset($dest) && null !== $dest) {
        $discussion = $discussionRepository->findOneBy(['compte_receveur' => $user, 'receveur' => $dest->getId()]);
        if($discussion) {
            $messages = $discussion->getMessages();
        }
    }
}

$alink = 4;

    ob_start();
?>
    <link rel="stylesheet" href="../assets/css/message.css">
    <script src="../assets/js/chat.js"></script>
    <style>

    </style>
<?php
    $style = ob_get_clean();

    ob_start();
?>

    <div class="w-100 d-flex flex-wrap justify-content-center align-items-center mt-2">
        <a href="<?= $router->getRoutes()->path('message', ['user' => 'dentiste']); ?>" class="btn shadow-none btn-users mt-2<?= isset($user) && $user == 'dentiste' ? ' active' : ''; ?>">Dentiste</a>
        <span class="separateur mx-2"></span>
        <a href="<?= $router->getRoutes()->path('message', ['user' => 'transporteur']); ?>" class="btn shadow-none btn-users mt-2<?= isset($user) && $user == 'transporteur' ? ' active' : ''; ?>">Fournisseur</a>
    </div>

    <div class="discussion-container">

        <div class="discussions">
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

            Z('.filter-users').onkeyup = function() {
                let texte = this.value.toLowerCase()
                if(texte !== '') {
                    closeReload()
                } else {
                    beginReload()
                }
                S('.conversation-item').forEach(c => {
                    if(c.getAttribute('data-filter').indexOf(texte) == -1) {
                        c.classList.add('d-none')
                    } else {
                        c.classList.remove('d-none')
                    }
                })
            }
    </script>
<?php
    $script = ob_get_clean();

require 'base.php';
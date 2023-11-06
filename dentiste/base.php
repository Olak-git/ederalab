<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <?php include '../layouts/_head-base.php'; ?>
    <link rel="stylesheet" href="./assets/css/base.css">

    <?= isset($style) ? $style : ''; ?>
    <script src="../assets/js/async.js"></script>
</head>
<body>

    <?php if($router->hasError()): ?>
        <div id="notifier">
            <a type="button" onclick="hideNotifier();" style="cursor:pointer;position:absolute;top:5px;right:1rem;color:#ff2222;"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/></svg></a>
            <div class="mt-2">
                <?php foreach($router->getError() as $note): ?>
                    <span class="d-flex justify-content-start align-items-start w-100">
                        <span class="d-inline-block pt-1 mr-1" style="line-height:.5;"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-asterisk" viewBox="0 0 16 16">
                            <path d="M8 0a1 1 0 0 1 1 1v5.268l4.562-2.634a1 1 0 1 1 1 1.732L10 8l4.562 2.634a1 1 0 1 1-1 1.732L9 9.732V15a1 1 0 1 1-2 0V9.732l-4.562 2.634a1 1 0 1 1-1-1.732L6 8 1.438 5.366a1 1 0 0 1 1-1.732L7 6.268V1a1 1 0 0 1 1-1z"/>
                        </svg></span>
                        <span class="d-block"><?= $note; ?></span>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if($router->getShowNotification()): ?>
        <div id="notifications" class="<?= $router->getNotificationColor(); ?>">
            <a type="button" onclick="hideNotification();" style="cursor:pointer;position:absolute;top:5px;right:1rem;color:#ffffff;"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/></svg></a>
            <div class="mt-2">
                <?php foreach($router->getNotifications() as $note): ?>
                    <span class="d-flex justify-content-start align-items-start w-100">
                        <!-- <span class="d-inline-block pt-1 mr-1" style="line-height:.5;"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-asterisk" viewBox="0 0 16 16">
                            <path d="M8 0a1 1 0 0 1 1 1v5.268l4.562-2.634a1 1 0 1 1 1 1.732L10 8l4.562 2.634a1 1 0 1 1-1 1.732L9 9.732V15a1 1 0 1 1-2 0V9.732l-4.562 2.634a1 1 0 1 1-1-1.732L6 8 1.438 5.366a1 1 0 0 1 1-1.732L7 6.268V1a1 1 0 0 1 1-1z"/>
                        </svg></span> -->
                        <span class="d-block"><?= $note; ?></span>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <header class="d-flex justify-content-between">
        <div class="logo flex-center">
            <img src="<?= $router->getLogo(); ?>" alt="">
        </div>
        <div class="links">
            <nav class="h-100">
                <ul class="h-100 mb-0 pl-0">
                    <li class="<?= isset($header_link) && $header_link == 1 ? 'active' : ''; ?>"><a href="accueil.php" class="flex-center header-link">Accueil</a></li>
                    <li class="<?= isset($header_link) && $header_link == 2 ? 'active' : ''; ?>"><a href="creer-commande.php" class="flex-center header-link">Faire une commande</a></li>
                    <li class="<?= isset($header_link) && $header_link == 3 ? 'active' : ''; ?>"><a href="message.php" class="flex-center header-link">Messagerie</a></li>
                    <li class="<?= isset($header_link) && $header_link == 4 ? 'active' : ''; ?>"><a href="a-savoir.php" class="flex-center header-link">important à savoir</a></li>
                </ul>
            </nav>
            <span class="d-flex justify-content-between align-items-center mt- h-100">
                <a href="#" class="d-flex justify-content-center align-items-center text-white h-100 mr3 hlink<?= isset($hlink) && $hlink == 1 ? ' active' : ''; ?>" style="width:50px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                        <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"/>
                    </svg>
                </a>
                <a href="parametres.php" class="d-flex justify-content-center align-items-center text-white h-100 hlink<?= isset($hlink) && $hlink == 2 ? ' active' : ''; ?>" style="width:50px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                        <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
                    </svg>
                </a>
            </span>
        </div>
    </header>

    <?php /*<div class="logo flex-center">
        <img src="<?= $router->getLogo(); ?>" alt="">
    </div>
    <header class="">
        <nav class="h-100">
            <ul class="h-100 mb-0 pl-0">
                <li class="<?= isset($header_link) && $header_link == 1 ? 'active' : ''; ?>"><a href="accueil.php" class="flex-center header-link">Accueil</a></li>
                <li class="<?= isset($header_link) && $header_link == 2 ? 'active' : ''; ?>"><a href="creer-commande.php" class="flex-center header-link">Faire une commande</a></li>
                <li class="<?= isset($header_link) && $header_link == 3 ? 'active' : ''; ?>"><a href="message.php" class="flex-center header-link">Messagerie</a></li>
                <li class="<?= isset($header_link) && $header_link == 4 ? 'active' : ''; ?>"><a href="" class="flex-center header-link">important à savoir</a></li>
            </ul>
        </nav>
        <span class="d-flex justify-content-between align-items-center mt- h-100">
            <a href="#" class="d-flex justify-content-center align-items-center text-white h-100 mr3 hlink<?= isset($hlink) && $hlink == 1 ? ' active' : ''; ?>" style="width:50px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"/>
                </svg>
            </a>
            <a href="parametres.php" class="d-flex justify-content-center align-items-center text-white h-100 hlink<?= isset($hlink) && $hlink == 2 ? ' active' : ''; ?>" style="width:50px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                    <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
                </svg>
            </a>
        </span>
    </header>*/ ?>

    <main class="container-fluid pb-5" style="">
        <?= isset($main) ? $main : ''; ?>
    </main>

    <script src="../assets/js/vendor.bundle.base.js"></script>
    <script>
        if(document.querySelector('.back-button')) {
            document.querySelector('.back-button').onclick = function (event) {
                event.preventDefault();
                let href = this.getAttribute('href')
                if(href !== '' && href !== '#') {
                    window.location = href
                } else {
                    window.history.go(-1);
                }
            }
        }
    </script>

    <?= isset($script) ? $script : ''; ?>
</body>
</html>
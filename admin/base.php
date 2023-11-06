<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="">
    <meta name="description" content="">
    <title>Ederalab<?= isset($title_child) ? ' - ' . $title_child : ''; ?></title>
    <link rel="shortcut icon" href="<?= $router->getLogo(); ?>">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/base.css">
    <style>
        a.commandes .icone-commandes {
            display:inline-block;width: 18px;height: 21px;background: url('../assets/images/site/icons8-livraison-importante-50.png');background-position: center;background-size: 100% 100%;background-repeat: no-repeat;
        }
        a.commandes.active .icone-commandes {
            background-image: url('../assets/images/site/icons8-livraison-importante-50-1.png')
        }
        a.partenaires .icone-partenaires {
            display:inline-block;width: 18px;height: 21px;background: url('../assets/images/site/icons8-soins-des-diamants-50.png');background-position: center;background-size: 100% 100%;background-repeat: no-repeat;
        }
        a.partenaires.active .icone-partenaires {
            background-image: url('../assets/images/site/icons8-soins-des-diamants-50-1.png')
        }
        a.clients .icone-clients {
            display:inline-block;width: 18px;height: 21px;background: url(' ../assets/images/site/icons8-rechercher-un-client-50.png');background-position: center;background-size: 100% 100%;background-repeat: no-repeat;
        }
        a.clients.active .icone-clients {
            background-image: url(' ../assets/images/site/icons8-rechercher-un-client-50-1.png')
        }
        /* */
    </style>

    <?= isset($style) ? $style : ''; ?>

    <script src="../assets/js/async.js"></script>

</head>
<body class="bg-light">

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

    <div class="nav-left bg-ederalab">
        <div class="py-3 px-4">
            <img src="<?= $router->getLogo(); ?>" width="60" />
        </div>
        <div class="link-nav d-flex flex-column mt-4">
            <a href="calendrier-de-planification-commande-recue.php" class="d-flex align-items-center link<?= isset($alink) && $alink == 1 ? ' active' : ''; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 bi bi-calendar3" viewBox="0 0 16 16">
                    <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z"/>
                    <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                </svg>Planification
            </a>
            <a href="gestion-suivi-commandes-recues.php" class="d-flex align-items-center link<?= isset($alink) && $alink == 2 ? ' active' : ''; ?> commandes">
                <span class="mr-2 icone-commandes" style=""></span>Commandes
            </a>
            <a href="chart.php" class="d-flex align-items-center link<?= isset($alink) && $alink == 3 ? ' active' : ''; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 bi bi-bar-chart-line" viewBox="0 0 16 16">
                    <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1V2zm1 12h2V2h-2v12zm-3 0V7H7v7h2zm-5 0v-3H2v3h2z"/>
                </svg>Rapport
            </a>
            <a href="message.php?user=dentiste" class="d-flex align-items-center  link<?= isset($alink) && $alink == 4 ? ' active' : ''; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 bi bi-chat-right" viewBox="0 0 16 16">
                    <path d="M2 1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h9.586a2 2 0 0 1 1.414.586l2 2V2a1 1 0 0 0-1-1H2zm12-1a2 2 0 0 1 2 2v12.793a.5.5 0 0 1-.854.353l-2.853-2.853a1 1 0 0 0-.707-.293H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12z"/>
                </svg>Messagerie
            </a>
            <a href="tracking.php" class="d-flex align-items-center link<?= isset($alink) && $alink == 5 ? ' active' : ''; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2 bi bi-map" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15.817.113A.5.5 0 0 1 16 .5v14a.5.5 0 0 1-.402.49l-5 1a.502.502 0 0 1-.196 0L5.5 15.01l-4.902.98A.5.5 0 0 1 0 15.5v-14a.5.5 0 0 1 .402-.49l5-1a.5.5 0 0 1 .196 0L10.5.99l4.902-.98a.5.5 0 0 1 .415.103zM10 1.91l-4-.8v12.98l4 .8V1.91zm1 12.98 4-.8V1.11l-4 .8v12.98zm-6-.8V1.11l-4 .8v12.98l4-.8z"/>
                </svg>Tracking
            </a>
            <a href="partenaires.php" class="d-flex align-items-center link<?= isset($alink) && $alink == 6 ? ' active' : ''; ?> partenaires">
                <span class="mr-2 icone-partenaires" style=""></span>
                Partenaires
            </a>
            <a href="dentistes.php" class="d-flex align-items-center link<?= isset($alink) && $alink == 7 ? ' active' : ''; ?> clients">
                <span class="mr-2 icone-clients" style=""></span>
                Dentistes
            </a>
        </div>
    </div>

    <div class="nav-fixed bg-ederalab">
        <img src="../assets/images/site/icons8-symbole-de-la-lune-90.png" style="position:absolute;bottom:-35px;left:129px;width:50px;height:50px;transform:rotate(76deg);">
        <div class="w-100 h-100 px-3 d-flex flex-wrap justify-content-end align-items-center">
            <form class="mt- mb-0 mr-4">
                <div class="input-group d-flex justify-content-between align-items-center">
                    <input type="search" placeholder="Recherche" class="form-control form-control-sm px-2" />
                    <button class="append-element text-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>
                    </button>
                </div>
            </form>
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
    </div>

    <main style="">
        <?= isset($main) ? $main : ''; ?>
    </main>

    <script src="../assets/js/vendor.bundle.base.js"></script>
    <script>
        //Back Button in Header
        S('.back-button').forEach(b => {
            b.onclick = function (event) {
                event.preventDefault();
                let href = this.getAttribute('href')
                if(href !== '' && href !== '#') {
                    window.location = href
                } else {
                    window.history.go(-1);
                }
            }
        });
    </script>
    <?= isset($script) ? $script : ''; ?>
</body>
</html>
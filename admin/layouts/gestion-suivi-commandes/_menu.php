
            <?php 
                $r1 = $commandeRepository->totalCommandesRecues(); 
                $r2 = $commandeRepository->totalCommandesLivrees();
                $r3 = $commandeRepository->totalCommandesEnattente();
                $r4 = $commandeRepository->totalCommandesAnnulees();
            ?>
            <a href="<?= $router->getRoutes()->path('gestion_commandes_recues'); ?>" class="btn-block<?= $code == 1 ? ' active' : ''; ?>">
                <span class="title">Commandes reçues</span>
                <span class="count"><?= $r1 < 10 ? '0' . $r1 : $r1; ?></span>
            </a>
            <a href="<?= $router->getRoutes()->path('gestion_commandes_livrees'); ?>" class="btn-block<?= $code == 2 ? ' active' : ''; ?>">
                <span class="title">Commandes livrées</span>
                <span class="count"><?= $r2 < 10 ? '0' . $r2 : $r2; ?></span>
            </a>
            <a href="<?= $router->getRoutes()->path('gestion_commandes_en_attente'); ?>" class="btn-block<?= $code == 3 ? ' active' : ''; ?>">
                <span class="title">Commandes en attente</span>
                <span class="count"><?= $r3 < 10 ? '0' . $r3 : $r3; ?></span>
            </a>
            <a href="<?= $router->getRoutes()->path('gestion_commandes_annulees'); ?>" class="btn-block<?= $code == 4 ? ' active' : ''; ?>">
                <span class="title">Commandes annulée</span>
                <span class="count"><?= $r4 < 10 ? '0' . $r4 : $r4; ?></span>
            </a>
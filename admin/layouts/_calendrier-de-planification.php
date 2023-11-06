
    <div class="row mb-5">
        <div class="col-12 text-right">
            <a href="<?= $router->getRoutes()->path('archives'); ?>" class="btn btn-archive border-ederalab bg-white text-ederalab font-weight-bold shadow-none" style="padding:.6rem 1.25rem;font-size:13px;border-radius: .75rem;">Archive</a>
        </div>
    </div>

    <div class="d-flex flex-wrap justify-content-center py-3 position-relative">
        <div class="div-recap" style="">
            <span class="d-block text-center small font-weight-bold mb-2">Tableau récapitulatif de vos transactions</span>
            <table class="table-recap bg-white shadow-sm" style="border-radius:1rem;overflow:hidden;">
                <thead>
                    <tr>
                        <td>Commande</td>
                        <td>Chiffre</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Reçue</td>
                        <td class="text-center"><?= $commandes_recues < 10 ? '0' . $commandes_recues : $commandes_recues; ?></td>
                    </tr>
                    <tr>
                        <td>Livrée</td>
                        <td class="text-center"><?= $commandes_livrees < 10 ? '0' . $commandes_livrees : $commandes_livrees; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="calendar col-12 col-sm-11 d-flex justify-content-center align-items-end">

            <div class="flex-1 shadow-sm">
                <div class="calendar--menu d-flex flex-column w-100 bg-white">
                    <a href="<?= $router->getRoutes()->path('planification_commande_recue'); ?>" class="c-link <?= isset($clink) ? ($clink == 1 ? 'active' : '') : 'active' ?>">Date de commande reçue</a>
                    <a href="<?= $router->getRoutes()->path('planification_commande_livree'); ?>" class="c-link <?= isset($clink) && $clink == 2 ? 'active' : ''; ?>">Date de commande livrée</a>
                    <a href="<?= $router->getRoutes()->path('planification_commande_transporteur'); ?>" class="c-link <?= isset($clink) && $clink == 3 ? 'active' : ''; ?>">Date de réception du fournisseur d'une commande</a>
                </div>
            </div>
            <div class="flex-2 bg-white position-relative shadow-sm">
                <table class="table-calendar">
                    <caption class="p-0" style="caption-side:top;">
                        <div class="table-calendar-caption p-3 bg-ederalab text-white d-flex justify-content-between align-items-center" style="height:85px;">
                            <form method="post" class="m-0">
                                <button class="border-0 text-white" style="padding:0 !important;background:transparent;cursor:pointer;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-arrow-left-circle-fill" viewBox="0 0 16 16">
                                        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/>
                                    </svg>
                                </button>
                                <input type="hidden" name="y" value="<?= $calendar->getPreviousYear() ?>">
                                <input type="hidden" name="m" value="<?= $calendar->getPreviousMonth() ?>">
                            </form>
                            
                            <h4 class="text-white text-uppercase"><?= $router->getMonth($calendar->getMonth()) . ' ' . $calendar->getYear(); ?></h4>

                            <form method="post" class="m-0">
                                <button class="border-0 text-white" style="padding:0 !important;background:transparent;cursor:pointer;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-arrow-right-circle-fill" viewBox="0 0 16 16">
                                        <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0zM4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z"/>
                                    </svg>
                                </button>
                                <input type="hidden" name="y" value="<?= $calendar->getNextYear() ?>">
                                <input type="hidden" name="m" value="<?= $calendar->getNextMonth() ?>">
                            </form>
                        </div>
                    </caption>
                    <thead>
                        <tr>
                            <th>Lun</th>
                            <th>Mar</th>
                            <th>Mer</th>
                            <th>Jeu</th>
                            <th>Ven</th>
                            <th>Sam</th>
                            <th>Dim</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($weeks as $k => $week): ?>
                            <tr>
                                <?php if($clink === 1): ?>
                                    <?php foreach($week as $ki => $day): ?>
                                        <?php $cmd = $day !== null ? $router->getCommandes($calendar->getDate($day)) : []; ?>
                                        <td class="<?= !empty($cmd) ? 'bg-ederalab text-white' : '' ?>">
                                            <?php if(!empty($cmd)): ?>
                                                <a href="#" 
                                                    type="button" 
                                                    class="d-inline-block w-100 h-100 text-white show-modal-commande" 
                                                    data-name="calendrier" 
                                                    data-name-value="cmd_recue" 
                                                    data-date="<?= $calendar->getDate($day); ?>" 
                                                    data-toggle="modal" 
                                                    data-target="#commandeModal" 
                                                    style="position:absolute;top:0;left:0;"><span class="__date"><?= $day ?></span></a>
                                            <?php else: ?>
                                                <span class="__date"><?= $day ?></span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>

                                <?php elseif($clink === 2): ?>
                                    <?php foreach($week as $ki => $day): ?>
                                        <?php $cmd = $day !== null ? $router->getCommandesLivrees($calendar->getDate($day)) : []; ?>
                                        <td class="<?= !empty($cmd) ? 'bg-ederalab text-white' : '' ?>">
                                            <?php if(!empty($cmd)): ?>
                                                <a href="#" 
                                                    type="button" 
                                                    class="d-inline-block w-100 h-100 text-white show-modal-commande" 
                                                    data-name="calendrier" 
                                                    data-name-value="cmd_livree" 
                                                    data-date="<?= $calendar->getDate($day); ?>" 
                                                    data-toggle="modal" 
                                                    data-target="#commandeModal" 
                                                    style="position:absolute;top:0;left:0;"><span class="__date"><?= $day ?></span></a>
                                            <?php else: ?>
                                                <span class="__date"><?= $day ?></span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>

                                <?php elseif($clink === 3): ?>
                                    <?php foreach($week as $ki => $day): ?>
                                        <?php $cmd = $day !== null ? $commandeRepository->getCommandeByTransReceptionDate($calendar->getDate($day)) : []; ?>
                                        <td class="<?= !empty($cmd) ? 'bg-ederalab text-white' : '' ?>">
                                            <?php if(!empty($cmd)): ?>
                                                <a href="#" 
                                                    type="button" 
                                                    class="d-inline-block w-100 h-100 text-white show-modal-commande" 
                                                    data-name="calendrier" 
                                                    data-name-value="reception_fournisseur" 
                                                    data-date="<?= $calendar->getDate($day); ?>" 
                                                    data-toggle="modal" 
                                                    data-target="#commandeModal" 
                                                    style="position:absolute;top:0;left:0;"><span class="__date"><?= $day ?></span></a>
                                            <?php else: ?>
                                                <span class="__date"><?= $day ?></span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>

                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
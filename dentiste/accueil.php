<?php
use src\Router\Router;
use src\Services\CalendarService;

require_once '../autoload.php';

$router = new Router;

$router->dentisteIsConnected();

$router->request();

$year = date('Y');
$month = date('m');

if(isset($_POST['y'])) {
    $year = $_POST['y'];
}

if(isset($_POST['m'])) {
    $month = $_POST['m'];
}

$calendar = new CalendarService($year, $month);
$weeks = $calendar->getWeeks();

$total_commande_livree = $router->getDentiste()->getTotalCommandesLivrees();
$total_commande_en_cours = $router->getDentiste()->getTotalCommandesEncours();

$header_link = 1;

ob_start();
?>
    <style>
        #td-month {
            background-color: var(--ederalab);
            color: #fff;
            font-weight: 700;
            text-transform: uppercase;
            text-orientation: upright;
            vertical-align: revert;
            writing-mode: vertical-lr;
            text-align: center;
            letter-spacing: 5px;
        }
        table.table-calendar {
            border-radius: 1.5rem;
            overflow: hidden;
        }
        table.table-calendar thead th:first-child,
        table.table-calendar tbody tr:first-child td:first-child {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        table.table-calendar td {
            width: 40px;
            max-width: 40px;
            height: 62px;
            padding: 1rem;
            position: relative;
            border: 1px solid #eee;
            word-break: break-all;
            text-align: center;
        }
        table.table-calendar td .__date {
            position: absolute;
            top: 5px;
            right: 10px;
        }

        @media screen and (max-width: 991px) {
            .div-recap {
                margin-left: 3.5rem;
            }
        }
    </style>
<?php
$style = ob_get_clean();

ob_start();
    
    include 'layouts/_modal-commande.php';
?>
    <div class="d-flex flex-wrap justify-content-between align-items-center p-3">

        <!-- <div class="calendar col-12 col-sm-11 d-flex justify-content-center align-items-end"> -->

            <div class="col-12 col-lg-8 bg-white position-relative order-2 order-lg-1 mb-3">
                <h3 style="padding-left:4rem;">Agenda</h3>
                <div class="w-100 d-flex justify-content-center">
                    <div class="" style="width:50px;">
                        <div class="card border-0 h-100 flex-center">
                            <form method="post" class="m-0">
                                <button class="border-0" style="padding:0 !important;background:transparent;cursor:pointer;font-weight:300;color:silver;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/>
                                    </svg>
                                </button>
                                <input type="hidden" name="y" value="<?= $calendar->getPreviousYear() ?>">
                                <input type="hidden" name="m" value="<?= $calendar->getPreviousMonth() ?>">
                            </form>
                        </div>
                    </div>
                    <div class="w-100 mx-1 p-1" style="overflow-x:auto;border-radius: 1.5rem;">
                        <table class="table table-bordered shadow table-calendar m-0">
                            <thead>
                                <tr>
                                    <th><?= $calendar->getYear(); ?></th>
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
                                        <?php if($k == 0): ?>
                                            <td id="td-month" rowspan="<?= count($weeks); ?>" style=""><?= $router->getMonth($calendar->getMonth()); ?></td>
                                        <?php endif; ?>
                                        <?php foreach($week as $ki => $day): ?>
                                            <?php $cmd = $day !== null ? $router->getCommandesDentiste($calendar->getDate($day)) : []; ?>
                                            <td class="<?= !empty($cmd) ? 'bg-ederalab text-white' : '' ?>">
                                                <?php if(!empty($cmd)): ?>
                                                    <a href="#" 
                                                        type="button" 
                                                        class="d-inline-block w-100 h-100 text-white show-modal-commande" 
                                                        data-name="calendrier" 
                                                        data-name-value="cmd_envoyee" 
                                                        data-date="<?= $calendar->getDate($day); ?>" 
                                                        data-toggle="modal" 
                                                        data-target="#commandeModal" 
                                                        style="position:absolute;top:0;left:0;"><span class="__date"><?= $day ?></span></a>
                                                <?php else: ?>
                                                    <span class="__date"><?= $day ?></span>
                                                <?php endif; ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="" style="width:50px;">
                        <div class="card border-0 h-100 flex-center">
                            <form method="post" class="m-0">
                                <button class="border-0" style="padding:0 !important;background:transparent;cursor:pointer;font-weight:300;color:silver;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z"/>
                                    </svg>
                                </button>
                                <input type="hidden" name="y" value="<?= $calendar->getNextYear() ?>">
                                <input type="hidden" name="m" value="<?= $calendar->getNextMonth() ?>">
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col col-auto div-recap order-1 order-lg-2 mb-3" style="">
                <span class="d-block text-center small font-weight-bold mb-2">Tableau récapitulatif de vos transactions</span>
                <table class="table table-bordered table-recap bg-white shadow-sm" style="border-radius:1rem;overflow:hidden;">
                    <thead>
                        <tr>
                            <td>Commande</td>
                            <td>Chiffre</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Livrée</td>
                            <td class="text-center"><?= $total_commande_livree < 10 ? '0' . $total_commande_livree : $total_commande_livree; ?></td>
                        </tr>
                        <tr>
                            <td>En cours</td>
                            <td class="text-center"><?= $total_commande_en_cours < 10 ? '0' . $total_commande_en_cours : $total_commande_en_cours; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <!-- </div> -->
    </div>

<?php 
$main = ob_get_clean();

ob_start();
?>
    <script>
        S('.show-modal-commande').forEach(e => {
            e.onclick = function() {
                const formData = new FormData()
                // formData.append('calendrier', 'cmd_recu')
                const data_name = this.getAttribute('data-name')
                const data_value = this.getAttribute('data-name-value')
                formData.append(data_name, data_value)
                if(data_name == 'calendrier') {
                    formData.append('date', this.getAttribute('data-date'))
                }
                // formData.append('calendrier[cmd_recu[date_envoie]]', this.getAttribute('data-date'))
                fetchText('async.php', formData)
            }
        });

        const setStateDelivery = function (e) {
            window.event.preventDefault()
            const formData = new FormData(e)
            formData.append('async', '1')
            fetch('async.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(response => {
                console.log(e.elements['delivery[v]'].value)
                if(response.success) {
                    const chi = Z('#delivery-text-' + e.getAttribute('data-uniq-s'))
                    if(e.elements['delivery[v]'].value == 2) {
                        if(chi) {
                            chi.textContent = 'Reçue'
                            chi.className = 'text-success'
                        }
                    } else if(e.elements['delivery[v]'].value == -1) {
                        if(chi) {
                            chi.textContent = 'non reçue'
                            chi.className = 'text-danger'
                        }
                    }
                    e.parentElement.nextElementSibling.remove()
                    e.parentElement.remove()
                } else {
                    const errors = response.errors
                    console.log(errors)
                    
                    // if(errors.hasOwnProperty('identite')) {
                    //     const error = errorHTML2('', errors.sous_categorie);
                    //     Z('.sous_categorie_nom').insertAdjacentElement('afterend', error)
                    // }
                    // if(errors.hasOwnProperty('edit_sous_categorie')) {
                    //     const error = errorHTML2('edit_sous_categorie', errors.edit_sous_categorie);
                    //     Z('.edit_sous_categorie_nom').insertAdjacentElement('afterend', error)
                    // }
                }
            })
            .catch(error => console.log(error))
        }
    </script>
<?php
    $script = ob_get_clean();

require 'base.php';
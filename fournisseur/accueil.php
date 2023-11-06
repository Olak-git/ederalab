<?php
use src\Router\Router;
use src\Services\CalendarService;

require_once '../autoload.php';

$router = new Router;

$router->transporteurIsConnected();

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
        table.table-calendar tbody td {
            font-weight:600;
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
    <div class="d-flex justify-content-center align-items-center p-3">

        <div class="calendar col-12 col-sm-11">

                <table class="table-calendar w-100">
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
                            <td>Lun</td>
                            <td>Mar</td>
                            <td>Mer</td>
                            <td>Jeu</td>
                            <td>Ven</td>
                            <td>Sam</td>
                            <td>Dim</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($weeks as $k => $week): ?>
                            <tr>
                                <?php foreach($week as $ki => $day): ?>
                                    <?php $cmd = $day !== null ? $router->getCommandesTransporteur($calendar->getDate($day)) : []; ?>
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
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            <!-- <div class="col-12 col-md-10 bg-white position-relative mb-3"> -->

                <?php //include 'layouts/_calendrier.php'; ?>

            <!-- </div> -->

        </div>

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
    </script>
<?php
    $script = ob_get_clean();

require 'base.php';
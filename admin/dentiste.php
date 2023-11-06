<?php
// name: dentiste
// route: dentiste-([a-z]*)

use src\Repository\CommandeRepository;
use src\Repository\DentisteRepository;
use src\Router\Router;

include '../autoload.php';

$router = new Router;

$router->adminIsConnected();

if(isset($_GET['s'])) {
    $dentiste = (new DentisteRepository)->findOneBy(['slug' => $_GET['s']]);
}

$commandeRepository = new CommandeRepository;

$cmd_recue = [];
$cmd_livree = [];
$cmd_en_attente = [];
$cmd_non_livree = [];

if(!empty($dentiste)) {
    $cmd_recue = $router->getDataChart($commandeRepository->chartCommandeRecue($dentiste->getId()));
    $cmd_livree = $router->getDataChart($commandeRepository->chartCommandeLivree($dentiste->getId()));
    $cmd_en_attente = $router->getDataChart($commandeRepository->chartCommandeEnAttente($dentiste->getId()));
    $cmd_non_livree = $router->getDataChart($commandeRepository->chartCommandeNonLivree($dentiste->getId()));
}

$_cmds = [$cmd_recue, $cmd_livree];
$cmds = [$cmd_recue, $cmd_livree, $cmd_en_attente, $cmd_non_livree];

$alink = 3;

    ob_start();
?>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        .apexcharts-toolbar {
            z-index: 1 !important;
        }
        .apexcharts-menu {
            border: none !important;
            box-shadow: 0 0 .25rem rgba(0,0,0,.25);
        }
        .apexcharts-menu-item {
            background-color: #eee;
            text-align: center;
            margin-bottom: 1px;
            font-size: 11px !important;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table thead td {
            color: gray;
            font-weight: 600;
        }
        table thead td:last-child {
            color: #000;
        }
        table th, table td {
            color: #000;
            border: 1px solid silver;
            padding: 1rem;
        }
        .table1 tbody tr:nth-child(1) {
            background-color: #304c64;
        }
        .table1 tbody tr:nth-child(2) {
            background-color: #5084ac;
        }
        .table2 tbody tr:nth-child(1) {
            background-color: #f4bc64;
        }
        .table2 tbody tr:nth-child(2) {
            background-color: #54a464;
        }
        .table2 tbody tr:nth-child(3) {
            background-color: #a0c0e0;
        }
        .table2 tbody tr:nth-child(4) {
            background-color: #cc6c6c;
        }
        table tbody td {
            color: #fff;
        }
        .apexcharts-menu-item.exportCSV {
            display: none !important;
        }
    </style>
    <script>
        const getOptions = function (params) {
            const options = {
                chart: {
                    events: {
                        animationEnd: function (chartContext, config) {
                            S('.apexcharts-menu-item.exportSVG').forEach(e => {
                                e.textContent = 'Télécherger en svg'
                            })
                            S('.apexcharts-menu-item.exportPNG').forEach(e => {
                                e.textContent = 'Télécherger en png'
                            })
                            S('.apexcharts-menu-item.exportCSV').forEach(e => {
                                e.textContent = 'Télécherger en csv'
                            })
                        },
                        updated: function (chartContext, config) {
                            S('.apexcharts-menu-item.exportSVG').forEach(e => {
                                e.textContent = 'Télécherger en svg'
                            })
                            S('.apexcharts-menu-item.exportPNG').forEach(e => {
                                e.textContent = 'Télécherger en png'
                            })
                            S('.apexcharts-menu-item.exportCSV').forEach(e => {
                                e.textContent = 'Télécherger en csv'
                            })
                        }
                    },
                    columnWidth: 25,
                    height: 350,
                    // width: 800,
                    type: 'bar',
                    stacked: true
                },
                colors: params.color,
                title: {
                    text: params.title
                },
                noData: {
                    text: 'Loading...'
                },
                series: [{
                    name: '',
                    data: params.data //[18, 28, 47, 57, 77, 0, 0, 10, 0, 0, 0, 0]
                }],
                yaxis: {
                    tickAmount: 2,
                    min: 0,
                    max: 100,
                    labels: {
                        formatter: function(value) {
                            return value + '%';
                        }
                    },
                    opposite: false,
                    // type: 'percent',
                    axisBorder: {
                        show: true,
                        color: '#78909C',
                        offsetX: 0,
                        offsetY: 0
                    },
                    axisTicks: {
                        show: true,
                        borderType: 'solid',
                        color: '#78909C',
                        width: 6,
                        offsetX: 0,
                        offsetY: 0
                    },
                },
                xaxis: {
                    type: 'year',
                    categories: ['J', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D']
                }
            }
            return options
        }
    </script>
<?php
    $style = ob_get_clean();

    ob_start();
?>

    <div class="w-100 d-flex justify-content-between align-items-center mt-4">

        <div class="col-right px-2 w-100" style="">

            <?php if(!empty($dentiste)): ?>

                <div class="mb-5 p-3" style="border:1px solid silver;border-radius:1rem;min-height:50px;">

                    <span class="d-flex align-items-start">
                        <span class="d-flex justify-content-center align-items-center p-1 mr-2" style="background-color:#dee2e6;width:40px;min-width:40px;height:40px;border-radius:50%;">
                            <img src="<?= $router->getAvatar($dentiste->getImage()); ?>" class="mw-100 mh-100">
                        </span>
                        <div>
                            <span class="d-block badge p-0 text-left mb-2"><?= $dentiste->getUsername(); ?></span>
                            <span class="d-block mb-1">
                                <img src="../assets/images/site/icons8-couronne-dentaire-50.png" class="mr-2" width="18" height="18">
                                <?= $dentiste->getCabinet(); ?></span>
                            <span class="d-block mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill mr-3" viewBox="0 0 16 16">
                                    <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                                </svg><?= $dentiste->getEmail(); ?>
                            </span>
                            <span class="d-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-fill mr-3" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999zm2.493 8.574a.5.5 0 0 1-.411.575c-.712.118-1.28.295-1.655.493a1.319 1.319 0 0 0-.37.265.301.301 0 0 0-.057.09V14l.002.008a.147.147 0 0 0 .016.033.617.617 0 0 0 .145.15c.165.13.435.27.813.395.751.25 1.82.414 3.024.414s2.273-.163 3.024-.414c.378-.126.648-.265.813-.395a.619.619 0 0 0 .146-.15.148.148 0 0 0 .015-.033L12 14v-.004a.301.301 0 0 0-.057-.09 1.318 1.318 0 0 0-.37-.264c-.376-.198-.943-.375-1.655-.493a.5.5 0 1 1 .164-.986c.77.127 1.452.328 1.957.594C12.5 13 13 13.4 13 14c0 .426-.26.752-.544.977-.29.228-.68.413-1.116.558-.878.293-2.059.465-3.34.465-1.281 0-2.462-.172-3.34-.465-.436-.145-.826-.33-1.116-.558C3.26 14.752 3 14.426 3 14c0-.599.5-1 .961-1.243.505-.266 1.187-.467 1.957-.594a.5.5 0 0 1 .575.411z"/>
                                </svg><?= $dentiste->getAdresse(); ?>
                            </span>
                        </div>
                    </span>

                </div>

                <div class="mb-5">
                    <h4 class="text-center mb-4">Vente</h4>
                    <div class="d-flex flex-wrap justify-content-between">
                        <div id="chart1" class="col-12 col-md-6"></div>
                        <div id="chart2" class="col-12 col-md-6"></div>
                    </div>
                    <div class="" style="width:100%;overflow:hidden;overflow-x:auto;">
                        <table class="table1 mt-3">
                            <thead>
                                <tr>
                                    <td>Mois</td>
                                    <td>JAN</td>
                                    <td>FEV</td>
                                    <td>MAR</td>
                                    <td>AVR</td>
                                    <td>MA</td>
                                    <td>JUN</td>
                                    <td>JUI</td>
                                    <td>AOU</td>
                                    <td>SEP</td>
                                    <td>OCT</td>
                                    <td>NOV</td>
                                    <td>DEC</td>
                                    <td>TOTAL</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($_cmds as $i => $cmd): ?>
                                    <?php $total = 0; ?>
                                    <tr>
                                        <?php if($i == 0): ?>
                                            <td>Entrée(%)</td>
                                        <?php elseif($i == 1): ?>
                                            <td>Sortie(%)</td>
                                        <?php endif; ?>
                                        <?php foreach($cmd as $c): ?>
                                            <?php $total += $c; ?>
                                            <td class="text-center"><?= $c ?></td>
                                        <?php endforeach; ?>
                                        <td><?= $total ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="">
                    <h4 class="text-center mb-4">Performances globales</h4>
                    <div class="d-flex flex-wrap justify-content-between">
                        <div id="chart3" class="col-12 col-md-6"></div>
                        <div id="chart4" class="col-12 col-md-6"></div>
                        <div id="chart5" class="col-12 col-md-6"></div>
                        <div id="chart6" class="col-12 col-md-6"></div>
                    </div>
                    <div class="" style="width:100%;overflow:hidden;overflow-x:auto">
                        <table class="table2 mt-3">
                            <thead>
                                <tr>
                                    <td>Mois</td>
                                    <td>JAN</td>
                                    <td>FEV</td>
                                    <td>MAR</td>
                                    <td>AVR</td>
                                    <td>MA</td>
                                    <td>JUN</td>
                                    <td>JUI</td>
                                    <td>AOU</td>
                                    <td>SEP</td>
                                    <td>OCT</td>
                                    <td>NOV</td>
                                    <td>DEC</td>
                                    <td>TOTAL</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($cmds as $i => $cmd): ?>
                                    <?php $total = 0; ?>
                                    <tr>
                                        <td>(%)</td>
                                        <?php foreach($cmd as $c): ?>
                                            <?php $total += $c; ?>
                                            <td class="text-center"><?= $c ?></td>
                                        <?php endforeach; ?>
                                        <td><?= $total ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php endif; ?>

        </div>
    </div>

<?php
    $main = ob_get_clean();

    ob_start();
?>
    <script>
        let cmd_recue = new Array()
        '<?php foreach($cmd_recue as $j): ?>'
            cmd_recue.push(parseFloat('<?= $j ?>'))
        '<?php endforeach; ?>'

        let cmd_livree = new Array()
        '<?php foreach($cmd_livree as $j): ?>'
            cmd_livree.push(parseFloat('<?= $j ?>'))
        '<?php endforeach; ?>'

        let cmd_en_attente = new Array()
        '<?php foreach($cmd_en_attente as $j): ?>'
            cmd_en_attente.push(parseFloat('<?= $j ?>'))
        '<?php endforeach; ?>'

        let cmd_non_livree = new Array()
        '<?php foreach($cmd_non_livree as $j): ?>'
            cmd_non_livree.push(parseFloat('<?= $j ?>'))
        '<?php endforeach; ?>'

        let myChart1 = new ApexCharts(document.querySelector('#chart1'), getOptions({title: 'Entrée', color: '#304c64', data: cmd_recue}))
        myChart1.render();

        let myChart2 = new ApexCharts(document.querySelector('#chart2'), getOptions({title: 'Sortie', color: '#5084ac', data: cmd_livree}))
        myChart2.render();

        let myChart3 = new ApexCharts(document.querySelector('#chart3'), getOptions({title: 'Commandes reçues', color: '#f4bc64', data: cmd_recue}))
        myChart3.render();

        let myChart4 = new ApexCharts(document.querySelector('#chart4'), getOptions({title: 'Commandes livrées', color: '#54a464', data: cmd_livree}))
        myChart4.render();

        let myChart5 = new ApexCharts(document.querySelector('#chart5'), getOptions({title: 'Commandes en attente', color: '#a0c0e0', data: cmd_en_attente}))
        myChart5.render();

        let myChart6 = new ApexCharts(document.querySelector('#chart6'), getOptions({title: 'Echec', color: '#cc6c6c', data: cmd_non_livree}))
        myChart6.render();

        // Ajax => (JSON)
            // chart.updateSeries([{
            //     name: 'Sales',
            //     data: response.data
            // }])
    </script>
<?php
    $script = ob_get_clean();

require 'base.php';
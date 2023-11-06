<?php
// name: planification_commande_recue
// route: planification-commande-recue

use src\Repository\CommandeRepository;
use src\Router\Router;
use src\Services\CalendarService;

include '../vendor/autoload.php';

include '../autoload.php';

$router = new Router;

$router->adminIsConnected();

$router->request();

$commandeRepository = new CommandeRepository;

function compare($y, $m, $d, $dt)
{
    return !is_null($d) && strtotime($y . '-' . $m . '-' . $d) == strtotime($dt) ? true : false;
}

$year = date('Y');
$month = date('m');

if(isset($_POST['y'])) {
    $year = $_POST['y'];
}

if(isset($_POST['m'])) {
    $month = $_POST['m'];
}

$commandes_recues = $commandeRepository->totalCommandesRecuesPlanification($year, $month);
$commandes_livrees = $commandeRepository->totalCommandesLivrees($year, $month);

$calendar = new CalendarService($year, $month);
$weeks = $calendar->getWeeks();

$alink = 1;
$clink = 1;

ob_start();
?>
    <link rel="stylesheet" href="./assets/css/style-calendrier-planification.css">
    <style>
    </style>
<?php
    $style = ob_get_clean();
    
    ob_start();

    include 'layouts/calendrier-planification/_commande-recue-modal.php';

    include 'layouts/_calendrier-de-planification.php';

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

        const setStateCommand = function (e, opt) {
            window.event.preventDefault()
            const formData = new FormData(e)
            formData.append('async', '1')
            fetch('async.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(response => {
                if(response.success) {
                    if(opt == 'accept') {
                        e.parentElement.remove()
                    } else if(opt == 'cancel') {
                        const elt = Z('#card-' + e.getAttribute('data-uniq-s'))
                        if(elt) {
                            elt.remove()
                        }
                    }
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
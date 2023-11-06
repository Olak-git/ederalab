<?php

use src\Repository\CommandeRepository;
use src\Router\Router;

require_once '../autoload.php';

$router = new Router;

$router->dentisteIsConnected();

$user = $router->getDentiste();

$commandesOfToday = []; //$user->findOrdersCommandToday();
$commandesOfLongTime = (new CommandeRepository)->findAll(); //$user->findOrdersCommandLongTime();

ob_start();
?>
    <style>
        .input-group {
            background-color: #f8f9fa;
            border-radius: 1rem;
            padding: .5rem;
            /* width: 40vw; */
            color: #fff;
            border: 1px solid silver;
        }
        .input-group input {
            background-color:  #f8f9fa;
            width:calc(100% - 36px);
            border: none;
            padding: 5px;
            color: gray;
            font-size: 14px;
        }
        .input-group input:focus {
            box-shadow: none !important;
            background:  #f8f9fa;
        }
        .input-group .append-element {
            display: inline-block;
            width:34px;
            /* padding-right: 1rem; */
            border: 0;
            background-color: transparent;
            /* border:1px solid; */
        }
        .archive {
            display: flex;
            /* flex-wrap: wrap; */
            justify-content: start;
            align-items: center;
            min-height: 65px;
            /* padding:1rem; */
            margin-bottom: 1rem;
            cursor: pointer;
        }
        .archive .title {
            font-weight: 700;
        }
        .shadow-s {
            box-shadow: 0 0 .25rem rgba(0,0,0,.075)!important;
        }
    </style>
<?php
$style = ob_get_clean();

ob_start();

    include 'layouts/_modal-commande.php';
?>

    <div class="bg-white pt-3" style="position:sticky;top:60px;z-index:1;">

        <div class="d-flex mb-2">
            <div class="px-4 ml-auto text-right" style="width:calc(100% - var(--logo-width));">
                <a href="#" class="back-button btn-link bg-ederalab d-inline-block text-center p-2 font-weight-bold border" style="width:80px;border-radius:.5rem;text-decoration:none;">Retour</a>
            </div>
        </div>

        <div class="d-flex px-4">
            <div class="ml-auto" style="width:calc(100% - var(--logo-width));">
                <form class="ml-auto">
                    <div class="input-group d-flex justify-content-between align-items-center">
                        <input type="search" placeholder="Recherche" class="form-control form-control-sm px-2" />
                        <button class="append-element text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- <div class="d-flex justify-content-center mb-3 ">
        <div class="col-11 d-flex">
            <a href="" class="ml-auto text-dark" style=""><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
            </svg></a>
        </div>
    </div> -->


    <div class="d-flex justify-content-center" style="padding-bottom:20rem;">
        <div class="col-12 mb-5">
            <h5 class="p-3">Dernièrement</h5>
            <?php foreach($commandesOfLongTime as $cmd): ?>
                <div class="archive border shadow-s d-flex justify-content-between align-items-center bg-white p-1 shadow-s" style="">
                    <a href="#" 
                        type="button" 
                        data-toggle="modal" 
                        data-target="#commandeModal" 
                        data-command-slug="<?= $cmd->getSlug(); ?>"  
                        class="show-modal-commande col-11 text-decoration-none text-dark">
                        <div class="d-flex flex-wrap w-100">
                            <div class="col-12 col-sm-6 col-md-8"><?= $cmd->getUsernamePatient(); ?></div>
                            <div class="col-12 col-sm-6 col-md-4"><?= (new \DateTime($cmd->getDateEnvoie()))->format('d/m/Y'); ?></div>
                        </div>
                    </a>
                    <div class="col text-right px-">
                        <form method="post">
                            <button class="btn" style="padding:0;background:transparent;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="26" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                </svg>
                            </button>
                            <input type="hidden" name="commande" value="<?= $cmd->getSlug(); ?>">
                            <input type="hidden" name="csrf" value="<?= password_hash('del-command', 1); ?>">
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
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
                formData.append('show-command', this.getAttribute('data-command-slug'))
                fetchText('async.php', formData)
            }
        });
    </script>
<?php
$script = ob_get_clean();

require 'base.php';
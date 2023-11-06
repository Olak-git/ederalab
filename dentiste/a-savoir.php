<?php

use src\Repository\CommandeRepository;
use src\Router\Router;

require_once '../autoload.php';

$router = new Router;

$router->dentisteIsConnected();

$header_link = 4;

ob_start();
?>
    <style>

    </style>
<?php
$style = ob_get_clean();

ob_start();

?>

    <!-- <div class="d-flex justify-content-center" style="padding-bottom:20rem;"> -->
        <div class="col-12 mb-5">
            <h3 class="p-3">Mes commandes</h3>
            <div class="d-flex">
                <div class="col-12 col-md-8 order-1 order-md-0">
                    <div class="card h-100 border-0">
                        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Culpa quod nam sapiente alias exercitationem excepturi, eaque dolorum ex consequuntur itaque molestiae repudiandae ad, repellat minima eligendi reprehenderit est, dolorem consequatur.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4 order-0 order-md-1">
                    <div class="card h-100 border-0">
                        <div class="bg-light" style="min-height:200px;">
                            <img src="" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-5">
            <h3 class="p-3">Suivi des commandes</h3>
            <div class="d-flex">
                <div class="col-12">
                    <div class="card h-100 border-0">
                        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Culpa quod nam sapiente alias exercitationem excepturi, eaque dolorum ex consequuntur itaque molestiae repudiandae ad, repellat minima eligendi reprehenderit est, dolorem consequatur.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-5">
            <h3 class="p-3">Informations sur les commandes</h3>
            <div class="d-flex">
                <div class="col-12">
                    <div class="card h-100 border-0">
                        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Culpa quod nam sapiente alias exercitationem excepturi, eaque dolorum ex consequuntur itaque molestiae repudiandae ad, repellat minima eligendi reprehenderit est, dolorem consequatur.</p>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. A amet accusantium repudiandae, maxime reprehenderit, atque quibusdam velit fugiat, mollitia sequi animi maiores! Dignissimos, sint recusandae! Repellat exercitationem quas molestias fugiat.</p>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur necessitatibus itaque exercitationem non architecto repellat! Vero rem aut eveniet reprehenderit recusandae. Fugit, dignissimos dolore dicta tempore voluptas magnam ab perferendis.</p>
                        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fugit ducimus officiis aut eum voluptatem, doloremque similique tempora ipsum, maxime non nisi expedita laborum dignissimos cumque neque dolorem aspernatur tempore corrupti?</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-5">
            <h3 class="p-3">Messagerie</h3>
            <div class="d-flex">
                <div class="col-12">
                    <div class="card h-100 border-0">
                        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Culpa quod nam sapiente alias exercitationem excepturi, eaque dolorum ex consequuntur itaque molestiae repudiandae ad, repellat minima eligendi reprehenderit est, dolorem consequatur.</p>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Facere minima suscipit ipsam enim. Numquam, nihil et officia eos harum molestias suscipit. Excepturi illum dignissimos sequi qui, natus ea? Non, asperiores.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-5">
            <h3 class="p-3">Planification</h3>
            <div class="d-flex">
                <div class="col-12">
                    <div class="card h-100 border-0">
                        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Culpa quod nam sapiente alias exercitationem excepturi, eaque dolorum ex consequuntur itaque molestiae repudiandae ad, repellat minima eligendi reprehenderit est, dolorem consequatur.</p>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Non magni totam aperiam fuga a? Voluptate, voluptatem nostrum dolorem possimus reprehenderit excepturi blanditiis tempora, modi consequuntur voluptatum, nulla deserunt fugiat illum?.</p>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perferendis modi quos recusandae veritatis eaque cum ex id doloribus, nobis sed ipsum ad itaque nulla nesciunt iusto porro quaerat est consequuntur?</p>
                    </div>
                </div>
            </div>
        </div>
    <!-- </div> -->

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
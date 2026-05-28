<?php
    $db = $router->getDb();
?>
<div class="accordion" id="accordionExample">

    <?php foreach ($commandes as $key => $commande): 
        
        $dentiste = $db->findOneBy("dentiste", ["id" => $commande["dentiste"]]);
    ?>

        <div class="card border-0">

            <div class="card-header" id="heading<?= $commande["id"]; ?>">
                <h5 class="mb-0">
                    <button class="btn btn-block btn-link word-break" type="button" data-toggle="collapse" data-target="#collapse<?= $commande["id"]; ?>" aria-expanded="false" aria-controls="collapse<?= $commande["id"]; ?>">CO-<?= strtoupper($commande["slug"]); ?></button>
                </h5>
            </div>

            <div id="collapse<?= $commande["id"]; ?>" class="collapse" aria-labelledby="heading<?= $commande["id"]; ?>" data-parent="#accordionExample">

                <!-- Begin Main -->
                <div class="w-100 border-bottom pt-3">

                    <div class="d-flex mb-3">
                        <div class="col-6 font-weight-bold pl-0">Dentiste :</div>
                        <div class="col-6" id="" style="font-weight:300;"><?= $commande["nom_dentiste"]; ?></div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="col-6 font-weight-bold pl-0">Cabinet :</div>
                        <div class="col-6" id="" style="font-weight:300;"><?= $commande["cabinet"]; ?></div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="col-6 font-weight-bold pl-0">Adresse :</div>
                        <div class="col-6" id="" style="font-weight:300;"><?= $dentiste["adresse"]; ?></div>
                    </div>

                    <div class="mb-3"><span class="font-weight-bold mr-2">Date de réception prévue de la commande :</span><span><?= (new \DateTime($commande["date_reception_prevue"]))->format('d/m/Y'); ?></div>
                
                </div>
                <!-- Begin Main -->

            </div>

        </div>

    <?php endforeach; ?>

</div>
<?php

use src\Repository\ProtheseRepository;
use src\Router\Router;

require_once '../autoload.php';

$router = new Router;

$router->dentisteIsConnected();

$router->request();

$protheses = (new ProtheseRepository)->findAll();

$header_link = 2;

ob_start();
?>
    <style>
        input.form-control.form-style {
            border-left:0;
            border-top:0;
            border-right:0;
            border-radius: 0;
        }
        input.form-control:focus {
            border-color: var(--ederalab);
        }
        textarea {
            resize: none;
        }
        span.date-style, input.date-style {
            border: 0;
            background-color: #f0ecec;
            padding:2.5rem 1rem;
            text-align: center;
            width: 215px;
        }
        input.date-style:focus {
            background-color: #f0ecec;
        }
        .rounded-pill {
            border-radius: 1rem;
        }
        .overflow-hidden {
            overflow: hidden;
        }
        .shadow-md {
            box-shadow: 0 0rem .5rem rgba(0,0,0,.45)!important;
        }

        .table thead {
            background: #f0ecec;
            color: #6c757d;
            font-weight: 300;
        }
        .table thead td, .table tbody tr:first-child td {
            border: 0 !important;
        }
        .table thead tr td:first-child {
            border-top-left-radius: 1rem;
            border-bottom-left-radius: 1rem;
        }
        .table thead tr td:last-child {
            border-top-right-radius: 1rem;
            border-bottom-right-radius: 1rem;
        }
        .container-choix-protheses {
            overflow: hidden;
            overflow-x: auto;
            /* min-width: 578.33px; */
        }
        .kv-file-content {
            width: calc(100% - 40px);
            height: calc(100% - 40px);
            border: 1px dotted #000;
        }
        .file-preview-image {
            width:auto;
            max-width: 100%;
            height: auto;
            max-height: 100%;
        }
        .fileinput-remove {
            position: absolute;top: 1px;right: 1px;line-height: 10px;
            padding: 0 !important;
            background: transparent !important;
        }

        /* #fff url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E") no-repeat right .75rem center */
        
    </style>
<?php
$style = ob_get_clean();

ob_start();
?>
    <div class="modal fade" id="new-prothese" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog<?= ''//modal-lg modal-dialog-centered ?>" role="document">
            <div class="modal-content border-0">
                <div class="modal-header text-white" style="background:#000;">
                    <!-- <h5 class="modal-title" id="exampleModalLabel"></h5> -->
                    <button type="button" class="close shadow-none" data-dismiss="modal" aria-label="Close" style="">
                        <span aria-hidden="true" style="color:#e00514;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                            </svg>
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-" id="nom">
                        <div class="d-flex flex-wrap align-items-center">
                            <div class="col col-auto p-0 mr-2"><label for="modal_nom_patient" class="mb-0">Nom patient</label></div>
                            <div class="col px-0"><input type="text" id="modal_nom_patient" class="form-control rounded-pill shadow-none" placeholder="Username" readonly></div>
                        </div>
                    </div>
                    <div class="form-group mb-" id="nom">
                        <div class="d-flex flex-wrap align-items-center">
                            <div class="col col-auto p-0"><label for="modal_cas_prothese" class="mb-0">Cas n°</label></div>
                            <div class="col col-auto pr-0" style="min-width:100px;">
                                <select class="custom-select shadow-none" id="modal_cas_prothese" required>
                                    <?php foreach($protheses as $prot): ?>
                                        <option data-details="<?= $prot->getDetail(); ?>" value="<?= $prot->getId(); ?>"><?= $prot->getNumero(); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-" id="nom">
                        <div class="d-flex flex-wrap">
                            <div class="col col-auto p-0 mr-2"><label for="modal_details_cmd" class="mb-0">Détails de la commande</label></div>
                            <div class="col px-0">
                                <textarea rows="3" class="form-control shadow-none line-height-normal" id="modal_details_cmd" readonly><?= count($protheses) !== 0 ? $protheses[0]->getDetail() : '' ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-" id="nom">
                        <div class="d-flex flex-wrap">
                            <div class="col col-auto p-0 mr-2"><label for="modal_modif_dmd" class="mb-0">Modification demandée</label></div>
                            <div class="col px-0">
                                <textarea rows="3" class="form-control shadow-none line-height-normal" id="modal_modif_dmd"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn text-white shadow-none btn-add-ch-prot" style="background:var(--ederalab);">ajouter</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mb-3 ">
        <div class="col-11 d-flex">
            <div class="btn-group dropleft ml-auto">
                <a href="" type="button" class="text-dark dropdown-toggl" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style=""><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                </svg></a>

                <div class="dropdown-menu border-0 bg-ederalab">
                    <a href="commandes.php" class="btn text-white">Liste des commandes effectuées</a>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <div class="col-10 shadow-md p-5" style="border-radius:2rem;">
            <h3 class="mb-4">Formulaire de commande</h3>
            <form method="post" enctype="multipart/form-data" class="w-100" name="cmd">
                <div class="form-group mb-" id="nom_dentiste">
                    <div class="d-flex align-items-end">
                        <div class="col col-auto p-0"><label for="add_commande_nom_dentiste" class="mb-0">Nom & prénom du dentiste</label></div>
                        <div class="col"><input type="text" name="add_commande[nom_dentiste]" class="form-control form-style rounded-pill shadow-none" id="add_commande_nom_dentiste" placeholder="Username" value="<?= $router->getValPost(['add_commande', 'nom_dentiste']) == '' ? $router->getDentiste()->getUsername() : $router->getValPost(['add_commande', 'nom_dentiste']); ?>"></div>
                    </div>
                    <?= $router->errorHTML2('nom_dentiste'); ?>
                </div>

                <div class="form-group mb-" id="cabinet">
                    <div class="d-flex align-items-end">
                        <div class="col col-auto p-0"><label for="add_commande_cabinet" class="mb-0">Cabinet</label></div>
                        <div class="col"><input type="text" name="add_commande[cabinet]" class="form-control form-style rounded-pill shadow-none" id="add_commande_cabinet" placeholder="Username" value="<?= $router->getValPost(['add_commande', 'cabinet']) == '' ? $router->getDentiste()->getCabinet() : $router->getValPost(['add_commande', 'cabinet']); ?>"></div>
                    </div>
                    <?= $router->errorHTML2('cabinet'); ?>
                </div>
                <div class="form-group mb-" id="nom_patient">
                    <div class="d-flex align-items-end">
                        <div class="col col-auto p-0"><label for="add_compte_nom_patient" class="mb-0">Nom du patient</label></div>
                        <div class="col">
                            <div class="d-flex align-items-end">
                                <div class="col-6"><input type="text" name="add_commande[nom_patient]" class="form-control form-style rounded-pill shadow-none" id="add_commande_nom_patient" placeholder="Nom" value="<?= $router->getValPost(['add_commande', 'nom_patient']); ?>"></div>
                                <div class="col-6"><input type="text" name="add_commande[prenom_patient]" class="form-control form-style rounded-pill shadow-none" id="" placeholder="Prénom" value="<?= $router->getValPost(['add_commande', 'prenom_patient']); ?>"></div>
                            </div>
                        </div>
                    </div>
                    <?= $router->errorHTML2('nom_patient'); ?>
                </div>
                <div class="form-group mb-" id="description_specifiq">
                    <div class="d-flex align-items-center">
                        <div class="col col-auto p-0"><label for="add_commande_description_specifiq" class="mb-0">Description spécifique</label></div>
                        <div class="col">
                            <div class="rounded-pill overflow-hidden p-2" style="border:1px solid;">
                                <textarea rows="4" name="add_commande[description_specifiq]" id="add_commande_description_specifiq" class="form-control border-0 shadow-none line-height-normal"><?= $router->getValPost(['add_commande', 'description_specifiq']); ?></textarea>
                                <span class="d-block text-right small"><span class="length"></span>/<span class="max-length">100</span></span>
                            </div>
                        </div>
                    </div>
                    <?= $router->errorHTML2('description_specifiq'); ?>
                </div>
                <div class="form-group mb-" id="description_libre">
                    <div class="d-flex align-items-center">
                        <div class="col col-auto p-0"><label for="add_commande_description_libre" class="mb-0">Description libre</label></div>
                        <div class="col">
                            <div class="rounded-pill overflow-hidden p-2" style="border:1px solid;">
                                <textarea rows="4" name="add_commande[description_libre]" id="add_commande_description_libre" class="form-control border-0 shadow-none line-height-normal"><?= $router->getValPost(['add_commande', 'description_libre']); ?></textarea>
                                <span class="d-block text-right small"><span class="length"></span>/<span class="max-length">100</span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-" id="choix_prothese">
                    <label for="">Choix prothèses</label>
                    <div class="container-choix-protheses">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td>Nom du patient</td>
                                    <td>Cas n°</td>
                                    <td>Détails de la commande</td>
                                    <td>Modif. demandée</td>
                                    <td>
                                        <div class="d-flex justify-content-around align-items-center">
                                            <a href="" type="button" class="btn-remove-line" style="color:#ff2222;"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-dash-circle-fill" viewBox="0 0 16 16">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z"/>
                                                </svg></a>
                                            <a href=""  type="button" data-toggle="modal" data-target="#new-prothese" style="color:#000;"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                                                </svg></a>
                                        </div>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- <tr>
                                    <td>Nom du patient</td>
                                    <td>Cas n°</td>
                                    <td>Détails de la commande</td>
                                    <td>Modif. demandée</td>
                                    <td>act.</td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                    <?= $router->errorHTML2('prothese'); ?>
                </div>

                <div class="form-group">
                    <label for="">Ajouter une pièce jointe</label>
                    <div class="d-flex flex-wrap">
                        <span class="d-inline-block position-relative">
                            <a href="" type="button" class="flex-center" style="width:80px;height:80px;color:#000;border:1px solid #000;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                                </svg>
                            </a>
                            <input type="file" name="piece_jointe" id="validatedFile" accept="*" style="width:80px;height:80px;position:absolute;top:0;left:0;opacity:0;cursor:pointer;">
                        </span>

                        <span class="mx-3"></span>

                        <div class="flex-center border position-relative" id="prev" style="width:250px;height:250px;">
                            <button type="button" class="btn btn-close fileinput-remove shadow-none" aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap">
                    <div class="col-12 col-md-6 col-lg-4 mb-2 px-0">
                        <div class="form-group">
                            <label for="">Date d'envoi de la commande</label>
                            <span class="form-control date-style flex-center rounded-pill text-muted"><?= (new DateTime('now'))->format('d/m/Y'); ?></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 mb-2 px-0">
                        <div class="form-group">
                            <label for="">Date de réception de la commande</label>
                            <input type="text" pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}" placeholder="<?= '--/--/' . date('Y'); ?>" name="add_commande[date_reception]" id="" value="<?= $router->getValPost(['add_commande', 'date_reception']); ?>" class="form-control flex-center shadow-none date-style rounded-pill">
                            <?= $router->errorHTML2('date_reception'); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group text-right">
                    <button class="btn shadow-none py-3 px-4 rounded-pill bg-ederalab">Valider ma commande</button>
                </div>

                <input type="hidden" name="csrf" value="<?= password_hash('add-command', 1); ?>">

            </form>
        </div>
    </div>

<?php
$main = ob_get_clean();

ob_start();
?>
    <script>
        function createThumbnail(file) {
            let reader = new FileReader();
            reader.onload = function() {
                let prev = document.querySelector('#prev');
                let imgElement = document.createElement('img');
                    imgElement.classList.add('file-preview-image')
                    imgElement.setAttribute('title', '')
                    imgElement.setAttribute('alt', '')
                    if(file.type === 'application/zip' || file.type === 'application/rar') {
                        imgElement.src = '../assets/images/svg/zip.svg';
                        imgElement.style.width = '150px';
                        imgElement.style.height = '150px';
                    } else if(file.type === 'application/pdf') {
                        imgElement.src = '../assets/images/svg/pdf.svg';
                        imgElement.style.width = '150px';
                        imgElement.style.height = '150px';
                    } else {
                        imgElement.src = this.result;
                    }

                let kv_file_content = document.createElement('div');
                    kv_file_content.classList.add('kv-file-content', 'flex-center');
                // let file_footer_caption = document.createElement('div');
                //     file_footer_caption.classList.add('file-footer-caption')
                //     file_footer_caption.setAttribute('title', file.name)
                // let file_caption_info = document.createElement('div');
                //     file_caption_info.classList.add('file-caption-info')
                //     file_caption_info.textContent = file.name
                // let file_size_info = document.createElement('div');
                //     file_size_info.classList.add('file-size-info')
                //     file_size_info.innerHTML = '<samp>(' + format_size(file.size) + ')</samp>';

                // file_footer_caption.append(file_caption_info)
                // file_footer_caption.append(file_size_info)
                kv_file_content.append(imgElement)
                // file_preview_frame.append(kv_file_content)

                prev.appendChild(kv_file_content); // prev.appendChild(file_preview_frame);
            };
            reader.readAsDataURL(file);
        }
        var allowedTypes = ['png', 'jpg', 'jpeg', 'zip', 'rar', 'pdf'],
            fileInput = document.querySelector('#validatedFile');
            
        fileInput.onchange = function() {
            if(document.querySelector('.kv-file-content')) {
                document.querySelector('.kv-file-content').remove()
            }
            // document.querySelector('#prev').innerHTML = '';
            var files = this.files,
            filesLen = files.length,
            imgType;
            // document.querySelector('.custom-file-label').textContent = filesLen + ' fichier' + (filesLen > 1 ? 's' : '') + ' sélectionné' + (filesLen > 1 ? 's' : '');
            for (var i = 0 ; i < filesLen ; i++) {
                imgType = files[i].name.split('.');
                imgType = imgType[imgType.length - 1].toLowerCase();
                if(allowedTypes.indexOf(imgType) != -1) {
                    createThumbnail(files[i]);
                }
            }
        };
        document.querySelector('.btn-close.fileinput-remove').onclick = function (event) {
            event.preventDefault()
            fileInput.value = ''
            if(document.querySelector('.kv-file-content')) {
                document.querySelector('.kv-file-content').remove()
            }
        }

        var index = 0;
        const tr = function (data) {
            index++;
            const d = '<div id="hidden' + index + '">'
                        +'<input type="hidden" name="add_commande[cas_prothese][]" value="' + data.cas + '">'
                        +'<input type="hidden" name="add_commande[modification_dmd][]" value="' + data.modif + '">'
                    +'</div>'
            document.forms.cmd.insertAdjacentHTML('beforeend', d)
            document.querySelector('.close').click()
            return '<tr id="line' + index + '">'
                        +'<td>' + data.nom + '</td>'
                        +'<td>' + data.cas + '</td>'
                        +'<td>' + data.details + '</td>'
                        +'<td>' + data.modif + '</td>'
                        +'<td></td>' // +'<td><a>suppr.</a></td>'
                    +'</tr>'
        }
        document.querySelector('.btn-add-ch-prot').onclick = function () {
            const tbody = document.querySelector('.table tbody')
            tbody.insertAdjacentHTML('beforeend', tr({
                nom: document.querySelector('#modal_nom_patient').value,
                cas: document.querySelector('#modal_cas_prothese').value,
                details: document.querySelector('#modal_details_cmd').value,
                modif: document.querySelector('#modal_modif_dmd').value
            }))
        }
        document.querySelector('.btn-remove-line').onclick = function(event) {
            event.preventDefault()
            const tr = document.querySelector('.table tbody').lastElementChild
            if(tr) {
                document.querySelector('#hidden' + tr.id.replace('line', '')).remove()
                tr.remove()
            }
        }

        Array.from(document.querySelectorAll('textarea')).map(t => {
            t.onkeyup = function () {
                this.nextElementSibling.firstElementChild.textContent = this.value.length
                // console.log(this.nextElementSibling.firstElementChild)
                // console.log(this.nextElementSibling.lastElementChild)
            }
        })

        Z('#modal_cas_prothese').onchange = function () {
            Z('#modal_details_cmd').value = this.options[this.selectedIndex].getAttribute('data-details').replace(/<br>/g, '\n')
        }
    </script>
<?php
$script = ob_get_clean();

require 'base.php';
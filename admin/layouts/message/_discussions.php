
            <div class="form-filter">
                <form class="mb-0">
                    <div class="input-group d-flex justify-content-between align-items-center">
                        <input type="search" placeholder="Recherche" class="form-control form-control-sm px-2 filter-users" />
                        <button class="append-element text-muted d-flex justify-content-center align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
            <div class="" style="position:absolute;bottom:0;left:0;z-index:1;width:100%;height:20px;background:#f4f4f4;"></div>
            <div class="conversation-liste border-top-0 border-bottom-0 text-white" data-label="proprietaire" style="">
                <?php
                    foreach($receveurs as $receveur) {
                        include '_user.php';
                    }
                ?>
            </div>
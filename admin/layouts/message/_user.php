
<a href="<?= "message.php?user={$user}&dest=".$receveur["slug"]; ?>" data-user-key="<?= $receveur["slug"]; ?>" class="<?= (isset($dest) && null !== $dest && $dest["slug"] === $receveur["slug"]) || (isset($user_active) && $user_active == $receveur["slug"]) ? 'active ' : ''; ?>conversation-item border border-0 border-top-0 border-left-0 border-right-0 btn-block" data-filter="<?= strtolower($router->getUsername($receveur["nom"], $receveur["prenom"])); ?>">
    <span class="z-image">
        <img src="<?= $router->getAvatar($receveur["image"]??null); ?>" alt="" class="mw-100 mh-100">
    </span>
    <div class="text-break" style="width:calc(100% - 40px - 0.5rem)">
        <h6 class="m-0"><?= $router->getUsername($receveur["nom"], $receveur["prenom"]); ?></h6>
        <p class="small mt-1 mb-0" style="line-height:13px;"><?= $router->getLastMessage($receveur["id"], $user); ?></p>
    </div>
</a>
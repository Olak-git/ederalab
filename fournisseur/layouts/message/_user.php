
<a href="<?= '#'//'message.php?user=' . $user . '&amp;dest=' . $receveur["slug"]; ?>" data-user-key="<?= $receveur["slug"]; ?>" class="<?= (isset($dest) && null !== $dest && $dest["slug"] === $receveur["slug"]) || (isset($user_active) && $user_active == $receveur["slug"]) ? 'active ' : ''; ?>conversation-item border border-0 border-top-0 border-left-0 border-right-0 btn-block">
    <span class="z-image">
        <img src="<?= ''; ?>">
    </span>
    <div class="text-break" style="width:calc(100% - 40px - 0.5rem)">
        <h6 class="m-0"><?= $router->getUsername($receveur["nom"], $receveur["prenom"]); ?></h6>
        <p class="small mt-1 mb-0" style="line-height:13px;"><?= 'jean'//$router->getLastMessage($receveur->getId(), $user); ?><?= ''//'Mon temps précieux'//$conv->getLastMessage() && $conv->getLastMessage()->getMessage() ? ((strtolower($conv->getLastMessage()->getMessage()->getLabel()) === 'locataire' ? '<span class="font-weight-bold">(moi) </span>' : '') . ($conv->getLastMessage()->getMessage()->getDoc() ? '<i>file</i>' : $router->formatText($conv->getLastMessage()->getMessage()->getTexte(), 50))) : ''; ?></p>
    </div>
</a>
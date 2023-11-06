
                    <a href="<?= '#'//'message.php?user=' . $user . '&amp;dest=' . $receveur->getSlug(); ?>" data-user-key="<?= $receveur->getSlug(); ?>" class="<?= (isset($dest) && null !== $dest && $dest->getSlug() === $receveur->getSlug()) || (isset($user_active) && $user_active == $receveur->getSlug()) ? 'active ' : ''; ?>conversation-item border border-0 border-top-0 border-left-0 border-right-0 btn-block">
                        <span class="z-image">
                            <img src="<?= ''//$router->getSrc($conv->getLocataire(), 'locataire'); ?>">
                        </span>
                        <div class="text-break" style="width:calc(100% - 40px - 0.5rem)">
                            <h6 class="m-0"><?= $receveur->getUsername(); ?></h6>
                            <p class="small mt-1 mb-0" style="line-height:13px;"><?= 'jean'//$router->getLastMessage($receveur->getId(), $user); ?><?= ''//'Mon temps précieux'//$conv->getLastMessage() && $conv->getLastMessage()->getMessage() ? ((strtolower($conv->getLastMessage()->getMessage()->getLabel()) === 'locataire' ? '<span class="font-weight-bold">(moi) </span>' : '') . ($conv->getLastMessage()->getMessage()->getDoc() ? '<i>file</i>' : $router->formatText($conv->getLastMessage()->getMessage()->getTexte(), 50))) : ''; ?></p>
                        </div>
                    </a>
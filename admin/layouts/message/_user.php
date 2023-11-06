
                    <a href="<?= $router->getRoutes()->path('message', ['user' => $user, 'dest' => $receveur->getSlug()]); ?>" data-user-key="<?= $receveur->getSlug(); ?>" class="<?= (isset($dest) && null !== $dest && $dest->getSlug() === $receveur->getSlug()) || (isset($user_active) && $user_active == $receveur->getSlug()) ? 'active ' : ''; ?>conversation-item border border-0 border-top-0 border-left-0 border-right-0 btn-block" data-filter="<?= strtolower($receveur->getUsername()); ?>">
                        <span class="z-image">
                            <img src="<?= $router->getAvatar($receveur->getImage()); ?>" alt="" class="mw-100 mh-100">
                        </span>
                        <div class="text-break" style="width:calc(100% - 40px - 0.5rem)">
                            <h6 class="m-0"><?= $receveur->getUsername(); ?></h6>
                            <p class="small mt-1 mb-0" style="line-height:13px;"><?= $router->getLastMessage($receveur->getId(), $user); ?><?= ''//'Mon temps précieux'//$conv->getLastMessage() && $conv->getLastMessage()->getMessage() ? ((strtolower($conv->getLastMessage()->getMessage()->getLabel()) === 'locataire' ? '<span class="font-weight-bold">(moi) </span>' : '') . ($conv->getLastMessage()->getMessage()->getDoc() ? '<i>file</i>' : $router->formatText($conv->getLastMessage()->getMessage()->getTexte(), 50))) : ''; ?></p>
                        </div>
                    </a>

                        <div class="w-100 d-flex justify-content-end align-items-center position-relative mb-3 response-right _chat message<?= $message->getId(); ?>" data-message-key="<?= $message->getId(); ?>">
                            <img src="../assets/images/site/triangle1.png" alt="" width="20" height="20" style="transform:rotateY(180deg);position:absolute;top:0;right:-9px;">
                            <div class="message-right ml-auto">
                                <?php include '_message.php'; ?>
                                <div class="text-right text-secondary hours font-italic" style="color:#ede !important;"><small><?= (new \DateTime($message->getDat()))->format('g:i a'); ?></small></small></div>
                            </div>
                        </div>
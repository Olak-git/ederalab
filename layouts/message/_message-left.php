                        
                        <div class="w-100 d-flex justify-content-start align-items-center position-relative mb-3 response-left _chat message<?= $message->getId(); ?>" data-message-key="<?= $message->getId(); ?>">
                            <img src="../assets/images/site/triangle2.png" alt="" width="20" height="20" style="position:absolute;top:0;left:-9px;">
                            <div class="message-left mr-auto">
                                <?php include '_message.php'; ?>
                                <div class="text-left text-secondary hours font-italic" style=""><small><?= (new \DateTime($message->getDat()))->format('g:i a'); ?></small></small></div>
                            </div>
                        </div>
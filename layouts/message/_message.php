
                                                <?php if($message->getFichier()): ?>
                                                    <?php if(strtolower($message->getTypeFichier()) === 'image'): ?>
                                                        <img src="<?= '../assets/message-file/' . $message->getFichier(); ?>" alt="" style="max-height:50vh;height:auto;max-width:100%;width:auto;" />
                                                        
                                                    <?php elseif(strtolower($message->getTypeFichier()) === 'doc'): ?>
                                                        <a href="<?= '../assets/message-file/' . $message->getFichier(); ?>" download="<?= $message->getNomFichier(); ?>" data-id="<?= '_' . $message->getId(); ?>" data-file="<?= '../assets/message-file/' . $message->getFichier(); ?>" data-file-name="<?= $message->getNomFichier(); ?>" <?php //onclick="downloadFile2(this);" ?> class="d-flex justify-content-start align-items-center text-light" style="font-style:italic;">
                                                            <span class="mr-1" style="text-decoration:underline !important;"><?= $message->getNomFichier(); ?><?= ''//'(' . $router->getSize($path) . ')'; ?></span>
                                                            <img src="<?= '../assets/message-file/loading.gif'; ?>" class="d-none <?= 'loading_' . $message->getId(); ?>" width="15" height="15">
                                                            <i class="<?= 'icon_' . $message->getId(); ?>" style="display:inline-block;background-image: url(&quot;data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz48c3ZnIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgeG1sbnM6Y2M9Imh0dHA6Ly9jcmVhdGl2ZWNvbW1vbnMub3JnL25zIyIgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIiB4bWxuczpzdmc9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZlcnNpb249IjEuMSIgd2lkdGg9IjE2IiBoZWlnaHQ9IjE2IiB2aWV3Qm94PSIwIDAgMTYgMTYiIGlkPSJzdmcyIiB4bWw6c3BhY2U9InByZXNlcnZlIj48cGF0aCBkPSJNIDQsMCA0LDggMCw4IDgsMTYgMTYsOCAxMiw4IDEyLDAgNCwwIHoiIGZpbGw9IiNmZmZmZmYiIC8+PC9zdmc+&quot;); background-size: 12px; background-repeat: no-repeat; background-position: center center; width: 16px; height: 16px;filter:brightness(0.5);"></i>
                                                        </a>
                                                    <?php endif; ?>

                                                <?php else: ?>
                                                    <?= nl2br($message->getMessage()); ?>

                                                <?php endif; ?>
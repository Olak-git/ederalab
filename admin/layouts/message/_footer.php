
            <footer id="footer-bar" class="footer-bar-5 shadow-none bg-ederalab border-0 p-3" style="">
                <form method="post" action="" enctype="multipart/form-data" name="chat" onsubmit="sendMessage(this);" class="w-100 h-100">
                    <div class="form-group h-100 p-1 d-flex align-items-center">
                        <div class="w-100 d-flex flex-row justify-content-center align-items-center position-relative">

                            <span class="d-flex flex-column position-absolute hide-input-file">
                                <a href="#" type="button" id="input-file-doc" onclick="clickInputFileDoc();" class="rounded-xl border-0 bg-danger text-light mb-2 btn-uploader">
                                    <span class="media-icon micon-1 d-flex w-100 h-100 justify-content-center align-items-center" style="margin-top:0;opacity:1;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-file-earmark-fill" viewBox="0 0 16 16" style="margin-top:0;">
                                            <path d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm5.5 1.5v2a1 1 0 0 0 1 1h2l-3-3z"/>
                                        </svg>
                                    </span>
                                </a>
                                <a href="#" type="button" id="input-file-image" onclick="clickInputFileImage();" class="rounded-xl border-0 bg-primary text-light mb-2 btn-uploader">
                                    <span class="media-icon micon-1 d-flex w-100 h-100 justify-content-center align-items-center" style="margin-top:0;opacity:1;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="26" fill="currentColor" class="bi bi-image-fill" viewBox="0 0 16 16" style="margin-top:0;">
                                            <path d="M.002 3a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-12a2 2 0 0 1-2-2V3zm1 9v1a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V9.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12zm5-6.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0z"/>
                                        </svg>
                                    </span>
                                </a>
                            </span>
                            
                            <div class="w-100 <?= isset($dest) && null !== $dest ? 'd-flex' : 'd-none'; ?> border rounded-pill" style="overflow:hidden;background:#fff;">
                                <textarea name="chat[message]" placeholder="Message" class="form-control chat-form-footer px-3 py-1 shadow-none border-0" onfocus="hideInputFile();" onkeyup="initTg();" style=""></textarea>
                                <a href="#" class="append-element text-muted d-flex justify-content-center align-items-center mx-1 chat-form-footer text-secondary" onclick="toggleInputFile();"><svg viewBox="0 0 24 24" width="24" height="24" class=""><path fill="currentColor" d="M1.816 15.556v.002c0 1.502.584 2.912 1.646 3.972s2.472 1.647 3.974 1.647a5.58 5.58 0 0 0 3.972-1.645l9.547-9.548c.769-.768 1.147-1.767 1.058-2.817-.079-.968-.548-1.927-1.319-2.698-1.594-1.592-4.068-1.711-5.517-.262l-7.916 7.915c-.881.881-.792 2.25.214 3.261.959.958 2.423 1.053 3.263.215l5.511-5.512c.28-.28.267-.722.053-.936l-.244-.244c-.191-.191-.567-.349-.957.04l-5.506 5.506c-.18.18-.635.127-.976-.214-.098-.097-.576-.613-.213-.973l7.915-7.917c.818-.817 2.267-.699 3.23.262.5.501.802 1.1.849 1.685.051.573-.156 1.111-.589 1.543l-9.547 9.549a3.97 3.97 0 0 1-2.829 1.171 3.975 3.975 0 0 1-2.83-1.173 3.973 3.973 0 0 1-1.172-2.828c0-1.071.415-2.076 1.172-2.83l7.209-7.211c.157-.157.264-.579.028-.814L11.5 4.36a.572.572 0 0 0-.834.018l-7.205 7.207a5.577 5.577 0 0 0-1.645 3.971z"></path></svg></a>
                                <button class="btn btn-sm mx-1 chat-form-footer shadow-none" style="background:transparent;"><span data-testid="send" data-icon="send" class=""><svg viewBox="0 0 24 24" width="24" height="24" class=""><path fill="currentColor" d="M1.101 21.757 23.8 12.028 1.101 2.3l.011 7.912 13.623 1.816-13.623 1.817-.011 7.912z"></path></svg></span></button>
                            </div>
                            <input type="file" name="image" accept="image/*" class="d-none">
                            <input type="file" name="doc" accept=".doc, .docx, .pdf, .txt, .csv, .ppt, .pptx, .zip, .rar, .jpg, .gif, .png, .jpeg" class="d-none">
                            <input type="hidden" name="chat[exp]" value="<?= password_hash('admin', 1); ?>">
                            <input type="hidden" name="chat[dest]" value="<?= isset($dest) && null !== $dest ? $dest->getSlug() : ''; ?>">
                            <input type="hidden" name="chat[compte_dest]" value="<?= isset($user) && (strtolower($user) == 'dentiste' || strtolower($user) == 'transporteur') ? strtolower($user) : '' ?>">
                            <input type="hidden" name="csrf" value="<?= password_hash('chat', 1); ?>">
                            
                        </div>
                    </div>
                </form>
            </footer>
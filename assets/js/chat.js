// const S = function (i) {
//     return Array.from(document.querySelectorAll(i))
// }
// const Z = function (i) {
//     return document.querySelector(i);
// }
// const removeLoading = function () {
//     if(Z('#loading')) {
//         Z('#loading').remove();
//     }
// }

// const removeErrorHTML2 = function($id) {
//     if(Z('#' + $id)) {
//         Z('#' + $id).remove();
//     }
// }
// const errorHTML2 = function($id, $v) {
//     removeErrorHTML2($id)
//     const error = document.createElement('div')
//     error.classList.add('error', 'text-danger')
//     error.id = $id
//     error.innerHTML = '<span class="badge badge-danger text-uppercase">error</span> ' + $v;
//     return error;
// }

const syncPath = function () {
    // const e = document.forms.chat.elements['chat[who]']
    // if(e) {
    //     const v = e.value.toLowerCase();
    //     if(v == 'locataire') {
    //         return '../asset/chat/async.php';
    //     } else if(v == 'proprietaire') {
    //         return '../../asset/chat/async.php';
    //     }
    // }
    return 'async.php';
}

var timeSync = null;

var rec = true

var mustScroll = true

var optionsScrollT = {
    root: null,
    rootMargin: '0px',
    threshold: 1
}
const callbackScr = function (entries, observer) {
    entries.map(entry => {
        if(entry.isIntersecting) {
            mustScroll = true
            if(Z('.back-to-bottom')) Z('.back-to-bottom').classList.add('hide-button-back-to-bottom')
        } else {
            mustScroll = false
            if(Z('.back-to-bottom')) Z('.back-to-bottom').classList.remove('hide-button-back-to-bottom')
        }
    })
}
const observerMarker = new IntersectionObserver(callbackScr, optionsScrollT);

const backToBottom = function () {
    if(Z('.chat-zone-main')) {
        Z('.chat-zone-main').scrollTop = Z('.chat-zone-main').scrollHeight;
        // animatedScrollTo({
        //     to: Z('.chat-zone-main').offsetHeight,
        //     easing: easeInQuint,
        //     duration: 500
        // })
    }
}
const downloadFile = function (e) {
    window.event.preventDefault();
    const url = e.getAttribute('data-file')
    if(navigator.userAgent.toLowerCase().search('chrome') != -1) {
        const anchor = document.createElement('a');
        anchor.href = url
        anchor.download = e.getAttribute('data-file-name')
        anchor.click()
    } else {
        document.getElementById('download_iframe').src = url;
    }
}
const downloadFile2 = function (e) {
    window.event.preventDefault()
    const ref = e.getAttribute('data-id');
    if(Z('.icon' + ref)) Z('.icon' + ref).classList.add('d-none')
    if(Z('.loading' + ref)) Z('.loading' + ref).classList.remove('d-none')
    const path = e.getAttribute('data-file')
    fetch(path)
    .then(resp => resp.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        // the filename you want
        a.download = e.getAttribute('data-file-name');
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        // alert('your file has downloaded!'); // or you know, something with better UX...
        if(Z('.icon' + ref)) Z('.icon' + ref).classList.remove('d-none')
        if(Z('.loading' + ref)) Z('.loading' + ref).classList.add('d-none')
    })
    .catch((error) => alert(error));   
}

function isElementOutViewport(el){
    var rect = el.getBoundingClientRect();
    if(Z('#footer-bar')) {
        const hg = Z('#footer-bar').getBoundingClientRect()
        return rect.bottom + hg < 0 || rect.right < 0 || rect.left > window.innerWidth || rect.top + hg > window.innerHeight;
    } else {
        return rect.bottom < 0 || rect.right < 0 || rect.left > window.innerWidth || rect.top > window.innerHeight;
    }
}

function chatScrollToBottom() {
    if(Z('.chat-zone-main')) {
        Z('.chat-zone-main').scrollTop = Z('.chat-zone-main').scrollHeight
        // window.scrollTo(0, Z('.chat-zone-main').offsetHeight)
    }
}

const showConversationsList = function() {
    Z('.division-1').classList.remove('hide')
}
const hideConversationList = function() {
    Z('.division-1').classList.add('hide')
}

const closeSync = function () {
    clearTimeout(timeSync)
    timeSync = null;
}
const beginSync = function () {
    timeSync = setTimeout(() => {
        if(document.forms.chat) {
            synchro()
        }
    }, 1000);
}
const recall = function () {
    closeSync();
    beginSync();
}

const synchro = function() {
    let lastChild = null
    if(Z('.chat-zone-main')) {
        lastChild = Z('.chat-zone-main').lastElementChild.previousElementSibling
    }
    const form = document.forms.chat;
    if(form != undefined) {
        const formData = new FormData();
        formData.append('async', '1');
        formData.append('upd_chat[exp]', form.elements['chat[exp]'].value);
        formData.append('upd_chat[compte_dest]', form.elements['chat[compte_dest]'].value);
        formData.append('upd_chat[dest]', form.elements['chat[dest]'].value);
        if(lastChild) {
            formData.append('upd_chat[key]', lastChild.getAttribute('data-message-key'))
        }
        formData.append('csrf', form.elements.csrf.value);
        fetch(syncPath(), {
            method: 'POST',
            body: formData
        }).then(function (response) {
            return response.text()
        }).then(function (response) {
            if(response.hasOwnProperty('xhkerrors')) {
                const errors = response.xhkerrors
                if(errors.hasOwnProperty('csrf')) {
                    showNotification(errors.csrf)
                }
            } else {
                if(response.trim() != '') {
                    if(Z('#empty-message')) {
                        Z('#empty-message').remove()
                    }
                    
                    Z('.chat-zone-main #marker').insertAdjacentHTML('beforebegin', response)
                    // Z('.chat-zone-main').insertAdjacentHTML('beforeend', response)
    
                    // supprimer les doublons
                    S('._chat').reverse().map(cs => {
                        const ms = S('.message' + cs.getAttribute('data-message-key'))
                        const l = ms.length
                        if(l > 1) {
                            for(let i = 1; i < l; i++) {
                                ms[i].remove();
                            }
                        }
                    })

                    if(mustScroll) {
                        chatScrollToBottom();
                    }
                }
            }
    
            // relancer la chargement des message
            if(rec)
                recall()
        }).catch(function (error) {
            console.log(error);    
        })
    }
}

const sendMessage = function (e) {
    window.event.preventDefault();
    const area = Z('textarea.chat-form-footer')
    if(area.value.trim() !== '') {
        closeSync();
        const formData = new FormData(e);
        formData.append('async', '1');
        fetch(syncPath(), {
            method: 'POST',
            body: formData
        }).then(function (response) {
            return response.text()
        }).then(function (response) {
            removeErrorHTML2('csrf');
            // console.log(response);
            if(response.hasOwnProperty('xhkerrors')) {
                const errors = response.xhkerrors
                if(errors.hasOwnProperty('csrf')) {
                    showNotification(errors.csrf)
                }
            } else {
                if(Z('#empty-message')) {
                    Z('#empty-message').remove()
                }
                Z('.chat-zone-main #marker').insertAdjacentHTML('beforebegin', response)
                // Z('.chat-zone-main').insertAdjacentHTML('beforeend', response)
                e.elements['chat[message]'].value = '';
                document.forms.chat.elements.image.value = '';
                document.forms.chat.elements.doc.value = '';
                chatScrollToBottom();
                setTimeout(() => {
                    beginSync();
                    if(Z('.chat-zone-main').lastElementChild.previousElementSibling) {
                        Array.from(document.querySelectorAll('.response-right[data-message-key="' + Z('.chat-zone-main').lastElementChild.previousElementSibling.getAttribute('data-message-key') + '"]')).map((d, id) => {
                            if(id != 0) {
                                d.remove()
                            }
                        })
                    }
                })
            }       
        }).catch(function (error) {
            console.log(error);    
        })
    }
}

timeReload = null;
const closeReload = function () {
    clearTimeout(timeReload)
    timeReload = null;
}
const beginReload = function () {
    timeReload = setTimeout(() => {
        if(Z('.conversation-liste')) {
            // Lancer le chargement
            reloadConversationList()
        }
    }, 2000);
}
const recallReload = function () {
    closeReload();
    beginReload();
}
const reloadConversationList = function () {
    const ref = Z('.conversation-liste')
    if(ref) {
        const formData = new FormData();
        formData.append('async', '1');
        formData.append('upd_conversation[user]', document.forms.chat.elements['chat[compte_dest]'].value);
        if(Z('.conversation-liste .conversation-item.active')) {
            formData.append('upd_conversation[user_active]', Z('.conversation-liste .conversation-item.active').getAttribute('data-user-key'))
        }
        fetch('async.php', {
            method: 'POST',
            body: formData
        }).then(function (response) {
            return response.text()
        }).then(function (response) {
            // console.log(response);
            if(response.hasOwnProperty('xhkerrors')) {
                // const errors = response.xhkerrors
                // if(errors.hasOwnProperty('csrf')) {
                //     showNotification(errors.csrf)
                // }
            } else {
                // console.log(response)
                if(response != '') {
                    ref.innerHTML = response
                }
            }

            // Relancer le chargement des conversation
            recallReload()
        }).catch(function (error) {
            console.log(error);    
        })
    }
}

const uploadFile = function (e,type) {
    closeSync();
    const form = document.forms.chat;
    const elements = form.elements
    const formData = new FormData(form);
    formData.append('async', '1')
    formData.append('csrf', elements.csrf.value)
    formData.append(type, e.value)
    fetch(syncPath(), {
        method: 'POST',
        body: formData
    }).then(function (response) {
        return response.text()
    }).then(function (response) {
        if(response.indexOf('xhkerrors') == 1) {
            showNotification(JSON.parse(response).xhkerrors.file)
        } else {
            Z('.chat-zone-main #marker').insertAdjacentHTML('beforebegin', response)

            form.elements['chat[message]'].value = '';
            document.forms.chat.elements.image.value = '';
            document.forms.chat.elements.doc.value = '';
            // form.elements['image'].value = '';
            // form.elements['doc]'].value = '';
        }
        setTimeout(() => {

            beginSync();

            if(Z('.chat-zone-main').lastElementChild.previousElementSibling) {
                Array.from(document.querySelectorAll('.response-right[data-message-key="' + Z('.chat-zone-main').lastElementChild.previousElementSibling.getAttribute('data-message-key') + '"]')).map((d, id) => {
                    if(id != 0) {
                        d.remove()
                    }
                })
            }
            chatScrollToBottom();
        }, 500)
    }).catch(function (error) {
        console.log(error)
    })
}

function createThumbnail(file) {
    let reader = new FileReader();
    reader.onload = function() {
        const preview_file = document.createElement('div')
        preview_file.id = 'preview-file'

        const fileinput_remove = document.createElement('button')
        fileinput_remove.classList.add('btn', 'btn-close', 'fileinput-remove', 'shadow-none')
        fileinput_remove.setAttribute('onclick', 'removePreview()')
        fileinput_remove.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/></svg>'

        const contain__file = document.createElement('div')
        contain__file.classList.add('__contain__file')

        const __footer = document.createElement('div')
        __footer.classList.add('__footer') 
        __footer.innerHTML = '<button class="btn btn-sm mx-1 sendFile shadow-none" onclick="sendFile();" style="background:transparent;"><span data-testid="send" data-icon="send" class=""><svg viewBox="0 0 24 24" width="24" height="24" class=""><path fill="currentColor" d="M1.101 21.757 23.8 12.028 1.101 2.3l.011 7.912 13.623 1.816-13.623 1.817-.011 7.912z"></path></svg></span></button>'

        const __prev = document.createElement('div')
        __prev.classList.add('__prev');

        const __data = document.createElement('div')
        __data.classList.add('__data', 'tex-secondary')

        let imgElement = document.createElement('img');
            imgElement.classList.add('file-preview-image', 'kv-preview-data')
            imgElement.setAttribute('title', '')
            imgElement.setAttribute('alt', '')
            let type = file.type.replace('application/', '')
            var typeZip = ['zip', 'rar']
            var otherType = ['doc', 'docx', 'pdf', 'txt', 'csv', 'ppt', 'pptx']

            if(typeZip.indexOf(type) !== -1) {
                imgElement.src = '../assets/images/svg/zip.svg';
                imgElement.style.width = '150px';
                imgElement.style.height = '150px';
            } else if(otherType.indexOf(type) !== -1) {
                imgElement.src = '../assets/images/svg/file.svg';
                imgElement.style.width = '150px';
                imgElement.style.height = '150px';
            } else {
                imgElement.src = this.result;
            }

        __data.innerHTML = file.name + ' <samp>(' + format_size(file.size) + ')</samp>'

        __prev.appendChild(imgElement)
        contain__file.appendChild(__prev);
        contain__file.appendChild(__data);
        preview_file.appendChild(fileinput_remove)
        preview_file.appendChild(contain__file);
        preview_file.appendChild(__footer);
        Z('.chat-zone').appendChild(preview_file)
    };
    reader.readAsDataURL(file);
}

const removePreview = function () {
    if(Z('#preview-file')) Z('#preview-file').remove()
    Z('input[name="image"]').value = ''
    Z('input[name="doc"]').value = ''
}

const sendFile = function () {
    if(Z('#preview-file')) Z('#preview-file').remove()
    if(document.forms.chat.elements['image'].value !== '') {
        uploadFile(document.forms.chat.elements['image'], 'image') 
    }
    else if(document.forms.chat.elements['doc'].value !== '') {
        uploadFile(document.forms.chat.elements['doc'], 'doc') 
    }
    Z('.hide-input-file').classList.toggle('show-input-file');
}

const clickInputFileDoc = function () {
    const form = document.forms.chat;
    const elements = form.elements
    elements['doc'].click();
    elements['doc'].onchange = function () {
        var allowedTypes = ['doc', 'docx', 'pdf', 'txt', 'csv', 'ppt', 'pptx', 'zip', 'rar', 'jpg', 'gif', 'png', 'jpeg']
        var files = this.files,
        filesLen = files.length,
        imgType;
        for (var i = 0 ; i < filesLen ; i++) {
            imgType = files[i].name.split('.');
            imgType = imgType[imgType.length - 1];
            if(allowedTypes.indexOf(imgType.toLowerCase()) != -1) {
                createThumbnail(files[i]);
            }
        }
    }
}

const clickInputFileImage = function () {
    const form = document.forms.chat;
    const elements = form.elements
    elements['image'].click();
    elements['image'].onchange = function () {

        var allowedTypes = ['png', 'jpeg', 'jpg']
        var files = this.files,
        filesLen = files.length,
        imgType;
        for (var i = 0 ; i < filesLen ; i++) {
            imgType = files[i].name.split('.');
            imgType = imgType[imgType.length - 1];
            if(allowedTypes.indexOf(imgType.toLowerCase()) != -1) {
                createThumbnail(files[i]);
            }
        }
    }
}

const toggleInputFile = function () {
    Z('.hide-input-file').classList.toggle('show-input-file');
}

const hideInputFile = function () {
    Z('.hide-input-file').classList.remove('show-input-file');
}

var obs;
if(window.attachEvent) {
    obs = function (element, event, handler) {
        if(element) {
            element.attachEvent('on' + event, handler)
        }
    }
} else {
    obs = function (element, event, handler) {
        if(element) {
            element.addEventListener(event, handler, false)
        }
    }
}

function resize() {
    const e = Z('textarea.chat-form-footer')
    const styles = getComputedStyle(e)
    const maxHeight = parseFloat(styles.maxHeight);
    e.style.height = 'auto';
    e.style.height = (e.scrollHeight > maxHeight ? maxHeight : e.scrollHeight) + 'px';
}

function delayResize() {
    window.setTimeout(resize, 0)
}

const toggleDisabledTextarea = function () {
    const textar = Z('textarea.chat-form-footer')
    if(textar) {
        const btnSnd = Z('button.chat-form-footer')
        if(btnSnd) {
            if(textar.value.trim() !== '') {
                btnSnd.removeAttribute('disabled')
                btnSnd.classList.add('text-primary')
            } else {
                btnSnd.setAttribute('disabled','disabled')
                btnSnd.classList.remove('text-primary')
            }
        }
    }
}

const initTg = function () {
    const textar = Z('textarea.chat-form-footer')
    if(textar) {
        obs(textar, 'keyup', toggleDisabledTextarea)
        obs(textar, 'cut', toggleDisabledTextarea)
        obs(textar, 'paste', toggleDisabledTextarea)

        obs(textar, 'keyup', resize)
        obs(textar, 'cut', resize)
        obs(textar, 'paste', resize)

        obs(textar, 'keyup', delayResize)
        obs(textar, 'cut', delayResize)
        obs(textar, 'paste', delayResize)
    }
}

const resizeChatZoneMain = function () {
    const h = window.innerHeight;
    if(Z('.chat-zone')) {
        if(Z('.chat-zone .chat-zone-main')){
            Z('.chat-zone .chat-zone-main').style.minHeight = (h - Z('.chat-zone header').offsetHeight - Z('.chat-zone #footer-bar').offsetHeight) + 'px';
        } 
    }    
}

// window.onload = function () {
//     setTimeout(() => {
//         resizeChatZoneMain()
//     }, 500)
//     setTimeout(() => {
//         chatScrollToBottom()
//     }, 700)
// }

window.onresize = function () {
    // resizeChatZoneMain()
}

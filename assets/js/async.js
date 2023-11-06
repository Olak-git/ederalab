const S = function (i) {
    return Array.from(document.querySelectorAll(i))
}
const Z = function (i) {
    return document.querySelector(i);
}
const arrondir = (A, B) => {
    return parseFloat(parseInt(A * Math.pow(10, B) + .5) / Math.pow(10, B));
}
const format_size = (S) => {
    let $unit = 'B';
    let $d = 0;
    if(S >= Math.pow(1024, 4)) {
        $d = 4;
        $unit = 'TB';
    } else if(S >= Math.pow(1024, 3)) {
        $d = 3;
        $unit = 'GB';
    } else if(S >= Math.pow(1024, 2)) {
        $d = 2;
        $unit = 'MB';
    } else if(S >= Math.pow(1024, 1)) {
        $d = 1;
        $unit = 'KB';
    }
    return arrondir(S / Math.pow(1024, $d), 2) + '' + $unit;
}
const ucFirst = function (v) {
    return v.substr(0, 1).toUpperCase() + v.substr(1, v.length)
}
const ucWords = function (v) {
    const val = v.split(' ')
    val.forEach((o, i) => {
        val[i] = ucFirst(o)
    })
    return val.join(' ');
}
const loading = function () {
    removeLoading();
    Z('body').insertAdjacentHTML('afterbegin', '<div id="loading" class="d-flex justify-content-center align-items-center" style="position:fixed;left:0;top:0;z-index:20000;width:100%;height:100%;background:rgba(255,255,255,.5);color:#fff;"><img src="./assets/images/icons8-loading-circle.gif" width="40"></div>')
}
const removeLoading = function () {
    if(Z('#loading')) {
        Z('#loading').remove();
    }
}
const progressLoading = function (e) {
    // loading.gif
    if(e) {
        e.insertAdjacentHTML('afterend', '<img src="./assets/images/icons8-loading-circle.gif" class="ml-1" width="30">')
    }
}
const removeProgressLoading = function (e) {
    if(e && e.nextElementSibling) {
        e.nextElementSibling.remove()
    }
}
const removeErrorHTML2 = function($id) {
    if(Z('#' + $id)) {
        Z('#' + $id).remove();
    }
}
const errorHTML2 = function($id, $v) {
    removeErrorHTML2($id)
    const error = document.createElement('div')
    error.classList.add('error', 'text-danger')
    error.style.lineHeight = '1'
    error.id = $id
    error.innerHTML = '<span class="badge badge-danger text-uppercase">error</span> ' + $v;
    return error;
}
const hideNotifier = function () {
    Z('#notifier').classList.add('d-none')
}
const hideNotification = function () {
    Z('#notifications').classList.add('d-none')
}

const fetchText = function (url, formData) {
    formData.append('async', '1')
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(response => {
        Z('#commandeModal .modal-body').innerHTML = response
    })
    .catch(error => console.log(error))
}

const fetchJson = function (data) {
    const formData = data.formData
    formData.append('async', '1')
    fetch(data.url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(response => {
        const action = data.element.getAttribute('data-action').toLowerCase()
        if(response.success) {
            if(action == 'edit-transporteur' || action == 'new-transporteur') {
                data.element.submit()
            }
        } else {
            const errors = response.errors
            if(action == 'edit-transporteur') {
                // if(errors.hasOwnProperty('identite')) {
                //     const error = errorHTML2('', errors.sous_categorie);
                //     Z('.sous_categorie_nom').insertAdjacentElement('afterend', error)
                // }
                // if(errors.hasOwnProperty('edit_sous_categorie')) {
                //     const error = errorHTML2('edit_sous_categorie', errors.edit_sous_categorie);
                //     Z('.edit_sous_categorie_nom').insertAdjacentElement('afterend', error)
                // }
            }
            if(action == 'new-transporteur') {
                data.element.submit()
            }
        }
    })
    .catch(error => console.log(error))
}
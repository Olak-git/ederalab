S('.show-modal-commande').forEach(e => {
    e.onclick = function() {
        const formData = new FormData()
        const data_name = this.getAttribute('data-name')
        const data_value = this.getAttribute('data-name-value')
        formData.append(data_name, data_value)
        if(data_name == 'calendrier') {
            formData.append('date', this.getAttribute('data-date'))
        }
        fetchText('async.php', formData)
    }
});
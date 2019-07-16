function init() {
    swup.cache.empty()

    if (document.querySelector('#companyTable')) {
        var table = $('#companyTable').DataTable({
            language: {
                url: '/json/fr_FR.json'
            },
            ajax: {
                url: '/api/companies',
                dataSrc: ''
            },
            columns: [
                {
                    data: 'id',
                    render: {
                        "_": function (data) {
                            return data
                        },
                        display: function (data, index, row) {
                            var button = `
                            <a href="/backend/pharmacies/${data}" class="btn btn-success btn-sm edit-modal">
                                <i class="fas fa-edit"></i>
                            </a>`
                            return button
                        }
                    }
                },
                { data: 'name' },
                { data: 'postal_code' },
                { data: 'city' },
                {
                    data: 'user',
                    render: function (data, index, row) {
                        if (null !== data) {
                            var url = `
                                <a class="link-green" href="/backend/gestionnaires/${data.id}">${data.firstname} ${data.lastname}<a>
                            `
                            return url
                        }
                        return '-'
                    }
                },
                {
                    data: 'created_at',
                    render: function (data, index, row) {
                        if (data) {
                            const d = new Date(data);
                            if (d.getMonth() < 10) {
                                var month = `0${d.getMonth() + 1}`;
                            } else {
                                var month = d.getMonth() + 1;
                            }
                            const dateFormated = `${d.getDate()}/${month}/${d.getFullYear()}`;
                            return dateFormated
                        }
                        return '-';
                    }
                },
                {
                    data: 'commercial',
                    render: function (data, index, row) {
                        if (data) {
                            return data.firstname + ' ' + data.lastname
                        }
                        return '-'
                    }
                },
                // {
                //     data: 'isActive',
                //     render: function (data) {
                //         return (data === true) ? 'oui' : 'non'
                //     }
                // }
            ]
        })
    }

    if (document.querySelector('#themeTable')) {
        var table = $('#themeTable').DataTable({
            language: {
                url: '/json/fr_FR.json'
            },
            ajax: {
                url: '/api/themes',
                dataSrc: ''
            },
            columns: [
                {
                    data: 'id',
                    render: {
                        "_": function (data) {
                            return data
                        },
                        display: function (data, index, row) {
                            var button = `
                            <a href="/backend/themes/${data}" class="btn btn-success btn-sm edit-modal">
                                <i class="fas fa-edit"></i>
                            </a>`
                            return button
                        }
                    }
                },
                { data: 'name' },
                { 
                    data: 'picture',
                    sortable: false,
                    render: function(data, index, row) {
                        if(data) {
                            return `<img class="img-thumbnail" alt="${row.name}" style="height:50px;width:75px;" src="/images/themes/${data}">`
                        }
                        return '-'
                    }
                },
                {
                    data: 'is_active',
                    render: function (data, index, row) {
                        if (data == true || data == 'true') {
                            var button = `<a href="/backend/themes/${row.id}/toggle" class="btn btn-success btn-sm"><i class="fas fa-toggle-on"></i></a>`
                        } else {
                            var button = `<a href="/backend/themes/${row.id}/toggle" class="btn btn-secondary btn-sm"><i class="fas fa-toggle-off"></i></a>`
                        }
                        return button
                    }
                },
            ]
        })
    }

    if (document.querySelector('#filesTable')) {
        var table = $('#filesTable').DataTable({
            language: {
                url: '/json/fr_FR.json'
            },
            ajax: {
                url: '/api/files',
                dataSrc: ''
            },
            columns: [
                {
                    data: 'id',
                    render: {
                        "_": function (data) {
                            return data
                        },
                        display: function (data, index, row) {
                            var button = `
                            <a href="/backend/documents/${data}" class="btn btn-success btn-sm edit-modal">
                                <i class="fas fa-edit"></i>
                            </a>`
                            return button
                        }
                    }
                },
                { data: 'name' },
                { data: 'description' },
                {
                    data: 'commercial',
                    render: function (data, index, row) {
                        if (data) {
                            return data.firstname + ' ' + data.lastname
                        }
                        return '-'
                    }
                },
                {
                    data: 'id',
                    sortable: false,
                    render: function (data, index, row) {
                        if (row.read_by && row.pharmacies) {
                            return row.read_by.length + '/' + row.pharmacies.length
                        }
                        return '-'
                    }
                },
                {
                    data: 'is_active',
                    render: function (data, index, row) {
                        if (data == true || data == 'true') {
                            var button = `<a href="/backend/documents/${row.id}/toggle" class="btn btn-success btn-sm"><i class="fas fa-toggle-on"></i></a>`
                        } else {
                            var button = `<a href="/backend/documents/${row.id}/toggle" class="btn btn-secondary btn-sm"><i class="fas fa-toggle-off"></i></a>`
                        }
                        return button
                    }
                },
            ],
        })
    }

    if (document.querySelector('#partnerTable')) {
        var table = $('#partnerTable').DataTable({
            order: [[0, "desc"]],
            language: {
                url: '/json/fr_FR.json'
            },
            ajax: {
                url: '/api/partners',
                dataSrc: ''
            },
            columns: [
                {
                    data: 'id',
                    render: {
                        "_" : function(data){
                            return data
                        },
                        display : function (data, index, row) {
                            var button = `
                            <a href="/backend/partenaires/${data}" class="btn btn-success btn-sm edit-modal">
                                <i class="fas fa-edit"></i>
                            </a>`
                            return button

                        }
                    }
                },
                { data: 'name' },
                { 
                    data: 'image',
                    sortable: false,
                    render: function (data, index, row) {
                        if (data) {
                            return `<img class="img-thumbnail" alt="${row.name}" style="height:50px;width:75px;" src="/images/partners/${data}">`
                        }
                        return '-'
                    }
                },
                {
                    data: 'link',
                    render: function (data, index, row) {
                        if (data != null) {
                            return '<a href="' + data + '" target="_blank">'+ data +'</a>' || '-'
                        } else {
                            return '-'
                        }
                    }
                },
                {
                    data: 'is_active',
                    render: function (data, index, row) {
                        if (data == true || data == 'true') {
                            var button = `<a href="/backend/partenaires/${row.id}/toggle" class="btn btn-success btn-sm"><i class="fas fa-toggle-on"></i></a>`
                        } else {
                            var button = `<a href="/backend/partenaires/${row.id}/toggle" class="btn btn-secondary btn-sm"><i class="fas fa-toggle-off"></i></a>`
                        }
                        return button
                    }
                },
            ]
        })
    }

    if (document.querySelector('#postTable')) {
        var table = $('#postTable').DataTable({
            order: [[0, "desc"]],
            language: {
                url: '/json/fr_FR.json'
            },
            ajax: {
                url: '/api/posts',
                dataSrc: ''
            },
            columns: [
                {
                    data: 'id',
                    render: {
                        "_" : function(data){
                            return data
                        },
                        display : function (data, index, row) {
                            var button = `
                            <a href="/backend/articles/${data}" class="btn btn-success btn-sm edit-modal">
                                <i class="fas fa-edit"></i>
                            </a>`
                            return button

                        }
                    }
                },
                { data: 'title' },
                {
                    data: 'author',
                    render: function (data, index, row) {
                        if (row.author != null) {
                            return (row.author.firstname + ' ' + row.author.lastname) || '-'
                        } else {
                            return '-'
                        }
                    }
                },
                { 
                    data: 'rssfeedname',
                    render: function (data) {
                        return data || 'Ynovagroup';
                    }
                },
                {
                    data: 'visibility'
                },
                {
                    data: 'is_active',
                    render: function (data, index, row) {
                        if (data == true || data == 'true') {
                            var button = `<a href="/backend/articles/${row.id}/toggle" class="btn btn-success btn-sm"><i class="fas fa-toggle-on"></i></a>`
                        } else {
                            var button = `<a href="/backend/articles/${row.id}/toggle" class="btn btn-secondary btn-sm"><i class="fas fa-toggle-off"></i></a>`
                        }
                        return button
                    }
                },
            ]
        })
    }

    if (document.querySelector('#userTable')) {
        var table = $('#userTable').DataTable({
            language: {
                url: '/json/fr_FR.json'
            },
            ajax: {
                url: '/api/users',
                dataSrc: ''
            },
            columns: [
                {
                    data: 'id',
                    render: function (data, index, row) {
                        var button = `
                        <a href="/backend/gestionnaires/${data}" class="btn btn-success btn-sm edit-modal">
                            <i class="fas fa-edit"></i>
                        </a>`
                        return button
                    }
                },
                { data: 'firstname' },
                { data: 'lastname' },
                {
                    data: 'company',
                    render: function (data, index, row) {
                        if (null !== data) {
                            var url = `
                                <a class="link-green" href="/backend/pharmacies/${data.id}">${data.name}<a>
                            `
                            return url
                        }
                        return '-'
                    }
                },
                { data: 'email' },
                {
                    data: 'is_active',
                    render: function (data, index, row) {
                        if (data == true || data == 'true') {
                            var button = `<a href="/backend/gestionnaires/${row.id}/toggle" class="btn btn-success btn-sm"><i class="fas fa-toggle-on"></i></a>`
                        } else {
                            var button = `<a href="/backend/gestionnaires/${row.id}/toggle" class="btn btn-secondary btn-sm"><i class="fas fa-toggle-off"></i></a>`
                        }
                        return button
                    }
                },
            ]
        })
    }

    if (document.querySelector('#salesrepTable')) {
        var table = $('#salesrepTable').DataTable({
            language: {
                url: '/json/fr_FR.json'
            },
            ajax: {
                url: '/api/commerciaux',
                dataSrc: ''
            },
            columns: [
                {
                    data: 'id',
                    render: function (data, index, row) {
                        var button = `
                        <a href="/backend/commerciaux/${data}" class="btn btn-success btn-sm edit-modal">
                            <i class="fas fa-edit"></i>
                        </a>`
                        return button
                    }
                },
                { data: 'firstname' },
                { data: 'lastname' },
                { data: 'email' },
                {
                    data: 'is_active',
                    render: function (data, index, row) {
                        if (data == true || data == 'true') {
                            var button = `<a href="/backend/commerciaux/${row.id}/toggle" class="btn btn-success btn-sm"><i class="fas fa-toggle-on"></i></a>`
                        } else {
                            var button = `<a href="/backend/commerciaux/${row.id}/toggle" class="btn btn-secondary btn-sm"><i class="fas fa-toggle-off"></i></a>`
                        }
                        return button
                    }
                },
            ]
        })
    }

    if (document.querySelector('#rssTable')) {
        var table = $('#rssTable').DataTable({
            language: {
                url: '/json/fr_FR.json'
            },
            ajax: {
                url: '/api/rss',
                dataSrc: ''
            },
            columns: [
                {
                    data: 'id',
                    render: function (data, index, row) {
                        var button = `
                        <a href="/backend/rss/${data}" class="btn btn-success btn-sm edit-modal">
                            <i class="fas fa-edit"></i>
                        </a>`
                        return button
                    }
                },
                { data: 'name' },
                { data: 'link' },
                {
                    data: 'is_active',
                    render: function (data, index, row) {
                        if (data == true || data == 'true') {
                            var button = `<a href="/backend/rss/${row.id}/toggle" class="btn btn-success btn-sm"><i class="fas fa-toggle-on"></i></a>`
                        } else {
                            var button = `<a href="/backend/rss/${row.id}/toggle" class="btn btn-secondary btn-sm"><i class="fas fa-toggle-off"></i></a>`
                        }
                        return button
                    }
                },
            ]
        })
    }

    if (document.querySelector('#post_content')) {
        ClassicEditor
            .create(document.querySelector('#post_content'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo']
            }).then(newEditor => { editor = newEditor })
        
        $('form[name="post"]').submit(function(){
            var content = editor.getData()
            $('#post_content').val(content)
        })
    }

    function formatState(state) {
        console.log(state)
        if (state.element.dataset.read == "true") {
            return $(`<span>${state.text} <i class="fas fa-check text-success"></i></span>`);
        }
        return $(`<span>${state.text} <i class="fas fa-times text-danger"></i></span>`);
    };

    $('.select2').select2({
        templateSelection: formatState,
        width: '100%',
        placeholder: 'Choisir un/des destinataire(s)',
        allowClear: true,
        closeOnSelect: false,
    })
    
    $("#files_selectAll").change(function () {
        if ($("#files_selectAll").is(':checked')) {
            $(".select2 > option").prop("selected", "selected");
            $(".select2").trigger("change");
        } else {
            $(".select2 > option").prop("selected", false);
            $(".select2").trigger("change");
        }
    });

    $(document).ready(function () {
        bsCustomFileInput.init()
    })
}

function unload() {
    if (typeof table != 'undefined') {
        table.destroy()
    }

    if (typeof editor != 'undefined') {
        editor.destroy()
    }
}

const swup = new Swup({
    containers: ['#swup', "#sidebar"]
});

document.addEventListener("DOMContentLoaded", init);
swup.on('contentReplaced', init)
swup.on('willReplaceContent', unload);
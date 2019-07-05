function init() {
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
                { data: 'first_adress_field' },
                //{ data: 'secondAdressField' },
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
                            <a href="/documents/${data}" class="btn btn-success btn-sm edit-modal">
                                <i class="fas fa-edit"></i>
                            </a>`
                            return button
                        }
                    }
                },
                { data: 'name' },
                { data: 'type' },
                { data: 'description' },

                {
                    data: 'isActive',
                    render: function (data) {
                        return (data == "true" || data == true) ? 'oui' : 'non'
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
            ],
        })
    }

    if (document.querySelector('#postTable')) {
        var table = $('#postTable').DataTable({
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
                        return (row.author.firstname + ' ' + row.author.lastname) || '-'
                    }
                },
                { data: 'picture' },
                {
                    data: 'isActive',
                    render: function (data) {
                        return (data == "true" || data == true) ? 'oui' : 'non'
                    }
                }
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
                    data: 'isActive',
                    render: function (data) {
                        return (data == "true" || data == true) ? 'oui' : 'non'
                    }
                }
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

    $('.select2').select2({
        width: '100%'
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

const swup = new Swup();

document.addEventListener("DOMContentLoaded", init);
swup.on('contentReplaced', init)
swup.on('willReplaceContent', unload);
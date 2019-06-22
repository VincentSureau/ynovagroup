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
                    render: function (data, index, row) {
                        var button = `
                        <btn class="btn btn-success btn-sm edit-modal"
                            data-id="${data}"
                            data-name="${row.name}"
                            data-member="${(row.user.firstname + ' ' + row.user.lastname)}"
                            data-commercial="${(row.user.firstname + ' ' + row.user.lastname)}"
                            data-adress1="${row.firstAdressField}"
                            data-adress2="${row.secondAdressField}"
                            data-postalcode="${row.postalCode}"
                            data-city="${row.city}"
                        >
                            <i class="fas fa-plus-circle"></i>
                        </btn>`
                        return button
                    }
                },
                { data: 'name' },
                // {
                //     data: 'name',
                //     render: function (data, index, row) {
                //         var link = "{{ path('backend_company_show', {'id': '0'})|escape('js') }}"
                //         // escape js est obligatoire sinon il y a une erreur javascript d'encodage
                //         // je suis obligé de tricher en mettant un id fictif pour générer la route
                //         // je le remplace ensuite pas la donnée du tableau
                //         link = link.replace('0', row.id)
                //         return '<a href="' + link + '">' + data + '</a>'
                //     }
                // },
                { data: 'firstAdressField' },
                { data: 'secondAdressField' },
                { data: 'postalCode' },
                { data: 'city' },
                {
                    data: 'user',
                    render: function (data, index, row) {
                        if (null !== data) {
                            return data.firstname + ' ' + data.lastname
                        }
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
                ],
            'drawCallback': function() {
                $('.edit-modal').click(function () {
                    var data = $(this).data()

                    $('#companyName').val(data.name)
                    $('#companyMember').val(data.member)
                    $('#companyCommercial').val(data.commercial)
                    $('#companyAdress1').val(data.adress1)
                    $('#companyAdress2').val(data.adress2)
                    $('#companyPostCode').val(data.postalcode)
                    $('#companyCity').val(data.city)

                    $('#editModal').modal({
                        focus: false
                    })

                })
            }
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
                    render: function (data, index, row) {
                        var button = `
                        <btn class="btn btn-success btn-sm edit-modal"
                            data-id="${data}"
                            data-name="${row.name}"
                            data-type="${row.type}"
                            data-description="${row.description}"
                            data-isActive="${row.isActive}"
                            data-commercial="${row.commercial.firstname + ' ' + row.commercial.lastname}"
                        >
                            <i class="fas fa-plus-circle"></i>
                        </btn>`
                        return button
                    }
                },
                { data: 'name' },
                { data: 'type' },
                { data: 'description' },

                {
                    data: 'isActive',
                    render: function (data) {
                        return (data === true) ? 'oui' : 'non'
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
            'drawCallback': function () {
                $('.edit-modal').click(function () {
                    var data = $(this).data()

                    $('#fileName').val(data.name)
                    $('#fileType').val(data.type)
                    $('#fileDescription').val(data.description)
                    $('#fileCommercial').val(data.commercial)
                    $('#isActive').val(data.isactive)
                    $('option[value="' + data.isactive + '"]').attr('selected', true)
                    $('#editModal').modal({
                        focus: false
                    })

                })
            }
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
                    render: function (data, index, row) {
                        var button = `
                        <btn class="btn btn-success btn-sm edit-modal"
                            data-id="${data}"
                            data-title="${row.title}"
                            data-author="${(row.author.firstname + ' ' + row.author.lastname)}"
                            data-picture="${row.picture}"
                            data-isActive="${row.isActive}"
                        >
                            <i class="fas fa-plus-circle"></i>
                        </btn>`
                        return button
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
                        return (data === true) ? 'oui' : 'non'
                    }
                }
            ],
            'drawCallback': function () {
                $('.edit-modal').click(function () {
                    var data = $(this).data()

                    $('#title').val(data.title)
                    $('#content').val(data.content)
                    $('#author').val(data.author)
                    $('#picture').val(data.picture)
                    $('#isActive').val(data.isactive)
                    $('option[value="' + data.isactive + '"]').attr('selected', true)

                    $('#editModal').modal({
                        focus: false
                    })
                })
            }
        })
        var cke = ClassicEditor
            .create(document.querySelector('#content'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo']
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
                        <btn class="btn btn-success btn-sm edit-modal"
                            data-id="${data}"
                            data-firstname="${row.firstname}"
                            data-lastname="${row.lastname}"
                            data-company="${row.company.name}"
                            data-email="${row.email}"
                            data-isActive="${row.isActive}"
                        >
                            <i class="fas fa-plus-circle"></i>
                        </btn>`
                        return button
                    }
                },
                { data: 'firstname' },
                { data: 'lastname' },
                {
                    data: 'company',
                    render: function (data, index, row) {
                        return row.company.name || '-'
                    }
                },
                { data: 'email' },
                {
                    data: 'isActive',
                    render: function (data) {
                        return (data === true) ? 'oui' : 'non'
                    }
                }
            ],
            'drawCallback': function () {
                $('.edit-modal').click(function () {
                    var data = $(this).data()

                    $('#firstname').val(data.firstname)
                    $('#lastname').val(data.lastname)
                    $('#email').val(data.email)
                    $('#company').val(data.company)
                    $('#isActive').val(data.isActive)

                    $('#editModal').modal({
                        focus: false
                    })

                })
            }

        })
    }

    if (document.querySelector('#something-else')) {
        // ...
    }
}

function unload() {
    if (typeof variable != 'undefined') {
        table.destroy()
    }
}

const swup = new Swup();

document.addEventListener("DOMContentLoaded", init);
swup.on('contentReplaced', init)
swup.on('willReplaceContent', unload);
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
                                <i class="fas fa-edit"></i>
                            </btn>`
                            return button
                        }
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
                    $('#id').val(data.id)
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
        $('#companyEdit').on('submit', function (e) {
            e.preventDefault()
            var id = $('#id').val()
            var companyName = $('#companyName').val()
            var companyAdress1 = $('#companyAdress1').val()
            var companyAdress2 = $('#companyAdress2').val()
            var companyPostCode = $('#companyPostCode').val()
            var companyCity = $('#companyCity').val()

            //var isActive = ($('#isActive').val() == "true") ? true : false
            var method = id ? 'PUT' : 'POST'
            var data = {
                "name": companyName,
                "firstAdressField": companyAdress1,
                "secondAdressField": companyAdress2,
                "postalCode": companyPostCode,
                "city": companyCity
            }
            var url = id ? '/api/companies/' + id : '/api/companies'
            data = JSON.stringify(data)
            $.ajax({
                method: method,
                url: url,
                data: data,
                datatype: 'json',
                contentType: "application/json",
                headers: {
                    accept: "application/json"
                }
            }).done(function (response) {
                table.ajax.reload()
                $('#editModal').modal('hide')
            })

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
                            <btn class="btn btn-success btn-sm edit-modal"
                                data-id="${data}"
                                data-name="${row.name}"
                                data-type="${row.type}"
                                data-description="${row.description}"
                                data-isActive="${row.isActive}"
                                data-commercial="${row.commercial.firstname + ' ' + row.commercial.lastname}"
                                data-delete="${row.deletedAt}"
                            >
                                <i class="fas fa-edit"></i>
                            </btn>`
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
            'drawCallback': function () {
                $('.edit-modal').click(function () {
                    var data = $(this).data()
                    console.log(data.delete)
                    $('#id').val(data.id)
                    $('#fileName').val(data.name)
                    $('#fileType').val(data.type)
                    $('#fileDescription').val(data.description)
                    $('#fileCommercial').val(data.commercial)
                    // to complete
                    $('#deletedAt').val(data.delete)
                    $('option').removeAttr('selected')
                    $('option[value="' + data.isactive + '"]').prop('selected', true)
                    $('option[value="' + data.isactive + '"]').attr('selected', true)
                    $('#editModal').modal({
                        focus: false
                    })
                })
            }
        })
        $('#fileEdit').on('submit', function (e) {
            e.preventDefault()
            var id = $('#id').val()
            var fileName = $('#fileName').val()
            var fileType = $('#fileType').val()
            var fileDescription = $('#fileDescription').val()
            var deletedAt = $('#deletedAt').val()
            var isActive = ($('#isActive').val() == "true") ? true : false
            //var isActive = ($('#isActive').val() == "true") ? true : false
            var method = id ? 'PUT' : 'POST'
            var data = {
                "name": fileName,
                "type": fileType,
                "description": fileDescription,
                "isActive": isActive,
                "deletedAt": deletedAt
            }
            var url = id ? '/api/files/' + id : '/api/files'
            data = JSON.stringify(data)
            $.ajax({
                method: method,
                url: url,
                data: data,
                datatype: 'json',
                contentType: "application/json",
                headers: {
                    accept: "application/json"
                }
            }).done(function (response) {
                table.ajax.reload()
                $('#editModal').modal('hide')
            })

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
                            <btn class="btn btn-success btn-sm edit-modal" data-id="${data}">
                                <i class="fas fa-edit"></i>
                            </btn>`
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
            ],
            'drawCallback': function () {
                $('.edit-modal').click(function () {
                    data = $(this).data()

                    $('#editModal').modal({
                        focus: false
                    })

                    $.ajax({
                        url: '/api/posts/'+data.id,
                        // data: {
                        //     id: data.id
                        // },
                        datatype: 'json'
                    }).done(function(response){
                        $('#title').val(response.title)
                        $('#content').val(response.content)
                        $("#id").val(response.id)
                        editor.setData(response.content)
                        $('#author').val(response.author.firstname + ' ' + response.author.lastname)
                        $('#picture').val(response.picture)
                        $('option').removeAttr('selected')
                        $('option[value="' + response.isActive + '"]').prop('selected', true)
                    })
                })

            }
        })
        ClassicEditor
        .create(document.querySelector('#content'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo']
        }).then(newEditor => { editor = newEditor})

        // $('#add-modal').click(function () {
        //     $('#editModal').modal({
        //         focus: false
        //     })

        //     $('#title').val('')
        //     $('#content').val('')
        //     $("#id").val('')
        //     editor.setData('')
        //     $('#author').val('')
        //     $('#picture').val('')
        //     $('option[value="true"]').attr('selected',true)

        // })

        $('#postEdit').on('submit', function(e){
            e.preventDefault()
            var id = $('#id').val()
            var title = $('#title').val()
            var content = $('#content').val()
            var isActive = ($('#isActive').val() == "true")? true : false
            var method = id? 'PUT' : 'POST'
            var data = {
                "title" : title,
                "content": content,
                "isActive": isActive
            }
            var url = id ? '/api/posts/' + id : '/api/posts'
            data= JSON.stringify(data)
            $.ajax({
                method: method,
                url: url,
                data: data,
                datatype: 'json',
                contentType: "application/json",
                headers: {
                    accept: "application/json"
                }
            }).done(function (response) {
                table.ajax.reload()
                $('#editModal').modal('hide')
            })

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
                            <i class="fas fa-edit"></i>
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
                        return (data == "true" || data == true) ? 'oui' : 'non'
                    }
                }
            ],
            'drawCallback': function () {
                $('.edit-modal').click(function () {
                    var data = $(this).data()

                    $('#id').val(data.id)
                    $('#firstname').val(data.firstname)
                    $('#lastname').val(data.lastname)
                    $('#email').val(data.email)
                    $('#company').val(data.company)
                    $('option').removeAttr('selected')
                    $('option[value="' + data.isactive + '"]').prop('selected', true)
                    $('#editModal').modal({
                        focus: false
                    })

                })
            }
        })
        $('#userEdit').on('submit', function (e) {
            e.preventDefault()
            var id = $('#id').val()
            var email = $('#email').val()
            var firstname = $('#firstname').val()
            var lastname = $('#lastname').val()
            var isActive = ($('#isActive').val() == "true") ? true : false
            //var isActive = ($('#isActive').val() == "true") ? true : false
            var method = id ? 'PUT' : 'POST'
            var data = {
                "firstname": firstname,
                "lastname": lastname,
                "email": email,
                "isActive": isActive
            }
            var url = id ? '/api/users/' + id : '/api/users'
            data = JSON.stringify(data)
            $.ajax({
                method: method,
                url: url,
                data: data,
                datatype: 'json',
                contentType: "application/json",
                headers: {
                    accept: "application/json"
                }
            }).done(function (response) {
                table.ajax.reload()
                $('#editModal').modal('hide')
            })

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
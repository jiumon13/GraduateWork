function warehouseFormat (item) {
    return 'Отделение №' + item.number + ' - ' + item.address;
}

$(document).ready(function () {

    var warehouses = [];

    $('input#city')
        .select2({
            minimumInputLength: 1,
            ajax: {
                url: "/api/v1/nova_poshta/cities",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        name: term // search term
                    };
                },
                results: function (response, page) {
                    var options = [];
                    for (var i = 0; i < response.data.length; i++) {
                        var item = response.data[i];
                        options.push({
                            id: item.id,
                            text: item.name
                        });
                    }
                    return { results: options };
                },
                cache: true
            }
        })
        .on('change', function (event) {
            $.ajax({
                type: 'GET',
                url: '/api/v1/nova_poshta/warehouses',
                data: {
                    city: event.val
                }
            }).done(function (response) {
                warehouses = [];
                for (var i = 0; i < response.data.length; i++) {
                    var item = response.data[i];
                    warehouses.push({
                        id: item.id,
                        text: warehouseFormat(item)
                    });
                }

                $('input#warehouse')
                    .select2({data: {results: warehouses}})
                    .parents('div.form-group')
                    .show()
                ;
            });
        })
    ;

    $('input#warehouse')
        .select2({
            data: { results: [] },
            query: function (options) {
                var results = [];
                for (var i = 0; i < warehouses.length; i++) {
                    var item = warehouses[i];
                    if (item.text.indexOf(options.term) !== -1) {
                        results.push(item);
                    }
                }
                options.callback({results: results});
            }
        })
        .on('change', function (event) {
            $(this).parents('form').find('button[type=submit]:disabled').removeAttr('disabled');
        })
});
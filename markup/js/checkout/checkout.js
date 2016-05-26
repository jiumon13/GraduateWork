function warehouseFormat (item) {
    return 'Отделение №' + item.number + ' - ' + item.address;
}

$(document).ready(function () {

    var cityInput = $('input#order_delivery_city');
    var warehouseInput = $('input#order_delivery_warehouse');
    var warehouseBlock = warehouseInput.parents('div.form-group');

    var warehouses = [];

    cityInput
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

                warehouseInput.select2({data: {results: warehouses}});
                warehouseBlock.show();
            });
        })
    ;

    warehouseInput
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
        });

    $('form.checkout').on('submit', function (event) {
        event.preventDefault();
        event.stopPropagation();

        var form = $(this);

        // Clear form errors
        form.find('b.help-block').remove();
        form.find('.form-group').removeClass('has-error');

        $.ajax({
            type: 'POST',
            url: '/api/v1/order/checkout',
            data: $(this).serializeArray()
        }).done(function (response) {
            if (response.success == true) {
                document.location = response.redirect;
            } else {
                for (var name in response.errors) {
                    var error = response.errors[name][0];
                    form.find('input[name="' + name + '"]')
                        .parents('.form-group')
                        .addClass('has-error');

                    form.find('input[name="' + name + '"]')
                        .parent()
                        .append('<b class="help-block">' + error + '</b>');
                }
            }
        });
    });
});
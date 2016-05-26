function warehouseFormat (item) {
    return 'Отделение №' + item.number + ' - ' + item.address;
}

$(document).ready(function () {
    var delivery = $('div.delivery');
    var receiver = $('div.receiver');
    var account = $('div.account');
    var forwardButton = $('button.forward');

    receiver.hide();
    account.hide();
    //forwardButton.attr('disabled', 1);

    var cityInput = delivery.find('input#city');
    var warehouseInput = delivery.find('input#warehouse');
    var warehouseBlock = warehouseInput.parents('div.form-group');

    warehouseBlock.hide();

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
        })
        .on('change', function (event) {
            receiver.show();
        });

    var form = $('section.checkout form');

    form.validate({
        'ignore': '',
        'showErrors': function (errorMap, errorList) {

            $('div.has-error .help-block').remove();
            $('div.has-error').removeClass('has-error');

            for (var i = 0; i < errorList.length; i++) {
                var element = errorList[i].element;
                var message = errorList[i].message;
                $(element).parents('div.form-group:visible').addClass('has-error');
                $(element).parents('div:first:visible').append('<b class="help-block">' + message + '</b>');
            }
        }
    });

    form.on('submit', function (event) {
        event.preventDefault();

        if (!form.valid()) {
            return false;
        }

        var data = form.serializeArray();

        $.ajax({
            type: 'POST',
            url: '/api/v1/order/checkout',
            data: data,
            dataType: 'json'
        }).done(function (response) {
           if (response.success == false) {
               for (key in response.errors) {
                   var message = response.errors[key][0];
                   var $element = $('input[name="' + key + '"]');
                   $element.parents('div.form-group:visible').addClass('has-error');
                   $element.parents('div:first:visible').append('<b class="help-block">' + message + '</b>');
               }
           }
        });

        return false;
    });

    form.find('.receiver input, .receiver select').on('keyup', function (event) {
        var elements = $('.receiver input, .receiver select');
        var valid = false;
        for (var i = 0; i < elements.length; i++) {
            var element = $(elements[i]);
            if (!element.valid()) {
                valid = false;
                break;
            } else {
                valid = true;
            }
        }

        if (valid) {
            account.show();
        }
    });

    $('input#create-account').on('change', function () {
        if (this.checked) {
            $('.account-form').show();
            $('.account-form input:not(:checkbox)').attr('required', '');
        } else {
            $('.account-form').hide();
            $('.account-form input').removeAttr('required');
        }
    });
});
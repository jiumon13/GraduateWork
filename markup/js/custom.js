function Template(name) {
    var blank = $('script[data-template=' + name + ']').html();
    this.render = function (variables) {
        var html = blank;
        for (name in variables) {
            var holder = '__' + name + '__';
            var value = variables[name];
            html = html.replace(holder, value);
        }
        return html;
    };
}

function Cart () {
    var $cart = $('ul.cart');
    var itemTemplate = new Template('cart-item');
    var totalTemplate = new Template('cart-total');

    this.refresh = function () {
        $.get('/api/v1/cart').done(function (response) {
            response = JSON.parse(response);
            if (!response.data.length) {
                $('<p>Ваша корзина пуста.</p>').appendTo('#cart-message');
                return true;
            } else {
                $('#cart-message').html('');
            }

            var quantity = 0;
            var sum = 0;

            $cart.html('');

            for (var i = 0; i < response.data.length; i++) {
                var item = response.data[i];
                quantity += item['quantity'];
                sum += item['price'] * item['quantity'];
                $cart.append(itemTemplate.render(item));
            }

            $cart.append(totalTemplate.render({
                quantity: quantity,
                sum: sum,
                currency: item['currency']
            }));

            $('.cart-items-quantity').html(quantity);
        });
    };

    this.add = function (product, services, quantity, callback) {
        $.post('/api/v1/cart', {
            product: product,
            services: services,
            quantity: quantity
        }).done(function (response) {
            callback(JSON.parse(response));
        }.bind(this));
    }.bind(this);
}

function ToggleBox(name)
{
    var input = $('input[name=' + name + ']');

    this.show = function (id, animated) {
        animated = animated == undefined
            ? true
            : animated;

        if (animated) {
            var visible = $('.toggle-box:visible');
            console.log(visible);
            if (visible.length > 0) {
                visible.slideToggle(200, function () {
                    $('.toggle-box[data-show-value=' + id + ']').slideToggle(400);
                });
            } else {
                $('.toggle-box[data-show-value=' + id + ']').slideToggle(400);
            }
        } else {
            $('.toggle-box:visible').hide();
            $('.toggle-box[data-show-value=' + id + ']').show();
        }
    };

    this.init = function () {
        this.show(input.val(), false);
    };

    input.change(function () {
        var input = $('input[name=' + name + ']:checked');
        this.show(input.val());
    }.bind(this));
}

$('.add-to-cart').click(function (e) {
    e.preventDefault();

    // Submit
    cart.add($(this).attr('data-product'), [], 1, function (response) {
        if (response.success) {
            cart.refresh();

            $('button.dropdown-toggle').click();
        }
    });
});

$('form#order').submit(function (e) {
    e.preventDefault();

    var form = $(this);

    // Clear form errors
    form.find('b.help-block').remove();
    form.find('.form-group').removeClass('has-error');

    var array = $(this).serializeArray();

    var order = {
        product: null,
        services: [],
        quantity: 0
    };

    for (var i = 0; i < array.length; i++) {
        var item = array[i];
        if (item.name == 'services[]') {
            order.services.push(item.value);
        } else {
            order[item.name] = item.value;
        }
    }

    cart.add(order.product, order.services, order.quantity, function (response) {

        if (response.success) {
            cart.refresh();
            $('a.cart').click();
        } else {
            for (var name in response.errors) {
                var error = response.errors[name];
                form.find('input[name=' + name + ']')
                    .parents('.form-group')
                    .addClass('has-error')
                    .append('<b class="help-block">' + error + '</b>');
            }
        }
    });
});

$(".product-service").click(function () {
    recountProductPrice();
});

productPrice = parseInt($("#product-price").html());

function recountProductPrice() {
    var checkedServices = $(".product-service:checked");
    var currency = $(".product-service").parents("tr").find("td span small").html();

    var sum = 0;

    for(var i = 0; i < checkedServices.length; i++) {
        var item = checkedServices[i];
        var servicePrice = parseInt($(item).parents("tr").find("td span.price").html());

        sum += servicePrice;
    }

    $("#product-price").html(productPrice+sum + ' <span style="font-size: 0.5em">' + currency + '</span>');
}

// Remove Button
$(".btn-remove").click(function () {
    $(this).closest(".remove-data").remove();
    recountCart();
});

var currency = $(".price small").html();

function recountCart() {
    var removeData = $(".remove-data");
    var totalQuantity = 0;
    var totalAmount = 0;

    for(var i = 0; i < removeData.length; i++) {
        var itemQuantity = parseInt($(removeData[i]).find(".quantity .form-group input").attr("value"));
        var itemAmount = parseFloat($(removeData[i]).find(".price").html());
        totalQuantity += itemQuantity;
        totalAmount += itemAmount * itemQuantity;
    }
    $(".total-quantity").html("Всего " + totalQuantity + " товаров");

    $(".total-amount").html(totalAmount + " <small>" + currency + "</small>");
}

function User()
{
    this.login = function (credentials) {
        var promise = new Promise();
        $.ajax({
            type: 'POST',
            url: '/api/v1/login',
            data: credentials,
            success: function (response) {
                if (response.success == true) {
                    promise.then(response);
                } else {
                    promise.else(response);
                }
            }
        });

        return promise;
    };

    this.register = function (data) {
        var promise = new Promise();
        $.ajax({
            type: 'POST',
            url: '/api/v1/users',
            data: data,
            success: function (response) {
                if (response.success == true) {
                    promise.then(response);
                } else {
                    promise.else(response);
                }
            }
        });

        return promise;
    }
}

function Promise()
{
    var _then = function () {};
    var _else = function () {};

    this.then = function (data) {
        if (typeof(data) == 'function') {
            _then = data;
            return this;
        } else {
            _then(data);
        }
    };

    this.else = function (data) {
        if (typeof(data) == 'function') {
            _else = data;
            return this;
        } else {
            _else(data);
        }
    };
}

function serialiseForm(form)
{
    var data = $(form).serializeArray();

    var result = {};
    for (var i = 0; i < data.length; i++) {
        var item = data[i];
        result[item.name] = item.value;
    }

    return result;
}

var user = new User();

$('form.login-form').submit(function (event) {
    event.preventDefault();

    var form = $(this);

    // Clear form errors
    form.find('b.help-block').remove();
    form.find('.form-group').removeClass('has-error');

    var data = serialiseForm(this);

    user.login(data)
        .then(function (data) {
            var target = this.getAttribute('data-target');
            document.location = target
                ? target
                : document.location;
        }.bind(this))
        .else(function (response) {
            for (var name in response.errors) {
                var error = response.errors[name][0];
                form.find('input[name="' + name + '"]')
                    .parents('.form-group')
                    .addClass('has-error');

                form.find('input[name="' + name + '"]')
                    .parent()
                    .append('<b class="help-block">' + error + '</b>');
            }
        })
});

$('form.registration-form').submit(function (event) {
    event.preventDefault();

    var form = $(this);

    // Clear form errors
    form.find('b.help-block').remove();
    form.find('.form-group').removeClass('has-error');

    var data = serialiseForm(this);

    user.register(data)
        .then(function (data) {
            var target = this.getAttribute('data-target');
            document.location = target
                ? target
                : document.location;
        }.bind(this))
        .else(function (response) {
            for (var name in response.errors) {
                var error = response.errors[name][0];
                form.find('input[name="' + name + '"]')
                    .parents('.form-group')
                    .addClass('has-error');

                form.find('input[name="' + name + '"]')
                    .parent()
                    .append('<b class="help-block">' + error + '</b>');
            }
        })
});

var cart = new Cart();
var accountToggleBox = new ToggleBox('account');

$(document).ready(function () {
    cart.refresh();
    accountToggleBox.init();
});
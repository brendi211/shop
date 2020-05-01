import axios from 'axios'

window.Cart = {
    async attach(id, selector) {
        selector.disabled = true

        await axios.post('/catalog/cart/attach', {id}).then((response) => {
            $('#cart-content').html(response.data.cartContent)
            toastr.success(response.data.message ?? '', response.data.title ?? undefined)
            selector.disabled = false
        })
    },

    async detach(id, selector) {
        if (selector.disabled) {
            return
        }

        selector.disabled = true

        await axios.post('/catalog/cart/detach', {id}).then(response => {
            $(selector).parents('li').remove()
            toastr.success(response.data.message ?? '', response.data.title ?? undefined)
            selector.disabled = false
        })
    },

    async switchDesire(product_id, selector) {
        if (selector.disabled) {
            return
        }

        selector.disabled = true

        await axios.post('catalog/desire/switch', {product_id}).then(response => {
            selector.disabled = false
            toastr.success(response.data.message, response.data.title)
        })
    }
}

// init add to cart button
$(document).on('click', '[data-type="cart_attach"]', function () {
    let id = $(this).data('id')

    axios.post('/catalog/cart/attach', {id}).then((response) => {
        $('.dropdown-cart-count').html(response.data.cartContProducts)
        $('.dropdown-cart-products').html(response.data.productsListHtml)
        $('.dropdown-cart-sum').html(response.data.cartSumProducts)
        toastr.success(response.data.message ?? '', response.data.title ?? undefined)
    })
})

$(document).on('click', '[data-type="cart_detach"]', function () {
    let id = $(this).data('id')

    axios.post('/catalog/cart/detach', {id}).then((response) => {
        $('.dropdown-cart-count').html(response.data.cartContProducts)
        $('.dropdown-cart-products').html(response.data.productsListHtml)
        $('.dropdown-cart-sum').html(response.data.cartSumProducts)
        toastr.success(response.data.message ?? '', response.data.title ?? undefined)
    })
})

$(document).on('click', '[data-type="cart_page_detach"]', function () {
    let id = $(this).data('id')

    axios.post('/catalog/cart/detach', {id}).then((response) => {
        $(this).parents('tr').remove()
        $('.dropdown-cart-count').html(response.data.cartContProducts)
        $('.dropdown-cart-sum').html(response.data.cartSumProducts)
        toastr.success(response.data.message ?? '', response.data.title ?? undefined)
    })
})

$(document).on('input', '[data-type="cart_change_amount"]', function () {
    alert(2)
})

$('form#checkout').on('submit', function (event) {
    event.preventDefault()
    let button = $(this).find('#sendCheckoutForm')

    if (button.attr('disabled')) {
        return
    }

    button.attr('disabled', true)

    let data = new FormData(this)

    axios.post('/checkout', data).then((response) => {
        window.location.href = response.data.redirectLink
    }).catch((response) => {
        button.attr('disabled', false)
        alert('Помилка')
    })
})

$('[name=delivery]').on('change', function () {
    let form = $(this).val()

    axios.post('/catalog/order/order_type_form', {form}).then((response) => {
        $('#delivery-container').html(response.data)
    })
})
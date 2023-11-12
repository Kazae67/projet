document.addEventListener('DOMContentLoaded', function() {
    var stripe = Stripe('pk_test_51OAZApEONNRmxI8sZ0xkvESVlByCAphUxSzTDVx3ZW4i7BETvtvbrKhjrWcXPqRz0DxDscnhJe60Fo1rLCnxr7yK00soiIHaJ6');
    var elements = stripe.elements();
    var card = elements.create('card');
    card.mount('#card-element');

    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        stripe.createToken(card).then(function(result) {
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', result.token.id);
                form.appendChild(hiddenInput);
                form.submit();
            }
        });
    });
});

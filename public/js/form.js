document.addEventListener('DOMContentLoaded', function() {
    const collectionHolder = document.querySelector('#addresses-fields-list');

    // Ajout du bouton 'Add an address'
    const addAddressButton = document.createElement('button');
    addAddressButton.type = 'button';
    addAddressButton.innerText = 'Add an address';
    collectionHolder.appendChild(addAddressButton);

    addAddressButton.addEventListener('click', function() {
        const prototype = collectionHolder.dataset.prototype;
        const index = collectionHolder.dataset.index;
        const newForm = prototype.replace(/__name__/g, index);
        collectionHolder.dataset.index = parseInt(index, 10) + 1;

        const formFragment = document.createRange().createContextualFragment(newForm);
        const newFormElem = formFragment.firstElementChild;
        collectionHolder.insertBefore(newFormElem, addAddressButton);
    });

    collectionHolder.addEventListener('change', function(e) {
        if (e.target.matches('.default-billing-radio, .default-delivery-radio')) {
            const selectedType = e.target.classList.contains('default-billing-radio') ? 'billing' : 'delivery';
            const radioName = e.target.name;

            // Décocher les autres radios du même type
            collectionHolder.querySelectorAll(`.${selectedType}-radio`).forEach(function(radio) {
                if (radio.name !== radioName) {
                    radio.checked = false;
                }
            });
        }
    });
});

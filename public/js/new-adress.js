document.addEventListener('DOMContentLoaded', function() {
    const addressRadios = document.querySelectorAll('input[name="order_confirmation_form[selectedAddress]"]');
    const newAddressForm = document.getElementById('new_address_form');
    const nameAndConfirmFields = document.getElementById('name_and_confirm_fields');

    // Fonction pour afficher/masquer le formulaire d'adresse pour une nouvelle adresse
    function toggleAddressForm() {
        const newAddressSelected = document.querySelector('input[value="new_address"]:checked') !== null;
        newAddressForm.style.display = newAddressSelected ? 'block' : 'none';
    }

    // Fonction pour afficher/masquer les champs "First Name", "Last Name", et "Confirm Order"
    function toggleNameAndConfirmFields() {
        const selectedAddress = document.querySelector('input[name="order_confirmation_form[selectedAddress]"]:checked');
        if (selectedAddress && selectedAddress.value !== 'new_address') {
            nameAndConfirmFields.style.display = 'block';
        } else {
            nameAndConfirmFields.style.display = 'none';
        }
    }

    // Ajouter l'événement de changement sur tous les boutons radio d'adresse
    addressRadios.forEach(function(radio) {
        radio.addEventListener('change', function() {
            toggleAddressForm();
            toggleNameAndConfirmFields();
        });
    });

    // Appeler les fonctions initialement pour configurer l'état correct
    toggleAddressForm();
    toggleNameAndConfirmFields();
});

document.addEventListener('DOMContentLoaded', function() {
    const addressRadios = document.querySelectorAll('input[name="order_confirmation_form[selectedAddress]"]');
    const newAddressForm = document.getElementById('new_address_form');

    // Fonction pour afficher/masquer le formulaire d'adresse
    const toggleAddressForm = function() {
        // Affiche le formulaire seulement si "new address" est sélectionné
        const newAddressSelected = document.querySelector('.new-address-radio:checked');
        newAddressForm.style.display = newAddressSelected ? 'block' : 'none';
    };

    // Ajouter l'événement de changement sur tous les boutons radio
    addressRadios.forEach(radio => radio.addEventListener('change', toggleAddressForm));

    // Appeler la fonction initialement pour configurer l'état correct
    toggleAddressForm();
});

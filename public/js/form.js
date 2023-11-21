document.addEventListener('DOMContentLoaded', function() {
    const addressRadios = document.querySelectorAll('input[name="order_confirmation_form[selectedAddress]"]');
    const newAddressForm = document.getElementById('new_address_form');

    // Fonction pour afficher/masquer le formulaire d'adresse
    const toggleAddressForm = function() {
        const newAddressRadio = document.getElementById('order_confirmation_form_selectedAddress_2');
        newAddressForm.style.display = newAddressRadio && newAddressRadio.checked ? 'block' : 'none';
    };

    // Ajouter l'événement de changement sur tous les boutons radio
    addressRadios.forEach(radio => radio.addEventListener('change', toggleAddressForm));

    // Appeler la fonction initialement pour configurer l'état correct
    toggleAddressForm();
});

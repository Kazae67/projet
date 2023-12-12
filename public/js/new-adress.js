document.addEventListener('DOMContentLoaded', function() {
    const newAddressForm = document.getElementById('new_address_form');
    const newAddressRadio = document.querySelector('.new-address-radio');

    // Fonction pour afficher/masquer le formulaire d'adresse
    const toggleAddressForm = function() {
        if (newAddressRadio) {
            newAddressForm.style.display = newAddressRadio.checked ? 'block' : 'none';
        }
    };

    // Ajouter l'événement de changement sur le bouton radio pour la nouvelle adresse
    if (newAddressRadio) {
        newAddressRadio.addEventListener('change', toggleAddressForm);
    }

    // Appeler la fonction initialement pour configurer l'état correct
    toggleAddressForm();
});

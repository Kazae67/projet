document.addEventListener('DOMContentLoaded', function() {
    const newAddressRadio = document.getElementById('new_address');
    const newAddressForm = document.getElementById('new_address_form');

    // Fonction pour afficher/masquer le formulaire d'adresse
    const toggleAddressForm = function() {
        if (newAddressRadio.checked) {
            newAddressForm.style.display = 'block';
        } else {
            newAddressForm.style.display = 'none';
        }
    };

    // Ajouter l'événement de changement sur le bouton radio
    newAddressRadio.addEventListener('change', toggleAddressForm);

    // Appeler la fonction initialement pour configurer l'état correct
    toggleAddressForm();
});

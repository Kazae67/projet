document.addEventListener('DOMContentLoaded', function() {
  const collectionHolder = document.querySelector('#addresses-fields-list');

  // Ajout du bouton 'Add an address'
  const addAddressButton = document.createElement('button');
  addAddressButton.type = 'button';
  addAddressButton.innerText = 'Add an address';
  collectionHolder.appendChild(addAddressButton);

  function addFormToCollection(e) {
      const prototype = collectionHolder.dataset.prototype;
      const index = collectionHolder.dataset.index;
      const newForm = prototype.replace(/__name__/g, index);
      collectionHolder.dataset.index = parseInt(index, 10) + 1;

      const formFragment = document.createRange().createContextualFragment(newForm);
      const newFormElem = formFragment.firstElementChild;
      collectionHolder.insertBefore(newFormElem, addAddressButton);

      // Appliquer la logique de visibilité et de comportement aux checkboxes du nouveau formulaire
      updateCheckboxVisibilityAndBehavior(newFormElem);
  }

  addAddressButton.addEventListener('click', addFormToCollection);

  function updateCheckboxVisibilityAndBehavior(newFormElement) {
      const selectElement = newFormElement.querySelector('.address-type-select');
      const defaultBillingCheckbox = newFormElement.querySelector('.default-billing-checkbox');
      const defaultDeliveryCheckbox = newFormElement.querySelector('.default-delivery-checkbox');

      selectElement.addEventListener('change', function(e) {
          updateCheckboxDisplay(defaultBillingCheckbox, defaultDeliveryCheckbox, e.target.value);
      });

      // Initialiser la visibilité en fonction de la sélection actuelle
      updateCheckboxDisplay(defaultBillingCheckbox, defaultDeliveryCheckbox, selectElement.value);

      // Gérer les interactions avec la checkbox 'billing'
      defaultBillingCheckbox.addEventListener('change', handleCheckboxChange);

      // Gérer les interactions avec la checkbox 'delivery'
      defaultDeliveryCheckbox.addEventListener('change', handleCheckboxChange);
  }

  function handleCheckboxChange() {
      const currentCheckbox = this;
      const isChecked = currentCheckbox.checked;
      const checkboxClass = currentCheckbox.classList.contains('default-billing-checkbox') 
                          ? '.default-billing-checkbox' 
                          : '.default-delivery-checkbox';

      // Si la checkbox actuelle est cochée, décocher les autres checkboxes de même classe
      if (isChecked) {
          const allSameClassCheckboxes = document.querySelectorAll(checkboxClass);
          allSameClassCheckboxes.forEach(function(checkbox) {
              if (checkbox !== currentCheckbox) {
                  checkbox.checked = false;
              }
          });
      }
  }

  function updateCheckboxDisplay(billingCheckbox, deliveryCheckbox, addressType) {
      // Afficher ou masquer les checkboxes en fonction du type d'adresse
      if (addressType === 'billing') {
          billingCheckbox.parentNode.style.display = 'block'; // Assurez-vous que le parentNode soit le div qui entoure la checkbox
          deliveryCheckbox.parentNode.style.display = 'none';
      } else if (addressType === 'delivery') {
          billingCheckbox.parentNode.style.display = 'none';
          deliveryCheckbox.parentNode.style.display = 'block';
      } else {
          billingCheckbox.parentNode.style.display = 'none';
          deliveryCheckbox.parentNode.style.display = 'none';
      }

      // Décocher les checkboxes si leur type d'adresse ne correspond pas
      if (billingCheckbox.parentNode.style.display === 'none') {
          billingCheckbox.checked = false;
      }
      if (deliveryCheckbox.parentNode.style.display === 'none') {
          deliveryCheckbox.checked = false;
      }
  }

  // Appliquer initialement la logique de visibilité à tous les formulaires d'adresse
  document.querySelectorAll('.address-item').forEach(updateCheckboxVisibilityAndBehavior);

});

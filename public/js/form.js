document.addEventListener('DOMContentLoaded', function() {
    const collectionHolder = document.querySelector('#addresses-fields-list');
    const addAddressButton = document.createElement('button');
    addAddressButton.innerText = 'Add an address';
    collectionHolder.appendChild(addAddressButton);
  
    addAddressButton.addEventListener('click', function(e) {
      const prototype = collectionHolder.dataset.prototype;
      const index = collectionHolder.dataset.index || collectionHolder.children.length;
      const newForm = prototype.replace(/__name__/g, index);
  
      collectionHolder.dataset.index = index + 1;
      const formFragment = document.createRange().createContextualFragment(newForm);
      collectionHolder.insertBefore(formFragment, addAddressButton);
    });
  });
  
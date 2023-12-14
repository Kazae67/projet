document.addEventListener('DOMContentLoaded', function () {
    var uploadButton = document.querySelector('.btn-upload');
    var deleteButton = document.querySelector('.btn-delete-picture');

    if (uploadButton) {
        uploadButton.addEventListener('click', function (event) {
            // Empêche le comportement par défaut si aucune image n'est sélectionnée
            var imageInput = document.querySelector('input[type="file"]');
            if (!imageInput.files.length) {
                event.preventDefault();
                alert('Please select an image to upload.');
            }
        });
    }

    if (deleteButton) {
        deleteButton.addEventListener('click', function (event) {
            // Demande confirmation avant de supprimer l'image
            var confirmation = confirm('Are you sure you want to delete your profile picture?');
            if (!confirmation) {
                event.preventDefault();
            }
        });
    }
});

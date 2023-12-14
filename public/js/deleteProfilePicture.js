document.addEventListener('DOMContentLoaded', function () {
    var uploadButton = document.querySelector('.btn-upload');

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
});

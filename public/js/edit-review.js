function editReview(reviewId) {
    // Masquer le commentaire et afficher le formulaire
    document.getElementById('review-comment-' + reviewId).style.display = 'none';
    document.getElementById('edit-review-form-' + reviewId).style.display = 'block';
}

function submitReviewEditForm(event, reviewId) {
    event.preventDefault();

    // Récupérer la nouvelle valeur du commentaire
    var updatedComment = document.getElementById('edit-comment-' + reviewId).value;

    // Préparer les données à envoyer
    var data = new FormData();
    data.append('comment', updatedComment);

    // Envoyer la requête AJAX
    fetch('/review/update/' + reviewId, {
        method: 'POST',
        body: data,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Mettre à jour l'affichage de la revue
        document.getElementById('review-comment-' + reviewId).innerHTML = updatedComment;
        document.getElementById('review-comment-' + reviewId).style.display = 'block';
        document.getElementById('edit-review-form-' + reviewId).style.display = 'none';
    })
    .catch(error => console.error('Error:', error));
}

document.addEventListener('DOMContentLoaded', () => {
    var addButton = document.getElementById('add-review-button');
    if(addButton) {
        addButton.addEventListener('click', function() {
            document.getElementById('review-form-container').style.display = 'block';
            this.style.display = 'none';
        });
    }
});
function editReview(reviewId) {
    // Masquer le commentaire et afficher le formulaire
    document.getElementById('review-comment-' + reviewId).style.display = 'none';
    document.getElementById('edit-review-form-' + reviewId).style.display = 'block';
}

function submitReviewEditForm(event, reviewId) {
    event.preventDefault();

    // Récupérer les nouvelles valeurs du titre, de la note et du commentaire
    var updatedTitle = document.getElementById('edit-title-' + reviewId).value;
    var updatedRating = document.getElementById('edit-rating-' + reviewId).value;
    var updatedComment = document.getElementById('edit-comment-' + reviewId).value;

    // Préparer les données à envoyer
    var data = new FormData();
    data.append('title', updatedTitle);
    data.append('rating', updatedRating);
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
        document.getElementById('review-title-' + reviewId).innerText = updatedTitle; // Mise à jour du titre
        document.getElementById('review-rating-' + reviewId).innerText = 'Rating: ' + updatedRating + '/5'; // Mise à jour de la note
        document.getElementById('review-comment-' + reviewId).innerText = updatedComment;
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
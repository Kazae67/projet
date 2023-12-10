function editReview(reviewId) {
    // Masque le commentaire et affiche le formulaire
    document.getElementById('review-comment-' + reviewId).style.display = 'none';
    document.getElementById('edit-review-form-' + reviewId).style.display = 'block';
}

function updateRatingDisplay(reviewId, updatedRating) {
    var ratingContainer = document.getElementById('review-rating-' + reviewId);
    ratingContainer.innerHTML = '';
    ratingContainer.setAttribute('data-rating', updatedRating); // data-rating (pour les couleurs des étoiles)

    for (var i = 1; i <= 5; i++) {
        var star = document.createElement('span');
        star.className = 'fa fa-star';
        if (i <= updatedRating) {
            star.classList.add('checked');
        }
        ratingContainer.appendChild(star);

        // espace blanc après chaque étoile
        ratingContainer.appendChild(document.createTextNode(' '));
    }
}

function submitReviewEditForm(event, reviewId) {
    event.preventDefault();

    // Récupère les nouvelles valeurs du titre, de la note et du commentaire
    var updatedTitle = document.getElementById('edit-title-' + reviewId).value;
    var updatedRatingSelect = document.getElementById('edit-rating-' + reviewId);
    var updatedRating = updatedRatingSelect.options[updatedRatingSelect.selectedIndex].value;
    var updatedComment = document.getElementById('edit-comment-' + reviewId).value;

    // Prépare les données à envoyer
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
        // Met à jour l'affichage du titre de la revue
        var titleElement = document.getElementById('review-title-' + reviewId);
        titleElement.innerHTML = ''; // Efface le contenu existant

        var strongElement = document.createElement('strong');
        strongElement.textContent = 'Title: ';
        titleElement.appendChild(strongElement);

        titleElement.append(updatedTitle);

        // Met à jour des étoiles et du commentaire
        updateRatingDisplay(reviewId, updatedRating);
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

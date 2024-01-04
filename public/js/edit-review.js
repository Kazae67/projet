function editReview(reviewId) {
    // Masque le commentaire et affiche le formulaire
    document.getElementById('review-comment-' + reviewId).style.display = 'none';
    document.getElementById('edit-review-form-' + reviewId).style.display = 'block';
}

function updateRatingDisplay(reviewId, updatedRating) {
    var ratingContainer = document.getElementById('review-rating-' + reviewId);
    ratingContainer.innerHTML = ''; // Réinitialise le contenu de l'étoile
    ratingContainer.setAttribute('data-rating', updatedRating);  // Mise à jour de la note (étoile)

    // Boucle pour créer et afficher les étoiles
    for (var i = 1; i <= 5; i++) {
        var star = document.createElement('span');
        star.className = 'fa fa-star';
        if (i <= updatedRating) {
            star.classList.add('checked'); // Ajout de la classe 'checked' pour les étoiles actives
        }
        ratingContainer.appendChild(star);

        // espace blanc après chaque étoile
        ratingContainer.appendChild(document.createTextNode(' ')); // Ajout d'un espace blanc après chaque étoile
    }
}

function submitReviewEditForm(event, reviewId) {
    event.preventDefault(); // Empêche le comportement de soumission par défaut du formulaire

    // Récupération des valeurs mises à jour dans le formulaire
    var updatedTitle = document.getElementById('edit-title-' + reviewId).value;
    var updatedRatingSelect = document.getElementById('edit-rating-' + reviewId);
    var updatedRating = updatedRatingSelect.options[updatedRatingSelect.selectedIndex].value;
    var updatedComment = document.getElementById('edit-comment-' + reviewId).value;

    // Création d'un objet FormData pour encapsuler les données du formulaire
    var data = new FormData();
    data.append('title', updatedTitle); // Ajout du titre mis à jour au FormData
    data.append('rating', updatedRating); // Ajout de la note mise à jour
    data.append('comment', updatedComment); // Ajout du commentaire mis à jour

    // Configuration et envoi de la requête AJAX
    fetch('/review/update/' + reviewId, {
        method: 'POST', // Méthode HTTP POST pour l'envoi des données
        body: data, // Corps de la requête contenant les données du formulaire
        headers: {'X-Requested-With': 'XMLHttpRequest'} // En-tête pour identifier la requête comme AJAX
    })
    .then(response => {
        // Gestion des réponses non réussies
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json(); // Conversion de la réponse en JSON
    })
    .then(data => {
        // Mise à jour de l'affichage de l'avis après la soumission réussie
        document.getElementById('review-title-' + reviewId).innerHTML = '<strong>Title: </strong>' + updatedTitle;
        updateRatingDisplay(reviewId, updatedRating); // Mise à jour des étoiles
        document.getElementById('review-comment-' + reviewId).innerText = updatedComment;
        // Réinitialisation de l'affichage pour montrer le commentaire et cacher le formulaire
        document.getElementById('review-comment-' + reviewId).style.display = 'block';
        document.getElementById('edit-review-form-' + reviewId).style.display = 'none';
    })
    .catch(error => {
        // Gestion des erreurs
        console.error('Error:', error);
    });
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

document.addEventListener('DOMContentLoaded', function() {
    const anonymizeButton = document.getElementById("anonymizeButton");
    const passwordConfirmModal = document.getElementById("passwordConfirmModal");
    const confirmPasswordForm = document.getElementById("confirmPasswordForm");
    const confirmPasswordUrl = passwordConfirmModal.getAttribute('data-confirm-password-url');
    const csrfToken = passwordConfirmModal.getAttribute('data-csrf-token');

    anonymizeButton.addEventListener("click", function () {
        passwordConfirmModal.style.display = "block";
    });

    confirmPasswordForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const password = confirmPasswordForm.querySelector('input[name="password"]').value;

        fetch(confirmPasswordUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ password: password })
        }).then(response => response.json()).then(data => {
            if (data.success) {
                alert('Account anonymized successfully.');
                window.location.href = data.redirectUrl;
            } else {
                alert('Incorrect password.');
            }
        });
    });
});

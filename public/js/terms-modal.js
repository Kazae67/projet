document.addEventListener('DOMContentLoaded', function() {
    var termsModal = document.getElementById('termsModal');
    var acceptTermsBtn = document.getElementById('acceptTermsBtn');
    var declineTermsBtn = document.getElementById('declineTermsBtn');
    var termsContent = document.querySelector('.terms-content');

    document.querySelector('[name="registration_form[agreeTerms]"]').addEventListener('change', function(e) {
        if (e.target.checked) {
            termsModal.style.display = 'block';
            e.target.checked = false;
        }
    });

    termsContent.addEventListener('scroll', function() {
        var scrollPosition = termsContent.scrollTop + termsContent.clientHeight;
        var bottomPosition = termsContent.scrollHeight - 1;

        if (scrollPosition >= bottomPosition) {
            acceptTermsBtn.disabled = false;
        }
    });

    acceptTermsBtn.addEventListener('click', function() {
        document.querySelector('[name="registration_form[agreeTerms]"]').checked = true;
        termsModal.style.display = 'none';
    });

    declineTermsBtn.addEventListener('click', function() {
        termsModal.style.display = 'none';
    });
});
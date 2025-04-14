document.addEventListener('DOMContentLoaded', function() {
    const signupForm = document.getElementById('signupForm');
    if (signupForm) {
        signupForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Envoyer les données au serveur
            fetch('../../../controller/signup.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Afficher le message de succès et fermer la modal
                    alert('Inscription réussie !');
                    $('#signupModal').modal('hide');
                    signupForm.reset();
                } else {
                    // Afficher le message d'erreur
                    alert(data.message || 'Erreur lors de l\'inscription');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de l\'inscription');
            });
        });
    }
});

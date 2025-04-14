document.addEventListener('DOMContentLoaded', function() {
    const profileLoginForm = document.getElementById('profileLoginForm');
    const profileUpdateForm = document.getElementById('profileUpdateForm');
    const cancelUpdateBtn = document.getElementById('cancelUpdate');
    const deleteAccountBtn = document.getElementById('deleteAccount');
    
    let currentEmail = '';
    let currentPassword = '';

    if (profileLoginForm) {
        profileLoginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            currentEmail = document.getElementById('profileEmail').value;
            currentPassword = document.getElementById('profilePassword').value;
            
            const formData = new FormData();
            formData.append('email', currentEmail);
            formData.append('password', currentPassword);
            
            console.log('Tentative de connexion avec - email:', currentEmail, 'password:', currentPassword);
            
            // Récupérer les informations de l'utilisateur
            fetch('../../../controller/getUserInfo.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(text => {
                console.log('Réponse brute du serveur:', text);
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Erreur de parsing JSON:', e);
                    throw new Error('Réponse invalide du serveur');
                }
            })
            .then(data => {
                console.log('Données parsées:', data);
                if (data.success) {
                    // Remplir le formulaire de modification
                    document.getElementById('updateFirstname').value = data.user.firstname;
                    document.getElementById('updateLastname').value = data.user.lastname;
                    document.getElementById('updatePhone').value = data.user.phone;
                    
                    // Sélectionner le rôle
                    const roleRadio = document.querySelector(`input[name="role"][value="${data.user.role}"]`);
                    if (roleRadio) roleRadio.checked = true;

                    // Cacher le formulaire de connexion et afficher le formulaire de modification
                    profileLoginForm.style.display = 'none';
                    profileUpdateForm.style.display = 'block';
                } else {
                    alert(data.message || 'Utilisateur non trouvé');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de la récupération des informations');
            });
        });
    }

    if (profileUpdateForm) {
        profileUpdateForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('email', currentEmail);
            formData.append('password', currentPassword);
            
            // Envoyer les modifications au serveur
            fetch('../../../controller/updateUser.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Modifications enregistrées avec succès !');
                    $('#profileModal').modal('hide');
                    resetForms();
                } else {
                    alert(data.message || 'Erreur lors de la mise à jour');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de la mise à jour');
            });
        });
    }

    if (cancelUpdateBtn) {
        cancelUpdateBtn.addEventListener('click', function() {
            resetForms();
        });
    }

    // Réinitialiser les formulaires
    function resetForms() {
        if (profileLoginForm) {
            profileLoginForm.reset();
            profileLoginForm.style.display = 'block';
        }
        if (profileUpdateForm) {
            profileUpdateForm.reset();
            profileUpdateForm.style.display = 'none';
        }
        currentEmail = '';
        currentPassword = '';
    }

    // Réinitialiser les formulaires quand la modal est fermée
    $('#profileModal').on('hidden.bs.modal', function () {
        resetForms();
    });
});

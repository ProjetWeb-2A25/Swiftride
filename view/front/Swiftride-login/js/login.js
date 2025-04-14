$(document).ready(function () {
    // Gestionnaire de clic pour le bouton de suppression de compte
    $('#deleteAccount').on('click', function() {
        Swal.fire({
            title: 'Delete Account',
            text: 'Are you sure you want to delete your account? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const email = $('#profileEmail').val();

                $.ajax({
                    type: 'POST',
                    url: '../../../controller/deleteAccount.php',
                    data: { email: email },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Your account has been deleted.',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.href = 'index.html';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Failed to delete account'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while deleting your account'
                        });
                    }
                });
            }
        });
    });
    console.log('Document ready');
    
    // Log tous les champs de formulaire sur la page
    console.log('=== État initial des formulaires ===');
    console.log('Formulaire de login:', $('#loginForm').length);
    console.log('Tous les inputs:', $('input').length);
    $('input').each(function() {
        console.log('Input:', {
            name: $(this).attr('name'),
            type: $(this).attr('type'),
            value: $(this).val()
        });
    });

    $('#loginForm').on('submit', function (e) {
        e.preventDefault();
        console.log('=== Début de la soumission du formulaire ===');

        // Récupérer tous les champs du formulaire
        const formData = {};
        const $form = $(this);

        // Log le formulaire complet
        console.log('Formulaire trouvé:', $form.length > 0);
        console.log('Action du formulaire:', $form.attr('action'));
        console.log('Méthode du formulaire:', $form.attr('method'));

        // Récupérer tous les champs
        $form.find('input').each(function() {
            const $input = $(this);
            const name = $input.attr('name');
            const value = $input.val();
            if (name) {
                formData[name] = value;
                console.log(`Champ ${name}:`, {
                    value: name.includes('password') ? '***' : value,
                    type: $input.attr('type'),
                    required: $input.prop('required')
                });
            }
        });

        // Vérifier spécifiquement les champs email et mot de passe
        const email = formData['email'];
        const password = formData['password'];

        // Vérifier les valeurs
        if (!email || !password) {
            console.log('Validation échouée:', {
                emailPresent: !!email,
                passwordPresent: !!password
            });
            alert('Veuillez remplir tous les champs.');
            return false;
        }

        // Préparer les données finales
        const finalData = {
            email: email,
            password: password
        };

        console.log('Données prêtes à envoyer:', {
            mail: email,
            'mot de passe': '***'
        });

        // Envoyer la requête
        console.log('Envoi de la requête AJAX...');
        console.log('=== Envoi de la requête AJAX ===');
        const formDataObj = new FormData();
        formDataObj.append('email', email);
        formDataObj.append('password', password);

        console.log('FormData contenu:');
        for (let pair of formDataObj.entries()) {
            console.log(pair[0] + ': ' + (pair[0] === 'password' ? '***' : pair[1]));
        }

        $.ajax({
            type: 'POST',
            url: '../../../controller/login.php',
            data: formDataObj,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log('Réponse brute reçue:', response);
                console.log('Type de réponse:', typeof response);
                
                let data;
                try {
                    if (typeof response === 'string') {
                        console.log('Parsing de la réponse string...');
                        data = JSON.parse(response);
                    } else {
                        console.log('Utilisation de la réponse comme objet...');
                        data = response;
                    }

                    console.log('Données reçues:', data);

                    if (data.success) {
                        console.log('Connexion réussie, données utilisateur:', data.user);
                        const role = (data.user.role || '').toLowerCase().trim();
                        console.log('Rôle détecté:', role);
                        
                        switch (role) {
                            case 'conducteur':
                                console.log('Redirection vers welcome.html');
                                window.location.href = '../welcome.html';
                                break;
                            case 'passager':
                                console.log('Redirection vers goodbye.html');
                                window.location.href = '../goodbye.html';
                                break;
                            default:
                                console.log('Rôle non reconnu:', role);
                                alert('Rôle non reconnu: ' + role);
                        }
                    } else {
                        alert(data.message || 'Erreur de connexion');
                    }
                } catch (e) {
                    console.error('Erreur de parsing JSON:', e);
                    console.error('Réponse brute:', response);
                    alert('Erreur lors du traitement de la réponse');
                }
            },
            error: function (xhr, status, error) {
                console.error('Erreur AJAX:', error);
                console.error('Status:', status);
                console.error('Réponse:', xhr.responseText);
                alert('Erreur de connexion au serveur');
            }
        });
    });
});

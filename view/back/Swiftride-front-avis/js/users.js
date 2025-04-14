document.addEventListener('DOMContentLoaded', function () {
    // Fonction utilisée dans les deux cas
    function fetchUsers(callback) {
        console.log('Fetching users...');
        fetch('/Projet-web-2a25/controller/getUsers.php')
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error ${response.status}`);
                return response.json();
            })
            .then(users => {
                console.log('Users received:', users);
                if (callback) callback(users);
            })
            .catch(error => {
                console.error('Error fetching users:', error);
            });
    }

    // === DROPDOWN UTILISATEURS ===
    const dropdownTrigger = document.getElementById('userDropdown');
    const userList = document.getElementById('userList');

    if (dropdownTrigger && userList) {
        const dropdownParent = dropdownTrigger.closest('.dropdown');

        dropdownParent.addEventListener('show.bs.dropdown', function () {
            fetchUsers(users => {
                const items = userList.querySelectorAll('.dropdown-item');
                items.forEach(item => item.remove());

                users.forEach(user => {
                    const li = document.createElement('li');
                    const item = document.createElement('a');
                    item.href = '#';
                    item.className = 'dropdown-item';
                    item.innerHTML = `
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-circle me-2"></i>
                            <div>
                                <div>${user.nom} ${user.prenom}</div>
                                <small class="text-muted">${user.mail}</small>
                            </div>
                        </div>
                    `;
                    li.appendChild(item);
                    userList.appendChild(li);
                });
            });
        });
    }

    // === TABLEAU DES UTILISATEURS ===
    const manageUsersBtn = document.getElementById('manageUsersBtn');
    const userTableSection = document.getElementById('userTableSection');
    const userTableBody = document.getElementById('userTableBody');

    if (manageUsersBtn && userTableSection && userTableBody) {
        manageUsersBtn.addEventListener('click', () => {
            fetchUsers(users => {
                userTableBody.innerHTML = '';

                users.forEach(user => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${user.nom}</td>
                        <td>${user.prenom}</td>
                        <td>${user.mail}</td>
                    `;
                    userTableBody.appendChild(row);
                });

                userTableSection.style.display = 'block';
            });
        });
    }
});

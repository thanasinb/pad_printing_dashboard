document.addEventListener('DOMContentLoaded', (event) => {
    document.querySelectorAll('.sidenav-menu a.nav-link').forEach((item) => {
        item.addEventListener('click', (event) => {
            let menuName = item.textContent.trim();
            logUserAction(menuName);
        });
    });
});

function logUserAction(action) {
    fetch('log_user_action.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ action: action })
    })
        .then(response => response.json())
        .then(data => console.log('Success:', data))
        .catch((error) => {
            console.error('Error:', error);
        });
}
document.addEventListener('DOMContentLoaded', (event) => {
    document.querySelectorAll('.sidenav-menu a.nav-link').forEach((item) => {
        item.addEventListener('click', (event) => {
            let menuName = item.textContent.trim();
            logUserAction(menuName);
        });
    });
});

function logUserAction(action) {
    fetch('log_user_action.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ action: action })
    })
        .then(response => response.json())
        .then(data => console.log('Success:', data))
        .catch((error) => {
            console.error('Error:', error);
        });
}

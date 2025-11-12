// Auto-hide alerts after 5 seconds
window.addEventListener('DOMContentLoaded', () => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});

// Confirm before canceling or deleting
function confirmAction(message = "Are you sure?") {
    return confirm(message);
}

// Optional: Toggle sidebar/menu on mobile
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('sidebar');
    if (toggle && menu) {
        toggle.addEventListener('click', () => {
            menu.classList.toggle('d-none');
        });
    }
});

// Optional: Filter table rows by input (live search)
function filterTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const filter = input.value.toLowerCase();
    const table = document.getElementById(tableId);
    const rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName("td");
        let match = false;
        for (let cell of cells) {
            if (cell.textContent.toLowerCase().indexOf(filter) > -1) {
                match = true;
                break;
            }
        }
        rows[i].style.display = match ? "" : "none";
    }
}

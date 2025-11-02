function toggleMenu() {
    const menu = document.getElementById('dropdownMenu');
    menu.classList.toggle('hidden');
}

// Cerrar el menú si se hace clic fuera de él
document.addEventListener('click', function(event) {
    const menu = document.getElementById('dropdownMenu');
    const menuButton = document.querySelector('.icon-btn[aria-label="Menú"]');
    
    if (!menu.contains(event.target) && !menuButton.contains(event.target)) {
        menu.classList.add('hidden');
    }
});
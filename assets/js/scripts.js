document.addEventListener('DOMContentLoaded', function() {
    // Fechar dropdown ao clicar fora
    document.addEventListener('click', function(e) {
        const dropdowns = document.querySelectorAll('.user-dropdown');
        dropdowns.forEach(dropdown => {
            if (!dropdown.contains(e.target)) {
                dropdown.querySelector('.dropdown-menu').style.display = 'none';
            }
        });
    });

    // Alternar dropdown ao clicar no botão
    const userBtns = document.querySelectorAll('.user-btn');
    userBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const menu = this.nextElementSibling;
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        });
    });
});
<nav class="sidebar">
        <div class="logo-menu">
            <h2 class="logo">RacketLink</h2>
            <i class='bx bx-menu toggle-btn'></i>
        </div>

        <ul class="list">
            <li class="list-item active">
                <a href="inicio.php">
                    <i class='bx bx-grid-alt'></i>
                    <span class="link-name" style="--i:1;">Inicio</span>
                </a>
            </li>
            <li class="list-item">
                <a href="perfil.php">
                    <i class='bx bx-user-circle' ></i>
                    <span class="link-name" style="--i:2;">Perfil</span>
                 </a>
            </li>
            <li class="list-item">
                <a href="#">
                    <i class='bx bx-message-square-detail'></i>
                    <span class="link-name" style="--i:3;">Mensajes</span>
                 </a>
            </li>
            <li class="list-item">
                <a href="ajustes.php">
                    <i class='bx bx-cog' ></i>
                    <span class="link-name" style="--i:4;">Ajustes</span>
                 </a>
            </li>
            <li class="list-item">
                <a href="logout.php">
                    <i class='bx bx-log-out'></i>
                    <span class="link-name" style="--i:5;">Cerrar Sesion</span>
                 </a>
            </li>
        </ul>
    </nav>

<script>  
const sidebar = document.querySelector('.sidebar');
const toggleBtn = document.querySelector('.toggle-btn');
const links = document.querySelectorAll('.list .list-item a');

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('active');
});

links.forEach(link => {
    link.addEventListener('click', () => {
        if (sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
        }
    });
});
    
</script>
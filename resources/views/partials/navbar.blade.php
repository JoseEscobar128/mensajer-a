<!-- resources/views/partials/navbar.blade.php -->
<nav class="navbar">
    <div class="container">
        <div class="greeting">
            @auth
                <span>Hola {{ auth()->user()->name }}</span>
            @else
                <span>Hola!</span>
            @endauth
        </div>
        <ul class="nav-links">
            @auth
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="logout-button">Cerrar Sesión</button>
                    </form>
                </li>
            @endauth
        </ul>
    </div>
</nav>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: Arial, sans-serif;
    }

    .navbar {
        background-color: #007bff;
        color: white;
        padding: 10px 0; /* Reducir padding vertical para ajustar mejor */
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: fixed; /* Asegurar que esté pegado a la parte superior */
        top: 0;
        width: 100%; /* Ocupar el ancho completo */
        z-index: 1000; /* Asegurarse de que esté sobre otros elementos */
        border-bottom: 2px solid #0056b3;
    }

    .container {
        max-width: 1200px;
        width: 100%;
        margin: 0 auto;
        padding: 0 20px; /* Espaciado lateral dentro de la navbar */
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .greeting {
        font-size: 18px;
        font-weight: 500;
    }

    .nav-links {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        gap: 20px;
    }

    .nav-links li {
        display: inline;
    }

    .nav-links a {
        color: white;
        text-decoration: none;
        font-size: 16px;
        padding: 8px 12px;
        border-radius: 4px;
        transition: background-color 0.3s, color 0.3s;
    }

    .nav-links a:hover {
        background-color: #495057;
        color: #e1e1e1;
    }

    .logout-button {
        background-color: transparent;
        border: none;
        color: white;
        cursor: pointer;
        font-size: 16px;
        padding: 8px 12px;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    .logout-button:hover {
        background-color: #495057;
        color: #e1e1e1;
    }

    /* Añadir margen superior para compensar la altura de la navbar */
    body {
        padding-top: 60px; /* Ajustar este valor según la altura de la navbar */
    }
</style>

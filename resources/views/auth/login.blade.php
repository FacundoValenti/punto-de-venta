<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Sistema de venta Erica" />
    <meta name="author" content="Brito Roque, Facundo Valenti, Virginia Bolo" />
    <title>Software Erica</title>
    <link href="{{ asset('css/login.css') }}" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="login-box">
        <p>Ingrese al sistema</p>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $error }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endforeach
        @endif
        <form action="/login" method="post">
            @csrf
            <div class="user-box">
                <input required name="email" id="inputEmail" type="email" />
                <label for="inputEmail">Correo electrónico</label>
            </div>
            <div class="user-box">
                <input required name="password" id="inputPassword" type="password" />
                <label for="inputPassword">Contraseña</label>
            </div>
            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                Iniciar sesión
            </a>
        </form>
    </div>
</body>

</html>

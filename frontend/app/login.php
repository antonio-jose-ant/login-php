<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <section>
        <div>
            <form action="../../backend/auth/login.php" method="post">
                <div>
                    <label for="user">Usuario</label>
                    <input type="text" name="user" id="user">
                </div>
                <div>
                    <label for="password" >contraseña</label>
                    <input type="password" name="password" id="password">
                </div>
                <div>
                    <button>
                        enviar
                    </button>
                </div>
            </form>
        </div>
    </section>
</body>
</html>
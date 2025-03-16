<?php
session_start();

class DbFunction {
    private $conn;

    public function __construct() {
        $host = 'localhost';
        $db = 'trex_pi';
        $user = 'usuario'; // Cambia esto por tu usuario
        $pass = 'contrasena'; // Cambia esto por tu contraseña

        // Crear conexión
        $this->conn = new mysqli($host, $user, $pass, $db);

        // Verificar conexión
        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }
    }

    public function login($id, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = ? AND password = ?");
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->conn->error);
        }

        $stmt->bind_param("ss", $id, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['login'] = $id;
            return true;
        } else {
            return false;
        }
    }

    public function __destruct() {
        $this->conn->close();
    }
}

$error = ""; // Inicializar variable de error
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $obj = new DbFunction();
    $loginSuccess = $obj->login($_POST['loginid'], $_POST['password']);

    if (!$loginSuccess) {
        $error = "Error en el inicio de sesión. Verifica tu email y contraseña.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="stile3.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - REX.PI</title>
</head>
<body>
    <form action="" method="post">
        <section class="login-form">
            <input type="text" required class="letras1" name="loginid" placeholder="Email">
            <input type="password" required id="contraseña" name="password" class="letras2" placeholder="Contraseña">
            <input type="submit" class="btn" name="submit" value="Iniciar">
            <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>
            <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
        </section>
    </form>
</body>
</html>
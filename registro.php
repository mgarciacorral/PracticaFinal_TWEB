<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$title = "Registro"; 
$user_existe = false;

include 'plantillas/header.php'; 
include 'plantillas/anti-injection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'plantillas/confDB.php';

    $correo = comprobar_entrada($conn, $_POST['correo']);
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $contra = password_hash($_POST['contra'], PASSWORD_DEFAULT);

    $stmt_check = $conn->prepare("SELECT Correo FROM USUARIO WHERE Correo = ?");
    $stmt_check->bind_param("s", $correo);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $user_existe = true;
    }else{
        $stmt = $conn->prepare("INSERT INTO USUARIO(Correo, Nombre, Apellidos, Contra) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $correo, $nombre, $apellidos, $contra);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $stmt_check->close();  
    $conn->close();
}
?>

<div class=formreg>
    <h1 class=txtreg>Registro de Usuario</h1>
    <?php if ($user_existe): ?>
        <a class=logError>El correo ya estaba registrado.</a>
        <script>
            document.getElementById('correo').value = '';
            document.getElementById('nombre').value = '';
            document.getElementById('apellidos').value = '';
            document.getElementById('contra').value = '';
        </script>    
    <?php endif; ?>
    <form action="registro.php" method="post" class=formulario>
        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" required>
        <br>
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        <br>
        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos" required>
        <br>
        <label for="contraseña">Contraseña:</label>
        <input type="password" id="contra" name="contra" required>
        <br>
        <input type="submit" value="Registrarse">
    </form>
    
    <div class=nopuesreg>
        <a>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a></a>
    </div>
</div>

<?php include 'plantillas/footer.php'; ?>
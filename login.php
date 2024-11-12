<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');

$title = "Iniciar Sesión"; 
$UoCincorrectos = false;

include 'plantillas/header.php'; 
include 'plantillas/anti-injection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'plantillas/confDB.php';

    $correo = comprobar_entrada($conn, $_POST['correo']);
    $contra = password_hash($_POST['contra'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT Contra FROM USUARIO WHERE Correo = ?");
    $stmt->bind_param("s", $correo);

    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error;
    }

    $stmt->store_result();
    $stmt->bind_result($contraBD);
    $stmt->fetch();

    $stmt = $conn->prepare("SELECT Nombre FROM USUARIO WHERE Correo = ?");
    $stmt->bind_param("s", $correo);

    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error;
    }

    $stmt->store_result();
    $stmt->bind_result($nombreBD);
    $stmt->fetch();

    if (password_verify($_POST['contra'], $contraBD)) {
        session_start();
        $_SESSION['correo'] = $correo;
        $_SESSION['nombre'] = $nombreBD;
        header("Location: index.php");
        exit();
    } else {
        $UoCincorrectos = true;
    }

    $stmt->close();
    $conn->close();
}
?>

<div class=formreg>
    <h1 class=txtreg>Iniciar Sesión</h1>
    <?php if ($UoCincorrectos): ?>
        <a class=logError>Usuario o contraseña incorrectos.</a>
        <script>
            document.getElementById('correo').value = '';
            document.getElementById('contra').value = '';
        </script>    
    <?php endif; ?>
    <form action="login.php" method="post" class=formulario>
        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" required>
        <br>
        <label for="contraseña">Contraseña:</label>
        <input type="password" id="contra" name="contra" required>
        <br>
        <input type="submit" value="Iniciar Sesión">
    </form>

    <div class=nopuesreg>
        <a>¿No tienes una cuenta? <a href="registro.php">Regístrate</a></a>    
    </div>
</div>

<?php include 'plantillas/footer.php'; ?>
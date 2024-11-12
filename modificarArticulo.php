<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$title = "Modificar Articulo"; 

include 'plantillas/header.php'; 

$id = $_SESSION['idMod'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'plantillas/anti-injection.php';
    include 'plantillas/confDB.php';

    $titulo = comprobar_entrada($conn, $_POST['titulo']);
    $descripcion = comprobar_entrada($conn, $_POST['descripcion']);
    $contenido = comprobar_entrada($conn, $_POST['contenido']);
    $categoria = comprobar_entrada($conn, $_POST['categoria']);

    $sql = "UPDATE ARTICULO 
               SET Titulo = ?, Descripcion = ?, Contenido = ?, Categoria = ?
             WHERE Id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssi", $titulo, $descripcion, $contenido, $categoria, $id);
    
        if ($stmt->execute()) {
            $stmt->close();
            $sql = "UPDATE ARTICULO SET Modificando = 0 WHERE Id=$id";
            $conn->query($sql);
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }

    $conn->close();
}

include "plantillas/confDB.php";
$sql = "UPDATE ARTICULO SET Modificando = 1 WHERE Id=$id";
$conn->query($sql);

$sql = "SELECT Id, Titulo, Autor, Categoria, Descripcion, Contenido, Fecha_creacion, Modificando FROM ARTICULO WHERE Id=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$timeout = 30 * 60;
$sql = "CREATE EVENT IF NOT EXISTS reset_modificando_$id
    ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL $timeout SECOND
    DO
    UPDATE ARTICULO SET Modificando = 0 WHERE Id = $id";

if ($conn->query($sql) === FALSE) {
    echo "Error creating event: " . $conn->error;
}

$conn->close();

?>

<div class=formreg>
    <h1 class=txtreg>Edición de Articulo</h1>
    <form action="modificarArticulo.php" method="post" class=formulario>
        <label for="Titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($row['Titulo']); ?>" required>
        <br>
        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion" value="<?php echo htmlspecialchars($row['Descripcion']); ?>" required>
        <br>
        <label for="contenido">Contenido:</label>
        <textarea id="contenido" name="contenido" required><?php echo htmlspecialchars($row['Contenido']); ?></textarea>
        <br>
        <label for="Categoria">Categoria:</label>
        <select id="categoria" name="categoria" required>
            <option value="">Seleccione una categoría</option>
            <option value="Tecnologia" <?php if ($row['Categoria'] == 'Tecnologia') echo 'selected'; ?>>Tecnología</option>
            <option value="Salud" <?php if ($row['Categoria'] == 'Salud') echo 'selected'; ?>>Salud</option>
            <option value="Ciencia" <?php if ($row['Categoria'] == 'Ciencia') echo 'selected'; ?>>Ciencia</option>
            <option value="Deportes" <?php if ($row['Categoria'] == 'Deportes') echo 'selected'; ?>>Deportes</option>
            <option value="Educacion" <?php if ($row['Categoria'] == 'Educacion') echo 'selected'; ?>>Educación</option>
            <option value="Finanzas" <?php if ($row['Categoria'] == 'Finanzas') echo 'selected'; ?>>Finanzas</option>
            <option value="Moda" <?php if ($row['Categoria'] == 'Moda') echo 'selected'; ?>>Moda</option>
            <option value="Cultura" <?php if ($row['Categoria'] == 'Cultura') echo 'selected'; ?>>Cultura</option>
        </select>
        <br>
        <input type="submit" value="Confirmar Cambios">
    </form>
</div>

<?php include 'plantillas/footer.php'; ?>

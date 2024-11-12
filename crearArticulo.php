<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$title = "Crear Articulo"; 

include 'plantillas/header.php'; 
include 'plantillas/anti-injection.php';
include 'plantillas/confDB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = comprobar_entrada($conn, $_POST['titulo']);
    $descripcion = comprobar_entrada($conn, $_POST['descripcion']);
    $contenido = comprobar_entrada($conn, $_POST['contenido']);
    $categoria = comprobar_entrada($conn, $_POST['categoria']);

    $stmt = $conn->prepare("INSERT INTO ARTICULO(Titulo, Descripcion, Categoria, Contenido, Fecha_creacion, Autor) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $titulo, $descripcion, $categoria, $contenido, date("Y-m-d"), $_SESSION['correo']);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<div class=formreg>
    <h1 class=txtreg>Creacion de Articulo</h1>
    <form action="crearArticulo.php" method="post" class=formulario>
        <label for="Titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required>
        <br>
        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion" required>
        <br>
        <label for="contenido">Contenido:</label>
        <textarea type="text" id="contenido" name="contenido" required></textarea>
        <br>
        <label for="Categoria">Categoria:</label>
        <select id="categoria" name="categoria" required>
            <option value="">Seleccione una categoría</option>
            <option value="Tecnologia">Tecnología</option>
            <option value="Salud">Salud</option>
            <option value="Ciencia">Ciencia</option>
            <option value="Deportes">Deportes</option>
            <option value="Educacion">Educacion</option>
            <option value="Finanzas">Finanzas</option>
            <option value="Moda">Moda</option>
            <option value="Cultura">Cultura</option>
        </select>
        <br>
        <input type="submit" value="Crear">
    </form>
</div>

<?php include 'plantillas/footer.php'; ?>
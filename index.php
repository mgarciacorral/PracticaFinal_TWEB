<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');

$title = "Articulos";

include 'plantillas/header.php'; 
include 'plantillas/funciones.php';
include 'plantillas/anti-injection.php';
include 'plantillas/mostrarArticulo.php';

if (!isset($_SESSION['correo'])) {
    header("Location: login.php");
    exit();
}
?>

<div class='arribaLista'>
    <h2 class=titulo> Titulo </h2>
    <h2 class=autor> Autor </h2>
    <h2 class=categoria> Categoria </h2>
    <h2 class=fecha> Fecha </h2>
    <form action="index.php" method="post">
        <input type="text" placeholder="Buscar Articulo" name="text" class="input">
    </form>
</div>

<?php 
$filtro = "";

include 'plantillas/insertarArticulos.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['text'])){
        include 'plantillas/confDB.php';
        $filtro = comprobar_entrada($conn, $_POST['text']);
        $conn -> close();
        include 'plantillas/insertarArticulos.php'; 
    }else if(isset($_POST['leer'])){
        popUpArticulo($_POST['leer']);
    }else if(isset($_POST['cerrar'])){
        borrarDiv("popUp");
    }else if(isset($_POST['eliminar'])){
        include 'plantillas/confDB.php';
        $id = $_POST['eliminar'];
        $sql = "DELETE FROM ARTICULO WHERE Id=$id";
        $conn->query($sql);
        $conn->close();
        borrarDiv("popUp");
        include 'plantillas/insertarArticulos.php'; 
    }else if(isset($_POST['modificar'])){
        echo "<script>location.href='modificarArticulo.php';</script>";
        exit();
    }else if(isset($_POST['abrirColab'])){
        popUpColab($_POST['abrirColab'], 0);
    }else if(isset($_POST['cerrarColab'])){
        borrarDiv("popUp");
        popUpArticulo($_POST['cerrarColab']);
    }else if(isset($_POST['addColab'])){
        include 'plantillas/confDB.php';
        $id = $_POST['addColab'];
        $sql = "SELECT * FROM USUARIO WHERE Correo = '{$_POST['colaborar']}'";
        $result = $conn->query($sql);
        if($result->num_rows == 0){
            borrarDiv("popUp");
            popUpColab($id, 1);
            $conn->close();
        }else{
            $sql = "INSERT INTO COLABORACION (Articulo, Colaborador) VALUES ($id,'{$_POST['colaborar']}')";
            $conn->query($sql);
            $conn->close();
            borrarDiv("popUp");
            popUpColab($id, 0);
        }
    }else if(isset($_POST['eliminarColab'])){
        include 'plantillas/confDB.php';
        $sql = "DELETE FROM COLABORACION WHERE Articulo='{$_SESSION['idMod']}' AND Colaborador='{$_POST['eliminarColab']}'";
        $conn->query($sql);
        $conn->close();
        borrarDiv("popUp");
        popUpColab($_SESSION['idMod'], 0);
    }
}
?>

<?php include 'plantillas/footer.php'; ?>

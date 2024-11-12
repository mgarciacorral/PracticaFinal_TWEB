<?php
include 'plantillas/confDB.php';

borrarDiv("articulos");

if($filtro == "" || $filtro == null){
    $sql = "SELECT Id, Titulo, Autor, Categoria, Fecha_creacion FROM ARTICULO";
}else{
    $sql = "SELECT Id, Titulo, Autor, Categoria, Fecha_creacion FROM ARTICULO WHERE Titulo LIKE '%$filtro%'";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div id='articulos'>";
    while($row = $result->fetch_assoc()) {
        echo "<div class='articulo' href='articulo.php'>";
            echo "<p class=titulo>" . $row["Titulo"] . "</p>";
            echo "<p class=autor>" . $row["Autor"] . "</p>";
            echo "<p class=categoria>" . $row["Categoria"] . "</p>";
            echo "<p class=fecha>" . $row["Fecha_creacion"] . "</p>";
            $id = $row["Id"];

            echo "<form method='post' action='index.php'>";
                echo "<button type='submit' name='leer' value=$id class='learn-more')>";
                    echo "<span class='circle' aria-hidden='true'>";
                        echo "<span class='icon arrow'></span>";
                    echo "</span>";
                    echo "<span class='button-text'>Leer</span>";
                echo "</button>";
            echo "</form>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "<div class=logError>No hay articulos</div>";
}

$conn->close();
?>
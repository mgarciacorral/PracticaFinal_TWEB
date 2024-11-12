<?php
function popUpArticulo($id){
    include "plantillas/confDB.php";
    $sql = "SELECT Id, Titulo, Autor, Categoria, Descripcion, Contenido, Fecha_creacion, Modificando FROM ARTICULO WHERE Id=$id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $esColab = false;
    $sql = "SELECT * FROM COLABORACION WHERE Colaborador = '{$_SESSION['correo']}' AND Articulo = '{$row['Id']}'";
    $result = $conn->query($sql);
    $esColab = $result->num_rows > 0;

    $conn->close();

    $_SESSION['idMod'] = $row['Id'];

    echo "<div id=popUp>";
        echo "<div id=popUpTitulo>";
            echo "<h1>" . $row['Titulo'] . "</h1>";
            echo "<form method='post' action='index.php'>
                    <button class='button' type='submit' name='cerrar'>
                        <span class='X'></span>
                        <span class='Y'></span>
                    </button>
                  </form>";
        echo "</div>";
        echo "<div id=popUpMedio>";
            echo "<div id=popUpDescripcion>";
                echo '<h3>Categoria: ' . $row['Categoria'] . '</h3>';
                echo '<h3>Descripcion:</h3>';
                echo "<p>" . $row['Descripcion'] . "</p>";
            echo "</div>";
            echo "<div id=popUpContent>";
                echo "<h3>Contenido:</h3>";
                echo "<textarea readonly rows='10' cols='50'>" . $row['Contenido'] . "</textarea>";
            echo "</div>";
        echo "</div>";
        echo "<div id=popUpAbajo>";
            echo "<form method='post' action='index.php'>";
                if(($row['Autor'] == $_SESSION['correo'] || $esColab) && $row['Modificando'] == 0){
                    echo "<button class='noselect' id='noselect2' type='submit' name='modificar'>
                        <span class='text'>Modificar</span>  
                        <span class='icon'><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'><path d='M19.43 12.98c.04-.32.07-.65.07-.98s-.02-.66-.07-.98l2.11-1.65a.504.504 0 00.11-.64l-2-3.46a.5.5 0 00-.61-.22l-2.49 1a7.154 7.154 0 00-1.7-.98l-.38-2.65A.5.5 0 0012 2h-4a.5.5 0 00-.5.43l-.38 2.65c-.61.24-1.19.56-1.7.98l-2.49-1a.5.5 0 00-.61.22l-2 3.46a.5.5 0 00.11.64l2.11 1.65c-.05.32-.07.65-.07.98s.02.66.07.98L1.57 14.63a.504.504 0 00-.11.64l2 3.46c.12.21.39.29.61.22l2.49-1c.52.42 1.09.76 1.7.98l.38 2.65a.5.5 0 00.5.43h4c.26 0 .47-.19.5-.43l.38-2.65a7.154 7.154 0 001.7-.98l2.49 1c.23.09.5 0 .61-.22l2-3.46a.5.5 0 00-.11-.64l-2.11-1.65zM12 15c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z'></path></svg></span>
                    </button>";
                }
                if($row['Autor'] == $_SESSION['correo']){
                    echo "<button class='noselect' id='noselect3' type='submit' name='abrirColab' value=$id>
                        <span class='text'>Colab</span>  
                        <span class='icon'><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'><path d='M16 11c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-8 0c2.21 0 4-1.79 4-4S10.21 3 8 3 4 4.79 4 7s1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 2.04 1.97 3.45v3h6v-3c0-2.66-5.33-4-8-4z'></path></svg></span>    
                    </button>
                    <button class='noselect' type='submit' name='eliminar' value=$id>
                        <span class='text'>Eliminar</span>  
                        <span class='icon'><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'><path d='M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z'></path></svg></span>
                    </button>";
                }else{
                    echo '<a class=logError>Este articulo es de ' . $row['Autor'] . '</a>';
                }                    
            echo "</form>";
            
        echo "</div>";
    echo "</div>";
}

function popUpColab($id, $err){
    include "plantillas/confDB.php";
    $sql = "SELECT Colaborador FROM COLABORACION WHERE Articulo=$id";
    $result = $conn->query($sql);
    $conn->close();
    echo "<div id=popUp>";
        echo "<div id=popUpTitulo>";
            echo "<h1>Añadir Colaborador</h1>";
            echo "<form method='post' action='index.php'>
                    <button class='button' type='submit' name='cerrarColab' value=$id>
                        <span class='X'></span>
                        <span class='Y'></span>
                    </button>
                  </form>";
        echo "</div>";
        echo "<div id=popUpMedio>";
            echo "<form method='post' action='index.php'>";
                echo "<input type='email' name='colaborar' placeholder='Correo del Colaborador' required>";
                if($err){
                    echo "<a class=logError>Correo no registrado.</a>";
                }
                echo "<button class='noselect' id='noselect3' type='submit' name='addColab' value=$id>
                        <span class='text'>Añadir</span>  
                        <span class='icon'><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'><path d='M16 11c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-8 0c2.21 0 4-1.79 4-4S10.21 3 8 3 4 4.79 4 7s1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 2.04 1.97 3.45v3h6v-3c0-2.66-5.33-4-8-4z'></path></svg></span>    
                    </button>";
            echo "</form>";
            echo "<h3>Colaboradores:</h3>";
            echo "<form method='post' action='index.php'>";
                while($row = $result->fetch_assoc()){
                    echo "<div class=colaborador>";
                        echo "<a>" . $row['Colaborador'] . "</a>";
                        echo "<div><button class='eliminarColab' type='submit' name='eliminarColab' value='" . $row['Colaborador'] . "'>
                                <svg viewBox='0 0 448 512' class='svgIcon'><path d='M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z'></path></svg>
                            </button></div>";
                    echo "</div>";
                }
            echo "</form>";
        echo "</div>";
    echo "</div>";
}
?>
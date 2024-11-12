<?php
function borrarDiv($id){
    echo "<script>";
        echo "var articulosDiv = document.getElementById('$id');";
        echo "if (articulosDiv) {";
            echo "articulosDiv.parentNode.removeChild(articulosDiv);";
        echo "}";
    echo "</script>";
}
?>
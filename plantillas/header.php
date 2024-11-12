<?php session_start(); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Gestor De Articulos'; ?></title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <header>
        <h1><a href="index.php">Gestor de Articulos</a></h1>

        <nav>            
            <?php if (isset($_SESSION['correo'])): ?>
                <a id=bienvenida>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></a>
                <a id=botonCrearArt href="crearArticulo.php">Crear Articulo</a>
                <a href="logout.php">Cerrar sesión</a>                
            <?php endif; ?>
        </nav>
    </header>
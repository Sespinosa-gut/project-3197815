<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Publicar Producto - Swap it</title>
  <link rel="stylesheet" href="CSS/index.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
  <header class="cabecera">
    <div class="contenedor navegacion">
      <a href="Dashboard.html" class="logo">Swap it Dashboard</a>
      <nav class="enlaces-nav">
        <a href="dashboardusuario.html">Inicio</a>
        <a href="gestionar-intercambios.html">Intercambios</a>
        <a href="gestionar-ventasyfacturas.html">Ventas</a>
        <a href="gestion-inventario.html">Productos</a>
        <a href="iniciosesion.html" class="boton boton-primario">Salir</a>
      </nav>
    </div>
  </header>

  <main class="contenedor">
    <section class="hero">
      <h1>Publicar un nuevo producto</h1>
      <p>Llena los campos para agregar un producto a la venta.</p>
    </section>

    <section class="seccion">
      <form class="tarjeta" action="publicar-producto.php" method="POST" enctype="multipart/form-data"">
        <label>Nombre del producto</label><br>
        <input type="text" name="nombre" placeholder="Ej: Teclado mecánico" style="width: 100%; padding: 10px; margin-bottom: 10px;" required><br>

        <label>Descripción</label><br>
        <textarea type="text" name="descripcion" placeholder="Describe el producto..." style="width: 100%; padding: 10px; margin-bottom: 10px;"></textarea><br>


        <label>Precio</label><br>
        <input type="text" id="precio" name="precio" placeholder="Ej: 250.00" style="width: 100%; padding: 10px; margin-bottom: 10px;" required><br>
        

        <label>Imagen (URL)</label><br><br>
        <input type="file" enctype="multipart/form-data" name="imagen" accept="image/*" style="center width: 100%; padding: 10px; margin-bottom: 20px;"><br>

        <label>Categoria</label><br>
        <select name="categoria" class="form-control" style="width: 100%; padding: 10px; margin-bottom: 10px;" >
          <option value="" disabled selected hidden>Seleccione una categoría</option>
          <option value="1">Juguetes</option>
          <option value="2">Electrónica</option>
          <option value="3">Cocina</option>
          <option value="4">Salud y Belleza</option>
          <option value="5">Hogar</option>
          <option value="6">Mascotas</option>
        </select>

        <button class="boton boton-primario" type="submit" >Publicar producto</button>
      </form>
    </section>
  </main>

  <footer class="pie-pagina">
    <p>&copy; 2025 Swap it Dashboard. Todos los derechos reservados.</p>
  </footer>
</body>

<?php
include "PHP/conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizar y validar inputs
    $nombre = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);

    // Normalizar precio (quitar separadores de miles y forzar decimal)
    $precio = str_replace(['.', ','], ['', '.'], $_POST["precio"]); 
    $precio = floatval(preg_replace('/[^\d.]/', '', $precio)); 

    $categoria = intval($_POST["categoria"]);

    // Manejo de archivo (imagen)
    $imagen = '';
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == UPLOAD_ERR_OK) {
        $nombreImagen = basename($_FILES['imagen']['name']);
        $rutaDestino = "IMG/" . $nombreImagen;
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino)) {
            $imagen = $nombreImagen;
        }
    }

    // Usar prepared statements
    $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, id_categoria, imagen) 
                            VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("❌ Error en prepare: " . $conn->error);
    }

    // s = string, s = string, d = double, i = integer, s = string
    $stmt->bind_param("ssdis", $nombre, $descripcion, $precio, $categoria, $imagen);

    if ($stmt->execute()) {
        echo "✅ Producto agregado";
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    catch (error $e) {
        echo "❌ Excepción: " . $e->getMessage();
    }

    $stmt->close();
}
?>



<script>
const precioInput = document.getElementById("precio");

precioInput.addEventListener("input", function(e) {
    // Quita todo lo que no sea dígito
    let valor = e.target.value.replace(/\D/g, "");  

    // Si hay algo escrito, lo formatea con separadores
    if (valor) {
        e.target.value = new Intl.NumberFormat('es-CO').format(valor);
    } else {
        e.target.value = "";
    }
});
</script>

<script>
        document.getElementById("precio").addEventListener("input", function(e) {
            let valor = e.target.value.replace(/\D/g, ""); // quitar todo lo que no sea dígito
            if (valor) {
                e.target.value = new Intl.NumberFormat('es-CO').format(valor);
            }
        });
</script>




</html>

<?php
//importar los estilos css
echo '<link href="./estilos.css" type="text/css" rel="stylesheet">';
require_once 'ArchivoPDF.php';

$archivos = array();
//se verifica si la carpeta Archivos existe, si no, la crea
if(!is_dir('Archivos')){
    mkdir('Archivos');
}

//nombre d ela carpeta
$dir = "Archivos";

//guardado de los objetos, conforme a los datos que estan en la carpeta
foreach (scandir($dir) as $archivo) {
    if (!in_array($archivo, array(".", ".."))) {

        $ruta = $dir . "/" . $archivo;
        $propiedades = stat($ruta);

        $nombreArchivo = $archivo;
        $tamanoArchivo = $propiedades["size"];
        $carpetaDestino = "./Archivos/" . $archivo;
        
        $archivos[] = new ArchivoPDF($nombreArchivo, $tamanoArchivo, $carpetaDestino);
    }
}

//Verificamos si se ha solicitado eliminar un archivo
if(isset($_GET['eliminar'])){
    
    echo $archivoEliminar;

    $archivoEliminar = $_GET['eliminar'];
    //Verificamos si el archivo existe
    if(file_exists('Archivos/'.$archivoEliminar)){
        unlink('Archivos/'.$archivoEliminar);
    }
    header('Location: index.php'); //redireccionamos a la misma página para actualizar la lista de archivos
    exit();
}

//Verificamos si se ha solicitado Actualizar un archivo
if (isset($_POST['accion']) && $_POST['accion'] === 'actualizarNombre') {
    $nombreActual = $_POST['nombreActual'];
    $nuevoNombre = $_POST['nuevoNombre'];
    $carpetaDestinoActual = 'Archivos/' . $nombreActual;
    $carpetaDestinoNuevo = 'Archivos/' . $nuevoNombre . ".pdf";
    rename($carpetaDestinoActual, $carpetaDestinoNuevo);
    // Redireccionamos a la misma página para actualizar la lista de archivos
    header('Location: index.php');
    exit();
}

//Verificamos si se ha solicitado subir un archivo
if(isset($_FILES['pdf'])){
    if( !empty( $_FILES['pdf']['name']) && $_FILES['pdf']['type'] == "application/pdf"){

        //Obtenemos los datos del archivo
        $nombreArchivo = $_FILES['pdf']['name'];
        $tipoArchivo = $_FILES['pdf']['type'];
        $tamanoArchivo = $_FILES['pdf']['size'];
        $tempArchivo = $_FILES['pdf']['tmp_name'];
        //Definimos la carpeta de destino
        $carpetaDestino = 'Archivos/'.$nombreArchivo;
        //Movemos el archivo del directorio temporal al directorio de destino
        move_uploaded_file($tempArchivo, $carpetaDestino);
        $archivos[] = new ArchivoPDF($nombreArchivo, $tamanoArchivo, $carpetaDestino);
    }
}

//Obtenemos la lista de archivos en la carpeta "Archivos"
$archivosEnCarpeta = scandir('Archivos');
//Filtramos los archivos para que sólo muestre los PDF
$pdfs = array_filter($archivosEnCarpeta, function($archivo){
    return pathinfo($archivo, PATHINFO_EXTENSION) === 'pdf';
});


?>


<!-- Mostramos el formulario para subir el archivo -->
<form class="formulario" action="" method="POST" enctype="multipart/form-data">
    <input  accept=".pdf" type="file" name="pdf">
    <input class="css-button-3d--blue" type="submit" value="Subir archivo">
</form>
<div class="lista"> 
<!-- Mostramos la lista de archivos PDF en la carpeta "Archivos" -->
<?php if(count($archivos)): ?>
    
    <h3>Archivos PDF:</h3>
    <ul >
        
        <?php foreach($archivos as $archivo): ?>
            <div >
                <li class="item">
                    
                    <a href="Archivos/<?php echo $archivo->getNombre(); ?>"><?php echo $archivo->getNombre(); ?></a>
                    <p> Tamaño: <?php echo $archivo->getTamano(); ?>  KB </p> 
                    <p> Ruta: <?php echo $archivo->getRuta(); ?>  </p> 
                    
                    <form class="editar" action="" method="GET" >
                        <input require="" type="hidden"  name="eliminar" value="<?php echo $archivo->getNombre(); ?>">
                        <button class="css-button-3d--red" id="deleteBtn" type="submit">Eliminar</button>
                    </form>

                    <form class="editar" action="" method="POST">
                        <input type="hidden" name="nombreActual" value="<?php echo $archivo->getNombre(); ?>">
                        <input type="hidden" name="accion" value="actualizarNombre">
                        <label for="nuevoNombre">Nuevo nombre:</label>
                        <input class="inputs" type="text" name="nuevoNombre" id="nuevoNombre">
                        <button class="css-button-3d--blue" type="submit">Actualizar nombre</button>
                    </form>
                    <a style="color: green" href="Archivos/<?php echo $archivo->getNombre(); ?>" target="_blank"> Ver PDF</a>
                </li>
            </div>
            
        <?php endforeach; ?>
    </ul>
    
    <?php else: ?>
    <p>No hay archivos PDF en la carpeta "Archivos".</p>
<?php endif; ?>
</div>

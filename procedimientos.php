<?php
// Incluir el archivo de conexion a la base de datos
include 'bd/conexion.php';
//Permitir los metodos GET, POST, PUT, DELETE, OPTIONS
//Encabezados CORS para permitir todas las solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Verificar el método de solicitud
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

//***************************************METODO GET*************************************************
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // Para llamar la tabla procedimientos de la base de datos
    if (isset($_GET['id_procedimiento'])) {

        $idproced = $_GET['id_procedimiento'];

        $sentencia = $conexion->prepare("SELECT * FROM procedimientos WHERE id_procedimiento LIKE CONCAT(?,'%')");

        $sentencia->bind_param('i', $idproced);

        $sentencia->execute();

        $resultado = $sentencia->get_result();

        if ($resultado->num_rows > 0) {

            $fila = $resultado->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['data' => $fila], JSON_UNESCAPED_UNICODE);

        } else {

            echo json_encode(['error' => 'No se encontro el id.'], JSON_UNESCAPED_UNICODE);
        }

        
        $sentencia->close();

    // Si se proporciona el id de la orden en la URL
    }elseif (isset($_GET['id_orden'])) {

        $idor = $_GET['id_orden'];

        $sentencia = $conexion->prepare("SELECT * FROM procedimientos WHERE id_orden LIKE CONCAT(?,'%')");

        $sentencia->bind_param('i', $idor);

        $sentencia->execute();

        $resultado = $sentencia->get_result();

        if ($resultado->num_rows > 0) {

            $fila = $resultado->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['data' => $fila], JSON_UNESCAPED_UNICODE);

        } else {

            echo json_encode(['error' => 'No se encontro el id.'], JSON_UNESCAPED_UNICODE);
        }

        $sentencia->close();

    // Si se proporciona id de profesionales en la URL
    }elseif (isset($_GET['id_profesional'])) {

        $idpro = $_GET['id_profesional'];

        $sentencia = $conexion->prepare("SELECT * FROM procedimientos WHERE id_profesional LIKE CONCAT(?,'%')");

        $sentencia->bind_param('i', $idpro);

        $sentencia->execute();

        $resultado = $sentencia->get_result();

        if ($resultado->num_rows > 0) {

            $fila = $resultado->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['data' => $fila], JSON_UNESCAPED_UNICODE);

        } else {

            echo json_encode(['error' => 'No se encontro el id.'], JSON_UNESCAPED_UNICODE);
        }

        $sentencia->close();
    
    // Si se proporciona el grupo sanguineo en la URL
    }elseif (isset($_GET['nombre_grupo'])) {

        $grupo = $_GET['nombre_grupo'];

        $sentencia = $conexion->prepare("SELECT * FROM procedimientos WHERE nombre_grupo LIKE CONCAT(?,'%')");

        $sentencia->bind_param('s', $grupo);

        $sentencia->execute();

        $resultado = $sentencia->get_result();

        if ($resultado->num_rows > 0) {

            $fila = $resultado->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['data' => $fila], JSON_UNESCAPED_UNICODE);

        } else {

            echo json_encode(['error' => 'No se encontro el grupo sanguineo.'], JSON_UNESCAPED_UNICODE);
        }

        $sentencia->close();

    // Si se proporciona el nombre del procedimiento en la URL
    }elseif (isset($_GET['nombre_procedimiento'])) {

        $nomproced = $_GET['nombre_procedimiento'];

        $sentencia = $conexion->prepare("SELECT * FROM procedimientos WHERE nombre_procedimiento LIKE CONCAT(?,'%')");

        $sentencia->bind_param('s', $nomproced);

        $sentencia->execute();

        $resultado = $sentencia->get_result();

        if ($resultado->num_rows > 0) {

            $fila = $resultado->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['data' => $fila], JSON_UNESCAPED_UNICODE);

        } else {

            echo json_encode(['error' => 'No se encontro el numero de orden.'], JSON_UNESCAPED_UNICODE);
        }

        $sentencia->close();

    // Si no se proporciona ningun parametro en la URL
    }else {

        $resultado = $conexion->query("SELECT * FROM procedimientos");

        if ($resultado->num_rows > 0) {

            $fila = $resultado->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['data' => $fila], JSON_UNESCAPED_UNICODE);

        } else {

            echo json_encode(['error' => 'No se encontraron resultados.'], JSON_UNESCAPED_UNICODE);
        }
    }
}

//***************************************METODO POST*************************************************

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si los campos estan presentes
    if (
        isset($_POST['t1']) &&
        isset($_POST['t2']) &&
        isset($_POST['t3']) &&
        isset($_POST['t4']) 
    ) {
        
        // Obtener datos desde la solicitud POST
        $idor = $_POST['t1'];
        $idpro = $_POST['t2'];
        $grupo = $_POST['t3'];
        $nomproced = $_POST['t4'];

        // Evitar SQL Injection usando consultas preparadas
        $sentencia = $conexion->prepare("INSERT INTO procedimientos (id_orden, id_profesional, nombre_grupo, nombre_procedimiento) VALUES (?, ?, ?, ?)");
        $sentencia->bind_param('iiss', $idor, $idpro, $grupo, $nomproced);

        // Ejecutar la consulta
        if ($sentencia->execute()) {
            echo json_encode(['message' => 'Registro exitoso.'], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['error' => 'Error no se pudo guardar.'], JSON_UNESCAPED_UNICODE);
        }

        $sentencia->close();
    } else {
        echo json_encode(['error' => 'Se necesitan todos los campos para poder guardar.'], JSON_UNESCAPED_UNICODE);
    }

    $conexion->close();
}

//***************************************METODO PUT*************************************************

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT); 
    // Verificar si los campos estan presentes
    if (
        isset($_PUT['t1']) &&
        isset($_PUT['t2']) &&
        isset($_PUT['t3']) &&
        isset($_PUT['t4']) &&
        isset($_PUT['t5'])
    ) {
        
        // Obtener datos desde la solicitud POST
        $idproced = $_PUT['t1'];
        $idor = $_PUT['t2'];
        $idpro = $_PUT['t3'];
        $grupo = $_PUT['t4'];
        $nomproced = $_PUT['t5'];
        
        // Evitar SQL Injection usando consultas preparadas
        $sentencia = $conexion->prepare("UPDATE procedimientos SET nombre_grupo=?, nombre_procedimiento=? WHERE id_procedimiento =? AND id_orden=? AND id_profesional=?");
        $sentencia->bind_param('ssiii', $grupo, $nomproced, $idproced, $idor, $idpro);

        // Ejecutar la consulta
        if ($sentencia->execute()) {
            echo json_encode(['message' => 'Actualizacion exitosa.'], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['error' => 'Error no se pudo actualizar.'], JSON_UNESCAPED_UNICODE);
        }

        $sentencia->close();
    } else {
        echo json_encode(['error' => 'Se necesitan todos los campos para actualizar.'], JSON_UNESCAPED_UNICODE);
    }

    $conexion->close();
}

//***************************************METODO DELETE*************************************************

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    parse_str(file_get_contents("php://input"), $_DELETE); 
    // Verificar si los campos estan presentes
    if (
        isset($_DELETE['id_procedimiento']) 
    ) {
        
        // Obtener datos desde la solicitud POST
        $idproced = $_DELETE['id_procedimiento'];
        
        // Evitar SQL Injection usando consultas preparadas
        $sentencia = $conexion->prepare("DELETE FROM procedimientos WHERE id_procedimiento=?");
        $sentencia->bind_param('i', $idproced);

        // Ejecutar la consulta
        if ($sentencia->execute()) {
            echo json_encode(['message' => 'orden eliminada con exito.'], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['error' => 'Error no se pudo eliminar.'], JSON_UNESCAPED_UNICODE);
        }

        $sentencia->close();
    } else {
        echo json_encode(['error' => 'Se necesitan todos los campos para poder eliminar.'], JSON_UNESCAPED_UNICODE);
    }

    $conexion->close();
}

?>
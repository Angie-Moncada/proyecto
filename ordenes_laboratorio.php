<?php
// Incluir el archivo de conexion a la base de datos
include 'bd/conexion.php';
//Permitir los metodos GET, POST, PUT, DELETE, OPTIONS
// Encabezados CORS para permitir todas las solicitudes desde cualquier origen
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

    // Para llamar la tabla usuario de la base de datos
    if (isset($_GET['id_orden'])) {

        $idor = $_GET['id_orden'];

        $sentencia = $conexion->prepare("SELECT * FROM ordenes_laboratorio WHERE id_orden LIKE CONCAT(?,'%')");

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

    // Si se proporciona el id del paciente en la URL
    }elseif (isset($_GET['id_paciente'])) {

        $idpac = $_GET['id_paciente'];

        $sentencia = $conexion->prepare("SELECT * FROM ordenes_laboratorio WHERE id_paciente LIKE CONCAT(?,'%')");

        $sentencia->bind_param('i', $idpac);

        $sentencia->execute();

        $resultado = $sentencia->get_result();

        if ($resultado->num_rows > 0) {

            $fila = $resultado->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['data' => $fila], JSON_UNESCAPED_UNICODE);

        } else {

            echo json_encode(['error' => 'No se encontro el id.'], JSON_UNESCAPED_UNICODE);
        }

        $sentencia->close();

    // Si se proporciona la fecha de la orden en la URL
    }elseif (isset($_GET['fecha_orden'])) {

        $forden = $_GET['fecha_orden'];

        $sentencia = $conexion->prepare("SELECT * FROM ordenes_laboratorio WHERE fecha_orden LIKE CONCAT(?,'%')");

        $sentencia->bind_param('s', $forden);

        $sentencia->execute();

        $resultado = $sentencia->get_result();

        if ($resultado->num_rows > 0) {

            $fila = $resultado->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['data' => $fila], JSON_UNESCAPED_UNICODE);

        } else {

            echo json_encode(['error' => 'No se encontro la fecha de orden.'], JSON_UNESCAPED_UNICODE);
        }

        $sentencia->close();
    
    // Si se proporciona un codigo de documento de la orden en la URL
    }elseif (isset($_GET['codigo_documento'])) {

        $codigo = $_GET['codigo_documento'];

        $sentencia = $conexion->prepare("SELECT * FROM ordenes_laboratorio WHERE codigo_documento LIKE CONCAT(?,'%')");

        $sentencia->bind_param('s', $codigo);

        $sentencia->execute();

        $resultado = $sentencia->get_result();

        if ($resultado->num_rows > 0) {

            $fila = $resultado->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['data' => $fila], JSON_UNESCAPED_UNICODE);

        } else {

            echo json_encode(['error' => 'No se encontro el codigo de documento de la orden.'], JSON_UNESCAPED_UNICODE);
        }

        $sentencia->close();

    // Si se proporciona el numero de la orden en la URL
    }elseif (isset($_GET['numero_orden'])) {

        $numorden = $_GET['numero_orden'];

        $sentencia = $conexion->prepare("SELECT * FROM ordenes_laboratorio WHERE numero_orden LIKE CONCAT(?,'%')");

        $sentencia->bind_param('s', $numorden);

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

        $resultado = $conexion->query("SELECT * FROM ordenes_laboratorio");

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
        $idpac = $_POST['t1'];
        $forden = $_POST['t2'];
        $codigo = $_POST['t3'];
        $numorden = $_POST['t4'];

        // Evitar SQL Injection usando consultas preparadas
        $sentencia = $conexion->prepare("INSERT INTO ordenes_laboratorio (id_paciente, fecha_orden, codigo_documento, numero_orden) VALUES (?, ?, ?, ?)");
        $sentencia->bind_param('isss', $idpac, $forden, $codigo, $numorden);

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
        $idor = $_PUT['t1'];
        $idpac = $_PUT['t2'];
        $forden = $_PUT['t3'];
        $codigo = $_PUT['t4'];
        $numorden = $_PUT['t5'];
        
        // Evitar SQL Injection usando consultas preparadas
        $sentencia = $conexion->prepare("UPDATE ordenes_laboratorio SET fecha_orden=?, codigo_documento=?, numero_orden=? WHERE id_orden=? AND id_paciente=?");
        $sentencia->bind_param('sssii', $forden, $codigo, $numorden, $idor, $idpac);

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
        isset($_DELETE['id_orden']) 
    ) {
        
        // Obtener datos desde la solicitud POST
        $idor = $_DELETE['id_orden'];
        
        // Evitar SQL Injection usando consultas preparadas
        $sentencia = $conexion->prepare("DELETE FROM ordenes_laboratorio WHERE id_orden=?");
        $sentencia->bind_param('i', $idor);

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
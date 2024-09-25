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
    if (isset($_GET['id_login'])) {

        $idlog = $_GET['id_login'];

        $sentencia = $conexion->prepare("SELECT * FROM loginp WHERE id_login LIKE CONCAT(?'%')");

        $sentencia->bind_param('i', $idlog);

        $sentencia->execute();

        $resultado = $sentencia->get_result();

        if ($resultado->num_rows > 0) {

            $fila = $resultado->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['data' => $fila], JSON_UNESCAPED_UNICODE);

        } else {

            echo json_encode(['error' => 'No se encontro el id.'], JSON_UNESCAPED_UNICODE);
        }

        
        $sentencia->close();

    // Si se proporciona el tipo de identificacion en la URL
    }elseif (isset($_GET['tipo_identificacion'])) {

        $ide = $_GET['tipo_identificacion'];

        $sentencia = $conexion->prepare("SELECT * FROM loginp WHERE tipo_identificacion LIKE CONCAT(?,'%')");

        $sentencia->bind_param('s', $ide);

        $sentencia->execute();

        $resultado = $sentencia->get_result();

        if ($resultado->num_rows > 0) {

            $fila = $resultado->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['data' => $fila], JSON_UNESCAPED_UNICODE);

        } else {

            echo json_encode(['error' => 'No se encontro el tipo identificacion.'], JSON_UNESCAPED_UNICODE);
        }

        $sentencia->close();

    // Si se proporciona el numero de identificacion en la URL
    }elseif (isset($_GET['numero_identificacion'])) {

        $numide = $_GET['numero_identificacion'];

        $sentencia = $conexion->prepare("SELECT * FROM loginp WHERE numero_identificacion LIKE CONCAT(?,'%')");

        $sentencia->bind_param('s', $numide);

        $sentencia->execute();

        $resultado = $sentencia->get_result();

        if ($resultado->num_rows > 0) {

            $fila = $resultado->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['data' => $fila], JSON_UNESCAPED_UNICODE);

        } else {

            echo json_encode(['error' => 'No se encontro el numero de identificacion.'], JSON_UNESCAPED_UNICODE);
        }

        $sentencia->close();
    
    // Si se proporciona la fecha de nacimiento en la URL
    }elseif (isset($_GET['fecha_nacimiento'])) {

        $fnacimiento = $_GET['fecha_nacimiento'];

        $sentencia = $conexion->prepare("SELECT * FROM loginp WHERE fecha_nacimiento LIKE CONCAT(?,'%')");

        $sentencia->bind_param('s', $fnacimiento);

        $sentencia->execute();

        $resultado = $sentencia->get_result();

        if ($resultado->num_rows > 0) {

            $fila = $resultado->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['data' => $fila], JSON_UNESCAPED_UNICODE);

        } else {

            echo json_encode(['error' => 'No se encontro la fecha de nacimiento.'], JSON_UNESCAPED_UNICODE);
        }

        $sentencia->close();
      

        // Si no se proporciona ningun parametro en la URL
    }else {
        // Definir el límite de resultados por página (10)
        $limit = 10;

        // Obtener el número de página de la URL o usar 1 por defecto
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Calcular el desplazamiento (offset) basado en la página
        $offset = ($page - 1) * $limit;

        // Consulta SQL modificada para la paginación
        $sentencia = $conexion->prepare("SELECT * FROM loginp LIMIT ? OFFSET ?");
        $sentencia->bind_param('ii', $limit, $offset);

        $sentencia->execute();

        $resultado = $sentencia->get_result();

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_all(MYSQLI_ASSOC);

        // Devolver datos con información de paginación
        echo json_encode([
            'data' => $fila,
            'page' => $page,
            'limit' => $limit,
            'total' => $resultado->num_rows // Total de registros obtenidos en esta página
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['error' => 'No se encontraron resultados.'], JSON_UNESCAPED_UNICODE);
    }

    $sentencia->close();
}
}


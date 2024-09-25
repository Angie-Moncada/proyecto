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

    // Para llamar la tabla paciente de la base de datos
    if (isset($_GET['id_paciente'])) {

        $idpa = $_GET['id_paciente'];

        $sentencia = $conexion->prepare("SELECT * FROM pacientes WHERE id_paciente LIKE CONCAT(?)");

        $sentencia->bind_param('i', $idpa);

        $sentencia->execute();

        $resultado = $sentencia->get_result();

        if ($resultado->num_rows > 0) {

            $fila = $resultado->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['data' => $fila], JSON_UNESCAPED_UNICODE);

        } else {

            echo json_encode(['error' => 'No se encontro el id.'], JSON_UNESCAPED_UNICODE);
        }

        
        $sentencia->close();

    // Si se proporciona la identificacion en la URL
    }elseif (isset($_GET['id_login'])) {

        $idlog = $_GET['id_login'];

        $sentencia = $conexion->prepare("SELECT * FROM pacientes WHERE id_login LIKE CONCAT(?,'%')");

        $sentencia->bind_param('i', $idlog);

        $sentencia->execute();

        $resultado = $sentencia->get_result();

        if ($resultado->num_rows > 0) {

            $fila = $resultado->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['data' => $fila], JSON_UNESCAPED_UNICODE);

        } else {

            echo json_encode(['error' => 'No se encontro la identificacion.'], JSON_UNESCAPED_UNICODE);
        }

        $sentencia->close();

    // Si se proporciona un nombre en la URL
    }elseif (isset($_GET['nombre_completo'])) {

        $nomco = $_GET['nombre_completo'];

        $sentencia = $conexion->prepare("SELECT * FROM pacientes WHERE nombre_completo LIKE CONCAT(?,'%')");

        $sentencia->bind_param('s', $nomco);

        $sentencia->execute();

        $resultado = $sentencia->get_result();

        if ($resultado->num_rows > 0) {

            $fila = $resultado->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['data' => $fila], JSON_UNESCAPED_UNICODE);

        } else {

            echo json_encode(['error' => 'No se encontro este usuario.'], JSON_UNESCAPED_UNICODE);
        }

        $sentencia->close();
    
    // Si se proporciona un sexo en la URL
    }elseif (isset($_GET['sexo_biologico'])) {

        $sexo = $_GET['sexo_biologico'];

        $sentencia = $conexion->prepare("SELECT * FROM pacientes WHERE sexo_biologico LIKE CONCAT(?,'%')");

        $sentencia->bind_param('s', $sexo);

        $sentencia->execute();

        $resultado = $sentencia->get_result();

        if ($resultado->num_rows > 0) {

            $fila = $resultado->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['data' => $fila], JSON_UNESCAPED_UNICODE);

        } else {

            echo json_encode(['error' => 'No se encontro el genero.'], JSON_UNESCAPED_UNICODE);
        }

        $sentencia->close();

    // Si se proporciona un direccion en la URL
    }elseif (isset($_GET['direccion'])) {

        $dir = $_GET['direccion'];

        $sentencia = $conexion->prepare("SELECT * FROM pacientes WHERE direccion LIKE CONCAT(?,'%')");

        $sentencia->bind_param('s', $dir);

        $sentencia->execute();

        $resultado = $sentencia->get_result();

        if ($resultado->num_rows > 0) {

            $fila = $resultado->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['data' => $fila], JSON_UNESCAPED_UNICODE);

        } else {

            echo json_encode(['error' => 'No se encontro el direccion.'], JSON_UNESCAPED_UNICODE);
        }

        $sentencia->close();

    // Si se proporciona el numero en la URL
    }elseif (isset($_GET['numero_celular'])) {

        $cel = $_GET['numero_celular'];

        $sentencia = $conexion->prepare("SELECT * FROM pacientes WHERE numero_celular LIKE CONCAT(?,'%')");

        $sentencia->bind_param('s', $cel);

        $sentencia->execute();

        $resultado = $sentencia->get_result();

        if ($resultado->num_rows > 0) {

            $fila = $resultado->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['data' => $fila], JSON_UNESCAPED_UNICODE);

        } else {

            echo json_encode(['error' => 'No se encontro el numero celular.'], JSON_UNESCAPED_UNICODE);
        }

        $sentencia->close();
      
    // Si se proporciona el correo electronico en la URL
    }elseif (isset($_GET['correo_electronico'])) {

        $correo = $_GET['correo_electronico'];

        $sentencia = $conexion->prepare("SELECT * FROM pacientes WHERE correo_electronico LIKE CONCAT(?,'%')");

        $sentencia->bind_param('s', $correo);

        $sentencia->execute();

        $resultado = $sentencia->get_result();

        if ($resultado->num_rows > 0) {

            $fila = $resultado->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['data' => $fila], JSON_UNESCAPED_UNICODE);

        } else {

            echo json_encode(['error' => 'No se encontro el correo electronico.'], JSON_UNESCAPED_UNICODE);
        }

        $sentencia->close();
      

    // Si no se proporciona ningun parametro en la URL
    }  else {
        // Definir el límite de resultados por página (en este caso, 10)
        $limit = 10;
    
        // Obtener el número de página de la URL o usar 1 por defecto
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    
        // Calcular el desplazamiento (offset) basado en la página
        $offset = ($page - 1) * $limit;
    
        // Consulta SQL modificada para la paginación
        $sentencia = $conexion->prepare("SELECT * FROM pacientes LIMIT ? OFFSET ?");
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

//***************************************METODO POST*************************************************

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si los campos estan presentes
    if (
        isset($_POST['t1']) &&
        isset($_POST['t2']) &&
        isset($_POST['t3']) &&
        isset($_POST['t4']) &&
        isset($_POST['t5']) &&
        isset($_POST['t6'])  
    ) {
        
        // Obtener datos desde la solicitud POST
        $nomco = $_POST['t1'];
        $sexo = $_POST['t2']; 
        $dir = $_POST['t3'];
        $cel = $_POST['t4'];
        $correo = $_POST['t5'];
        $idlog = $_POST['t6'];
       

        // Evitar SQL Injection usando consultas preparadas
        $sentencia = $conexion->prepare("INSERT INTO pacientes ( nombre_completo, sexo_biologico, direccion, numero_celular, correo_electronico, id_login) VALUES ( ?, ?, ?, ?, ?,?)");
        $sentencia->bind_param('sssssi', $nomco, $sexo, $dir, $cel,  $correo, $idlog);

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
        isset($_PUT['t5']) &&
        isset($_PUT['t6']) &&
        isset($_PUT['t7'])
    ) {
        
        // Obtener datos desde la solicitud PUT
        $idpa = $_PUT['t1'];
        $idlog = $_PUT['t2'];
        $nomco = $_PUT['t3'];
        $sexo= $_PUT['t4'];
        $dir = $_PUT['t5'];
        $cel = $_PUT['t6'];
        $correo =$_PUT['t7'];
        
        // Evitar SQL Injection usando consultas preparadas
        $sentencia = $conexion->prepare("UPDATE pacientes SET id_login=?, nombre_completo=?, sexo_biologico=?, direccion=?, numero_celular=?, correo_electronico=? WHERE id_paciente=?");
        $sentencia->bind_param('isssssi', $idlog, $nomco, $sexo, $dir, $cel,  $correo, $idpa);

        // Ejecutar la consulta
        if ($sentencia->execute()) {
            echo json_encode(['message' => 'Edicion exitosa.'], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['error' => 'Error no se pudo editar.'], JSON_UNESCAPED_UNICODE);
        }

        $sentencia->close();
    } else {
        echo json_encode(['error' => 'Se necesitan todos los campos para poder editar.'], JSON_UNESCAPED_UNICODE);
    }

    $conexion->close();
}

//***************************************METODO DELETE*************************************************

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    parse_str(file_get_contents("php://input"), $_DELETE); 
    // Verificar si los campos estan presentes
    if (
        isset($_DELETE['id_paciente']) 
    ) {
        
        // Obtener datos desde la solicitud POST
        $idpa = $_DELETE['id_paciente'];
        
        // Evitar SQL Injection usando consultas preparadas
        $sentencia = $conexion->prepare("DELETE FROM pacientes  WHERE id_paciente=?");
        $sentencia->bind_param('i', $idpa);

        // Ejecutar la consulta
        if ($sentencia->execute()) {
            echo json_encode(['message' => 'paciente eliminado con exito.'], JSON_UNESCAPED_UNICODE);
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

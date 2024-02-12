<?php
if ($_SESSION['name'] != 'martin' && $_SESSION['name'] != 'caro') {
    // Configura el código de estado HTTP 403
    header("HTTP/1.1 403 Unauthorized");
    exit(); // Asegura que no se ejecute más código después de mostrar la página de error
}

function conectar_base_datos() {
    $servername = "34.27.15.81:3306";
    $username = "authentication_system";
    $password = "t0ZpZwXx5ICJzL7";
    $dbname = "db_em";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        $error_message = "Conexión fallida: " . $conn->connect_error;
        error_log($error_message);
        die($error_message);
    }

    return $conn;
}

function obtener_ultimo_dato() {
    $conn = conectar_base_datos();

    $query = "SELECT * FROM Estacion_test ORDER BY fecha DESC LIMIT 1";
    $result = $conn->query($query);

    if ($result === false) {
        $error_message = "Error en la consulta SQL: " . $conn->error;
        error_log($error_message);
        die($error_message);
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row;
    } else {
        return false;
    }

    $conn->close();
}

function mostrar_dato_n_shortcode() {
    $dato = obtener_ultimo_dato();

    if ($dato !== false && isset($dato['Dato N°'])) {
        return "<p>Dato N°: {$dato['Dato N°']}</p>";
    } else {
        return "<p>No se encontraron datos para Dato N°.</p>";
    }
}


function mostrar_fecha_shortcode() {
    $dato = obtener_ultimo_dato();

    if ($dato !== false && isset($dato['Fecha'])) {
        return "<p>Fecha: {$dato['Fecha']}</p>";
    } else {
        return "<p>No se encontraron datos para Fecha.</p>";
    }
}


function mostrar_temperatura_shortcode() {
    $dato = obtener_ultimo_dato();

    if ($dato !== false && isset($dato['Temperatura'])) {
        return "<p>Temperatura actual: {$dato['Temperatura']}</p>";
    } else {
        return "<p>No se encontraron datos para Temperatura.</p>";
    }
    $temp_html = '';
}
$temp_html = mostrar_temperatura_shortcode();


function mostrar_presion_atmosferica_shortcode() {
    $dato = obtener_ultimo_dato();

    if ($dato !== false && isset($dato['Presión Atmosférica'])) {
        return "<p>Presión atmosférica: {$dato['Presión Atmosférica']}</p>";
    } else {
        return "<p>No se encontraron datos para Presión Atmosférica.</p>";
    }
    $bp_html = '';
}
$bp_html = mostrar_presion_atmosferica_shortcode();


function mostrar_altitud_shortcode() {
    $dato = obtener_ultimo_dato();

    if ($dato !== false && isset($dato['Altitud'])) {
        return "<p>Altitud a nivel del mar: {$dato['Altitud']}</p>";
    } else {
        return "<p>No se encontraron datos para Altitud.</p>";
    }
    $altitud_html = '';
}
$altitud_html = mostrar_altitud_shortcode();


function mostrar_humedad_relativa_shortcode() {
    $dato = obtener_ultimo_dato();

    if ($dato !== false && isset($dato['Humedad Relativa'])) {
        return "<p>Humedad relativa: {$dato['Humedad Relativa']}</p>";
    } else {
        return "<p>No se encontraron datos para Humedad Relativa.</p>";
    }
    $humedad_relativa_html = '';
}
$humedad_relativa_html = mostrar_humedad_relativa_shortcode();

function tabla_Datos() {
    $servername = "34.27.15.81:3306";
    $username = "authentication_system";
    $password = "t0ZpZwXx5ICJzL7";
    $dbname = "db_em";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta para obtener los últimos 10 datos de la tabla en orden descendente (sustituye 'nombre_tabla' con el nombre real de tu tabla)
    $sql = "SELECT * FROM (SELECT * FROM Estacion_test ORDER BY `Dato N°` DESC LIMIT 10) AS ultimos_datos ORDER BY `Dato N°` ASC";
    $result = $conn->query($sql);

    // Verificar si se obtuvieron resultados
    if ($result->num_rows > 0) {
        // Iniciar la tabla en formato HTML sin bordes y con la fuente Poppins
        $table_datos = '<table style="width: 100%; border-collapse: collapse; font-family: Poppins;"><tr><th>Dato N°</th><th>Fecha</th><th>Temperatura</th><th>Presión Atmosférica</th><th>Altitud</th><th>Humedad Relativa</th></tr>';

        // Iterar sobre los resultados y agregar a la tabla
        while ($row = $result->fetch_assoc()) {
            $table_datos .= '<tr>';
            $table_datos .= '<td>' . $row["Dato N°"] . '</td>';
            $table_datos .= '<td>' . $row["Fecha"] . '</td>';
            $table_datos .= '<td>' . $row["Temperatura"] . '</td>';
            $table_datos .= '<td>' . $row["Presión Atmosférica"] . '</td>';
            $table_datos .= '<td>' . $row["Altitud"] . '</td>';
            $table_datos .= '<td>' . $row["Humedad Relativa"] . '</td>';
            $table_datos .= '</tr>';
        }

        // Cerrar la tabla
        $table_datos .= '</table>';

        // Retornar la tabla
        return $table_datos;
    } else {
        return "No se encontraron resultados.";
    }

    // Cerrar la conexión
    $conn->close();
}


?>
<?php
//Conectar a la base de datos
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
    $password = "1234";
    $dbname = "db_em";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta para obtener todos los datos de la tabla (sustituye 'nombre_tabla' con el nombre real de tu tabla)
    $sql = "SELECT * FROM Estacion_test ORDER BY `Dato N°` ASC LIMIT 10"; // Modificado para ordenar por 'Dato N°' de menor a mayor
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

function generarGraficoTemperaturassemanales() {
    // Detalles de la conexión a la base de datos
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

    $sql = "SELECT Temperatura, Fecha FROM Estacion_test ORDER BY Fecha DESC LIMIT 7;";
    $result = $conn->query($sql);
    
    // Crear un array para almacenar los datos por día de la semana
    $temperaturas_por_dia = array();
    
    // Traducir los nombres de los días al español
    $traducciones_dias = array(
        "Monday" => "Lunes",
        "Tuesday" => "Martes",
        "Wednesday" => "Miércoles",
        "Thursday" => "Jueves",
        "Friday" => "Viernes",
        "Saturday" => "Sábado",
        "Sunday" => "Domingo"
    );
    
    // Colores bonitos para las barras
    $colores_bonitos = array('#FF5733', '#FFC300', '#33FF57', '#3385FF', '#FF33D6', '#A633FF', '#33FFE7');
    
    // Procesar los resultados y almacenar las temperaturas por día de la semana
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Asegúrate de que el array $row contiene las claves "Temperatura" y "Fecha"
            if (isset($row['Temperatura'], $row['Fecha'])) {
                $fecha = DateTime::createFromFormat('d-m-Y H:i:s', $row['Fecha']);
    
                // Verificar si la creación de la instancia fue exitosa
                if ($fecha === false) {
                    echo "Error al interpretar la fecha: " . $row['Fecha'];
                    continue;  // Saltar a la siguiente iteración en caso de error
                }
    
                // Obtener el nombre del día traducido al español
                $nombre_dia = $traducciones_dias[$fecha->format('l')];
    
                // Almacenar la temperatura en el array correspondiente al día de la semana
                $temperaturas_por_dia[$fecha->format('N')] = array(
                    "nombre_dia" => $nombre_dia,
                    "fecha" => $fecha->format('d/m'),
                    "temperatura" => $row['Temperatura']
                );
            } else {
                // Muestra un mensaje de error si faltan claves en el array $row
                echo "Error: el array \$row no contiene las claves necesarias.";
            }
        }
    }
    
    // Ordenar el array por el índice numérico del día de la semana
    ksort($temperaturas_por_dia);
    
    // Preparar los datos para Highcharts
    $data = array();
    $i = 0;
    foreach ($temperaturas_por_dia as $item) {
        $data[] = array(
            "name" => $item["nombre_dia"] . " - " . $item["fecha"],
            "y" => (float)$item["temperatura"],
            "color" => $colores_bonitos[$i]
        );
        $i++;
    }
    
    // Convertir datos a formato JSON
    $data_json = json_encode($data);

    // Cerrar la conexión
    $conn->close();

    // Retornar solo el código del gráfico Highcharts sin HTML adicional
    return <<<HTML
    <head>
        <script src="https://code.highcharts.com/highcharts.js"></script>
    </head>
    <div id="grafico_barras"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Parsear los datos JSON
            var data = $data_json;

            // Inicializar el gráfico de barras
            Highcharts.chart('grafico_barras', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Temperaturas por Día de la Semana'
                },
                xAxis: {
                    categories: data.map(function(item) {
                        return item.name;
                    }),
                    title: {
                        text: 'Día de la Semana - Fecha'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Temperatura Media'
                    }
                },
                series: [{
                    name: 'Temperatura Media',
                    data: data
                }]
            });
        });
    </script>
HTML;
}
generarGraficoTemperaturassemanales();
?>



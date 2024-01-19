<?php
if ($_SESSION['name'] != 'martin') {
    // Configura el código de estado HTTP 403
    header("HTTP/1.1 404 Unauthorized'");
    exit(); // Asegura que no se ejecute más código después de mostrar la página de error
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
                    text: 'Temperatura media de la semana'
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

function generarGraficoPresionAtmosfericaSemanal() {
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

    $sql = "SELECT `Presión Atmosférica`, Fecha FROM Estacion_test ORDER BY Fecha DESC LIMIT 7;";
    $result = $conn->query($sql);
    
    // Crear un array para almacenar los datos por día de la semana
    $presion_por_dia = array();
    
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
    
    // Procesar los resultados y almacenar las presiones por día de la semana
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Asegúrate de que el array $row contiene las claves "Presión Atmosférica" y "Fecha"
            if (isset($row['Presión Atmosférica'], $row['Fecha'])) {
                $fecha = DateTime::createFromFormat('d-m-Y H:i:s', $row['Fecha']);
    
                // Verificar si la creación de la instancia fue exitosa
                if ($fecha === false) {
                    echo "Error al interpretar la fecha: " . $row['Fecha'];
                    continue;  // Saltar a la siguiente iteración en caso de error
                }
    
                // Obtener el nombre del día traducido al español
                $nombre_dia = $traducciones_dias[$fecha->format('l')];
    
                // Almacenar la presión en el array correspondiente al día de la semana
                $presion_por_dia[$fecha->format('N')] = array(
                    "nombre_dia" => $nombre_dia,
                    "fecha" => $fecha->format('d/m'),
                    "presion" => $row['Presión Atmosférica']
                );
            } else {
                // Muestra un mensaje de error si faltan claves en el array $row
                echo "Error: el array \$row no contiene las claves necesarias.";
            }
        }
    }
    
    // Ordenar el array por el índice numérico del día de la semana
    ksort($presion_por_dia);
    
    // Preparar los datos para Highcharts
    $data = array();
    $i = 0;
    foreach ($presion_por_dia as $item) {
        $data[] = array(
            "name" => $item["nombre_dia"] . " - " . $item["fecha"],
            "y" => (float)$item["presion"],
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
    <div id="grafico_barras_presion"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Parsear los datos JSON
            var data = $data_json;

            // Inicializar el gráfico de barras
            Highcharts.chart('grafico_barras_presion', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Presión Atmosférica Media de la Semana'
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
                        text: 'Presión Atmosférica Media'
                    }
                },
                series: [{
                    name: 'Presión Atmosférica Media',
                    data: data
                }]
            });
        });
    </script>
HTML;
}
generarGraficoPresionAtmosfericaSemanal();

function generarGraficoAltitudSemanal() {
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

    $sql = "SELECT Altitud, Fecha FROM Estacion_test ORDER BY Fecha DESC LIMIT 7;";
    $result = $conn->query($sql);
    
    // Crear un array para almacenar los datos por día de la semana
    $altitud_por_dia = array();
    
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
    
    // Procesar los resultados y almacenar las altitudes por día de la semana
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Asegúrate de que el array $row contiene las claves "Altitud" y "Fecha"
            if (isset($row['Altitud'], $row['Fecha'])) {
                $fecha = DateTime::createFromFormat('d-m-Y H:i:s', $row['Fecha']);
    
                // Verificar si la creación de la instancia fue exitosa
                if ($fecha === false) {
                    echo "Error al interpretar la fecha: " . $row['Fecha'];
                    continue;  // Saltar a la siguiente iteración en caso de error
                }
    
                // Obtener el nombre del día traducido al español
                $nombre_dia = $traducciones_dias[$fecha->format('l')];
    
                // Almacenar la altitud en el array correspondiente al día de la semana
                $altitud_por_dia[$fecha->format('N')] = array(
                    "nombre_dia" => $nombre_dia,
                    "fecha" => $fecha->format('d/m'),
                    "altitud" => $row['Altitud']
                );
            } else {
                // Muestra un mensaje de error si faltan claves en el array $row
                echo "Error: el array \$row no contiene las claves necesarias.";
            }
        }
    }
    
    // Ordenar el array por el índice numérico del día de la semana
    ksort($altitud_por_dia);
    
    // Preparar los datos para Highcharts
    $data = array();
    $i = 0;
    foreach ($altitud_por_dia as $item) {
        $data[] = array(
            "name" => $item["nombre_dia"] . " - " . $item["fecha"],
            "y" => (float)$item["altitud"],
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
    <div id="grafico_barras_altitud"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Parsear los datos JSON
            var data = $data_json;

            // Inicializar el gráfico de barras
            Highcharts.chart('grafico_barras_altitud', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Altitud Media de la Semana'
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
                        text: 'Altitud Media'
                    }
                },
                series: [{
                    name: 'Altitud Media',
                    data: data
                }]
            });
        });
    </script>
HTML;
}
generarGraficoAltitudSemanal();


function generarGraficoHumedadRelativaSemanal() {
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

    $sql = "SELECT `Humedad Relativa`, Fecha FROM Estacion_test ORDER BY Fecha DESC LIMIT 7;";
    $result = $conn->query($sql);
    
    // Crear un array para almacenar los datos por día de la semana
    $humedad_por_dia = array();
    
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
    
    // Procesar los resultados y almacenar las humedades por día de la semana
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Asegúrate de que el array $row contiene las claves "Humedad Relativa" y "Fecha"
            if (isset($row['Humedad Relativa'], $row['Fecha'])) {
                $fecha = DateTime::createFromFormat('d-m-Y H:i:s', $row['Fecha']);
    
                // Verificar si la creación de la instancia fue exitosa
                if ($fecha === false) {
                    echo "Error al interpretar la fecha: " . $row['Fecha'];
                    continue;  // Saltar a la siguiente iteración en caso de error
                }
    
                // Obtener el nombre del día traducido al español
                $nombre_dia = $traducciones_dias[$fecha->format('l')];
    
                // Almacenar la humedad en el array correspondiente al día de la semana
                $humedad_por_dia[$fecha->format('N')] = array(
                    "nombre_dia" => $nombre_dia,
                    "fecha" => $fecha->format('d/m'),
                    "humedad" => $row['Humedad Relativa']
                );
            } else {
                // Muestra un mensaje de error si faltan claves en el array $row
                echo "Error: el array \$row no contiene las claves necesarias.";
            }
        }
    }
    
    // Ordenar el array por el índice numérico del día de la semana
    ksort($humedad_por_dia);
    
    // Preparar los datos para Highcharts
    $data = array();
    $i = 0;
    foreach ($humedad_por_dia as $item) {
        $data[] = array(
            "name" => $item["nombre_dia"] . " - " . $item["fecha"],
            "y" => (float)$item["humedad"],
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
    <div id="grafico_barras_humedad"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Parsear los datos JSON
            var data = $data_json;

            // Inicializar el gráfico de barras
            Highcharts.chart('grafico_barras_humedad', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Humedad Relativa Media de la Semana'
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
                        text: 'Humedad Relativa Media'
                    }
                },
                series: [{
                    name: 'Humedad Relativa Media',
                    data: data
                }]
            });
        });
    </script>
HTML;
}
generarGraficoHumedadRelativaSemanal();




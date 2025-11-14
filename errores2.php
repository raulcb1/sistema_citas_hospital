<?php
// Ruta al archivo de registro
$log_file = 'php-error.log';

// Leer el contenido del archivo de registro
$log_content = file_get_contents($log_file);

// Dividir el contenido en líneas
$log_lines = explode(PHP_EOL, $log_content);

// Invertir el orden de las líneas para mostrar los más recientes primero
$log_lines = array_reverse($log_lines);

// Convertir el array de líneas a JSON para usar en JavaScript
$log_lines_json = json_encode($log_lines);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Errores</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Tus estilos aquí */
        body {
            font-family: Arial, sans-serif;
        }
        .log-container {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
            max-height: 500px;
            overflow-y: auto;
        }
        .log-line {
            padding: 5px;
            border-bottom: 1px solid #eee;
        }
        .log-line:nth-child(odd) {
            background-color: #f0f0f0;
        }
        .log-line.error {
            color: red;
        }
        .log-line.warning {
            color: orange;
        }
        .log-line.notice {
            color: black;
        }
        .log-line.info {
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="my-4">Registro de Errores</h1>
        <input type="text" id="search" class="form-control mb-3" placeholder="Buscar en los registros">
        <div class="log-container" id="logContainer">
            <!-- Aquí se insertarán las líneas del log -->
        </div>
        <nav>
            <ul class="pagination justify-content-center" id="pagination">
                <!-- Aquí se generará la paginación -->
            </ul>
        </nav>
    </div>

    <!-- Incluir jQuery si no lo has hecho ya -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script>
    // Obtener las líneas de log desde PHP
    var logLines = <?php echo $log_lines_json; ?>;
    var filteredLogLines = logLines.slice(); // Copia de logLines para aplicar filtros
    var currentPage = 1;
    var linesPerPage = 50;

    // Función para renderizar las líneas de log de la página actual
    function renderLogLines() {
        var logContainer = document.getElementById('logContainer');
        logContainer.innerHTML = '';

        var start = (currentPage - 1) * linesPerPage;
        var end = start + linesPerPage;
        var paginatedLines = filteredLogLines.slice(start, end);

        paginatedLines.forEach(function(line, index) {
            var lineDiv = document.createElement('div');
            lineDiv.classList.add('log-line');

            if (line.includes('PHP Fatal error')) {
                lineDiv.classList.add('error');
            } else if (line.includes('PHP Warning')) {
                lineDiv.classList.add('warning');
            } else if (line.includes('PHP Notice')) {
                lineDiv.classList.add('notice');
            } else {
                lineDiv.classList.add('info');
            }

            lineDiv.textContent = line;
            logContainer.appendChild(lineDiv);
        });

        renderPagination();
    }

    // Función para renderizar la paginación
    function renderPagination() {
        var pagination = document.getElementById('pagination');
        pagination.innerHTML = '';

        var totalLines = filteredLogLines.length;
        var totalPages = Math.ceil(totalLines / linesPerPage);

        for (var i = 1; i <= totalPages; i++) {
            var li = document.createElement('li');
            li.classList.add('page-item');
            if (i === currentPage) {
                li.classList.add('active');
            }
            var a = document.createElement('a');
            a.classList.add('page-link');
            a.href = '#';
            a.textContent = i;
            a.setAttribute('data-page', i);
            a.addEventListener('click', function(e) {
                e.preventDefault();
                currentPage = parseInt(this.getAttribute('data-page'));
                renderLogLines();
            });
            li.appendChild(a);
            pagination.appendChild(li);
        }
    }

    // Evento para el campo de búsqueda
    document.getElementById('search').addEventListener('input', function() {
        var searchValue = this.value.toLowerCase();
        filteredLogLines = logLines.filter(function(line) {
            return line.toLowerCase().includes(searchValue);
        });
        currentPage = 1; // Reiniciar a la primera página
        renderLogLines();
    });

    // Inicializar la visualización
    renderLogLines();
    </script>
</body>
</html>

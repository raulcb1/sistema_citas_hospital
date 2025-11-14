<?php
// Ruta al archivo de registro
$log_file = 'php-error.log';

// Leer el contenido del archivo de registro
$log_content = file_get_contents($log_file);

// Dividir el contenido en líneas
$log_lines = explode(PHP_EOL, $log_content);

// Invertir el orden de las líneas
$log_lines = array_reverse($log_lines);



// Define el número de líneas por página
$lines_per_page = 5;
$total_lines = count($log_lines);
$total_pages = ceil($total_lines / $lines_per_page);
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $lines_per_page;
$end = min($start + $lines_per_page, $total_lines);
$log_lines_paged = array_slice($log_lines, $start, $lines_per_page);


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Errores</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
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
        <input type="text" id="search" class="form-control" placeholder="Buscar en los registros">
        <div class="log-container" id="logContainer">
            <?php foreach ($log_lines_paged as $line): ?>
            <div class="log-line
                    <?php
                        if (strpos($line, 'PHP Fatal error') !== false) {
                            echo 'error';
                        } elseif (strpos($line, 'PHP Warning') !== false) {
                            echo 'warning';
                        } elseif (strpos($line, 'PHP Notice') !== false) {
                            echo 'notice';
                        } else {
                            echo 'info';
                        }
                    ?>">
                <?= htmlspecialchars($line) ?>
            </div>
            <?php endforeach; ?>
        </div>
        <nav>
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <script>
    document.getElementById('search').addEventListener('input', function() {
        var searchValue = this.value.toLowerCase();
        var logLines = document.querySelectorAll('.log-line');

        logLines.forEach(function(line) {
            if (line.textContent.toLowerCase().includes(searchValue)) {
                line.style.display = '';
            } else {
                line.style.display = 'none';
            }
        });
    });
    </script>

</body>

</html>
<section class="content-header">
    <div class="container-fluid">
        <h2>Generar Cita</h2>
    </div>
</section>
<section class="content">
    <div class="container-fluid">

        <label for="dni_paciente">DNI del Paciente:</label>
        <?php //echo "Ruta del script en ejecución: " . __FILE__; ?>
        <?php //echo "Directorio del script en ejecución: " . __DIR__; ?>
        <input type="text" id="dni_paciente" name="dni_paciente" required>
        <button type="button" onclick="getDatosPaciente()">Buscar</button>
        <div id="paciente-info">
            <!-- Aquí se mostrará la información del paciente -->
        </div>
        <br><br>

        <label for="fecha_cita">Fecha de la Cita:</label>
        <input type="date" id="fecha_cita" name="fecha_cita" required><br><br>

        <h2>Servicios Disponibles</h2>
        <table id="tabla_servicios" class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Servicio</th>
                    <th>Turno</th>
                    <th>Citas asignadas</th>
                    <th>Capacidad</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id="servicios-body">
                <!-- Aquí se poblarán dinámicamente los servicios -->
            </tbody>
        </table>

        <form action="paginas/cita/op_cita.php" method="post">
            <input type="hidden" name="action" value="create">

            <h5>Citas a grabar</h5>
            <table id="tabla_citas" class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Servicio</th>
                        <th>Turno</th>
                        <th>Fecha</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se agregarán dinámicamente las citas -->
                </tbody>
            </table>
            <span id="pacienteIdSeleccionado"></span>
            <input type="hidden" id="ups_id" name="ups_id" value="2">
            <button type="submit" class="btn btn-primary">Guardar Citas</button>
        </form>

        <script>
        function getDatosPaciente() {
            var dniPaciente = document.getElementById('dni_paciente').value;

            $.ajax({
                url: 'funciones/get_paciente.php',
                method: 'POST',
                data: {
                    dni_paciente: dniPaciente
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        var pacienteInfo = `
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>DNI</th>
                                        <th>Apellidos y Nombres</th>
                                        <th>Teléfono</th>
                                        <th>Seguro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>${response.data.dni}</td>
                                        <td>${response.data.apellido_p} ${response.data.apellido_m}, ${response.data.nombre}</td>
                                        <td>${response.data.telefono}</td>
                                        <td>${response.data.id}</td>
                                    </tr>
                                </tbody>
                            </table>`;
                        var pacienteIdSeleccionado = `
                            <input type="hidden" id="paciente_id" name="paciente_id" value="${response.data.id}">
                        `;
                        document.getElementById('paciente-info').innerHTML = pacienteInfo;
                        document.getElementById('pacienteIdSeleccionado').innerHTML = pacienteIdSeleccionado;
                    } else {
                        document.getElementById('paciente-info').innerHTML = '<p>Paciente no encontrado</p>';
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#paciente-info').empty().html(
                        `<tr><td colspan="5">${textStatus}: ${errorThrown}</td></tr>`
                    );
                    alert("Hubo un error: " + errorThrown + "\n" + jqXHR.responseText); // Opcional: muestra alerta con el error
                }
            });
        }

        function cargarServicios() {
            var fechaCita = document.getElementById('fecha_cita').value;

            if (!fechaCita) {
                $('#servicios-body').empty();
                $('#servicios-body').html('<tr><td colspan="5">Seleccione una fecha para ver los servicios disponibles</td></tr>');
                return;
            }

            $.ajax({
                url: 'funciones/get_servicios_fecha.php',
                method: 'POST',
                data: {
                    fecha_cita: fechaCita
                },
                dataType: 'json',
                success: function(response) {
                    $('#servicios-body').empty();

                    if (response.length > 0) {
                        response.forEach(function(servicio) {
                            var fila = `
                                <tr>
                                    <td>${servicio.nombre}</td>
                                    <td>${servicio.turno}</td>
                                    <td>${servicio.citas_asignadas}</td>
                                    <td>${servicio.capacidad_total}</td>
                                    <td><button type="button" class="btn btn-block btn-primary btn-sm" onclick="agregarCita(${servicio.id}, '${servicio.nombre}', '${servicio.turno}', '${fechaCita}')">Agregar</button></td>
                                </tr>`;
                            $('#servicios-body').append(fila);
                        });
                    } else {
                        $('#servicios-body').html('<tr><td colspan="5">No hay servicios disponibles para esta fecha</td></tr>');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#servicios-body').empty().html(
                        `<tr><td colspan="5">${textStatus}: ${errorThrown}</td></tr>`
                    );
                }
            });
        }

        function agregarCita(servicioId, servicioNombre, servicioTurno, fechaCita) {
            var tablaCitas = document.getElementById('tabla_citas').getElementsByTagName('tbody')[0];
            var filas = tablaCitas.getElementsByTagName('tr');
            for (var i = 0; i < filas.length; i++) {
                var celdas = filas[i].getElementsByTagName('td');
                if (celdas[0].innerText == servicioNombre && celdas[2].innerText == fechaCita) {
                    alert('El servicio ya está agregado en esta fecha.');
                    return;
                }
            }

            var fila = tablaCitas.insertRow();
            var celdaServicio = fila.insertCell(0);
            var celdaTurno = fila.insertCell(1);
            var celdaFecha = fila.insertCell(2);
            var celdaBoton = fila.insertCell(3);

            celdaServicio.innerHTML = servicioNombre;
            celdaTurno.innerHTML = servicioTurno;
            celdaFecha.innerHTML = fechaCita;
            celdaBoton.innerHTML = '<button type="button" class="btn btn-danger btn-sm" onclick="retirarCita(this)">Retirar</button>';

            var inputServicioId = document.createElement('input');
            inputServicioId.type = 'hidden';
            inputServicioId.name = `citas[${tablaCitas.rows.length - 1}][servicio_id]`;
            inputServicioId.value = servicioId;
            fila.appendChild(inputServicioId);

            var inputFechaCita = document.createElement('input');
            inputFechaCita.type = 'hidden';
            inputFechaCita.name = `citas[${tablaCitas.rows.length - 1}][fecha_cita]`;
            inputFechaCita.value = fechaCita;
            fila.appendChild(inputFechaCita);
        }

        function retirarCita(boton) {
            var fila = boton.parentNode.parentNode;
            fila.parentNode.removeChild(fila);
        }

        $('#fecha_cita').change(cargarServicios);
        </script>
    </div>
    <!-- Script para mostrar el mensaje -->
    <?php if (!empty($_SESSION['mensaje'])): ?>
        <script>
            alert("<?php echo $_SESSION['mensaje']; ?>");
        </script>
        <?php
        // Eliminar el mensaje de la variable de sesión después de mostrarlo
        unset($_SESSION['mensaje']);
    endif;
    ?>
</section>

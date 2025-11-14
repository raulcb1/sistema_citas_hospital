<?php
$_SESSION['datatable'] = 'consultorios';
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Programación de Consultorios</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#" onclick="loadPage('dashboard_admin')">Home</a></li>
                    <li class="breadcrumb-item active">Programar Consultorios</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Calendario y formularios en una sola vista -->
        <div class="row">
            <!-- Calendario -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Calendario de Programaciones
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="filtro-servicio">Filtrar por Servicio:</label>
                                <select id="filtro-servicio" class="form-control">
                                    <option value="">Todos los servicios</option>
                                    <!-- Se llenarán dinámicamente -->
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>&nbsp;</label>
                                <div>
                                    <button id="btn-hoy" class="btn btn-sm btn-info">Hoy</button>
                                    <button id="btn-semana" class="btn btn-sm btn-secondary">Semana</button>
                                    <button id="btn-mes" class="btn btn-sm btn-secondary">Mes</button>
                                </div>
                            </div>
                        </div>
                        <!-- Calendario -->
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>

            <!-- Panel de Programación -->
            <div class="col-md-4">
                <!-- Programación Individual -->
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title">
                            <i class="fas fa-user-md mr-2"></i>
                            Programación Individual
                        </h3>
                    </div>
                    <div class="card-body">
                        <form id="form-individual">
                            <div class="form-group">
                                <label for="medico_individual">Médico:</label>
                                <select id="medico_individual" name="medico_id" class="form-control" required>
                                    <option value="">Seleccionar médico</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="servicio_individual">Servicio:</label>
                                <select id="servicio_individual" name="servicio_id" class="form-control" required>
                                    <option value="">Seleccionar servicio</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="fecha_individual">Fecha:</label>
                                <input type="date" id="fecha_individual" name="fecha" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="turno_individual">Turno:</label>
                                <select id="turno_individual" name="turno" class="form-control" required>
                                    <option value="">Seleccionar turno</option>
                                    <option value="mañana">Mañana</option>
                                    <option value="tarde">Tarde</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save mr-2"></i>Programar
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Programación Recurrente -->
                <div class="card mt-3">
                    <div class="card-header bg-success">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-week mr-2"></i>
                            Programación Recurrente
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="form-recurrente">
                            <div class="form-group">
                                <label for="medico_recurrente">Médico:</label>
                                <select id="medico_recurrente" name="medico_id" class="form-control" required>
                                    <option value="">Seleccionar médico</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="servicio_recurrente">Servicio:</label>
                                <select id="servicio_recurrente" name="servicio_id" class="form-control" required>
                                    <option value="">Seleccionar servicio</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="turno_recurrente">Turno:</label>
                                <select id="turno_recurrente" name="turno" class="form-control" required>
                                    <option value="">Seleccionar turno</option>
                                    <option value="mañana">Mañana</option>
                                    <option value="tarde">Tarde</option>
                                </select>
                            </div>
                            
                            <!-- Tabs para diferentes tipos de recurrencia -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#tab-diario" data-toggle="tab">Diario</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#tab-semanal" data-toggle="tab">Semanal</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#tab-mensual" data-toggle="tab">Mensual</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <!-- Programación Diaria -->
                                    <div class="tab-pane active" id="tab-diario">
                                        <div class="form-group mt-3">
                                            <label>Rango de fechas:</label>
                                            <div class="row">
                                                <div class="col-6">
                                                    <input type="date" id="fecha_inicio_diario" class="form-control" placeholder="Desde">
                                                </div>
                                                <div class="col-6">
                                                    <input type="date" id="fecha_fin_diario" class="form-control" placeholder="Hasta">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Excluir fines de semana:</label>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="excluir_fines_semana" checked>
                                                <label class="custom-control-label" for="excluir_fines_semana">Sí</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Programación Semanal -->
                                    <div class="tab-pane" id="tab-semanal">
                                        <div class="form-group mt-3">
                                            <label>Días de la semana:</label>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input dias-semana" id="lunes" value="1">
                                                        <label class="custom-control-label" for="lunes">Lunes</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input dias-semana" id="martes" value="2">
                                                        <label class="custom-control-label" for="martes">Martes</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input dias-semana" id="miercoles" value="3">
                                                        <label class="custom-control-label" for="miercoles">Miércoles</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input dias-semana" id="jueves" value="4">
                                                        <label class="custom-control-label" for="jueves">Jueves</label>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input dias-semana" id="viernes" value="5">
                                                        <label class="custom-control-label" for="viernes">Viernes</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input dias-semana" id="sabado" value="6">
                                                        <label class="custom-control-label" for="sabado">Sábado</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input dias-semana" id="domingo" value="0">
                                                        <label class="custom-control-label" for="domingo">Domingo</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Rango de fechas:</label>
                                            <div class="row">
                                                <div class="col-6">
                                                    <input type="date" id="fecha_inicio_semanal" class="form-control" placeholder="Desde">
                                                </div>
                                                <div class="col-6">
                                                    <input type="date" id="fecha_fin_semanal" class="form-control" placeholder="Hasta">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Programación Mensual -->
                                    <div class="tab-pane" id="tab-mensual">
                                        <div class="form-group mt-3">
                                            <label>Tipo de repetición:</label>
                                            <select id="tipo_mensual" class="form-control">
                                                <option value="dia_mes">Mismo día del mes</option>
                                                <option value="dia_semana">Mismo día de la semana</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Fecha de inicio:</label>
                                            <input type="date" id="fecha_inicio_mensual" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Número de meses:</label>
                                            <input type="number" id="num_meses" class="form-control" min="1" max="12" value="3">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mt-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="sobreescribir">
                                    <label class="custom-control-label" for="sobreescribir">
                                        Sobreescribir programaciones existentes
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-calendar-plus mr-2"></i>Programar Recurrente
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Información del día seleccionado -->
                <div class="card mt-3">
                    <div class="card-header bg-info">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-2"></i>
                            Información del Día
                        </h3>
                    </div>
                    <div class="card-body" id="info-dia">
                        <p class="text-muted">Selecciona una fecha en el calendario para ver las programaciones.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Scripts específicos para esta página -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales/es.global.min.js'></script>

<style>
.fc-event {
    border-radius: 5px !important;
    border: none !important;
    padding: 2px 5px !important;
    font-size: 12px !important;
}

.fc-daygrid-event {
    margin-bottom: 2px !important;
}

.card-header.bg-primary {
    background: linear-gradient(45deg, #007bff, #0056b3) !important;
    color: white;
}

.card-header.bg-success {
    background: linear-gradient(45deg, #28a745, #1e7e34) !important;
    color: white;
}

.card-header.bg-info {
    background: linear-gradient(45deg, #17a2b8, #117a8b) !important;
    color: white;
}

.nav-tabs-custom {
    margin-top: 10px;
}

.tab-content {
    padding-top: 10px;
}

#calendar {
    min-height: 500px;
}

.alert-dismissible {
    position: fixed;
    top: 70px;
    right: 20px;
    z-index: 1050;
    min-width: 300px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let calendar;
    
    // Inicializar calendario
    initializeCalendar();
    
    // Cargar datos iniciales
    loadMedicos();
    loadServicios();
    
    // Event listeners
    setupEventListeners();
    
    function initializeCalendar() {
        var calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            locale: 'es',
            height: 'auto',
            events: function(fetchInfo, successCallback, failureCallback) {
                const servicioId = document.getElementById('filtro-servicio').value;
                let url = `paginas/consultorios/get_asignaciones.php?start=${fetchInfo.startStr}&end=${fetchInfo.endStr}`;
                if (servicioId) {
                    url += `&servicio_id=${servicioId}`;
                }
                
                fetch(url)
                .then(response => response.json())
                .then(data => successCallback(data))
                .catch(error => {
                    console.error('Error:', error);
                    failureCallback(error);
                });
            },
            dateClick: function(info) {
                // Actualizar fecha en formulario individual
                document.getElementById('fecha_individual').value = info.dateStr;
                
                // Mostrar información del día
                loadDayInfo(info.dateStr);
                
                // Highlight del día seleccionado
                document.querySelectorAll('.fc-day').forEach(el => el.classList.remove('selected-day'));
                info.dayEl.classList.add('selected-day');
            },
            eventClick: function(info) {
                showEventDetails(info.event);
            }
        });
        calendar.render();
    }
    
    function loadMedicos() {
        fetch('paginas/consultorios/get_medicos.php')
        .then(response => response.json())
        .then(data => {
            const selects = ['medico_individual', 'medico_recurrente'];
            selects.forEach(selectId => {
                const select = document.getElementById(selectId);
                select.innerHTML = '<option value="">Seleccionar médico</option>';
                data.forEach(medico => {
                    select.innerHTML += `<option value="${medico.id}">${medico.nombre}</option>`;
                });
            });
        })
        .catch(error => console.error('Error:', error));
    }
    
    function loadServicios() {
        fetch('paginas/consultorios/get_servicios.php')
        .then(response => response.json())
        .then(data => {
            const selects = ['servicio_individual', 'servicio_recurrente', 'filtro-servicio'];
            selects.forEach(selectId => {
                const select = document.getElementById(selectId);
                const isFilter = selectId === 'filtro-servicio';
                select.innerHTML = isFilter ? '<option value="">Todos los servicios</option>' : '<option value="">Seleccionar servicio</option>';
                data.forEach(servicio => {
                    select.innerHTML += `<option value="${servicio.id}">${servicio.nombre}</option>`;
                });
            });
        })
        .catch(error => console.error('Error:', error));
    }
    
    function setupEventListeners() {
        // Formulario individual
        document.getElementById('form-individual').addEventListener('submit', function(e) {
            e.preventDefault();
            saveIndividualAssignment();
        });
        
        // Formulario recurrente
        document.getElementById('form-recurrente').addEventListener('submit', function(e) {
            e.preventDefault();
            saveRecurrentAssignment();
        });
        
        // Filtro de servicio
        document.getElementById('filtro-servicio').addEventListener('change', function() {
            calendar.refetchEvents();
        });
        
        // Botones de vista
        document.getElementById('btn-hoy').addEventListener('click', () => calendar.today());
        document.getElementById('btn-semana').addEventListener('click', () => calendar.changeView('timeGridWeek'));
        document.getElementById('btn-mes').addEventListener('click', () => calendar.changeView('dayGridMonth'));
    }
    
    function saveIndividualAssignment() {
        const formData = new FormData(document.getElementById('form-individual'));
        
        fetch('paginas/consultorios/guardar_asignacion.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Programación guardada exitosamente', 'success');
                document.getElementById('form-individual').reset();
                calendar.refetchEvents();
            } else {
                showAlert(data.error || 'Error al guardar la programación', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error de conexión', 'danger');
        });
    }
    
    function saveRecurrentAssignment() {
        const medicoId = document.getElementById('medico_recurrente').value;
        const servicioId = document.getElementById('servicio_recurrente').value;
        const turno = document.getElementById('turno_recurrente').value;
        const sobreescribir = document.getElementById('sobreescribir').checked;
        
        if (!medicoId || !servicioId || !turno) {
            showAlert('Por favor complete todos los campos requeridos', 'warning');
            return;
        }
        
        const activeTab = document.querySelector('.tab-pane.active').id;
        let fechas = [];
        
        switch(activeTab) {
            case 'tab-diario':
                fechas = generateDailyDates();
                break;
            case 'tab-semanal':
                fechas = generateWeeklyDates();
                break;
            case 'tab-mensual':
                fechas = generateMonthlyDates();
                break;
        }
        
        if (fechas.length === 0) {
            showAlert('No se generaron fechas válidas', 'warning');
            return;
        }
        
        const data = {
            medico_id: parseInt(medicoId),
            servicio_id: parseInt(servicioId),
            turno: turno,
            fechas: fechas,
            sobreescribir: sobreescribir
        };
        
        fetch('paginas/consultorios/guardar_asignaciones_recurrentes_2.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let message = `Se programaron ${fechas.length} citas exitosamente`;
                if (data.conflictos && data.conflictos.length > 0) {
                    message += `. Advertencia: ${data.conflictos.length} fechas tenían programaciones existentes`;
                }
                showAlert(message, 'success');
                document.getElementById('form-recurrente').reset();
                calendar.refetchEvents();
            } else {
                showAlert(data.error || 'Error al guardar las programaciones', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error de conexión', 'danger');
        });
    }
    
    function generateDailyDates() {
        const fechaInicio = document.getElementById('fecha_inicio_diario').value;
        const fechaFin = document.getElementById('fecha_fin_diario').value;
        const excluirFinesSemana = document.getElementById('excluir_fines_semana').checked;
        
        if (!fechaInicio || !fechaFin) return [];
        
        const fechas = [];
        const inicio = new Date(fechaInicio);
        const fin = new Date(fechaFin);
        
        for (let d = new Date(inicio); d <= fin; d.setDate(d.getDate() + 1)) {
            const diaSemana = d.getDay();
            if (!excluirFinesSemana || (diaSemana !== 0 && diaSemana !== 6)) {
                fechas.push(d.toISOString().split('T')[0]);
            }
        }
        
        return fechas;
    }
    
    function generateWeeklyDates() {
        const fechaInicio = document.getElementById('fecha_inicio_semanal').value;
        const fechaFin = document.getElementById('fecha_fin_semanal').value;
        const diasSeleccionados = Array.from(document.querySelectorAll('.dias-semana:checked')).map(cb => parseInt(cb.value));
        
        if (!fechaInicio || !fechaFin || diasSeleccionados.length === 0) return [];
        
        const fechas = [];
        const inicio = new Date(fechaInicio);
        const fin = new Date(fechaFin);
        
        for (let d = new Date(inicio); d <= fin; d.setDate(d.getDate() + 1)) {
            if (diasSeleccionados.includes(d.getDay())) {
                fechas.push(d.toISOString().split('T')[0]);
            }
        }
        
        return fechas;
    }
    
    function generateMonthlyDates() {
        const fechaInicio = document.getElementById('fecha_inicio_mensual').value;
        const numMeses = parseInt(document.getElementById('num_meses').value);
        const tipoMensual = document.getElementById('tipo_mensual').value;
        
        if (!fechaInicio || !numMeses) return [];
        
        const fechas = [];
        const inicio = new Date(fechaInicio);
        
        for (let i = 0; i < numMeses; i++) {
            let fecha;
            if (tipoMensual === 'dia_mes') {
                fecha = new Date(inicio.getFullYear(), inicio.getMonth() + i, inicio.getDate());
            } else {
                // Mismo día de la semana (ej: segundo martes de cada mes)
                const primerDiaMes = new Date(inicio.getFullYear(), inicio.getMonth() + i, 1);
                const diaSemanaObjetivo = inicio.getDay();
                const semanaDelMes = Math.ceil(inicio.getDate() / 7);
                
                fecha = new Date(primerDiaMes);
                fecha.setDate(1 + (diaSemanaObjetivo - primerDiaMes.getDay() + 7) % 7 + (semanaDelMes - 1) * 7);
            }
            
            if (fecha.getMonth() === (inicio.getMonth() + i) % 12) {
                fechas.push(fecha.toISOString().split('T')[0]);
            }
        }
        
        return fechas;
    }
    
    function loadDayInfo(fecha) {
        fetch(`paginas/consultorios/get_eventos_dia.php?fecha=${fecha}`)
        .then(response => response.json())
        .then(data => {
            const infoDiv = document.getElementById('info-dia');
            if (data.length === 0) {
                infoDiv.innerHTML = `<p class="text-muted">No hay programaciones para ${formatDate(fecha)}</p>`;
            } else {
                let html = `<h6><strong>${formatDate(fecha)}</strong></h6>`;
                const manana = data.filter(item => item.turno === 'mañana');
                const tarde = data.filter(item => item.turno === 'tarde');
                
                if (manana.length > 0) {
                    html += '<h6 class="text-primary">Mañana:</h6><ul class="list-unstyled">';
                    manana.forEach(item => {
                        html += `<li><small><i class="fas fa-user-md"></i> ${item.medico} - ${item.servicio}</small></li>`;
                    });
                    html += '</ul>';
                }
                
                if (tarde.length > 0) {
                    html += '<h6 class="text-danger">Tarde:</h6><ul class="list-unstyled">';
                    tarde.forEach(item => {
                        html += `<li><small><i class="fas fa-user-md"></i> ${item.medico} - ${item.servicio}</small></li>`;
                    });
                    html += '</ul>';
                }
                
                infoDiv.innerHTML = html;
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function showAlert(message, type) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;
        
        // Remover alertas anteriores
        const existingAlerts = document.querySelectorAll('.alert-dismissible');
        existingAlerts.forEach(alert => alert.remove());
        
        // Agregar nueva alerta
        document.body.insertAdjacentHTML('afterbegin', alertHtml);
        
        // Auto-remover después de 5 segundos
        setTimeout(() => {
            const alert = document.querySelector('.alert-dismissible');
            if (alert) alert.remove();
        }, 5000);
    }
    
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('es-ES', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
    }
    
    function showEventDetails(event) {
        const props = event.extendedProps;
        const details = `
            <strong>Servicio:</strong> ${props.servicio}<br>
            <strong>Médico:</strong> ${props.medico}<br>
            <strong>Turno:</strong> ${props.turno}<br>
            <strong>Fecha:</strong> ${formatDate(event.startStr)}
        `;
        
        // Aquí podrías mostrar un modal o tooltip con los detalles
        showAlert(details, 'info');
    }
});

// Función para cargar página (compatible con tu sistema de navegación)
function loadPage(pageName) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.style.display = 'none';
    
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'pagina';
    input.value = pageName;
    
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}
</script>

<!-- CSS adicional para el día seleccionado -->
<style>
.selected-day {
    background-color: rgba(0, 123, 255, 0.2) !important;
}

.fc-day:hover {
    background-color: rgba(0, 123, 255, 0.1) !important;
    cursor: pointer;
}

.custom-control-label {
    font-size: 14px;
}

.form-group label {
    font-weight: 600;
    color: #495057;
}

.btn-block {
    font-weight: 600;
}

.card-tools .btn-tool {
    color: rgba(255, 255, 255, 0.8);
}

.card-tools .btn-tool:hover {
    color: white;
}

.nav-tabs .nav-link {
    font-size: 14px;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    font-weight: 600;
}
</style>
// Devuelve una lista de eventos (citas ocupadas) para FullCalendar
async function obtenerCitasOcupadas(info, servicioId, anio, mes) {
  try {
    const res = await fetch(`funciones/get_horas_ocupadas_mes.php?servicio_id=${servicioId}&anio=${anio}&mes=${mes}`);
    const data = await res.json();
    if (data.success) {
      return data.data;
    } 
    else {
      if (DEBUG_MODE) debug_log('⚠️ Error: ' + data.error);
      return [];
    }
  } 
  catch (err) {
    if (DEBUG_MODE) debug_log('❌ Error de red: ' + err.message);
    return [];
  }
}

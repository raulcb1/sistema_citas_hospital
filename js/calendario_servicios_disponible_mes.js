// Devuelve bloques background con días programados por servicio
async function obtenerDiasProgramados(info, servicioId, anio, mes) {
  try {
    const res = await fetch(`funciones/get_dias_programados_mes.php?servicio_id=${servicioId}&anio=${anio}&mes=${mes}`);
    const data = await res.json();
    if (data.success) {
      return data.data;
    } else {
      if (DEBUG_MODE) debug_log('⚠️ Error días programados: ' + data.error);
      return [];
    }
  } catch (err) {
    if (DEBUG_MODE) debug_log('❌ Error de red (programación): ' + err.message);
    return [];
  }
}
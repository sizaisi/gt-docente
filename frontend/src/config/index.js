const config = { 

  /***** SISDOCE *****/
  API_URL: '//190.119.145.150:8022/sisacad/sisdoce/graduacion/gt-docente/backend',

  /***** LOCAL *****/
  //API_URL: '//localhost/gt-docente/backend',

  //para asignacion de colores a los movimientos o rutas de salida de los procedimientos
  color_acciones: {
    enviar: 'success',
    derivar: 'success',
    devolver: 'danger',
    denegar: 'danger',
    observar: 'warning',
    rechazar: 'danger',
    aceptar: 'success',
    aprobar: 'success',
    anular: 'danger',
    finalizar: 'primary',
  },
  //para asignacion de colores a los estados del listado de expedientes
  color_estados: {
    enviado: 'success',
    derivado: 'success',
    devuelto: 'danger',
    denegado: 'danger',
    observado: 'warning',
    rechazado: 'danger',
    aceptado: 'success',
    aprobado: 'success',
    anulado: 'danger',
    finalizado: 'primary',
  },
  //para movimientos de ingreso a un procedimiento
  estados: {
    enviar: 'enviado',
    derivar: 'derivado',
    devolver: 'devuelto',
    denegar: 'denegado',
    observar: 'observado',
    rechazar: 'rechazado',
    aceptar: 'aceptado',
    aprobar: 'aprobado',
    anular: 'anulado',
    finalizar: 'finalizado',
  },
};

export default config;

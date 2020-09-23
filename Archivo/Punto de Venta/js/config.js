var linkRest = "http://localhost:8080/Archivo/punto_venta_rest/";

// Funcion para llamar a ajax
function ajax(url, data, metodo){
  var ajax = $.ajax({
    "method" : metodo,
    "url" : url,
    "data" : data
  })

  return ajax;
}
// Fin de la funcion

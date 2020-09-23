// Preguntamos si ya se encuentra logueado o no
function preguntarSession(){
  if (localStorage.getItem("isLoged")!="TRUE") {
    sessionClose();
  }
}


permission();


function permission(){
  data = {
    data: localStorage.getItem("data"),
    pruebaData: localStorage.getItem("pruebaData")
  };
  ajax(linkRest+"permission.php", data, "POST")
    .done(function(info){
      if (info.data == "fail") {
        errorGeneral();
      }else{
        changeHeader(info.data[0][0]);
      }
    })
    .fail(function(){
      errorGeneral();
    });
}


// Funcion Header

function changeHeader(permiso){
  $("header").empty();
  if(permiso == "1"){
    $("header").append(
      "<nav>"
        +"<a href='#' data-target='slide-out' class='sidenav-trigger'><i class='material-icons'>menu</i></a>"
        +"<span>Bienvenido/a, "+localStorage.getItem("nombre")+"</span>"
      +"</nav>"
      +"<ul id='slide-out' class='sidenav'>"
        +"<li><div class='user-view'>"
        +"<div class='background'>"
          +"<img src='img/logoCim.jpg'>"
        +"</div>"
        +"</div></li>"
        +"<li><a class='subheader'>Pedidos -</a></li>"
        +"<li><a class='waves-effect' href='index.html'>Lista de pedidos</a></li>"
        +"<li><a class='waves-effect' href='nuevo_pedido.html'>Nuevo Pedido</a></li>"
        +"<li><div class='divider'></div></li>"        
        +"<li><a class='subheader'>Productos -</a></li>"
        +"<li><a class='waves-effect' href='nuevo_producto.html'>Nuevo producto</a></li>"
        +"<li><a class='waves-effect' href='lista_productos.html'>Lista de productos</a></li>"
        +"<li><div class='divider'></div></li>"
        +"<li><a class='subheader'>Gastos -</a></li>"
        +"<li><a class='waves-effect' href='lista_gastos.html'>Lista de gastos</a></li>"
        +"<li><a class='waves-effect' href='#'>Ingresar Gasto</a></li>"
        +"<li><div class='divider'></div></li>"
        +"<li><a class='subheader'>Caja -</a></li>"
        +"<li><a class='waves-effect' href='#!' onclick='cerrarCaja()'>Cerrar caja</a></li>"
        +"<li><div class='divider'></div></li>"
        +"<li><a class='subheader'>Clientes -</a></li>"
        +"<li><a class='waves-effect' href='nuevo_cliente.html'>Nuevo cliente</a></li>"
        +"<li><a class='waves-effect' href='clientes.html'>Listado clientes</a></li>"
        +"<li><div class='divider'></div></li>"
        +"<li><a class='subheader'>Informes -</a></li>"
        +"<li><a class='waves-effect' href='informes.html'>Historial de ventas</a></li>"
        +"<li><div class='divider'></div></li>"
        +"<li><a class='subheader'>Sesión -</a></li>"
        +"<li><a class='waves-effect' onclick='sessionClose()'>Cerrar Sesión</a></li>"
        +"<li><div class='divider'></div></li>"
      +"</ul>"
    );
  }else{
    $("header").append(
      "<nav>"
        +"<a href='#' data-target='slide-out' class='sidenav-trigger'><i class='material-icons'>menu</i></a>"
        +"<span>Bienvenido/a, "+localStorage.getItem("nombre")+"</span>"
      +"</nav>"
      +"<ul id='slide-out' class='sidenav'>"
        +"<li><div class='user-view'>"
        +"<div class='background'>"
          +"<img src='img/logoCim.jpg'>"
        +"</div>"
        +"</div></li>"
        +"<li><a class='subheader'>Pedidos -</a></li>"
        +"<li><a class='waves-effect' href='index.html'>Nuevo pedido</a></li>"
        +"<li><a class='waves-effect' href='lista_pedidos.html'>Lista de pedidos</a></li>"
        +"<li><div class='divider'></div></li>"
        +"<li><a class='subheader'>Productos -</a></li>"
        +"<li><a class='waves-effect' href='nuevo_producto.html'>Nuevo producto</a></li>"
        +"<li><a class='waves-effect' href='lista_productos.html'>Lista de productos</a></li>"
        +"<li><div class='divider'></div></li>"
        +"<li><a class='subheader'>Caja -</a></li>"
        +"<li><a class='waves-effect' href='#!' onclick='cerrarCaja()'>Cerrar caja</a></li>"
        +"<li><div class='divider'></div></li>"
        +"<li><a class='subheader'>Sesión -</a></li>"
        +"<li><a class='waves-effect' onclick='sessionClose()'>Cerrar Sesión</a></li>"
        +"<li><div class='divider'></div></li>"
      +"</ul>"
    );
  }

  $('.sidenav').sidenav();
  
}



// Función login
$("#formLogin").submit(function(e){
  activarPreloader();
  e.preventDefault();
  data = {
    user: $("#login-user").val(),
    pass: $("#login-pass").val()
  };
    ajax(linkRest+"login.php", data, "POST")
    .done(function(info){
      if (info.data == "fail") {
        desactivarPreloader();
        M.toast({html: 'Usuario o contraseña incorrectos'});
      }else{
        localStorage.setItem("isLoged", "TRUE");
        desactivarPreloader();
        localStorage.setItem("nombre", info.data[1].nombre);
        localStorage.setItem("data", info.data[1].id_usuario);
        localStorage.setItem("pruebaData", info.data[2]);
        M.toast({html: 'Bienvenido/a, '+info.data[1].nombre, completeCallback: function(){location.href = "index.html";}, displayLength: 1000});
      }
    })
    .fail(function(){
      desactivarPreloader();
      errorGeneral();
    });
});

// FUNCION AGREGAR PRODUCTO

$("#guardarProducto").click(function(){

  if( $('#isIlimitado').prop('checked') ) {
    var ilimitado = 1;
  }else{
    var ilimitado = 0;
  }

  data = {
    data: localStorage.getItem("data"),
    pruebaData: localStorage.getItem("pruebaData"),
    codigo: $("#codProducto").val(),
    descripcion: $("#descProducto").val(),
    stock: $("#stock").val(),
    precioCosto: $("#precioCosto").val(),
    precioVenta: $("#precioProducto").val(),
    precioMayorista: $("#precioMayorista").val(),    
    ilimitado : ilimitado
  };

  ajax(linkRest+"add_producto.php", data, "POST")
    .done(function(info){

      if (info.data == "fail") {
        M.toast({html: ''+info.errores[1]});
      }else{
        M.toast({html: 'Producto añadido con éxito'});
        $("#codProducto").val("");
        $("#descProducto").val("");
        $("#stock").val("");
        $("#precioCosto").val("");
        $("#precioProducto").val("");
        $("#precioMayorista").val("");
        $('#isIlimitado').prop('checked', false);
      }
      
    })
    .fail(function(){
      desactivarPreloader();
      errorGeneral();
    });

});

// FUNCION PARA LISTAR PRODUCTOS

function listarProductos(param){
  data = {
    data: localStorage.getItem("data"),
    pruebaData: localStorage.getItem("pruebaData"),
    parametro: param
  };
  ajax(linkRest+"listar_productos.php", data, "POST")
    .done(function(info){
      
      if (info.data == "fail") {
        M.toast({html: ''+info.errores[1]});
      }else{       
        listaDeProductos = info.data;
        console.log(info);      
        for (var i in info.data) {
          if(info.data[i].ilimitado == 1){
            var ilimitado = "Sí";
          }else{
            var ilimitado = "No"
          }
          $("#listaProductos tbody").append(
            "<tr>"
              +"<td>"+info.data[i].codigo+"</td>"
              +"<td>"+info.data[i].descripcion+"</td>"
              +"<td>"+info.data[i].stock+"</td>"
              +"<td>"+ilimitado+"</td>"
              +"<td>"+info.data[i].precio+"</td>"
              +"<td>"+info.data[i].precio_mayorista+"</td>"
              +"<td>"+info.data[i].precio_costo+"</td>"
              +"<td><a href='editar_producto.html?id_producto="+info.data[i].id_producto+"'>Editar</a></td>"
              +"<td class='btn-borrar' onclick='toastBorrarProducto("+info.data[i].id_producto+")'>Borrar</td>"
          );

          $("#listaProductosPrint tbody").append(
            "<tr>"
              +"<td>"+info.data[i].codigo+"</td>"
              +"<td>"+info.data[i].descripcion+"</td>"
              +"<td>"+info.data[i].stock+"</td>"
              +"<td>"+ilimitado+"</td>"
              +"<td>"+info.data[i].precio+"</td>"
              +"<td>"+info.data[i].precio_costo+"</td>"
              +"<td>"+info.data[i].precio_mayorista+"</td>"
          );
        }
      }
      
    })
    .fail(function(){

      errorGeneral();
    });
}

// Funcion para traer los datos del producto a editar



function single_producto(){
  // funcion para usar los parametros url
  var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;
  
    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');
  
        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
  };
  var id_producto = getUrlParameter('id_producto');
  data = {
    data: localStorage.getItem("data"),
    pruebaData: localStorage.getItem("pruebaData"),
    id_producto: id_producto
  };
  ajax(linkRest+"single_producto.php", data, "POST")
    .done(function(info){
      console.log(info);      
      if (info.data == "fail") {
        M.toast({html: ''+info.errores[1]});
      }else{
        $("#codProducto").val(info.data[0].codigo);
        $("#descProducto").val(info.data[0].descripcion);
        $("#stock").val(info.data[0].stock);
        $("#precioProducto").val(info.data[0].precio);
        $("#precioCosto").val(info.data[0].precio_costo);
        $("#precioMayorista").val(info.data[0].precio_mayorista);
      }
      
    })
    .fail(function(){

      errorGeneral();
    });
}

// Funcion para editar producto

$("#editarProducto").click(function(){
  // funcion para usar los parametros url
  if(($("#precioCosto").val())< ($("#precioProducto").val()) ){
  var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;
  
    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');
  
        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
  };
  var id_producto = getUrlParameter('id_producto');
  if( $('#isIlimitado').prop('checked') ) {
    var ilimitado = 1;
  }else{
    var ilimitado = 0;
  }
  data = {
    data: localStorage.getItem("data"),
    pruebaData: localStorage.getItem("pruebaData"),
    codigo: $("#codProducto").val(),
    descripcion: $("#descProducto").val(),
    stock: $("#stock").val(),
    precioCosto: $("#precioCosto").val(),
    precioVenta: $("#precioProducto").val(),
    precioMayorista: $("#precioMayorista").val(),    
    id_producto: id_producto,
    ilimitado : ilimitado
  };
  

  ajax(linkRest+"editar_producto.php", data, "POST")
    .done(function(info){
      console.log(info);
      

      if (info.data == "fail") {
        M.toast({html: ''+info.errores[1]});
      }else{
        M.toast({html: 'Cambios guardados'});
        setTimeout(() => {
          location.href = "lista_productos.html";;
        }, 2000);
      }      
      
    })
    .fail(function(){
      desactivarPreloader();
      errorGeneral();
    });
  }else{
    M.toast({html: 'ERROR: El precio de Venta es menor al Precio de costo'});
  }
});

// Funcion para buscar producto
$("#searchProductPerCode").keyup(function(){
  $("#listaProductos tbody").empty();
  for (var i in listaDeProductos) {
    if (listaDeProductos[i].codigo.match($("#searchProductPerCode").val())){
      if(listaDeProductos[i].ilimitado == 1){
        var ilimitado = "Sí";
      }else{
        var ilimitado = "No"
      }
      $("#listaProductos tbody").append(
        "<tr>"
          +"<td>"+listaDeProductos[i].codigo+"</td>"
          +"<td>"+listaDeProductos[i].descripcion+"</td>"
          +"<td>"+listaDeProductos[i].stock+"</td>"
          +"<td>"+ilimitado+"</td>"
          +"<td>"+listaDeProductos[i].precio+"</td>"
          +"<td><a href='editar_producto.html?id_producto="+listaDeProductos[i].id_producto+"'>Editar</a></td>"
          +"<td class='btn-borrar' onclick='toastBorrarProducto("+listaDeProductos[i].id_producto+")'>Borrar</td>"
      );
    }
  }
});

$("#searchProductPerName").keyup(function(){
  $("#listaProductos tbody").empty();  
  for (var i in listaDeProductos) {
    if (listaDeProductos[i].descripcion.toLowerCase().match($("#searchProductPerName").val().toLowerCase())){
      if(listaDeProductos[i].ilimitado == 1){
        var ilimitado = "Sí";
      }else{
        var ilimitado = "No"
      }
      $("#listaProductos tbody").append(
        "<tr>"
          +"<td>"+listaDeProductos[i].codigo+"</td>"
          +"<td>"+listaDeProductos[i].descripcion+"</td>"
          +"<td>"+listaDeProductos[i].stock+"</td>"
          +"<td>"+ilimitado+"</td>"
          +"<td>"+listaDeProductos[i].precio+"</td>"
          +"<td><a href='editar_producto.html?id_producto="+listaDeProductos[i].id_producto+"'>Editar</a></td>"
          +"<td class='btn-borrar' onclick='toastBorrarProducto("+listaDeProductos[i].id_producto+")'>Borrar</td>"
      );
    }
  }
});

// Funcion para preguntar si quiere borrar o no el producto
function toastBorrarProducto(id_producto){
  var toastHTML = '<span>¿Borrar producto?</span><button class="btn-flat toast-action" onclick="borrarProducto('+id_producto+')">ok</button>';
  M.toast({html: toastHTML});
}

// Funcion para borrado logico de productos
function borrarProducto(id_producto){
  data = {
    data: localStorage.getItem("data"),
    pruebaData: localStorage.getItem("pruebaData"),
    id_producto: id_producto
  };
  ajax(linkRest+"borrar_producto.php", data, "POST")
    .done(function(info){  
      if (info.data == "fail") {
        M.toast({html: ''+info.errores[1]});
      }else{
        M.toast({html: 'Producto borrado con éxito'});
        setTimeout(() => {
          location.reload();
        }, 1000);
      }
      
    })
    .fail(function(){
      errorGeneral();
    });
}

// Preguntar si hay un pedido no guardado del usuario, si hay uno guardado se abre ese pedido, si no
// se crea un nuevo pedido a nombre de ese usuario, se trae el id del pedido y se crea la estructura html

function nuevoPedido(){
  var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;
  
    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');
  
        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
  };
  var id_pedido = getUrlParameter('id_pedido');
  if(id_pedido){
    data = {
      data: localStorage.getItem("data"),
      pruebaData: localStorage.getItem("pruebaData"),
      id_pedido_actual: id_pedido
    };
  }else{
    data = {
      data: localStorage.getItem("data"),
      pruebaData: localStorage.getItem("pruebaData"),
      id_pedido_actual: ""
    };
  }
  ajax(linkRest+"nuevo_pedido.php", data, "POST")
    .done(function(info){  
      console.log(info);
      if (info.data == "fail") {
        M.toast({html: ''+info.errores[1]});
      }else{
        id_pedido_actual = info.data[0].id_pedido;
        $(".listaPedidoItem").empty();
        totalParcial = 0;
        for (var i in info.productos) {
          var precioTotal = info.productos[i].precio*info.productos[i].cantidad;
          var precioUnitario = parseInt(info.productos[i].precio);
          totalParcial = totalParcial + precioTotal;
          $(".listaPedidoItem").append(
            "<tr id='"+info.productos[i].codigo+"'>"
              +"<td class='codigo'>"+info.productos[i].codigo+"</td>"
              +"<td class='descripcion'>"+info.productos[i].descripcion+"</td>"
              +"<td class='cantidad'>"+info.productos[i].cantidad+"</td>"
              +"<td class='precUnitario'>"+precioUnitario.toLocaleString('es-PY')+"</td>"
              +"<td class='precioTotal'>"+precioTotal.toLocaleString('es-PY')+"</td>"
              +"<td class='' onclick='eliminarPedidoItem("+info.productos[i].id_producto+", "+id_pedido_actual+")' ><i class='short material-icons'>delete</i></td>"
            +"</tr>"
          );
        }
        $("#totalParcial").text(totalParcial.toLocaleString('es-PY', { style: 'currency', currency: 'PYG' }));
        
      }
      
      
    })
    .fail(function(){
      errorGeneral();
    });
}

// AGREGAR ITEM AL PEDIDO

function addProductToPedido(codigoProducto){
  data = {
    data: localStorage.getItem("data"),
    pruebaData: localStorage.getItem("pruebaData"),
    codigoProducto: codigoProducto,
    id_pedido: id_pedido_actual,
    cantidad: $("#cantidadItem").val()
  };

  ajax(linkRest+"add_product_to_pedido.php", data, "POST")
    .done(function(info){  
      console.log(info);
      if(info.data=="fail"){
        M.toast({html: ''+info.errores[0]});
      }else{
        if($("#"+info.data[0].codigo).length){
          var sumarCantidad = parseInt($("#"+info.data[0].codigo+" .cantidad").text()) + parseInt($("#cantidadItem").val());
          var precioTotal = parseFloat(info.data[0].precio) * parseFloat(sumarCantidad);
          $("#"+info.data[0].codigo+" .cantidad").text(sumarCantidad);
          $("#"+info.data[0].codigo+" .precioTotal").text(precioTotal.toLocaleString('es-PY'));
        }else{
          var precioTotal = parseFloat(info.data[0].precio) * parseFloat($("#cantidadItem").val());
          var precioUnitario = info.data[0].precio;
          $(".listaPedidoItem").append(
            "<tr id='"+info.data[0].codigo+"'>"
              +"<td class='codigo'>"+info.data[0].codigo+"</td>"
              +"<td class='descripcion'>"+info.data[0].descripcion+"</td>"
              +"<td class='cantidad'>"+$("#cantidadItem").val()+"</td>"
              +"<td class='precUnitario'>"+precioUnitario.toLocaleString("es-PY")+"</td>"
              +"<td class='precioTotal'>"+precioTotal.toLocaleString('es-PY')+"</td>"
              +"<td class='' onclick='eliminarPedidoItem("+info.data[0].id_producto+", "+id_pedido_actual+")' ><i class='short material-icons'>delete</i></td>"
            +"</tr>"
          );
        }
        totalParcial = totalParcial + (parseFloat($("#cantidadItem").val())* parseFloat(info.data[0].precio));
        $("#totalParcial").text(totalParcial.toLocaleString('es-PY', { style: 'currency', currency: 'PYG' }));
        $("#cantidadItem").val(1);
        $("#codProductAdd").val("");
        $("#nameProductAdd").val("");
      }
      
    })
    .fail(function(){
      errorGeneral();
    });
}

$("#add-product").submit(function(e){
  e.preventDefault();
  if($("#codProductAdd").val()!=""){
    addProductToPedido($("#codProductAdd").val());  
  }else{
    var InputInicial = $("#nameProductAdd").val();
    var Dividido = InputInicial.split('Código: ');
    addProductToPedido(Dividido[1]);
  }
  
});

$("#add-product-per-name").submit(function(e){
  e.preventDefault();

  

  // var codigo = $("#nameProductAdd").val()
  // addProductToPedido(); 
});

// FUNCIONES PARA BORRADO DE PEDIDO ITEM

function eliminarPedidoItem(id_producto, id_pedido){

  var toastHTML = '<span>¿Eliminar producto del pedido?</span><button class="btn-flat toast-action" onclick="borrarPedidoItem('+id_producto+','+id_pedido+')">ok</button>';
  M.toast({html: toastHTML});

}

function borrarPedidoItem(id_producto, id_pedido){
  data = {
    data: localStorage.getItem("data"),
    pruebaData: localStorage.getItem("pruebaData"),
    id_producto: id_producto,
    id_pedido: id_pedido
  };
  ajax(linkRest+"borrar_pedido_item.php", data, "POST")
    .done(function(info){  
      console.log(info);
      if (info.data == "fail") {
        M.toast({html: ''+info.errores[1]});
      }else{
        totalParcial = totalParcial - info.data['totalItem'];
        $("#"+info.data[0]).remove();
        $("#totalParcial").text(totalParcial.toLocaleString('es-PY', { style: 'currency', currency: 'PYG' }));
        M.toast({html: 'Producto eliminado del pedido'});
        
      }
      
    })
    .fail(function(){
      errorGeneral();
    });
}

// FUNCION PARA LISTAR PEDIDOS

function listarPedidos(parametro){
  data = {
    data: localStorage.getItem("data"),
    pruebaData: localStorage.getItem("pruebaData"),
    parametro: parametro
  };
  ajax(linkRest+"listarPedidos.php", data, "POST")
  .done(function(info){  
    console.log(info);
    if (info.data == "fail") {
      M.toast({html: ''+info.errores[1]});
    }else{
      listaDePedidos = info.data;
      $(".tbodyPedidos").empty();
      for (var i in info.data) {
        var estadoPedido = "";
        var claseEstadoPedido = "";
        if(info.data[i].vendido == 0){
          estadoPedido = "Abierto";
          claseEstadoPedido = "estado_abierto";
        }else{
          estadoPedido = "Finalizado";
          claseEstadoPedido = "estado_cerrado";
        }
        $(".tbodyPedidos").append(
          "<tr>"
            +"<td>"+info.data[i].id_pedido+"</td>"
            +"<td class = '"+claseEstadoPedido+"'>"+estadoPedido+"</td>"
            +"<td>"+info.data[i].fecha_creacion+"</td>"
            +"<td>"+info.data[i].fecha_modificacion+"</td>"
            +"<td><a href='nuevo_pedido.html?id_pedido="+info.data[i].id_pedido+"'>Ver pedido</a></td>"
          );
      }
      
    }
    
  })
  .fail(function(){
    errorGeneral();
  });
}

// Funcion para buscar pedido por codigo

$("#searchPedidoPerCode").keyup(function(){
  
  $(".tbodyPedidos").empty();
  for (var i in listaDePedidos) {
    if (listaDePedidos[i].id_pedido.match($("#searchPedidoPerCode").val())){
      var estadoPedido = "";
      var claseEstadoPedido = "";
      if(listaDePedidos[i].vendido == 0){
        estadoPedido = "Abierto";
        claseEstadoPedido = "estado_abierto";
      }else{
        estadoPedido = "Finalizado";
        claseEstadoPedido = "estado_cerrado";
      }
      $(".tbodyPedidos").append(
        "<tr>"
          +"<td>"+listaDePedidos[i].id_pedido+"</td>"
          +"<td class = '"+claseEstadoPedido+"'>"+estadoPedido+"</td>"
          +"<td>"+listaDePedidos[i].fecha_creacion+"</td>"
          +"<td>"+listaDePedidos[i].fecha_modificacion+"</td>"
          +"<td><a href='nuevo_pedido.html?id_pedido="+listaDePedidos[i].id_pedido+"'>Ver pedido</a></td>"
      );
    }
    
  }
});

// FUNCION PARA GUARDAR UN PEDIDO
$(".opcSave").click(function(){
  data = {
    data: localStorage.getItem("data"),
    pruebaData: localStorage.getItem("pruebaData"),
    id_pedido_actual: id_pedido_actual
  };
  ajax(linkRest+"guardar_pedido.php", data, "POST")
    .done(function(info){  
      console.log(info);
      if (info.data == "fail") {
        M.toast({html: ''+info.errores[1]});
      }else{
        M.toast({html: 'Pedido guardado con éxito'});      
      }
      
    })
    .fail(function(){
      errorGeneral();
    });
});

// ELIMINAR PEDIDO

$(".opcDelete").click(function(){
  var toastHTML = '<span>¿Eliminar pedido actual?</span><button class="btn-flat toast-action" onclick="borrarPedido('+id_pedido_actual+')">ok</button>';
  M.toast({html: toastHTML});
});

function borrarPedido(id_pedido){
  data = {
    data: localStorage.getItem("data"),
    pruebaData: localStorage.getItem("pruebaData"),
    id_pedido_actual: id_pedido
  };
  ajax(linkRest+"eliminar_pedido.php", data, "POST")
  .done(function(info){  
    console.log(info);
    if (info.data == "fail") {
      M.toast({html: ''+info.errores[1]});
    }else{
      M.toast({html: 'Pedido eliminado con éxito', completeCallback: function(){location.href = "index.html";}, displayLength: 1000});    
    }
    
  })
  .fail(function(){
    errorGeneral();
  });
}

// FUNCION PARA COBRAR PEDIDO
$(".opcSell").click(function(){
  $("#totalACobrar p").text(totalParcial.toLocaleString('es-PY', { style: 'currency', currency: 'PYG' }));
  $("#cambio p").text((totalParcial* -1).toLocaleString('es-PY', { style: 'currency', currency: 'PYG' }));
});

$("#montoAbonado input").keyup(function(){
  
  if($("#montoAbonado input").val()!==""){
    cambioPedidoActual =  parseFloat($("#montoAbonado input").val()) - parseFloat(totalParcial);
    $("#cambio p").text(cambioPedidoActual.toLocaleString('es-PY', { style: 'currency', currency: 'PYG' }));
  }else{
    $("#cambio p").text((totalParcial* -1).toLocaleString('es-PY', { style: 'currency', currency: 'PYG' }));
  }
  
});

$(".btnCobrar").click(function(){
  if(typeof cambioPedidoActual !== 'undefined'){
    if(cambioPedidoActual<0){
      M.toast({html: 'El monto a abonar es menor al total a cobrar'});
    }else{
      data = {
        data: localStorage.getItem("data"),
        pruebaData: localStorage.getItem("pruebaData"),
        id_pedido_actual: id_pedido_actual,
        total : totalParcial
      };
      ajax(linkRest+"cobrar_pedido.php", data, "POST")
      .done(function(info){  
        console.log(info);
        if (info.data == "fail") {
          M.toast({html: ''+info.errores[1]});
        }else{
          var toastHtml = '<span>Pedido cerrado con éxito. Cambio : '+cambioPedidoActual+'</span><button class="btn-flat toast-action" onclick="toIndex()">Ok</button>';
          M.toast({html: toastHtml, completeCallback: function(){location.href = "index.html";}, displayLength: 20000});    
        }
        
      })
      .fail(function(){
        errorGeneral();
      });
    }
  }else{
    M.toast({html: 'Inserte un monto a abonar'});
  }
  
});

function toIndex(){
  location.href = "index.html";
}


// FUNCION PARA LISTAR CLIENTES

function listarClientes(){
  data = {
    data: localStorage.getItem("data"),
    pruebaData: localStorage.getItem("pruebaData")
  };
  ajax(linkRest+"listar_clientes.php", data, "POST")
  .done(function(info){  
    console.log(info);
    if (info.data == "fail") {
      M.toast({html: ''+info.errores[1]});
    }else{
      listaDeClientes = info.data;
      $("#listaClientes tbody").empty();
      for (var i in info.data) {
        $("#listaClientes tbody").append(
          "<tr>"
            +"<td>"+info.data[i].ci+"</td>"
            +"<td>"+info.data[i].nombre+"</td>"
            +"<td>"+info.data[i].telefono+"</td>"
            +"<td>"+info.data[i].direccion+"</td>"
            +"<td><a href='editar_cliente.html?id_cliente="+info.data[i].id_cliente+"'>Editar</a></td>"
            +"<td class='btn-borrar' onclick='toastBorrarCliente("+info.data[i].id_cliente+")'>Borrar</td>"
          );
      }
      
    }
    
  })
  .fail(function(){
    errorGeneral();
  });
}


// BUSCAR CLIENTES POR NRO DE DOCUMENTO

$("#searchClientePerCi").keyup(function(){
  
  $("#listaClientes tbody").empty();
  for (var i in listaDeClientes) {
    if (listaDeClientes[i].ci.match($("#searchClientePerCi").val())){
      $("#listaClientes tbody").append(
        "<tr>"
          +"<td>"+listaDeClientes[i].ci+"</td>"
          +"<td>"+listaDeClientes[i].nombre+"</td>"
          +"<td>"+listaDeClientes[i].telefono+"</td>"
          +"<td>"+listaDeClientes[i].direccion+"</td>"
          +"<td><a href='editar_cliente.html?id_cliente="+listaDeClientes[i].id_cliente+"'>Editar</a></td>"
            +"<td class='btn-borrar' onclick='toastBorrarCliente("+listaDeClientes[i].id_cliente+")'>Borrar</td>"
      );
    }
    
  }
});


// Agregar nuevo CLIENTE

$("#guardarCliente").click(function(){

  data = {
    data: localStorage.getItem("data"),
    pruebaData: localStorage.getItem("pruebaData"),
    doc: $("#documentoCliente").val(),
    nombre: $("#nombreCliente").val(),
    tel: $("#telCliente").val(),
    direccion: $("#direccionCliente").val()
  };

  ajax(linkRest+"add_cliente.php", data, "POST")
    .done(function(info){

      if (info.data == "fail") {
        M.toast({html: ''+info.errores[1]});
      }else{
        M.toast({html: 'Cliente agregado con éxito'});
        setTimeout(() => {
          location.href = 'clientes.html';
        }, 2000);
      }
      
    })
    .fail(function(){
      desactivarPreloader();
      errorGeneral();
    });

});

// TRAER DATOS DEL CLIENTE A EDITAR
function single_cliente(){
  // funcion para usar los parametros url
  var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;
  
    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');
  
        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
  };
  var id_cliente = getUrlParameter('id_cliente');
  data = {
    data: localStorage.getItem("data"),
    pruebaData: localStorage.getItem("pruebaData"),
    id_cliente: id_cliente
  };
  ajax(linkRest+"single_cliente.php", data, "POST")
    .done(function(info){    
      if (info.data == "fail") {
        M.toast({html: ''+info.errores[1]});
      }else{
        console.log(info);
        $("#docCliente").val(info.data[0].ci);
        $("#nombreCliente").val(info.data[0].nombre);
        $("#dirCliente").val(info.data[0].direccion);
        $("#telCliente").val(info.data[0].telefono);
      }
      
    })
    .fail(function(){

      errorGeneral();
    });
}

// EDITAR CLIENTE

$("#editarCliente").click(function(){
  // funcion para usar los parametros url
  var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;
  
    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');
  
        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
  };
  var id_cliente = getUrlParameter('id_cliente');
  data = {
    data: localStorage.getItem("data"),
    pruebaData: localStorage.getItem("pruebaData"),
    doc: $("#docCliente").val(),
    nombre: $("#nombreCliente").val(),
    tel: $("#telCliente").val(),
    dir: $("#dirCliente").val(),
    id_cliente: id_cliente
  };
  

  ajax(linkRest+"editar_cliente.php", data, "POST")
    .done(function(info){
      console.log(info);
      if (info.data == "fail") {
        M.toast({html: ''+info.errores[1]});
      }else{
        M.toast({html: 'Cambios guardados'});
        setTimeout(() => {
          location.href = 'clientes.html';
        }, 2000);
      }
      
    })
    .fail(function(){
      desactivarPreloader();
      errorGeneral();
    });
});

// TOAST BORRAR CLIENTE (preguntamos si quiere borrar el cliente o no)
function toastBorrarCliente(id_cliente){
  var toastHTML = '<span>¿Borrar este cliente?</span><button class="btn-flat toast-action" onclick="borrarCliente('+id_cliente+')">ok</button>';
  M.toast({html: toastHTML});
}

// BORRADO LOGICO DE CLIENTE

function borrarCliente(id_cliente){
  data = {
    data: localStorage.getItem("data"),
    pruebaData: localStorage.getItem("pruebaData"),
    id_cliente: id_cliente
  };
  ajax(linkRest+"borrar_cliente.php", data, "POST")
    .done(function(info){  
      if (info.data == "fail") {
        M.toast({html: ''+info.errores[1]});
      }else{
        M.toast({html: 'Cliente borrado con éxito'});
        setTimeout(() => {
          location.reload();
        }, 1000);
      }
      
    })
    .fail(function(){
      errorGeneral();
    });
}


// Cerrar Sesión

function sessionClose(){
  localStorage.setItem("isLoged", "FALSE");
  location.href = "login.html";
}


// Funciones para activar o desactivar la animacion de carga

function activarPreloader(){
  $(".cargando").css("display", "block");
}

function desactivarPreloader(){
  $(".cargando").css("display", "none");
}


// Funcion para error general
function errorGeneral(){
  M.toast({html: 'Ha ocurrido un error, intente de nuevo más tarde'});
}


// FUNCTION AUTOCOMPLETE

function autocomplete(inp, arr) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
              b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
      x[i].parentNode.removeChild(x[i]);
    }
  }
}
/*execute a function when someone clicks in the document:*/
document.addEventListener("click", function (e) {
    closeAllLists(e.target);
});
}

// FUNCION PARA TRAER PRODUCTOS A LA HORA DE BUSCAR EN NUEVO PEDIDO

function listarProductos2(param){
  data = {
    data: localStorage.getItem("data"),
    pruebaData: localStorage.getItem("pruebaData"),
    parametro: param
  };
  ajax(linkRest+"listar_productos.php", data, "POST")
    .done(function(info){   
      if (info.data == "fail") {
        M.toast({html: ''+info.errores[1]});
      }else{
        listaDeProductos = info.data;
        nombreDeProductos = new Array();
        for(var i in listaDeProductos){
          nombreDeProductos.push(listaDeProductos[i].descripcion+"; Código: "+ listaDeProductos[i].codigo);
        }
        autocomplete(document.getElementById("nameProductAdd"), nombreDeProductos);
        console.log(nombreDeProductos);
      }

      
      
    })
    .fail(function(){

      errorGeneral();
    });
}

// FUNCION APERTURA DE CAJA

function modalCaja(){
  var options = {
    'dismissible' : false
  };
  var elems = document.querySelectorAll('#modalAperturaCaja');
  var instances = M.Modal.init(elems, options);
  $('#modalAperturaCaja').modal('open');
}

function aperturaCaja(){
  if(localStorage.getItem('caja_abierta') != "TRUE"){
    modalCaja();
  }
}

$(".btnAperturaCaja").click(function(){
  data = {
    data: localStorage.getItem("data"),
    pruebaData: localStorage.getItem("pruebaData"),
    fondoCaja: $("#fondoCaja input").val()
  };
  ajax(linkRest+"apertura_caja.php", data, "POST")
  .done(function(info){  
    console.log(info);
    if (info.data == "fail") {
      M.toast({html: ''+info.errores[1]});
      if(info.errores[1] == "La caja ya está abierta"){
        localStorage.setItem("caja_abierta", "TRUE");
        setTimeout(() => {
          location.href = 'index.html';
        }, 2000);
      }
    }else{
      M.toast({html: 'Caja abierta'});
      $('#modalAperturaCaja').modal('close');
      localStorage.setItem("caja_abierta", "TRUE");
    }
    
  })
  .fail(function(){
    errorGeneral();
  });

});

// FUNCIONES CIERRE DE CAJA

function cerrarCaja(){
  var toastHTML = '<span>¿Cerrar caja actual?</span><button class="btn-flat toast-action" onclick="location.href = \'cerrar_caja.html\';">Sí</button>';
  M.toast({html: toastHTML});
}

function cierreDeCaja(){
  if(localStorage.getItem('caja_abierta') != "TRUE"){
    M.toast({html: 'No se ha abierto ninguna caja'});
    setTimeout(() => {
      location.href = 'index.html';
    }, 2000);
    modalCaja();
  }else{
    data = {
      data: localStorage.getItem("data"),
      pruebaData: localStorage.getItem("pruebaData")
    };
    ajax(linkRest+"cierre_de_caja.php", data, "POST")
    .done(function(info){  
      console.log(info);
      if (info.data == "fail") {
        M.toast({html: ''+info.errores[1]});
        if(info.errores[1] == "No se ha abierto ninguna caja"){
          localStorage.setItem("caja_abierta", "FALSE")
          setTimeout(() => {
            location.href = 'index.html';
          }, 2000);
        }
      }else{
        $("#cc_fondoCaja").text(parseFloat(info.data[1]).toLocaleString('es-PY', { style: 'currency', currency: 'PYG' }));
        $("#cc_totalVenta").text(info.data[0].toLocaleString('es-PY', { style: 'currency', currency: 'PYG' }));
        var diferencia = parseFloat(info.data[0]) + parseFloat(info.data[1]);
        $("#cc_totalCaja").text(diferencia.toLocaleString('es-PY', { style: 'currency', currency: 'PYG' }))
        localStorage.setItem("caja_abierta", "FALSE");
        M.toast({html: 'Caja cerrada con éxito! Vuelva a la lista de pedidos para abrir una nueva caja'});
        javascript:demoFromHTML('customers2');
      }
      
    })
    .fail(function(){
      errorGeneral();
    });
  }
  
}



// FUNCION PARA IMPRIMIR TABLA EN PDF

function demoFromHTML(idDiv) {
  var pdf = new jsPDF('p', 'pt', 'a4');
  // source can be HTML-formatted string, or a reference
  // to an actual DOM element from which the text will be scraped.
  source = $('#'+idDiv)[0];

  // we support special element handlers. Register them with jQuery-style 
  // ID selector for either ID or node name. ("#iAmID", "div", "span" etc.)
  // There is no support for any other type of selectors 
  // (class, of compound) at this time.
  specialElementHandlers = {
      // element with id of "bypass" - jQuery style selector
      '#bypassme': function (element, renderer) {
          // true = "handled elsewhere, bypass text extraction"
          return true
      }
  };
  margins = {
      top: 40,
      bottom: 60,
      left: 40,
      width: 1000
  };
  // all coords and widths are in jsPDF instance's declared units
  // 'inches' in this case
  pdf.fromHTML(
  source, // HTML string or DOM elem ref.
  margins.left, // x coord
  margins.top, { // y coord
      'width': margins.width, // max width of content on PDF
      'elementHandlers': specialElementHandlers
  },

  function (dispose) {
      // dispose: object with X, Y of the last line add to the PDF 
      //          this allow the insertion of new lines after html
      pdf.setFontSize(10);
      pdf.save('Archivo del punto de venta.pdf');
  }, margins);
}

// FUNCION PARA FECHA Y HORA ACTUAL
function showTime(){
  var myDate = new Date();
  var hours = myDate.getHours();
  var minutes = myDate.getMinutes();
  var seconds = myDate.getSeconds();
  myDate = myDate.toLocaleDateString();
  if (hours < 10) hours = 0 + hours;
  if (minutes < 10) minutes = "0" + minutes;
  if (seconds < 10) seconds = "0" + seconds;
  $("#HoraActual").text("Hora y Fecha: " +hours+ ":" +minutes+ ":" +seconds+ " - " +myDate);
}

// FUNCION PARA LOS INFORMES

function informes(){
  data = {
    data: localStorage.getItem("data"),
    pruebaData: localStorage.getItem("pruebaData"),
    codigo: $("#filterCodProduct").val(),
    id_pedido: $("#filterNroPedido").val(),
    fecha_desde: $("#filterDateDesde").val(),
    fecha_hasta: $("#filterDateHasta").val()
  };
  ajax(linkRest+"informes.php", data, "POST")
  .done(function(info){  
    console.log(info);
    if (info.data == "fail") {
        desactivarPreloader();
      M.toast({html: ''+info.errores[1]});
    }else{
      $("#tablaInformes tbody").empty();
      $("#tablaInformesPrint tbody").empty();
      for(var i in info.data){
        $("#tablaInformes tbody").append(
          "<tr>"
              +"<td>"+info.data[i].codigo+"</td>"
              +"<td>"+info.data[i].id_pedido+"</td>"
              +"<td>"+info.data[i].descripcion+"</td>"
              +"<td>"+info.data[i].cantidad+"</td>"
              +"<td>"+info.data[i].fecha_modificacion+"</td>"
          +"</tr>"
        );
        $("#tablaInformesPrint tbody").append(
          "<tr>"
              +"<td>"+info.data[i].codigo+"</td>"
              +"<td>"+info.data[i].id_pedido+"</td>"
              +"<td>"+info.data[i].descripcion+"</td>"
              +"<td>"+info.data[i].cantidad+"</td>"
              +"<td>"+info.data[i].fecha_modificacion+"</td>"
          +"</tr>"
        );
      }
      desactivarPreloader();
    }
    
  })
  .fail(function(){
    errorGeneral();
  });
  desactivarPreloader();
}

$("#filterInformes").submit(function(e){
  e.preventDefault();
  informes();
  
});



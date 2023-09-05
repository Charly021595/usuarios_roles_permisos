let table_usuarios,
today_actual = '',
nombre_archivo = '',
datos;

jQuery(function () {
    cargargrid_usuarios();
    FechaHoraActual();
});

function traer_roles(nombre_user){
    $("#cargar_roles").hide();
    $("#aviso_roles").hide();
    $("#cargando_roles").show();
    let token = $('input[name=_token]').val(),
    nombre_usuario = nombre_user != '' ? nombre_user : '';
    $("#cargar_roles").html("");
    $.ajax({
        url: "traer_roles",
        type: "post",
        data: {"_token": token, "nombre_usuario":nombre_usuario},
        success: function(result) {
            let data = result;
            if (data.estatus == 'success') {
                if (data.accion == 'actualizado') {
                    datos = data.datos["roles"];
                    rol_usuarios = data.datos["rol_usuarios"];
                }else{
                    datos = data.datos;
                }
                let id_div = 0,
                cont = 1;
                for (let i = 0; i < datos.length; i++) {
                    if ((cont % 4) == 0 || cont == 1) {
                        $("#cargar_roles").append(
                            `<div class="row form-group" id="div_rol_${datos[i].id}">
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                    <label for="lbl_roles_descripcion">
                                        <input type="checkbox" id="rol_id_${datos[i].id}" name="roles[]"  value="${datos[i].id}" /> ${datos[i].name}
                                    </label>
                                </div>
                            </div>`
                        );
                        id_div = datos[i].id;
                    }else{
                        $("#div_rol_"+id_div).append(
                            `<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                <label for="lbl_roles_descripcion">
                                    <input type="checkbox" id="rol_id_${datos[i].id}" name="roles[]" value="${datos[i].id}" /> ${datos[i].name}
                                </label>
                            </div>`
                        );
                    }
                    cont++;
                }
                if (data.accion == 'actualizado') {
                    for (let x = 0; x < rol_usuarios.length; x++) {
                        $("#rol_id_"+rol_usuarios[x].id_usuario_rol).prop('checked', true);
                    }
                }
                $("#cargando_roles").hide();
                $("#aviso_roles").hide();
                $("#cargar_roles").show();
            }else{
                Swal.fire('Sin registros', data.mensaje, "info");
                $("#cargando_roles").hide();
                $("#aviso_roles").show();
                $("#mensaje_roles").html(data.mensaje);
            }
        }
    });
}

function FechaHoraActual(){ 
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1;
    var minute = today.getMinutes();
    var hours = today.getHours();
    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd;
    }
    if (mm < 10) {
        mm = '0' + mm;
    }
    if (hours < 10) {
        hours = '0' + hours;
    }
    if (minute < 10) {
        minute = '0' + minute;
    }
    today = 'Fecha: ' + dd + '/' + mm + '/' + yyyy;
    nombre_archivo = dd + '_' + mm + '_' + yyyy;
    today_actual = today;
}

function cargargrid_usuarios(){
    $("#btn_filtros_buscar").addClass("deshabilitar");
  	$('#btn_filtros_buscar').attr("disabled", true);
    let token = $('input[name=_token]').val();
    $('#mostrar_usuarios').hide();
    $("#cargando_tabla").show();
    $.ajax({
        url: "traer_usuarios",
        type: "post",
        data: {"_token": token},
        success: function(result) {
            let data = result;
            if (data.estatus == 'success') {
                datos = data.datos;
                $("#cargando_tabla").hide();
                $('#mostrar_usuarios').show();
                listar_usuarios(datos);
            }else{
                Swal.fire('Sin registros', data.mensaje, "info");
                $("#cargando_tabla").hide();
                $('#mostrar_usuarios').show();
                $("#btn_filtros_buscar").removeAttr("disabled, disabled");
                $("#btn_filtros_buscar").removeClass("deshabilitar");
                $('#btn_filtros_buscar').attr("disabled", false);
            }
        }
    });
}

function listar_usuarios(datos){
    if(table_usuarios != null){
    table_usuarios.clear().draw();
    table_usuarios.destroy();
    }
    $("#tabla_usuarios > tbody").html('');
    table_usuarios = $("#tabla_usuarios").DataTable({
        "order": [],
        "targets": "no-sort",
        "ordertable": false,
        "data": datos,
        "columns":[
            {"data":"givenname"},
            {"data":"samaccountname"},
            {"data":"estatus"},
            {"defaultContent":`<button id="btn_editar_usuario" class="btn btn-primary" data-toggle="modal" onclick="traer_roles()" data-target="#model_editar_usuario">
                <i class='fa fa-edit'></i>
            </button>`}
        ],

        createdRow: function(row, data, index){
            $("td", row).eq(3).html(`<button id="btn_editar_usuario" class="btn btn-primary" data-toggle="modal" onclick="traer_roles('${data.samaccountname}')" data-target="#model_editar_usuario">
                <i class='fa fa-edit'></i>
            </button>`);
            if(data.estatus == true){
                $("td", row).eq(2).html(`Activo`);
            }else{
                $("td", row).eq(2).html('Desactivado');
            }
        },

        "columnDefs": [
        { width: "auto", targets: "_all" },
        {"className": "text-center", "targets": "_all"}
        ],

        fixedColumns: true,
    
        "language": idioma_espanol,

        dom: "<'row'<'col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12'<'row'"
        +"<'col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2 col-xxl-2'l>"
        +"<'col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4 col-xxl-4 botones_datatables'B>"
        +"<'col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6'f>>>>"
                +"<rt>"
                +"<'row'<'col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12'<'form-inline'"
                +"<'col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6'i>"
                +"<'col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 float-rigth'p>>>>",
        buttons: [
            // 'copy', 'csv', 'excel', 'pdf', 'print'
            {
            extend:'excelHtml5',
            text:'<i class="fa-solid fa-file-excel"></i>',
            titleAttr: 'Excel',
            filename: 'Listado_usuarios',
            autoFilter: true,
            exportOptions: {
                stripHtml: true,
                columns: [ 0, 1, 2]
            },
            sheetName: 'Listado usuarios',
            excelStyles: {                // Add an excelStyles definition
                template: "blue_medium",  // Apply the 'blue_medium' template
            }
            },
            { 
            extend: 'pdfHtml5',
            text:'<i class="fa-solid fa-file-pdf"></i>',
            titleAttr: 'PDF',
            title:'Listado_usuarios',
            exportOptions: {
                stripHtml: true,
                columns: [ 0, 1, 2]
            },
            // messageTop: today_actual,
            download: 'open',
            filename: 'Listado_usuarios_pdf',
                customize:function(doc) {
                doc.styles.title = {
                    color: '#0xff525659',
                    fontSize: '20',
                    alignment: 'left'
                }
                doc.styles.message = {
                    color: 'black',
                    fontSize: '10',
                    alignment: 'right'
                }
                doc.styles.tableHeader = {
                    fillColor:'#0xff525659',
                    color:'white',
                    alignment:'left'
                }
                doc.styles.tableBodyEven = {
                    alignment: 'left'
                }
                doc.styles.tableBodyOdd = {
                    alignment: 'left'
                }
                doc.styles['td:nth-child(2)'] = { 
                    width: '100px'
                }
            }
            },
            {
            extend:'csvHtml5',
            text:'<i class="fa-solid fa-file-csv"></i>',
            titleAttr: 'CSV',
            filename: 'Listado_usuarios_'+nombre_archivo,
            },
            {
            extend:'copyHtml5',
            text:'<i class="fa fa-clipboard" aria-hidden="true"></i>',
            titleAttr: 'Copiar',
            filename: 'Listado_usuarios_'+nombre_archivo,
            },
            {
            extend:'print',
            text:'<i class="fa fa-print" aria-hidden="true"></i>',
            titleAttr: 'Imprimir',
            filename: 'Listado_usuarios_'+nombre_archivo,
            },
        ],
        
        initComplete: function(settings, json) {
        $("#tabla_usuarios").removeClass("hide");
        $("#tabla_usuarios").show();
        $("#cargando_tabla").hide();
        }
    });
    obtener_data_usuarios_roles("#tabla_usuarios tbody", table_usuarios);
    $("#btn_filtros_buscar").removeAttr("disabled, disabled");
    $("#btn_filtros_buscar").removeClass("deshabilitar");
    $('#btn_filtros_buscar').attr("disabled", false);
    $("#nombre_empleado").val('');
    $("#nombre_usuario").val('');
    $("#estatus_usuario").val('');
}

let obtener_data_usuarios_roles = function(tbody, table_usuarios){
    $(tbody).on("click", "button#btn_editar_usuario", function(){
        let datos_usuarios = table_usuarios.row($(this).parents("tr") ).data();
        let estatus = datos_usuarios.estatus === true ? 1 : 0 ;
        $("#editar_nombre_empleado").val(datos_usuarios.givenname);
        $("#editar_nombre_equipo").val(datos_usuarios.samaccountname);
        $("#editar_estatus_usuario").val(estatus);
    });
}

$("#btn_filtros_buscar").on("click", function(e){
    e.preventDefault();
    $("#btn_filtros_buscar").addClass("deshabilitar");
  	$('#btn_filtros_buscar').attr("disabled", true);

	let busqueda_nombre_empleado = $("#nombre_empleado").val(),
	busqueda_nombre_usuario = $("#nombre_usuario").val(),
	busqueda_estatus_usuario = $("#estatus_usuario").val(),
    expresion;

    if ((busqueda_nombre_empleado === '') && (busqueda_nombre_usuario === '') && (busqueda_estatus_usuario === '')) {
        Swal.fire('No pueden ir vacios los filtros', 'Minimo debe haber un filtro', 'info');
		$("#btn_filtros_buscar").removeAttr("disabled, disabled");
		$("#btn_filtros_buscar").removeClass("deshabilitar");
		$('#btn_filtros_buscar').attr("disabled", false);
        cargargrid_usuarios();
		return false;
    }

    if (busqueda_nombre_empleado != '') {
		expresion = new RegExp(`${busqueda_nombre_empleado}.*`, "i");
        busqueda_nombre_usuario = '';
        busqueda_estatus_usuario = '';
	}else if (busqueda_nombre_usuario != '') {
		expresion = new RegExp(`${busqueda_nombre_usuario}.*`, "i");
        busqueda_nombre_empleado = '';
        busqueda_estatus_usuario = '';
	}else if (busqueda_estatus_usuario != '') {
		expresion = new RegExp(`${busqueda_estatus_usuario}.*`, "i");
        busqueda_nombre_empleado = '';
        busqueda_nombre_usuario = '';
	}

    if (datos != '') {
        datos = busqueda_nombre_empleado != '' ? datos.filter(usuario => expresion.test(usuario.givenname)) : busqueda_nombre_usuario != '' ? 
        datos.filter(usuario => expresion.test(usuario.samaccountname)) : busqueda_estatus_usuario != '' ?  datos.filter(usuario => expresion.test(usuario.estatus)) : 
        datos.filter(usuario => expresion.test(usuario.givenname));
        listar_usuarios(datos);   
    }else{
        cargargrid_usuarios();
    }
});

$("#btn_agregar_usuario").on("click", function(e){
    e.preventDefault();
    $("#btn_agregar_usuario").addClass("deshabilitar");
  	$('#btn_agregar_usuario').attr("disabled", true);

    let nombre_empleado = $("#editar_nombre_empleado").val(),
    nombre_equipo = $("#editar_nombre_equipo").val(),
    editar_estatus_usuario = $("#editar_estatus_usuario").val();

    if (nombre_empleado == "") {
        Swal.fire('El nombre del empleado no puede ir vació', "","info");
		$("#btn_agregar_usuario").removeAttr("disabled, disabled");
        $("#btn_agregar_usuario").removeClass("deshabilitar");
        $('#btn_agregar_usuario').attr("disabled", false);
        return false;
    }
    if (nombre_equipo == "") {
        Swal.fire('El nombre del usuario no puede ir vació', "","info");
		$("#btn_agregar_usuario").removeAttr("disabled, disabled");
        $("#btn_agregar_usuario").removeClass("deshabilitar");
        $('#btn_agregar_usuario').attr("disabled", false);
        return false;
    }
    if (editar_estatus_usuario == "") {
        Swal.fire('El estatus del usuario no puede ir vació', "","info");
        $("#btn_agregar_usuario").removeAttr("disabled, disabled");
        $("#btn_agregar_usuario").removeClass("deshabilitar");
        $('#btn_agregar_usuario').attr("disabled", false);
        return false;
    }
    let formData = new FormData(document.getElementById("form_editar_usuarios"));
    formData.append("dato", "valor");
    $.ajax({
        url: "guardar_usuarios",
        type: "post",
        data: formData,
        dataType: "html",
        cache: false, 	  	
        contentType: false,
        processData: false,
        success: function(result) {
            data = JSON.parse(result);
            if (data.estatus == "success") {
                if (data.accion == 'creado') {
                    Swal.fire('Guardado', data.mensaje, "success");
                }else{
                    Swal.fire('Actualizado', data.mensaje, "success");
                }
                $("#btn_agregar_usuario").removeAttr("disabled, disabled");
                $("#btn_agregar_usuario").removeClass("deshabilitar");
                $('#btn_agregar_usuario').attr("disabled", false);
                cargargrid_usuarios();
            }else{
                Swal.fire( 
                    data.mensaje,
                    '',
                    'error'
                );
                $("#btn_agregar_usuario").removeAttr("disabled, disabled");
                $("#btn_agregar_usuario").removeClass("deshabilitar");
                $('#btn_agregar_usuario').attr("disabled", false);
            }
        }
    });
});

let idioma_espanol = {
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ registros",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar:",
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
}
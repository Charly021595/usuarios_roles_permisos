let table_permisos,
today_actual = '',
nombre_archivo = '',
datos,
permiso;

jQuery(function () {
    cargargrid_permisos();
    FechaHoraActual();
    active();
});

function active() {
	$("#link_dashoard").removeClass('active');
	$("#a_roles").removeClass('active');
    $("#a_permisos").addClass('active');
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

function cargargrid_permisos(){
    $("#btn_filtros_buscar").addClass("deshabilitar");
  	$('#btn_filtros_buscar').attr("disabled", true);
    let token = $('input[name=_token]').val();
    $('#mostrar_permisos').hide();
    $("#cargando_tabla").show();
    $.ajax({
        url: "traer_permisos",
        type: "post",
        data: {"_token": token},
        success: function(result) {
            let data = result;
            if (data.estatus == 'success') {
                datos = data.datos;
                $("#cargando_tabla").hide();
                $('#mostrar_permisos').show();
                listar_permisos(datos);
            }else{
                Swal.fire('Sin registros', data.mensaje, "info");
                $("#btn_filtros_buscar").removeAttr("disabled, disabled");
                $("#btn_filtros_buscar").removeClass("deshabilitar");
                $('#btn_filtros_buscar').attr("disabled", false);
                $("#cargando_tabla").hide();
                $('#mostrar_permisos').hide();
            }
        }
    });
}

function listar_permisos(datos){
    if(table_permisos != null){
        table_permisos.clear().draw();
        table_permisos.destroy();
    }
    $("#tabla_permisos > tbody").html('');
    table_permisos = $("#tabla_permisos").DataTable({
        "order": [],
        "targets": "no-sort",
        "ordertable": false,
        "data": datos,
        "columns":[
            {"data":"name"},
            {"data":"descripcion"},
            {"data":"estatus"},
            {"defaultContent":`<button id="btn_editar_permisos" class="btn btn-primary" data-toggle="modal" data-target="#modal_actualizar_permiso">
                <i class='fa fa-edit'></i>
            </button>`}
        ],

        createdRow: function(row, data, index){
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
                columns: [ 0, 1, 2, 3, 4]
            },
            sheetName: 'Listado movimientos',
            excelStyles: {                  // Add an excelStyles definition
                template: "green_medium",  // Apply the 'blue_medium' template
            }
            },
            { 
            extend: 'pdfHtml5',
            text:'<i class="fa-solid fa-file-pdf"></i>',
            titleAttr: 'PDF',
            title:'Listado_usuarios',
            exportOptions: {
                stripHtml: true,
                columns: [ 0, 1, 2, 3, 4]
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
        $("#tabla_permisos").removeClass("hide");
        $("#tabla_permisos").show();
        $("#cargando_tabla").hide();
        }
    });
    
    $("#btn_filtros_buscar").removeAttr("disabled, disabled");
    $("#btn_filtros_buscar").removeClass("deshabilitar");
    $('#btn_filtros_buscar').attr("disabled", false);
    obtener_data_permisos("#tabla_permisos tbody", table_permisos);
}

let obtener_data_permisos = function(tbody, table_permisos){
    $(tbody).on("click", "button#btn_editar_permisos", function(){
        let datos_actualizar = table_permisos.row($(this).parents("tr") ).data();
        permiso = datos_actualizar;
        $("#editar_nombre_permiso").val(datos_actualizar.name),
        $("#editar_descripcion").val(datos_actualizar.descripcion),
        $("#editar_estatus_permiso").val(datos_actualizar.estatus);
    });
}

$("#btn_filtros_buscar").on("click", function(e){
    e.preventDefault();
    $('#mostrar_permisos').hide();
    $("#cargando_tabla").show();
    $("#btn_filtros_buscar").addClass("deshabilitar");
  	$('#btn_filtros_buscar').attr("disabled", true);
    let formData = new FormData(document.getElementById("form_buscar_permisos"));
    formData.append("dato", "valor");
    $.ajax({
        url: "buscar_permisos",
        type: "post",
        data: formData,
        dataType: "html",
        cache: false, 	  	
        contentType: false,
        processData: false,
        success: function(result) {
            data = JSON.parse(result);
            if (data.estatus == "success") {
                let datos = data.datos;
                $("#btn_filtros_buscar").removeAttr("disabled, disabled");
                $("#btn_filtros_buscar").removeClass("deshabilitar");
                $('#btn_filtros_buscar').attr("disabled", false);
                $("#cargando_tabla").hide();
                $('#mostrar_permisos').show();
                listar_permisos(datos);
            }else{
                Swal.fire( 
                    data.mensaje,
                    '',
                    'error'
                );
                $("#btn_filtros_buscar").removeAttr("disabled, disabled");
                $("#btn_filtros_buscar").removeClass("deshabilitar");
                $('#btn_filtros_buscar').attr("disabled", false);
                $("#cargando_tabla").hide();
                $('#mostrar_permisos').show();
            }
        }
    });
});

$("#btn_modal_agregar_permiso").on("click", function(e){
    $("#guardar_nombre_permiso").val("");
    $("#guardar_descripcion").val("");
    $("#guardar_estatus_permiso").val("");
});

$("#btn_guardar_permiso").on("click", function(e){
    e.preventDefault();
    $("#btn_guardar_permiso").addClass("deshabilitar");
  	$('#btn_guardar_permiso').attr("disabled", true);

    let nombre_permiso = $("#guardar_nombre_permiso").val(),
    descripcion = $("#guardar_descripcion").val(),
    estatus = $("#guardar_estatus_permiso").val();

    if (nombre_permiso == "") {
        Swal.fire('El nombre del permiso no puede ir vació', "","info");
		$("#btn_guardar_permiso").removeAttr("disabled, disabled");
        $("#btn_guardar_permiso").removeClass("deshabilitar");
        $('#btn_guardar_permiso').attr("disabled", false);
        return false;
    }
    if (descripcion == "") {
        Swal.fire('La descripcion del permiso no puede ir vaciá', "","info");
		$("#btn_guardar_permiso").removeAttr("disabled, disabled");
        $("#btn_guardar_permiso").removeClass("deshabilitar");
        $('#btn_guardar_permiso').attr("disabled", false);
        return false;
    }
    if (estatus == "") {
        Swal.fire('El estatus del permiso no puede ir vació', "","info");
        $("#btn_guardar_permiso").removeAttr("disabled, disabled");
        $("#btn_guardar_permiso").removeClass("deshabilitar");
        $('#btn_guardar_permiso').attr("disabled", false);
        return false;
    }
    let formData = new FormData(document.getElementById("form_guardar_permisos"));
    formData.append("dato", "valor");
    $.ajax({
        url: "guardar_permisos",
        type: "post",
        data: formData,
        dataType: "html",
        cache: false, 	  	
        contentType: false,
        processData: false,
        success: function(result) {
            data = JSON.parse(result);
            if (data.estatus == "success") {
                Swal.fire('Guardado', data.mensaje, "success");
                $("#btn_guardar_permiso").removeAttr("disabled, disabled");
                $("#btn_guardar_permiso").removeClass("deshabilitar");
                $('#btn_guardar_permiso').attr("disabled", false);
                $("#guardar_nombre_permiso").val("");
                $("#guardar_descripcion").val("");
                $("#guardar_estatus_permiso").val("");
                cargargrid_permisos();
            }else{
                Swal.fire( 
                    data.mensaje,
                    '',
                    'error'
                );
                $("#btn_guardar_permiso").removeAttr("disabled, disabled");
                $("#btn_guardar_permiso").removeClass("deshabilitar");
                $('#btn_guardar_permiso').attr("disabled", false);
            }
        }
    });
});

$("#btn_editar_permiso").on("click", function(e){
    e.preventDefault();
    $("#btn_editar_permiso").addClass("deshabilitar");
  	$('#btn_editar_permiso').attr("disabled", true);

    let ediar_nombre_permiso = $("#editar_nombre_permiso").val(),
    editar_descripcion = $("#editar_descripcion").val(),
    editar_estatus = $("#editar_estatus_permiso").val();

    if (ediar_nombre_permiso == "") {
        Swal.fire('El nombre del permiso no puede ir vació', "","info");
		$("#btn_editar_permiso").removeAttr("disabled, disabled");
        $("#btn_editar_permiso").removeClass("deshabilitar");
        $('#btn_editar_permiso').attr("disabled", false);
        return false;
    }
    if (editar_descripcion == "") {
        Swal.fire('La descripcion del permiso no puede ir vaciá', "","info");
		$("#btn_editar_permiso").removeAttr("disabled, disabled");
        $("#btn_editar_permiso").removeClass("deshabilitar");
        $('#btn_editar_permiso').attr("disabled", false);
        return false;
    }
    if (editar_estatus == "") {
        Swal.fire('El estatus del permiso no puede ir vació', "","info");
        $("#btn_editar_permiso").removeAttr("disabled, disabled");
        $("#btn_editar_permiso").removeClass("deshabilitar");
        $('#btn_editar_permiso').attr("disabled", false);
        return false;
    }
    let formData = new FormData(document.getElementById("form_editar_permisos"));
    formData.append("dato", "valor");
    formData.append("permiso", permiso.id);
    $.ajax({
        url: 'actualizar',
        type: "post",
        data: formData,
        dataType: "html",
        cache: false, 	  	
        contentType: false,
        processData: false,
        success: function(result) {
            data = JSON.parse(result);
            if (data.estatus == "success") {
                $("#modal_actualizar_permiso").modal('hide');
                Swal.fire('Actualizado', data.mensaje, "success");
                $("#btn_editar_permiso").removeAttr("disabled, disabled");
                $("#btn_editar_permiso").removeClass("deshabilitar");
                $('#btn_editar_permiso').attr("disabled", false);
                cargargrid_permisos();
            }else{
                Swal.fire( 
                    data.mensaje,
                    '',
                    'error'
                );
                $("#btn_editar_permiso").removeAttr("disabled, disabled");
                $("#btn_editar_permiso").removeClass("deshabilitar");
                $('#btn_editar_permiso').attr("disabled", false);
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
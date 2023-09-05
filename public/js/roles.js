let table_roles,
today_actual = '',
nombre_archivo = '',
datos,
rol;

jQuery(function () {
    cargargrid_roles();
    FechaHoraActual();
    active();
});

function active() {
	$("#link_dashoard").removeClass('active');
	$("#a_roles").addClass('active');
    $("#a_permisos").removeClass('active');
}

function traer_permisos(){
    let token = $('input[name=_token]').val();
    $("#cargar_permisos").html("");
    $.ajax({
        url: "traer_permisos",
        type: "post",
        data: {"_token": token},
        success: function(result) {
            let data = result;
            if (data.estatus == 'success') {
                datos = data.datos;
                let id_div = 0,
                cont = 1;
                for (let i = 0; i < datos.length; i++) {
                    if ((cont % 4) == 0 || cont == 1) {
                        $("#cargar_permisos").append(
                            `<div class="row form-group" id="div_permiso_${datos[i].id}">
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                    <label for="lbl_permisos_descripcion">
                                        <input type="checkbox" id="permiso_id_${datos[i].id}" name="permisos[]"  value="${datos[i].id}" /> ${datos[i].name}
                                    </label>
                                </div>
                            </div>`
                        );
                        id_div = datos[i].id;
                    }else{
                        $("#div_permiso_"+id_div).append(
                            `<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                <label for="lbl_permisos_descripcion">
                                    <input type="checkbox" id="permiso_id_${datos[i].id}" name="permisos[]" value="${datos[i].id}" /> ${datos[i].name}
                                </label>
                            </div>`
                        );
                    }
                    cont++;
                }
            }else{
                Swal.fire('Sin registros', data.mensaje, "info");
            }
        }
    });
}

function traer_permisos_editar(id_rol){
    let token = $('input[name=_token]').val();
    $("#cargar_permisos_roles").html("");
    $.ajax({
        url: "traer_permisos_roles",
        type: "post",
        data: {"_token": token, "id_rol":id_rol},
        success: function(result) {
            let data = result;
            if (data.estatus == 'success') {
                permisos = data.datos["permisos"];
                rol_permisos = data.datos["rol_permisos"];
                let id_div = 0,
                cont = 1;
                for (let i = 0; i < permisos.length; i++) {
                    // let found = rol_permisos.find(e => e.id_permiso_rol === permisos[i].id);
                    // let found = rol_permisos.find(element => element > 1);
                    // console.log(found);
                    if ((cont % 4) == 0 || cont == 1) {
                        $("#cargar_permisos_roles").append(
                            `<div class="row form-group" id="div_permiso_rol_${permisos[i].id}">
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                    <label for="lbl_permisos_descripcion">
                                        <input type="checkbox" id="permiso_rol_id_${permisos[i].id}" name="permisos_editar[]"  value="${permisos[i].id}" /> ${permisos[i].name}
                                    </label>
                                </div>
                            </div>`
                        );
                        id_div = permisos[i].id;
                    }else{
                        $("#div_permiso_rol_"+id_div).append(
                            `<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                <label for="lbl_permisos_descripcion">
                                    <input type="checkbox" id="permiso_rol_id_${permisos[i].id}" name="permisos_editar[]" value="${permisos[i].id}" /> ${permisos[i].name}
                                </label>
                            </div>`
                        );
                    }
                    cont++;
                }
                for (let x = 0; x < rol_permisos.length; x++) {
                    $("#permiso_rol_id_"+rol_permisos[x].id_permiso_rol).prop('checked', true);
                }
            }else{
                Swal.fire('Sin registros', data.mensaje, "info");
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

function cargargrid_roles(){
    $("#btn_filtros_buscar").addClass("deshabilitar");
  	$('#btn_filtros_buscar').attr("disabled", true);
    let token = $('input[name=_token]').val();
    $('#mostrar_roles').hide();
    $("#cargando_tabla").show();
    $.ajax({
        url: "traer_roles",
        type: "post",
        data: {"_token": token},
        success: function(result) {
            let data = result;
            if (data.estatus == 'success') {
                datos = data.datos;
                $("#cargando_tabla").hide();
                $('#mostrar_roles').show();
                listar_roles(datos);
            }else{
                Swal.fire('Sin registros', data.mensaje, "info");
                $("#btn_filtros_buscar").removeAttr("disabled, disabled");
                $("#btn_filtros_buscar").removeClass("deshabilitar");
                $('#btn_filtros_buscar').attr("disabled", false);
                $("#cargando_tabla").hide();
                $('#mostrar_roles').hide();
            }
        }
    });
}

function listar_roles(datos){
    if(table_roles != null){
        table_roles.clear().draw();
        table_roles.destroy();
    }
    $("#tabla_roles > tbody").html('');
    table_roles = $("#tabla_roles").DataTable({
        "order": [],
        "targets": "no-sort",
        "ordertable": false,
        "data": datos,
        "columns":[
            {"data":"name"},
            {"data":"descripcion"},
            {"data":"estatus"},
            {"defaultContent":``}
        ],

        createdRow: function(row, data, index){
            $("td", row).eq(3).html(`<button id="btn_editar_roles" class="btn btn-primary" onclick="traer_permisos_editar(${data.id})" data-toggle="modal" data-target="#modal_actualizar_rol">
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
        $("#tabla_roles").removeClass("hide");
        $("#tabla_roles").show();
        $("#cargando_tabla").hide();
        }
    });
    
    $("#btn_filtros_buscar").removeAttr("disabled, disabled");
    $("#btn_filtros_buscar").removeClass("deshabilitar");
    $('#btn_filtros_buscar').attr("disabled", false);
    obtener_data_roles("#tabla_roles tbody", table_roles);
}

let obtener_data_roles = function(tbody, table_roles){
    $(tbody).on("click", "button#btn_editar_roles", function(){
        let datos_actualizar = table_roles.row($(this).parents("tr") ).data();
        rol = datos_actualizar;
        $("#id_rol").val(datos_actualizar.id),
        $("#editar_nombre_rol").val(datos_actualizar.name),
        $("#editar_descripcion_rol").val(datos_actualizar.descripcion),
        $("#editar_estatus_rol").val(datos_actualizar.estatus);
    });
}

$("#btn_modal_agregar_rol").on("click", function(e){
    traer_permisos();
});

$("#btn_filtros_buscar").on("click", function(e){
    e.preventDefault();
    $('#mostrar_roles').hide();
    $("#cargando_tabla").show();
    $("#btn_filtros_buscar").addClass("deshabilitar");
  	$('#btn_filtros_buscar').attr("disabled", true);
    let formData = new FormData(document.getElementById("form_buscar_roles"));
    formData.append("dato", "valor");
    $.ajax({
        url: "buscar_roles",
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
                $('#mostrar_roles').show();
                listar_roles(datos);
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
                $('#mostrar_roles').show();
            }
        }
    });
});

$("#btn_modal_agregar_rol").on("click", function(e){
    $("#guardar_nombre_rol").val("");
    $("#guardar_descripcion_rol").val("");
    $("#guardar_estatus_rol").val("");
});

$("#btn_guardar_rol").on("click", function(e){
    e.preventDefault();
    $("#btn_guardar_rol").addClass("deshabilitar");
  	$('#btn_guardar_rol').attr("disabled", true);

    let nombre_rol = $("#guardar_nombre_rol").val(),
    descripcion_rol = $("#guardar_descripcion_rol").val(),
    estatus_rol = $("#guardar_estatus_rol").val();

    if (nombre_rol == "") {
        Swal.fire('El nombre del rol no puede ir vació', "","info");
		$("#btn_guardar_rol").removeAttr("disabled, disabled");
        $("#btn_guardar_rol").removeClass("deshabilitar");
        $('#btn_guardar_rol').attr("disabled", false);
        return false;
    }
    if (descripcion_rol == "") {
        Swal.fire('La descripcion del rol no puede ir vaciá', "","info");
		$("#btn_guardar_rol").removeAttr("disabled, disabled");
        $("#btn_guardar_rol").removeClass("deshabilitar");
        $('#btn_guardar_rol').attr("disabled", false);
        return false;
    }
    if (estatus_rol == "") {
        Swal.fire('El estatus del rol no puede ir vació', "","info");
        $("#btn_guardar_rol").removeAttr("disabled, disabled");
        $("#btn_guardar_rol").removeClass("deshabilitar");
        $('#btn_guardar_rol').attr("disabled", false);
        return false;
    }
    let formData = new FormData(document.getElementById("form_guardar_roles"));
    formData.append("dato", "valor");
    $.ajax({
        url: "guardar_roles",
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
                $("#btn_guardar_rol").removeAttr("disabled, disabled");
                $("#btn_guardar_rol").removeClass("deshabilitar");
                $('#btn_guardar_rol').attr("disabled", false);
                $("#guardar_nombre_rol").val("");
                $("#guardar_descripcion_rol").val("");
                $("#guardar_estatus_rol").val("");
                cargargrid_roles();
                traer_permisos();
            }else{
                Swal.fire( 
                    data.mensaje,
                    '',
                    'error'
                );
                $("#btn_guardar_rol").removeAttr("disabled, disabled");
                $("#btn_guardar_rol").removeClass("deshabilitar");
                $('#btn_guardar_rol').attr("disabled", false);
            }
        }
    });
});

$("#btn_editar_rol").on("click", function(e){
    e.preventDefault();
    $("#btn_editar_rol").addClass("deshabilitar");
  	$('#btn_editar_rol').attr("disabled", true);

    let ediar_nombre_rol = $("#editar_nombre_rol").val(),
    editar_descripcion_rol = $("#editar_descripcion_rol").val(),
    editar_estatus = $("#editar_estatus_rol").val();

    if (ediar_nombre_rol == "") {
        Swal.fire('El nombre del permiso no puede ir vació', "","info");
		$("#btn_editar_rol").removeAttr("disabled, disabled");
        $("#btn_editar_rol").removeClass("deshabilitar");
        $('#btn_editar_rol').attr("disabled", false);
        return false;
    }
    if (editar_descripcion_rol == "") {
        Swal.fire('La descripcion del permiso no puede ir vaciá', "","info");
		$("#btn_editar_rol").removeAttr("disabled, disabled");
        $("#btn_editar_rol").removeClass("deshabilitar");
        $('#btn_editar_rol').attr("disabled", false);
        return false;
    }
    if (editar_estatus == "") {
        Swal.fire('El estatus del permiso no puede ir vació', "","info");
        $("#btn_editar_rol").removeAttr("disabled, disabled");
        $("#btn_editar_rol").removeClass("deshabilitar");
        $('#btn_editar_rol').attr("disabled", false);
        return false;
    }
    let formData = new FormData(document.getElementById("form_editar_roles"));
    formData.append("dato", "valor");
    $.ajax({
        url: 'actualizar_rol',
        type: "post",
        data: formData,
        dataType: "html",
        cache: false, 	  	
        contentType: false,
        processData: false,
        success: function(result) {
            data = JSON.parse(result);
            if (data.estatus == "success") {
                $("#modal_actualizar_rol").modal('hide');
                Swal.fire('Actualizado', data.mensaje, "success");
                $("#btn_editar_rol").removeAttr("disabled, disabled");
                $("#btn_editar_rol").removeClass("deshabilitar");
                $('#btn_editar_rol').attr("disabled", false);
                cargargrid_roles();
            }else{
                Swal.fire( 
                    data.mensaje,
                    '',
                    'error'
                );
                $("#btn_editar_rol").removeAttr("disabled, disabled");
                $("#btn_editar_rol").removeClass("deshabilitar");
                $('#btn_editar_rol').attr("disabled", false);
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
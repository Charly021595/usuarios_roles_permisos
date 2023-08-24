jQuery(function () {
    // traer_users();
    services_wsld();
});

function traer_users(){
    debugger;
    $.ajax({
        url: "http://192.168.1.202:84/wsArzyz/ObtenerInformacionAD.asmx?op=ListadoEmpleado",
        type: "post",
        data: JSON.stringify({"Usuario":'Arzyz$2023AD', "Contrasena":'Arzyz$2020'}),
        dataType: "json",
        success: function(result) {
            console.log(result);
            // let data = JSON.parse(result);
            // if (data.estatus == 'success') {
            //     let datos = data.data;
            //     for (let i = 0; i < datos.length; i++) {
            //         $("#add_rama").append(`
            //             <option value="${datos[i].id_rama}">${datos[i].nombre_rama}</option>
            //         `);
            //         $("#editar_rama").append(`
            //             <option value="${datos[i].id_rama}">${datos[i].nombre_rama}</option>
            //         `);
            //     }
            // }
        }
    });
}

function services_wsld(){
    debugger;
    $.ajax({
        url: "prueba",
		type: "get",
		data: {},
        success: function(result) {
            console.log(result);
            // let data = result;
            // console.log(data);
            // if (data.estatus == 'success') {
            //     let datos = data.data;
            //     for (let i = 0; i < datos.length; i++) {
            //         $("#add_rama").append(`
            //             <option value="${datos[i].id_rama}">${datos[i].nombre_rama}</option>
            //         `);
            //         $("#editar_rama").append(`
            //             <option value="${datos[i].id_rama}">${datos[i].nombre_rama}</option>
            //         `);
            //     }
            // }
        }
    });
}

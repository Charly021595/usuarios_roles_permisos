@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
            <h1 class="m-0">Roles</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
              <li class="breadcrumb-item active">Roles</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->

        <!-- boton Agregar Modal -->
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="text-align: end;">
            <div class="p-2">
              <button id="btn_modal_agregar_rol" class="btn btn-primary" data-toggle="modal" data-target="#modal_rol">Agregar Rol</button>
            </div>
          </div>
        </div>

        <!-- Filtro roles -->
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
            <div class="card">
              <div class="card-header bg-success centrar_texto">Filtros Roles</div>
              <div class="card-body">
                <form action="" method="POST" id="form_buscar_roles">
                    {{ csrf_field() }}
                    <div class="row">
                      <div class="col-xs-3 col-sm-3 col-md-3 col-xl-3">
                        <input type="text" id="nombre_rol" name="nombre_rol" placeholder="Nombre Rol" class="form-control">
                      </div>
                      <div class="col-xs-3 col-sm-3 col-md-3 col-xl-3">
                        <input type="text" id="descripcion_rol" name="descripcion_rol" placeholder="Descripción" class="form-control">
                      </div>
                      <div class="col-xs-3 col-sm-3 col-md-3 col-xl-3">
                        <select name="estatus_rol" id="estatus_rol" class="form-control">
                          <option value="">Selecciona Estatus</option>
                          <option value="1">Activo</option>
                          <option value="0">Inactivo</option>
                        </select>
                      </div>
                      <div class="col-xs-3 col-sm-3 col-md-3 col-xl-3  btn_mover_final">
                        <button id="btn_filtros_buscar" name="btn_filtros_buscar" class="btn btn-primary btn_buscar">Buscar</button>
                      </div>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        
        <!-- tabla listado usuarios -->
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
            <div class="card">
              <div class="card-header bg-success centrar_texto">Listado Roles</div>
              <div class="card-body">
                <form action="" method="POST" id="form_roles">
                    {{ csrf_field() }}
                </form>
                <div class="row" id="cargando_tabla">
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 centrar_texto">
                    <img id="cargando_tabla_roles" class="cargando_tablas" src="{{ asset('img/loading.gif') }}">
                  </div>
                </div>
                <div class="row" id="mostrar_roles" style="display:none;">
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                    <table id="tabla_roles" class="table table-hover table-bordered 
                    table-responsive-xs table-responsive-sm table-responsive-md 
                    table-responsive-lg table-responsive-xl table-responsive-xxl" style="width:100%;">
                      <thead>
                        <tr>
                          <th scope="col">Nombre Rol</th>
                          <th scope="col">Descripción</th>
                          <th scope="col">Estatus</th>
                          <th scope="col">Acciones</th> 
                        </tr>
                      </thead>
                    </table> 
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->

        <!-- Modal agregar Rol -->
        <div class="modal fade" id="modal_rol" tabindex="-1" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header color_fratech letra_iconos_blancos">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Rol</h5>
                <button id="CerrarModal" type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <i class="fa-solid fa-x letra_iconos_blancos"></i>
                </button>
              </div>
              <div class="modal-body">
                <form action="" method="POST" id="form_guardar_roles">
                    {{ csrf_field() }}
                    <div class="row form-group">
                      <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                        <label for="lbl_nombre_rol">Nombre Rol:</label>
                      </div>
                      <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                        <input type="text" id="guardar_nombre_rol" name="guardar_nombre_rol" placeholder="Nombre Rol" class="form-control">
                      </div>
                    </div>
                    <div class="row form-group">
                      <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                        <label for="lbl_descripcion_rol">Descripción Rol:</label>
                      </div>
                      <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                        <input type="text" id="guardar_descripcion_rol" name="guardar_descripcion_rol" placeholder="Descripción" class="form-control">
                      </div>
                    </div>
                    <div class="row form-group">
                      <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                        <label for="lbl_estatus_rol">Estatus Rol:</label>
                      </div>
                      <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                        <select name="guardar_estatus_rol" id="guardar_estatus_rol" class="form-control">
                          <option value="">Selecciona Estatus</option>
                          <option value="1">Activo</option>
                          <option value="0">Inactivo</option>
                        </select>
                      </div>
                    </div>
                    <div class="row form-group">
                      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <label for="lbl_permisos">Permisos:</label>
                      </div>
                    </div>
                    <div id="cargar_permisos">
                    </div>
                </form>
               
              </div>
              <div class="modal-footer">
                <button id="btn_cerrar" type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button id="btn_guardar_rol" type="button" class="btn btn-primary">Guardar</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Editar Rol -->
        <div class="modal fade" id="modal_actualizar_rol" tabindex="-1" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header color_fratech letra_iconos_blancos">
                <h5 class="modal-title" id="exampleModalLabel">Actualizar Rol</h5>
                <button id="EditarCerrarModal" type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <i class="fa-solid fa-x letra_iconos_blancos"></i>
                </button>
              </div>
              <div class="modal-body">
                <form action="" method="POST" id="form_editar_roles">
                    {{ csrf_field() }}
                    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                      <input type="hidden" id="id_rol" name="id_rol" placeholder="Editar Rol" class="form-control">
                    </div>
                    <div class="row form-group">
                      <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                        <label for="lbl_nombre_rol">Nombre Role:</label>
                      </div>
                      <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                        <input type="text" id="editar_nombre_rol" name="editar_nombre_rol" placeholder="Editar Rol" class="form-control">
                      </div>
                    </div>
                    <div class="row form-group">
                      <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                        <label for="lbl_descripcion_rol">Descripción Rol:</label>
                      </div>
                      <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                        <input type="text" id="editar_descripcion_rol" name="editar_descripcion_rol" placeholder="Editar Descripción" class="form-control">
                      </div>
                    </div>
                    <div class="row form-group">
                      <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                        <label for="lbl_estatus_rol">Estatus Rol:</label>
                      </div>
                      <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                        <select name="editar_estatus_rol" id="editar_estatus_rol" class="form-control">
                          <option value="">Selecciona Estatus</option>
                          <option value="1">Activo</option>
                          <option value="0">Inactivo</option>
                        </select>
                      </div>
                    </div>
                    <div id="cargar_permisos_roles">
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                <button id="btn_cerrar" type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button id="btn_editar_rol" type="button" class="btn btn-primary">Editar</button>
              </div>
            </div>
          </div>
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <script src="{{ asset('js/roles.js') }}"></script>
@endsection

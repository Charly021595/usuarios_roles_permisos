@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
            <h1 class="m-0">Usuarios</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
              <li class="breadcrumb-item active">Usuarios</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->

        <!-- Filtro usuarios -->
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
            <div class="card">
              <div class="card-header bg-success centrar_texto">Filtros Usuarios</div>
              <div class="card-body">
                <form action="" method="POST" id="form_usuarios">
                    {{ csrf_field() }}
                    <div class="row">
                      <div class="col-xs-3 col-sm-3 col-md-3 col-xl-3">
                        <input type="text" id="nombre_empleado" name="nombre_empleado" placeholder="Nombre Empleado" class="form-control">
                      </div>
                      <div class="col-xs-3 col-sm-3 col-md-3 col-xl-3">
                        <input type="text" id="nombre_usuario" name="nombre_usuario" placeholder="Nombre Usuario" class="form-control">
                      </div>
                      <div class="col-xs-3 col-sm-3 col-md-3 col-xl-3">
                        <select name="estatus_usuario" id="estatus_usuario" class="form-control">
                          <option value="">Selecciona Estatus</option>
                          <option value="True">Activo</option>
                          <option value="False">Inactivo</option>
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
              <div class="card-header bg-success centrar_texto">Listado Usuarios</div>
              <div class="card-body">
                <form action="" method="POST" id="form_usuarios">
                    {{ csrf_field() }}
                </form>
                <div class="row" id="cargando_tabla">
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 centrar_texto">
                    <img id="cargando_tabla_usuarios" class="cargando_tablas" src="{{ asset('img/loading.gif') }}">
                  </div>
                </div>
                <div class="row" id="mostrar_usuarios" style="display:none;">
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                    <table id="tabla_usuarios" class="table table-hover table-bordered 
                    table-responsive-xs table-responsive-sm table-responsive-md 
                    table-responsive-lg table-responsive-xl table-responsive-xxl" style="width:100%;">
                      <thead>
                        <tr>
                          <th scope="col">Nombre</th>
                          <th scope="col">Nombre de Usuario</th>
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
        <!-- Modal agregar jugador -->
        <div class="modal fade" id="model_editar_usuario" tabindex="-1" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header color_fratech letra_iconos_blancos">
                <h5 class="modal-title" id="exampleModalLabel">Editar usuario</h5>
                <button id="CerrarModal" type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="" method="POST" id="form_editar_usuarios">
                  {{ csrf_field() }}
                  <div class="row form-group">
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                      <label for="lbl_nombre_empleado">Nombre Empleado:</label>
                    </div>
                    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                      <input type="text" id="editar_nombre_empleado" name="editar_nombre_empleado" placeholder="Editar Nombre Empleado" class="form-control">
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                      <label for="lbl_nombre_equipo">Nombre Equipo:</label>
                    </div>
                    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                      <input type="text" id="editar_nombre_equipo" name="editar_nombre_equipo" placeholder="Editar Nombre Equipo" class="form-control">
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                      <label for="lbl_estatus_usuario">Estatus Usuario:</label>
                    </div>
                    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                      <select name="editar_estatus_usuario" id="editar_estatus_usuario" class="form-control">
                        <option value="">Selecciona Estatus</option>
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                      </select>
                    </div>
                  </div>
                  <div class="row" id="cargando_roles">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 centrar_texto">
                      <img id="cargando_roles_usuario" class="cargando_tablas" src="{{ asset('img/loading.gif') }}">
                    </div>
                  </div>
                  <div id="cargar_roles">
                  </div>
                  <div class="row" id="aviso_roles" style="display:none;">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 centrar_texto alert alert-info" role="alert">
                      <h1 id="mensaje_roles"></h1>
                    </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button id="btn_cerrar" type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button id="btn_agregar_usuario" type="button" class="btn btn-primary">Editar Usuario</button>
              </div>
            </div>
          </div>
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <script src="{{ asset('js/dashboard.js') }}"></script>
@endsection

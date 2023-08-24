@extends('layouts.app_login')

@section('content')
<div id="prueba">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="row">
            <div class="col-12">
                <img src="{{ asset('img/logo.jpg') }}" width="350" height="100" alt="ARZYZ" class="img-responsive">    
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <p class="login-box-msg" style="text-align: center;">Inicia Sessión</p>
                        <form method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('nombre_usuario') ? ' has-error' : '' }}">
                                <div class="input-group mb-3">
                                    <input id="nombre_usuario" type="text" class="form-control validanumericos" placeholder="Usuario" name="nombre_usuario" value="{{ old('nombre_usuario') }}" required autofocus />
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    @if ($errors->has('nombre_usuario'))
                                        <span class="help-block">
                                            <strong style="color:red;">{{ $errors->first('nombre_usuario') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" name="password" id="password" onkeyup="Validar()" placeholder="Contraseña" required />
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span id="mostrar_password" style="cursor:pointer;" class="fas fa-solid fa-eye-slash"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong style="color:red;">{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-xs-12 col-sm-12 col-md-12 form-group col-xs-offset-3 col-sm-offset-3 col-md-offset-3">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Recordar
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 form-group col-xs-offset-3 col-sm-offset-3 col-md-offset-3">
                                    <button type="submit" class="btn btn-primary btn-block btn-login" id="login">Ingresar</button>
                                </div>
                            </div>

                            <!-- <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 form-group col-xs-offset-3 col-sm-offset-3 col-md-offset-3">
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        Forgot Your Password?
                                    </a>
                                </div>
                            </div> -->
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/sweetalert2.js') }}"></script>
<script src="{{ asset('js/login.js') }}?t=<?=time()?>"></script>
@endsection

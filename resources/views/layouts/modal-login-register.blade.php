<div class="modal fade login-modal" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div v-show="option != 'recovery'" class="login-modal-tabs">
                <button class="login-modal-tab" :class="{ selected: option == 'login'}" v-on:click="option = 'login'">Iniciar sesión</button>
                <button class="login-modal-tab" :class="{ selected: option == 'register'}" v-on:click="option = 'register'">Registrate</button>
            </div>
            <div class="login-modal-border">
                <div class="text-center login-modal-tab" v-show="option == 'recovery'">
                    <span>Recupera tu contraseña</span>
                </div>
            </div>
            <div class="login-modal-text">
                <div v-show="option == 'login'">
                    <div class="text-center login-modal-text-large">
                        <span>Debes iniciar sesión para comprar</span>
                    </div>
                    <div class="text-center">
                        <span>¿No estas registrado? hazlo ahora</span>
                    </div>
                </div>
                <div class="text-center" v-show="option == 'register'">
                    <span>Llena los campos para crear tu cuenta</span>
                </div>
                <div v-show="option == 'recovery'">
                    <div class="nav nav-tabs nav-fill recovery-option-tabs col-12" id="nav-tab" role="tablist">
                        <div class="recovery-option col-4 text-center">
                            <a class="nav-item nav-link" :class="{ isActive: optionRecovery ==  '1' }">1</a>
                        </div>
                        <div class="recovery-option col-4 text-center">
                            <a class="nav-item nav-link" :class="{ isActive: optionRecovery ==  '2' }">2</a>
                        </div>
                        <div class="recovery-option col-4 text-center">
                            <a class="nav-item nav-link" :class="{ isActive: optionRecovery ==  '3' }">3</a>
                        </div>
                    </div>
                </div>
            </div>
            {{ Form::open(['v-on:submit.prevent' => 'submit()', "v-show" => "option == 'login'"]) }}
                <div class="form-group">
                    {{ Form::text('email','',['class' => 'form-control','v-model' => 'form.email', 'placeholder' => Lang::get('Page.Register.Email')]) }}
                </div>
                <div class="form-group">
                    {{ Form::password('password',['class' => 'form-control','v-model' => 'form.password', 'placeholder' => Lang::get('Page.Register.Password')]) }}
                </div>
                <div class="form-group text-center mt-4 pt-2">
                    <button class="btn btn-primary btn-viveres">Entrar</button>
                </div>
                <div class="text-center">
                    <a href="#!" v-on:click="option = 'recovery'" class="login-modal-link">
                        @lang('Page.Login.Recuperar')
                    </a>
                </div>
            {{ Form::close() }}
            {{ Form::open(['v-on:submit.prevent' => 'signin()', "v-show" => "option == 'register'"]) }}
                <div class="form-row">
                    <div class="col form-group">
                        {{ Form::text('nombre','',['class' => 'form-control','v-model' => 'formRegister.name', 'tabindex' => "1", 'maxlength' => '20', 'placeholder' => 'Nombre y apellido']) }}
                    </div>
                </div>    
                {{-- <div class="form-row">
                    <div class="form-group col-md-2">
                        {{ Form::select('type',[
                            'natural' => 'N',
                            'juridica' => 'J'
                        ],null,['class' => 'form-control','v-model' => 'formRegister.type']) }}
                    </div>
                    <div class="form-group " :class="`${formRegister.type != 'juridica' ? 'col-md-10' : 'col-md-4'}`">
                        {{ Form::number('identificacion','',['class' => 'form-control','v-model' => 'formRegister.identificacion', 'placeholder' => Lang::get('Page.Register.Identificacion')]) }}
                    </div>
                    <div class="form-group col-md-6" v-show="formRegister.type == 'juridica'">
                        {{ Form::text('empresa','',['class' => 'form-control', 'v-model' => 'formRegister.empresa', 'maxlength' => '20', 'placeholder' => 'Razón social']) }}
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col" v-show="formRegister.type == 'juridica'">
                        {{ Form::text('fiscal','',['class' => 'form-control', 'v-model' => 'formRegister.fiscal',  'maxlength' => '20', 'placeholder' => 'Dirección fiscal']) }}
                    </div>
                    <div class="form-group col">
                        {{ Form::text('email','',['class' => 'form-control','v-model' => 'formRegister.email', 'placeholder' => Lang::get('Page.Register.Email')]) }}
                    </div>
                </div> --}}
                <div class="form-row">
                    <div class="form-group col-md-2">
                        {{ Form::select('type',[
                            'natural' => 'C.I',
                            'juridica' => 'RIF'
                        ],null,['class' => 'form-control yellow-select','v-model' => 'formRegister.type']) }}
                    </div>
                    <div class="form-group col-md-10" >
                        {{ Form::number('identificacion','',['class' => 'form-control input-gray','v-model' => 'formRegister.identificacion', 'placeholder' => 'C.I./RIF']) }}
                    </div>
                </div>
                <div class="form-row" v-show="formRegister.type == 'juridica'">
                    <div class="form-group col">
                        {{ Form::text('empresa','',['class' => 'form-control', 'v-model' => 'formRegister.empresa', 'maxlength' => '20', 'placeholder' => 'Razón social']) }}
                    </div>
                    <div class="form-group col">
                        {{ Form::text('fiscal','',['class' => 'form-control', 'v-model' => 'formRegister.fiscal',  'maxlength' => '20', 'placeholder' => 'Dirección fiscal']) }}
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col">
                        {{ Form::text('email','',['class' => 'form-control','v-model' => 'formRegister.email', 'placeholder' => Lang::get('Page.Register.Email')]) }}
                    </div>
                    <div class="form-group col">
                        {{ Form::number('telefono','',['class' => 'form-control','v-model' => 'formRegister.telefono', 'placeholder' => Lang::get('Page.Register.Telefono')]) }}
                    </div>
                </div>
                <div class="form-row">
                    {{--<div class="form-group col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                        {{ Form::select('estado',$estados,null,['class' => 'form-control yellow-select','v-model' => 'formRegister.estado', 'v-on:change' => "formRegister.municipio = ''", 'tabindex' => '7', 'placeholder' => 'Estado']) }}
                    </div>--}}
                    {{--<div class="form-group col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                        <select placeholder="Municipio" :disabled="formRegister.estado == ''" name="municipio" 
                        id="municipio" class="form-control yellow-select" tabindex="8" v-model="formRegister.municipio">
                            <option value="" disabled selected>Municipio</option>
                            @foreach ($municipios as $municipio)
                                <option v-show="formRegister.estado == {{ $municipio->estado_id }}" value="{{ $municipio->id }}">{{ $municipio->name }}</option>
                            @endforeach
                        </select>
                    </div>--}}
                    {{--<div class="form-group col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                        <select 
                            placeholder="Sector" 
                            :disabled="formRegister.municipio == ''" 
                            name="parroquia" 
                            id="parroquia" 
                            class="form-control yellow-select" tabindex="8" 
                            v-model="formRegister.parroquia">
                            <option value="" disabled selected>Sector</option>
                            @foreach ($parroquias as $parroquia)
                                <option v-show="formRegister.municipio == {{ $parroquia->municipality_id }}" value="{{ $parroquia->id }}">{{ $parroquia->name }}</option>
                            @endforeach
                        </select>
                    </div>--}}
                </div>
                <div class="form-row">
                    <div class="form-group col">
                        {{ Form::text('direccion','',['class' => 'form-control',
                        'v-model' => 'formRegister.direccion',  'placeholder' => Lang::get('Page.Register.Direccion')]) }}
                    </div>
                    <div class="form-group col">
                        {{ Form::text('referencia','',['class' => 'form-control',
                            'v-model' => 'formRegister.referencia',  'placeholder' => 'Punto de referencia']) }}
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col">
                        {{ Form::password('password',['class' => 'form-control input-gray','v-model' => 'formRegister.password', 'placeholder' => Lang::get('Page.Register.Password')]) }}
                    </div>
                    <div class="form-group col">
                        {{ Form::password('password_confirmation',['class' => 'form-control input-gray','v-model' => 'formRegister.password_confirmation', 'placeholder' => Lang::get('Page.Register.RePassword')]) }}
                    </div>
                </div>
                <div class="form-group form-check mt-3">
                    <input type="checkbox" value="true" class="form-check-input" id="exampleCheck1" v-model="terminos">
                        <label class="form-check-label" for="exampleCheck1"><a href="{{ url('terminos') }}" class="login-modal-link">Términos y condiciones</a></label>
                  </div>
                <div class="form-group text-center mt-4">
                    <button class="btn btn-primary btn-viveres">Registrarme</button>
                </div>
                
            {{ Form::close() }}

            <div v-show="option == 'recovery'">
                <div v-show="optionRecovery == '1'">
                    <div class="form-group">
                        {{ Form::text('email','',['class' => 'form-control','v-model' => 'formRecovery.email', 'placeholder' => Lang::get('Page.Register.Email')]) }}
                    </div>
                    <div class="form-group text-center mt-4 pt-2" v-on:click="sendEmailRecovery()">
                        <button class="btn btn-primary btn-viveres">Continuar</button>
                    </div>  
                    <div class="form-group text-center mt-2 pt-2" v-on:click="setOptionDefault()">
                        <button class="btn btn-primary btn-viveres">Cancelar</button>
                    </div>
                    <div class="form-group text-center mt-2 pt-2" v-on:click="replaceStep('2')">
                        <button class="btn btn-primary btn-viveres">Ya tengo un código</button>
                    </div>
                </div>
                <div v-show="optionRecovery == '2'">
                    <div class="form-group">
                        {{ Form::text('text','',['class' => 'form-control','v-model' => 'formRecovery.codigo', 'placeholder' => Lang::get('Page.Register.Verificacion')]) }}
                    </div>
                    <div class="form-group text-center mt-4 pt-2" v-on:click="sendCodeRecovery()">
                        <button class="btn btn-primary btn-viveres">Continuar</button>
                    </div>
                    <div class="form-group text-center mt-2 pt-2" v-on:click="replaceStep('1')">
                        <button class="btn btn-primary btn-viveres">Volver</button>
                    </div>
                </div>
                <div v-show="optionRecovery == '3'">
                    <div class="form-row">
                        <div class="form-group col">
                            {{ Form::password('password',['class' => 'form-control input-gray','v-model' => 'formRecovery.password', 'placeholder' => Lang::get('Page.Register.Password')]) }}
                        </div>
                        <div class="form-group col">
                            {{ Form::password('password_confirmation',['class' => 'form-control input-gray','v-model' => 'formRecovery.password_confirmation', 'placeholder' => Lang::get('Page.Register.RePassword')]) }}
                        </div>
                    </div>
                    <div class="form-group text-center mt-4 pt-2" v-on:click="sendPasswordRecovery()">
                        <button class="btn btn-primary btn-viveres">Finalizar</button>
                    </div>
                    <div class="form-group text-center mt-2 pt-2" v-on:click="setOptionDefault()">
                        <button class="btn btn-primary btn-viveres">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
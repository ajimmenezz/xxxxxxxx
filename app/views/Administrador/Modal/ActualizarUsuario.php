<!-- Empezando panel actualizar usuarios-->
<div id="actualizarUsuario" class="panel panel-inverse">
    <!--Empezando cuerpo del panel-->
    <div class="panel-body">
        <form class="margin-bottom-0" id="formActualizarUsuarios" data-parsley-validate="true">
            <div id="primerColumna" class="row m-t-10">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nombreActualizarUsuarios">Perfil *</label>
                        <select id="selectActualizarPerfil" class="form-control" style="width: 100%" data-parsley-required="true">
                            <option value="">Seleccionar</option>
                            <?php
                            foreach ($perfiles as $item) {
                                echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nombreActualizarUsuarios">Email Corporativo *</label>
                        <input type="text" class="form-control" id="inputActualizarEmail" placeholder="Ingresa el nuevo Email Corporativo" data-parsley-type="email" data-parsley-required="true"/> 
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nombreActualizarUsuarios">Estatus</label>
                        <select id="selectActualizarEstatus" class="form-control" style="width: 100%" data-parsley-required="true">
                            <?php
                            foreach ($flag as $item) {
                                if ($item['Flag'] === '1') {
                                    ?>
                                    <option value="1" selected>Activo</option>
                                    <option value="0">Inactivo</option>
                                    <?php
                                } else {
                                    ?>
                                    <option value="1">Activo</option>
                                    <option value="0" selected>Inactivo</option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row m-t-10">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="nombreActualizarUsuarios">API Key Service Desk</label>
                        <input type="text" class="form-control" id="inputActualizarSDKey" placeholder="Ingresa el API Service Desk"/> 
                    </div>
                </div>
            </div>
            <div id="tercerCalumna" class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="catalogoActualizarPermisos">Permisos Adicionales</label>
                        <select id="selectActualizarPermisos" class="form-control" style="width: 100%" multiple="multiple">
                            <?php
                            foreach ($permisos as $item) {
                                echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div> 
            </div>     
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="form-inline muestraCarga"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!--Empezando error--> 
                <div class="col-md-12">
                    <div class="errorActualizarUsuario"></div>
                </div>
                <!--Finalizando Error-->
            </div>
        </form>
    </div>
</div>
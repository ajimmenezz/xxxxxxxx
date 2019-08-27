<!-- Empezando #contenido -->
<div id="contentCalendarioPermisos" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Calendario Permisos</h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel Autorizacion Permisos-->
    <div id="panelCalendarioPermisos" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <h4 class="panel-title"><strong> Calendario Permisos</h4>
            
        </div>
        <div class="tab-content">
            <div id="calendar" class=" calendar"></div>
        </div>
    </div>
   <?php 
   $_SESSION['Id'];
    echo " <span hidden id='spanID'>".$_SESSION['Id']."</span>";
   ?>
    <!-- Finalizando panel Autorizacion Permisos-->   

</div>
<!-- Modal -->
<div class="modal fade" id="modalDatosPermiso" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-black">
        <h5 class="modal-title text-white" id="exampleModalLabel">Información del permiso
        <button type="button" class="close " data-dismiss="modal" aria-label="Close" style="color: #000">
          <span class="text-white" aria-hidden="true" style="color:#000">&times;</span>
        </button>
        </h5>
        
      </div>
      <div class="modal-body text-center">
        <div id="idPermiso" hidden></div>
        <div id="idUsr" hidden></div>

        <div ><h5>Usuario: </h5></div>
        <input type="text" class="form-control text-center" readonly="readonly" name="" id="usr">
        <div> <h5>Estatus:</h5> </div>
        <input type="text" class="form-control text-center" readonly="readonly" name="" id="sts">
        <div ><h5>Motivo de ausencia:</h5></div>
        <input type="text" class="form-control text-center" readonly="readonly" name="" id="aus">
        <div><h5>Fecha de permiso: </h5></div>
        <input type="text" class="form-control text-center" readonly="readonly" name="" id="fed">
        
        <div id="feh"></div>
        <div id="hoe"></div>
        <div id="hos"></div>
        
        <div ><h5>Motivo : </h5></div>
        <input type="text" class="form-control text-center" readonly="readonly" name="" id="jus">
        <div ><h5>Tipo motivo: </h5><span id=""></span></div>
        <input type="text" class="form-control text-center" readonly="readonly" name="" id="mot">
        <div id="idus" hidden></div>
        <div id="idper" hidden></div>
        <div id="arc" hidden></div>

        <div id="datosAutorizacion" ></div>

      </div>
      <div class="modal-footer">
              <span id="BotonesAcciones"></span>
            <button type="button" class="btn btn-secondary" data-dismiss="modal" data-toggle="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<!-- Finalizando #contenido -->
<script src="../assets/js/customize/calendario/calendar.js"></script>
<script src="../assets/js/customize/calendario/es.js"></script>

<!-- Finalizando panel Revisar Permiso-->
<div id="modalRechazo" class="modal modal-message fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="loader"></div>
            <!--Empieza titulo del modal-->
            <div class="modal-header" style="text-align: center">
            </div>
            <!--Finaliza titulo del modal-->
            <!--Empieza cuerpo del modal-->
            <div class="modal-body">
                <div class="form-group">
                    <label>Motivo de rechazo</label>
                    <select id="motivoRechazo" class="form-control efectoDescuento" name="motivoRechazo" style="width: 100%">
                        <option value="" selected disabled>Seleccionar...</option>
                        <option id="rechazos"></option>
                    </select>
                </div>
            </div>
            <!--Finaliza cuerpo del modal-->
            <!--Empieza pie del modal-->
            <div class="modal-footer text-center">
                <a id="btnAceptarRechazo" class="btn btn-sm btn-success" data-dismiss="modal"><i class="fa fa-check"></i> Cerrar</a>
                <a id="btnCerrarAM" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</a>
            </div>
            <!--Finaliza pie del modal-->
        </div>
    </div>
</div>

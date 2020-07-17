

<?php 
 $titulo="Subir archivo";

?>


<div id="modalDelete" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">

            <div class="modal-body m-10">

                <div class="">

                        <form class="margin-bottom-0" id="formActualizarPerfiles" data-parsley-validate="true">
                            <div class="row m-t-10">
                                <div class="col-md-12">                        
                                    <div class="form-group">
                                        <h3 class="m-t-10"><?php $titulo ?></h3>
                                        <div class="underline m-b-15 m-t-15"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-t-10">
                                <div class="col-sm-10">
                                Para poder subir los cursos a través de un archivo de Excel es necesario seguir los siguientes pasos: <br><br>
                                1.- Debes descargar la plantilla de Excel en el botón descargar plantilla.<br>
                                2.- LLenar la plantilla con los datos solicitados.<br>
                                3.- Subir la platilla con el botón archivo (solo formato Excel).<br>
                                4.- Una vez cargado el archivo solo dar clic en subir archivo.<br><br>
                                </div>

                                <div class="col-sm-10">
                                    <span class="btn btn-primary btn-file" style="position: absolute; margin-right: -45px;" @click="upload()">
                                        <i class=" iconMenu icon-md universalicon-camera cursor f-14 mr-0 m-t-3" style="position: absolute; padding-top: 12px;  margin-left: -18px;">
                                        <!-- <input type="file" id="imgLogo" name="imgLogo" @change="subirLogo()"> -->
                                        <input :id="idInp+'--inputfile'" @change="onSelectedFiles" ref="file" type="file" name="files" style="display: none">
                                        </i>Subir archivo

                                    </span>
                                </div>
                            
                            </div>
                        
                                <!--Empezando error--> 
                                <div class="col-md-12">
                                    <div class="errorActualizarPerfil"></div>
                                </div>
                                <!--Finalizando Error-->
                                <div class="row m-t-10">
                                    <div class="col-md-12">
                                        <div class="form-group text-center">
                                            <br>
                                            <a href="javascript:;" class="btn btn-primary m-r-5 " id="cerrar"> Cerrar</a>
                                            <a href="javascript:;" class="btn btn-primary m-r-5 " id="desPlantilla">Descargar plantilla</a>
                                            <a href="javascript:;" class="btn btn-success m-r-5 " id="save"> Subir plantilla</a>
                                        </div>
                                    </div>
                                </div>
                            
                        </form>

                </div>

            </div>

        </div>
    </div>
</div>


<script>
    $('#modalDelete').modal('show')

</script>
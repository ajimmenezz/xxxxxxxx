<form id="formProblema" data-parsley-validate="true" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <h5 class="f-w-700">Describe el problema *</h5>
                <textarea id="textareaDescProblema" class="form-control" rows="4" data-parsley-required="true"></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h5 class="f-w-700">Adjuntos del problema *</h5>
            <div id="archivoProblema" class="form-group">
                <input id="agregarEvidenciaProblema" name="agregarEvidenciaProblema[]" type="file" multiple >
            </div>
        </div>
    </div>
    <div id="fileMostrarEvidenciaProblema" class="row hidden">
        <div class="col-md-12">
            <div id="evidenciasProblema">
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-12">
            <div id="errorAgregarProblema"></div>
        </div>
    </div>
</form>

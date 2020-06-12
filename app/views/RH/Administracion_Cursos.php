<!-- Empezando #contenido -->
<div id="administracion-cursos" class="content">
    <!-- begin page-header -->
    <h1 class="page-header">Administración de Cursos</h1>
    <!-- end page-header -->

    <div class="panel panel-inverse" data-sortable-id="ui-widget-1">
        <div class="panel-heading">
            <h4 class="panel-title">Panel (Default)</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-10">
                    Texto
                </div>
                <div class="col-md-2">
                    <button id="btn-subir-cursos" type="button" class="btn btn-info m-r-5 m-b-5">Subir Cursos</button>
                    <button id="btn-nuevo-curso" type="button" class="btn btn-primary m-r-5 m-b-5">Nuevo Curso</button>
                </div>
            </div>

            <!-- begin tabla cursos -->
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="tabla-cursos" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <?php
                                    foreach ($datos['columnas'] as $value) {
                                        echo '<th>' . $value . '</th>';
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($datos['filas'] as $value) {
                                    echo '<tr>';
                                    foreach ($value as $dato) {
                                        echo '<td>' . $dato . '</td>';
                                    }
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- end tabla cursos -->
        </div>
    </div>
</div>
<!-- Finalizando #contenido -->

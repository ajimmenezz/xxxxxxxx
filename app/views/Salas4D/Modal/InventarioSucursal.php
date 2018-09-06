<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-12">
        <h1 class="page-header">Captura de inventario</h1>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 text-right">
        <div class="btn-group">
            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Acciones <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li id="btnVersionImprimible"><a href="#"><i class="fa fa-print"></i> Versión imprimible</a></li>
            </ul>
        </div>
        <label id="btnRegresarToMain" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>    
<div id="panelResumenInventario" class="panel panel-inverse">        
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
        </div>
        <h4 class="panel-title">Inventario del complejo</h4>
        <input type="hidden" id="idSucursal" value="">
    </div>
    <div class="panel-body">
        <div class="row"> 
            <div class="col-md-12">
                <div class="form-grup">
                    <h4 class="m-t-10" id="titleResumenInventario"></h4>                        
                    <div class="underline m-b-15 m-t-15"></div>                                               
                </div>
            </div>
            <div class="col-md-12">
                <div id="errorCapturePageInventario"></div>
            </div>
        </div>           
        <div class="m-t-30" id="vistaElementos">
            <div class="row">
                <div class="col-md-12 text-center">
                    <a role="button" id="btnVerVistaSubelementos" class="f-w-600 f-s-16 m-l-15">Vista sub-elementos</a>
                </div>
            </div>
            <div class="row m-t-20"> 
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-grup">
                        <h4 class="m-t-10">Vista de elementos</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="btn-group pull-right">
                        <button class="btn btn-success f-w-700 f-s-15" id="btnAddElement"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Elemento</button>                        
                    </div>
                </div>
            </div>
            <div class="row m-b-20">
                <div class="col-md-12">
                    <div class="underline m-b-15 m-t-15"></div>                                               
                </div>
            </div>  
            <div class="table-responsive">
                <table id="data-table-elementos" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                    <thead>
                        <tr>
                            <th class="never">Id</th>
                            <th class="all">Elemento</th>
                            <th class="all">Serie</th>
                            <th class="all">Clave Cinemex</th>
                            <th class="all">Ubicación</th>
                            <th class="all">Sistema</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($elementos as $key => $value) {
                            echo '<tr>'
                            . '<td>' . $value['Id'] . '</td>'
                            . '<td>' . $value['Elemento'] . '</td>'
                            . '<td>' . $value['Serie'] . '</td>'
                            . '<td>' . $value['ClaveCinemex'] . '</td>'
                            . '<td>' . $value['Ubicacion'] . '</td>'
                            . '<td>' . $value['Sistema'] . '</td>'
                            . '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="m-t-30" id="vistaSubelementos" style="display: none">
            <div class="row">
                <div class="col-md-12 text-center">
                    <a role="button" id="btnVerVistaElementos" class="f-w-600 f-s-16 m-r-15">Vista elementos</a>
                </div>
            </div>
            <div class="row m-t-20"> 
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-grup">
                        <h4 class="m-t-10">Vista de sub-elementos</h4>
                    </div>
                </div>
            </div>
            <div class="row m-b-20">
                <div class="col-md-12">
                    <div class="underline m-b-15 m-t-15"></div>                                               
                </div>
            </div>
            <div class="table-responsive">
                <table id="data-table-subelementos" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                    <thead>
                        <tr>
                            <th class="never">Id</th>
                            <th class="all">Subelemento</th>
                            <th class="all">Elemento</th>
                            <th class="all">Serie</th>
                            <th class="all">Clave Cinemex</th>
                            <th class="all">Ubicación</th>
                            <th class="all">Sistema</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($subelementos as $key => $value) {
                            echo '<tr>'
                            . '<td>' . $value['Id'] . '</td>'
                            . '<td>' . $value['Subelemento'] . '</td>'
                            . '<td>' . $value['Elemento'] . '</td>'
                            . '<td>' . $value['Serie'] . '</td>'
                            . '<td>' . $value['ClaveCinemex'] . '</td>'
                            . '<td>' . $value['Ubicacion'] . '</td>'
                            . '<td>' . $value['Sistema'] . '</td>'
                            . '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>        
</div>
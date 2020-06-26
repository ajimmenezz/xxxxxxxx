<!-- Empezando #contenido -->
<div class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Contratos CIMOS</h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel seguimiento Mesa de Ayuda-->
    <div id="panelContratosCIMOS" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">                
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Contratos CIMOS</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row"> 
                <div class="col-md-12">  
                    <h3 class="m-t-10">Buscar Contratos</h3>

                    <div class="underline m-b-15"></div>                        
                </div>    
            </div>                 
            <div class="row">
                <div class="col-md-3 col-sm-4 col-xs-12 form-group">
                    <label class="f-s-14 f-w-600">ID Cliente (Mindbody)</label>
                    <div class="input-group">     
                        <div class="input-group-btn">                                       
                            <input type="text" id="txtID" class="form-control" />
                            <button type="button" id="btnBuscarContratos" class="btn btn-success f-s-13 f-w-600"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>  
                    <div class="divError"></div>
                </div>
            </div>
            <div id="divResult" style="display: none">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <h4 class="m-t-10">Contrato de Prestación de Servicios</h4>                        
                        <div class="underline m-b-15"></div>                        
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <h4 class="m-t-10">Suscripciones Activas</h4>
                        <div class="underline m-b-15"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12" id="divContratos">

                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12" id="divSuscripciones">                        
                    </div>
                </div>            
            </div>
            <!--Finalizando cuerpo del panel-->
        </div>
        <!-- Finalizando panel seguimiento-->   
    </div>
    <!-- Finalizando #contenido -->
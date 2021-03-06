<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h1 class="page-header">Detalles del Gasto</h1>
    </div>
    <div class="col-md-6 col-xs-6 text-right">
        <div class="btn-group">
            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Acciones <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">                
                <li id="btnMarcarLeido"><a href="#"><i class="fa fa-envelope-open" aria-hidden="true"></i> Marcar como leído</a></li>
<!--                <li id="btnSolicitudMaterial"><a href="#"><i class="fa fa-file"></i> Imprimir Solicitud de Material</a></li>
                <li id="btnSolicitudMaterialFaltante"><a href="#"><i class="fa fa-file"></i> Imprimir Material Faltante</a></li>-->
        </div>
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label> 
    </div>
</div>
<div id="panelFormularioGasto" class="panel panel-inverse">        
    <div class="panel-heading">    
        <h4 class="panel-title">Detalles del gasto</h4>
    </div>
    <div class="panel-body">
        <?php
//        echo "<pre>";
//        var_dump($Pagos);
//        echo "</pre>";

        $_cliente = $Gasto['gasto']['Cliente'];
        $_tipoServicio = $Gasto['gasto']['TipoServicio'];
        $_tipoBeneficiario = $Gasto['gasto']['TipoBeneficiario'];
        $_tipoTrans = $Gasto['gasto']['TipoTrans'];
        $_oc = $Gasto['gasto']['OrdenCompra'];
        $_fechaCredito = $Gasto['gasto']['FechaPagoCredito'];
        if ($_fechaCredito == '') {
            $_credito = '';
        } else {
            $_credito = 'checked';
            $_fechaCredito = substr($_fechaCredito, 0, 10);
        }

        $__proyecto = $Gasto['gasto']['Proyecto'];
        $__sucursal = $Gasto['gasto']['Sucursal'];
        $__beneficiario = $Gasto['gasto']['IDBeneficiario'];
        $__disable = ($Editable) ? '' : 'disabled="disabled"';
        ?>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div id="errorTop"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h4>Detalles del gasto <?php echo $Gasto['gasto']['ID']; ?></h4>
                <input type="hidden" id="IDGasto" value="<?php echo $Gasto['gasto']['ID']; ?>" />                
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h4 class="text-danger f-w-600 pull-right"><?php echo $Gasto['gasto']['Status']; ?></h4>               
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="underline m-b-10"></div>
            </div>
        </div>
        <?php
        if ($Gasto['gasto']["Status"] == "Rechazado") {
            ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="note note-danger">
                        <h4>Comentarios del Rechazo</h4>
                        <p>
                            <?php echo $Gasto["gasto"]["ComentarioRechazo"]; ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <form id="formGasto" data-parsley-validate="true">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Cliente:*</label>
                        <select id="listClientes" class="form-control" style="width: 100%" data-parsley-required="true" <?php echo $__disable; ?>>
                            <option value="">Selecciona . . .</option>
                            <?php
                            if (isset($Clientes) && count($Clientes) > 0) {
                                foreach ($Clientes as $key => $value) {
                                    $selected = ($value['ID'] == $_cliente) ? 'selected' : '';
                                    echo '<option value="' . $value['ID'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>                 
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Proyecto:*</label>
                        <select id="listProyectos" class="form-control" style="width: 100%" data-parsley-required="true" <?php echo $__disable; ?>>
                            <option value="">Selecciona . . .</option>
                            <?php
                            if (isset($Proyectos['proyectos']) && count($Proyectos['proyectos']) > 0) {
                                foreach ($Proyectos['proyectos'] as $key => $value) {
                                    $selected = ($value['ID'] == $__proyecto) ? 'selected' : '';
                                    echo '<option data-tipo="' . $value['Tipo'] . '" value="' . $value['ID'] . '" ' . $selected . '>' . $value['Tipo'] . ' - ' . $value['Nombre'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>                 
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Sucursal:*</label>
                        <select id="listSucursales" class="form-control" style="width: 100%" data-parsley-required="true" <?php echo $__disable; ?>>
                            <option value="">Selecciona . . .</option>
                            <?php
                            if (isset($Sucursales['sucursales']) && count($Sucursales['sucursales']) > 0) {
                                foreach ($Sucursales['sucursales'] as $key => $value) {
                                    $selected = ($value['ID'] == $__sucursal) ? 'selected' : '';
                                    echo '<option value="' . $value['ID'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Tipo de Servicio:*</label>
                        <select id="listTiposServicio" class="form-control" style="width: 100%" data-parsley-required="true" <?php echo $__disable; ?>>
                            <option value="">Selecciona . . .</option>
                            <?php
                            if (isset($TiposServicio) && count($TiposServicio) > 0) {
                                foreach ($TiposServicio as $key => $value) {
                                    $selected = ($value['Nombre'] == $_tipoServicio) ? 'selected' : '';
                                    echo '<option value="' . $value['ID'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                                }
                            }
                            ?>                                
                        </select>
                    </div>
                </div>                
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Tipo de Beneficiario:*</label>
                        <select id="listTipoBeneficiario" class="form-control" style="width: 100%" data-parsley-required="true" <?php echo $__disable; ?>>
                            <option value="">Selecciona . . .</option>
                            <?php
                            if (isset($TiposBeneficiario) && count($TiposBeneficiario) > 0) {
                                foreach ($TiposBeneficiario as $key => $value) {
                                    $selected = ($value['ID'] == $_tipoBeneficiario) ? 'selected' : '';
                                    echo '<option value="' . $value['ID'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                                }
                            }
                            ?>                                
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Beneficiario:*</label>
                        <select id="listBeneficiarios" class="form-control" style="width: 100%" data-parsley-required="true" <?php echo $__disable; ?>>
                            <option value="">Selecciona . . .</option>
                            <?php
                            if (isset($Beneficiarios['beneficiarios']) && count($Beneficiarios['beneficiarios']) > 0) {
                                foreach ($Beneficiarios['beneficiarios'] as $key => $value) {
                                    $selected = ($value['ID'] == $__beneficiario) ? 'selected' : '';
                                    echo '<option value="' . $value['ID'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Tipo de Transferencia:*</label>
                        <select id="listTipoTrasnferencia" class="form-control" style="width: 100%" <?php echo $__disable; ?>>
                            <option value="">Selecciona . . .</option>
                            <?php
                            if (isset($TiposTransferencia) && count($TiposTransferencia) > 0) {
                                foreach ($TiposTransferencia as $key => $value) {
                                    $selected = ($value['Nombre'] == $_tipoTrans) ? 'selected' : '';
                                    echo '<option value="' . $value['ID'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Orden de Compra:</label>
                        <input type="text" class="form-control" id="txtOC" placeholder="" value="<?php echo $_oc; ?>"  data-parsley-required="false" <?php echo $__disable; ?>/>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">¿A crédito? <input type="checkbox" id="checkCredito" <?php echo $_credito; ?>/></label>
                        <input type="date" class="form-control" id="fechaCredito" disabled="" data-parsley-required="false" value="<?php echo $_fechaCredito; ?>" />
                    </div>
                </div>
            </div>            
            <div class="row m-t-10">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h4>Conceptos del Gasto</h4>
                    <div class="underline m-b-10"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div id="errorConceptoGasto"></div>
                </div>
            </div>
            <?php if ($Editable) { ?>
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Categoría:*</label>
                            <select id="listCategoria" class="form-control" style="width: 100%" disabled="">
                                <option value="">Selecciona . . .</option>                            
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Subcategoría:*</label>
                            <select id="listSubcategoria" class="form-control" style="width: 100%" disabled="">
                                <option value="">Selecciona . . .</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Concepto:*</label>
                            <select id="listConceptos" class="form-control" style="width: 100%" disabled="">
                                <option value="">Selecciona . . .</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Monto:*</label>    
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="number" step="any" id="txtMonto" class="form-control"> 
                                <div class="input-group-btn m-l-10">
                                    <a id="btnAddConcepto" class="btn btn-success"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="row m-t-10">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table id="table-conceptos-gasto" class="table table-condensed table-stripped">
                            <thead>
                                <tr>
                                    <th>Categoría</th>
                                    <th>Subcategoría</th>
                                    <th>Concepto</th>
                                    <th>Monto</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($Gasto['conceptos']) && count($Gasto['conceptos']) > 0) {
                                    foreach ($Gasto['conceptos'] as $key => $value) {
                                        echo '
                                            <tr>
                                                <td>' . $value['Categoria'] . '<input type="hidden" class="value-categoria" value="' . $value['Categoria'] . '" /></td>
                                                <td>' . $value['SubCategoria'] . '<input type="hidden" class="value-subcategoria" value="' . $value['SubCategoria'] . '" /></td>
                                                <td>' . $value['Concepto'] . '<input type="hidden" class="value-concepto" value="' . $value['Concepto'] . '" /></td>
                                                <td>$' . (float) $value['Monto'] . '<input type="hidden" class="value-monto" value="' . $value['Monto'] . '" /></td>';

                                        if ($Editable) {
                                            echo '<td><button class="btn btn-danger btnRemoveConcepto"><i class="fa fa-trash"></i></button></td>';
                                        } else {
                                            echo '<td></td>';
                                        }
                                        echo '</tr>';
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" style="text-align: right !important;" class="f-w-700 f-s-15">TOTAL</th>
                                    <th id="columna-total" class="f-w-700 f-s-15">$0.00</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row m-t-10">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h4>Información Adicional</h4>
                    <div class="underline m-b-10"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Descripción:*</label>
                        <input type="text" class="form-control" id="txtDescripcion" value="<?php echo $Gasto['gasto']['Descripcion']; ?>" placeholder="Breve Descripción del Gasto"  data-parsley-required="true" <?php echo $__disable; ?>/>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Moneda:*</label>
                        <select class="form-control" id="listMonedas" data-parsley-required="true" <?php echo $__disable; ?>>
                            <option value="" selected="">Selecciona . . .</option>
                            <option value="MN" <?php echo ($Gasto['gasto']['Moneda'] == 'MN') ? 'selected' : ''; ?>>MN (Peso Mexicano)</option>
                            <option value="USD" <?php echo ($Gasto['gasto']['Moneda'] == 'USD') ? 'selected' : ''; ?>>USD (Dolar Americano)</option>
                        </select>
                    </div>
                </div>
            </div>
            <?php
            if (isset($Gasto['archivosGasto']) && $Gasto['archivosGasto'] != '') {
                $archivosGasto = explode(",", $Gasto['archivosGasto']);
                ?>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 m-t-20">
                        <?php
                        foreach ($archivosGasto as $key => $value) {
                            echo '<div class="thumbnail-pic m-5 p-5">';
                            if ($Editable) {
                                echo '<span role="button" class="edit deleteButton" data-src="' . $value . '"><i class="fa fa-2x fa-minus-circle text-danger" aria-hidden="true"></i></span>';
                            }
                            $ext = strtolower(pathinfo($value, PATHINFO_EXTENSION));
                            switch ($ext) {
                                case 'png': case 'jpeg': case 'jpg': case 'gif':
                                    echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="' . $value . '" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                    break;
                                case 'xls': case 'xlsx':
                                    echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/excel_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                    break;
                                case 'doc': case 'docx':
                                    echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/word_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                    break;
                                case 'pdf':
                                    echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/pdf_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                    break;
                                default :
                                    echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/no-thumbnail.jpg" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                    break;
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div id="errorDeleteImages"></div>
                    </div>
                </div>
                <?php
            }

            if ($Editable) {
                ?>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Archivos Adicionales</label>
                            <input id="fotosGasto" name="fotosGasto[]" type="file" multiple=""/>    
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="row">
                <div class="col-md-6 col-sm-9 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Observaciones:</label>
                        <textarea class="form-control" id="txtObservaciones" rows="5" placeholder="Observaciones adicionales de la solicitud de gasto." <?php echo $__disable; ?>><?php echo $Gasto['gasto']['Observaciones']; ?></textarea>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div id="errorFormulario"></div>
            </div>
        </div>
        <?php
        if ($Editable) {
            ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                    <a id="btnGuardarGasto" class="btn btn-info m-t-10 m-r-10 f-w-600 f-s-15">Guardar Gasto</a>                
                </div>
            </div>
            <?php
        }


        if (isset($Pagos) && count($Pagos) > 0) {
            ?>
            <div class="row m-t-10">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h4>Comprobantes de Pago</h4>
                    <div class="underline m-b-10"></div>
                </div>
            </div>            
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 m-t-20">
                    <?php
                    foreach ($Pagos as $key => $value) {
                        echo '<div class="thumbnail-pic m-5 p-5">';
                        $ext = strtolower(pathinfo($value, PATHINFO_EXTENSION));
                        switch ($ext) {
                            case 'png': case 'jpeg': case 'jpg': case 'gif':
                                echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="' . $value . '" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                break;
                            case 'xls': case 'xlsx':
                                echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/excel_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                break;
                            case 'doc': case 'docx':
                                echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/word_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                break;
                            case 'pdf':
                                echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/pdf_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                break;
                            default :
                                echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/no-thumbnail.jpg" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                break;
                        }
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            <?php
        }
        ?>
    </div>        
</div>        

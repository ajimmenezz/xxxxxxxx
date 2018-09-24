
<!-- Empezando #sidebar -->
<div id="sidebar" class="sidebar">
    <!-- Empezando sidebar scrollbar -->
    <div data-scrollbar="true" data-height="100%">
        <!-- Empezando sidebar usuario -->
        <ul class="nav">
            <li class="nav-profile">
                <div class="image">
                    <?php (empty($datosUsuario['UrlFoto'])) ? $foto = '/assets/img/user-13.jpg' : $foto = $datosUsuario['UrlFoto']; ?>
                    <a href="javascript:;"><img src="<?php echo $foto; ?>" alt="" /></a>
                </div>
                <div class="info">
                    <?php echo $usuario['Nombre']; ?>                    
                    <small class="text-center m-t-5"><br /><strong><?php echo $usuario['Perfil']; ?></strong></small>
                    <small class="text-center m-t-10"><br /><?php echo $fechaServidor; ?><br /><span id="hora"></span></small>
                    <input type="hidden" id="horaServidor" value="<?php echo $horaServidor; ?>" />
                </div>
            </li>
        </ul>
        <!-- Finalizando sidebar usuario -->

        <!-- Empezando sidebar menu nav -->
        <ul class="nav" id="menuPrincipal">
            <!-- Empezando sidebar boton oculatar menu nav -->
            <li id="hideMenu"><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
            <!-- Finalizando sidebar boton oculatar menu nav -->

            <!-- Empezando secciones Generales -->
            <?php
            $Modulos = array();
            $generales = NULL;
            $menuPrincipal = NULL;
            $cantidadNotificaciones = NULL;
            foreach ($notificaciones as $key => $value) {
                if (array_key_exists('cantidad', $value)) {
                    $cantidadNotificaciones = $value['cantidad'];
                }
            }
            foreach ($menu['Menu'] as $elementos) {
                foreach ($elementos as $modulo => $secciones) {
                    if (!in_array($modulo, $Modulos)) {
                        array_push($Modulos, $modulo);
                        if ($modulo !== 'Generales' && $modulo !== 'Configuracion') {
                            $menuPrincipal .= '<li class="has-sub ' . $modulo . '">
                                            <a href="javascript:;">
                                                <b class="caret pull-right"></b>
                                                <i class="' . $menu['Modulos'][$modulo]['icono'] . '"></i>
                                                <span>' . $modulo . '</span>
                                            </a>
                                            <ul class="sub-menu">';

                            foreach ($secciones as $indice) {
                                foreach ($indice as $pagina => $datos) {
                                    if ($pagina === $librerias) {
                                        $menuPrincipal = str_replace('<li class="has-sub ' . $modulo . '">', '<li class="has-sub active">', $menuPrincipal);
                                        $menuPrincipal .= '
                                                        <li class="has-sub active">
                                                            <a href="' . $datos['Url'] . '">
                                                                <i class="' . $datos['icono'] . '"></i>
                                                                <span>' . $datos['liga'] . '</span>
                                                            </a>                                                
                                                        </li>';
                                    } else {
                                        $menuPrincipal .= '
                                                        <li class="has-sub">
                                                            <a href="' . $datos['Url'] . '">
                                                                <i class="' . $datos['icono'] . '"></i>
                                                                <span>' . $datos['liga'] . '</span>
                                                            </a>                                                
                                                        </li>';
                                    }
                                }
                            }
                            $menuPrincipal .= '</ul></li>';
                        } else if ($modulo === 'Generales') {
                            $identificadroNotificaion = null;
                            if (!empty($cantidadNotificaciones)) {
                                $identificadroNotificaion = '<span id="notificaciones-menu" class="badge pull-right">' . $cantidadNotificaciones . '</span>';
                            }
                            foreach ($secciones as $indice) {
                                foreach ($indice as $pagina => $datos) {
                                    if ($pagina == $librerias) {
                                        if ($pagina !== 'Notificaciones') {
                                            $generales .= '<li class="has-sub active">
                                                <a href="' . $datos['Url'] . '">
                                                    <i class="' . $datos['icono'] . '"></i>
                                                    <span>' . $datos['liga'] . '</span>
                                                </a>
                                             </li>';
                                        } else {
                                            $generales .= '<li class="has-sub active">
                                                <a href="' . $datos['Url'] . '">
                                                    ' . $identificadroNotificaion . '
                                                    <i class="' . $datos['icono'] . '"></i>
                                                    <span>' . $datos['liga'] . '</span>
                                                </a>
                                             </li>';
                                        }
                                    } else {
                                        if ($pagina !== 'Notificaciones') {
                                            $generales .= '<li class="has-sub">
                                                <a href="' . $datos['Url'] . '">
                                                    <i class="' . $datos['icono'] . '"></i>
                                                    <span>' . $datos['liga'] . '</span>
                                                </a>
                                             </li>';
                                        } else {
                                            $generales .= '<li class="has-sub">
                                                <a href="' . $datos['Url'] . '">
                                                    ' . $identificadroNotificaion . '
                                                    <i class="' . $datos['icono'] . '"></i>
                                                    <span>' . $datos['liga'] . '</span>
                                                </a>
                                             </li>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            echo $generales;
            ?>

            <!-- Finalizando secciones generales -->

            <!--Empezando titulo del menu nav-->
            <li class="nav-header">Menu Principal</li>
            <!--Finalizando titulo del menu nav-->

            <!--Empezando opciones del menu nav-->
            <?php echo $menuPrincipal; ?>
            <!--Finalizando opciones del menu nav-->
        </ul>
        <!-- Finalizando sidebar menu nav -->
    </div>
    <!-- Finalizando sidebar scrollbar -->
</div>
<div class="sidebar-bg"></div>
<!-- Finalizando #sidebar -->

<?php

foreach ($notas as $key => $value) {
    $image = ($value['UrlFoto'] != '') ? $value['UrlFoto'] : '/assets/img/siccob-logo.png';
    ?>
    <div class="timeline-header m-t-15">
        <span class="userimage"><img class="icon_user" src="<?php echo $image; ?>" alt=""></span>
        <span class="username"><?php echo $value['Usuario']; ?></span>
        <span class="pull-right text-muted"><?php echo $value['Fecha']; ?></span>
    </div>
    <div class="timeline-content">
        <p class="lead">
            <i class="fa fa-quote-left fa-fw pull-left"></i>
            <?php echo $value['Nota']; ?>
            <i class="fa fa-quote-right fa-fw pull-right"></i>
        </p>
        <?php
        if ($value['Adjuntos'] != '') {
            ?>            
            <div class="gallery gallery-inline">
                <?php
                $adjuntos = explode(",", $value['Adjuntos']);
                foreach ($adjuntos as $k => $v) {
                    $partes_ruta = pathinfo($v);
                    $src = '';
                    $lightboxOTarget = '';
                    switch (strtolower($partes_ruta['extension'])) {
                        case 'xls': case 'xlsx':
                            $src = '/assets/img/Iconos/excel_icon.png';
                            $lightboxOTarget = ' target="_blank" ';
                            break;
                        case 'doc': case 'docx':
                            $src = '/assets/img/Iconos/word_icon.png';
                            $lightboxOTarget = ' target="_blank" ';
                            break;
                        case 'pdf':
                            $src = '/assets/img/Iconos/pdf_icon.png';
                            $lightboxOTarget = ' target="_blank" ';
                            break;
                        case 'png': case 'jpg': case 'jpeg': case 'bmp': case 'gif':
                            $src = $v;
                            $lightboxOTarget = 'data-lightbox="gallery-group-' . $value['Id'] . '" ';
                            break;
                        default:
                            $src = '/assets/img/Iconos/no-thumbnail.jpg';
                            $lightboxOTarget = ' target="_blank" ';
                            break;
                    }
                    ?>

                    <div class="image gallery-group-<?php echo $value['Id']; ?>">                    
                        <div class="image-inner">
                            <a href="<?php echo $v; ?>" <?php echo $lightboxOTarget; ?>>
                                <img src="<?php echo $src; ?>" alt="">
                            </a>
                            <p class="image-caption">
                                <?php echo $partes_ruta['filename']; ?>
                            </p>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}
?>

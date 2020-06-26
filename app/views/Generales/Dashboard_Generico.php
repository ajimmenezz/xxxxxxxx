<!-- Empezando #contenido -->
<div id="content" class="content">
    <div id="initialPage">
        <!-- Empezando titulo de la pagina -->
        <h1 class="page-header">Dashboards</h1>
        <?php 
            foreach ($datos['dashboard'] as $value) {
                echo $value;
            }
        ?>
        <!-- Finalizando titulo de la pagina -->
       
    </div>
</div>
<!-- Finalizando #contenido -->
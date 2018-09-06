<html>
    <head>
        <link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
        <link href="/assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
        <link href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
        <link href="/assets/css/style.min.css" rel="stylesheet" />
        <link href="/assets/css/theme/default.css" rel="stylesheet" id="theme" />         
    </head>
    <style>
        *{
            font-size: 10px !important;
        }
        a{
            font-size: 0px !important;
        }
    </style>
    <body style="background-color: #fff !important; ">        
        <table>
            <tr>
                <th style="width:15%"><img src="/assets/img/siccob-logo.png" style="max-height:100px !important;" /></th>
                <th style="width:15%; text-align:right !important; valign:top"><h3 class="f-w-700" style="text-align:right !important; width:100%"><?php echo $titulo; ?></h3></th>
            </tr>
        </table>        
        <?php echo $html; ?>
    </body>
</html>

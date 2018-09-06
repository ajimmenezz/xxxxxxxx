<html>
    <head>
        <link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
        <link href="/assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
        <link href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
        <link href="/assets/css/style.min.css" rel="stylesheet" />
        <link href="/assets/css/theme/default.css" rel="stylesheet" id="theme" /> 
        <style>
            /*            body {
                            background-image: url(/assets/img/siccob-logo.png);
                            background-attachment: fixed;
                            background-repeat: no-repeat;
                            background-position: 50% 50%;
                        }*/

            .marcaAgua:after {
                content: "SICCOB"; 
                font-size: 12em;  
                color: #000;
                opacity: 0.2;
                /*transform: rotate(-190deg);*/
                display: block; 
                -webkit-transform: rotate(-65deg); 
                -moz-transform: rotate(-65deg);
                text-align: -webkit-center;

                /*: 15px;*/
                /*background-color: lightblue;*/
                /*white-space: nowrap;*/
                /*z-index: 9999;*/

                /*display: flex;*/
                /*align-items: center;*/
                /*justify-content: center;*/
                /*position: fixed;*/
                /*                top: 0;
                                right: 0;
                                bottom: 0;
                                left: 0;*/

                /*padding: .3em;*/

                /*                -webkit-pointer-events: none;
                                -moz-pointer-events: none;
                                -ms-pointer-events: none;
                                -o-pointer-events: none;
                                pointer-events: none;
                
                                -webkit-user-select: none;
                                -moz-user-select: none;
                                -ms-user-select: none;
                                -o-user-select: none;
                                user-select: none;*/
            }
            body .divTablas{
                font-size: small;
            }            

            h2,h5,h6{
                font-size: 0.7em;
            }

            h1{
                font-size: 0.9em;
            }

            h4{
                font-size: 0.75em;
            }

            h3{
                font-size: 0.875em;
            }

            td,th{
                padding: .1px 5px;
                font-size: 0.7em;
            }

        </style>
    </head>
    <body style="background-color: #fff !important; ">
        <div class="divTablas">
            <table>
                <tr>
                    <th style="width:15%"><img src="/assets/img/siccob-logo.png" style="max-height:100px !important;" /></th>
                    <th style="width:15%; text-align:right !important; valign:top"><h3 class="f-w-700" style="text-align:right !important; width:100%"><?php echo $titulo; ?></h3></th>
                </tr>
            </table>
        </div>
        <?php echo $html; ?>
    </body>
</html>

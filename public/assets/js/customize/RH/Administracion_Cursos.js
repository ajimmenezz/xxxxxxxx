$(function () {

    var evento = new Base();
    var eventoPagina = new Pagina();
    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();
    
    let tablaCursos = new TablaBasica('tabla-cursos');
    tablaCursos.iniciarTabla();

    
    
    
    $('#btn-nuevo-curso').on('click',function(e){
        $("#administracion-cursos").css('display', 'none')
        $("#administracion-cursos_nuevoCurso").css('display', 'block')
        console.log('clic en boton');
        let datos = { nombre : 'Noe'};
        eventoPagina.enviarPeticionServidor('administracion-cursos','Administracion_Cursos/Nuevo-Curso',datos,function(respuesta){
            console.log(respuesta);

        });
    });


    $('#btn-adminVerCurso').on('click',function(e){
     alert("VER CURSO");
    });
  
    $('#btn-adminEditarCurso').on('click',function(e){
     alert("EDITAR CURSO");
    });

    $('#btn-adminEliminarCurso').on('click',function(e){
     alert("ELIMINAR CURSO");
    });

    let tablaTemarios = null;
    tablaTemarios = new TablaBasica('tabla-cursos-temario');
    
    $("#wizard").bwizard({ validating: function (e, ui) {
      alert("VALIDATE");
      console.log(e,ui.index,ui.nextIndex);
      // $("#wizard").bwizard("abort");
      // $("#wizard").bwizard("back");
      //let infoTabla = tablaTemarios.validarNumeroFilas();
     // tablaTemarios.datosTabla()
     if(ui.nextIndex==1){
       alert("paso1-2");
     if (evento.validarFormulario('#formDatosNewCurso') ) { //&& infoTabla == true
     }
    }
    if(ui.nextIndex==2){
      alert("paso2-3");
    }
    
     } });

     

     

    

    // $("#wizard").bwizard({ activeIndexChanged: function (e, ui) { 
    
    //   console.log(ui.index);
    //   //$("#wizard").bwizard("abort");
    //   $("#wizard").bwizard("back");
    //   alert("ABORT");
    //   //showContentWizard(ui.index)
    // } });

    function showContentWizard(index) {
      
    }


  
  


    $(".btn-cancel_wizard").on('click',function(e){
        //modalSubirTemarios
        console.log("cancelar wizard")
        $('#modalSubirTemarios').modal('hide')
        $('#modalValidateTemario').modal('hide')
        $("#modalValidateParticipantes").modal('hide');

        $("#administracion-cursos").css('display', 'block')
        $("#administracion-cursos_nuevoCurso").css('display', 'none')
      
  });
  


$('#btn-agregar-nuevo-temario').on('click',function(e){
        //modalSubirTemarios
        console.log("btn-agregar-nuevo-temario")
      //$('#modalSubirTemarios').modal('show')
  });

  $("#btn-agregar-nuevo-temario").on('click',function(e){
    //modalSubirTemarios
    console.log("btn-agregar-nuevo-temario")
  $('#modalValidateTemario').modal('show')
});


$("#btn-nuevo-puestoParticipante").on('click',function(e){
    //modalSubirTemarios
    console.log("btn-nuevo-puestoParticipante")
  $('#modalValidateParticipantes').modal('show')
});

$("#btn-save-curso").on('click',function(e){
    //modalSubirTemarios
    console.log("btn-save-curso")
  $('#modalresponseSave').modal('show')
});


  
    $("#btn-loadExcel-temario").on('click',function(e){
          //modalSubirTemarios
          console.log("ABRIR_MODAL")
        $('#modalSubirTemarios').modal('show')
    });




    $("#wizard").bwizard();

    //nuevo curso

    let tablaCursosTemario = new TablaBasica('tabla-cursos-temario');
    tablaCursosTemario.iniciarTabla();
    

    let tablaCursosParticipantes = new TablaBasica('tabla-cursos-participantes');
    tablaCursosParticipantes.iniciarTabla();

   
  
    // FormWizardValidation.init();

    
    
});

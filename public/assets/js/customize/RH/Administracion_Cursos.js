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
     $('#modalSubirTemarios').modal('hide')
     $('#modalValidateTemario').modal('hide')
     $("#modalValidateParticipantes").modal('hide');
     $("#modalDeletoCursoAdmin").modal('hide')

     $("#administracion-cursos").css('display', 'none')
     $("#administracion-cursos_nuevoCurso").css('display', 'none')

     $("#administracion-cursos-ver").css('display', 'block')
    });
  
    $('#btn-adminEditarCurso').on('click',function(e){
     alert("EDITAR CURSO");
    });

    // $('#btn-adminEliminarCurso').on('click',function(e){
    //  alert("ELIMINAR CURSO");
    //  $("#modalDeletoCursoAdmin").modal('show')
    // });

    $("#EliminarCursoAdminConfirm").on('click',function (e) {
      console.log('clic en boton elimnar curso confirmacion');
      var curso=$("#idElementSeleccionAccion").val();
      let datos = { idCurso : curso};
      eventoPagina.enviarPeticionServidor('administracion-cursos','Administracion_Cursos/Eliminar-Curso',datos,function(respuesta){
          console.log(respuesta);
          evento.mostrarMensaje('.messageAcciones', true, 'Se ha eliminado el curso.', 5000);
       //   location.reload();
      });
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
  



let listTemario=[]
  $('#btn-agregar-nuevo-temario').on('click',function(e){
    //modalSubirTemarios
    console.log("btn-agregar-nuevo-temario")
  $('#modalValidateTemario').modal('show')
//   tablaTemarios.evento(function () {
//     let numItemsTemario = tablaTemarios.datosTabla();
//     $filas_num=numItemsTemario.length;
//     let datos = tablaTemarios.datosFila(this);
//   });

//   console.log("DATOS TABLA TEMARIOS", numItemsTemario,$filas_num,datos);
//   tablaTemarios.agregarDatosFila([
//     $("#nombreTemario").val(),
//     10,
//    no
// ]);
$long=listTemario.length+1;
$porcentaje=100/$long;
$nombreTemario=$("#nombreTemario").val();
console.log($nombreTemario,$porcentaje,"DATOS tEMARIO1",listTemario)
listTemario.push({'nombre':$nombreTemario,'porcentaje':$porcentaje});

listTemario.forEach(element => {
  element.porcentaje=$porcentaje;
});

//$datos['temario']=listTemario;

$("#arrayTemario").html("es");
console.log($nombreTemario,$porcentaje,"DATOS tEMARIO2",listTemario)
});


$("#file-upload-button").addClass("btn btn-success m-r-5 ");

let listPuesto=[];
$("#btn-nuevo-puestoParticipante").on('click',function(e){
    //modalSubirTemarios
    console.log("btn-nuevo-puestoParticipante")

     $nombrePuesto=$("#puesto").val();
   
     console.log($nombrePuesto,"DATOS tEMARIO1",listPuesto)
     listPuesto.push($nombrePuesto);
     
     
     //$datos['temario']=listPuesto;
     
   
     console.log($nombrePuesto,"DATOS tEMARIO2",listPuesto)

  $('#modalValidateParticipantes').modal('show')
     
});

$("#btn-save-curso").on('click',function(e){
    //modalSubirTemarios
    console.log("btn-save-curso")

    $json={
      'curso':{
        img:"ruta img",
        nombre:$("#nombreCurso").val(),
        url:$("#urlCurso").val(),
        descripcion:$("#textareaDescripcionCurso").val(),
        certificado:$("#certificadoCurso").val(),
        costo:$("#costoCurso").val(),
        },
        'temario':[
          {temario:'string',porcentaje:0},
          {temario:'string',porcentaje:0}
          
        ],
        'participantes':[3,9]
        
    }

    console.log("DATOS_SAVE",$json);
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

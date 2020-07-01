$(function () {

    var evento = new Base();
    var eventoPagina = new Pagina();
    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();
    var file = new Upload();

    file.crearUpload('#inputImgCurso', 'Administracion_Cursos/Nuevo-Curso', ['jpg','jpeg','png'], false, [], '', null, false, 1);


    //Inicializa funciones de la plantilla
    App.init();
    
    let tablaCursos = new TablaBasica('tabla-cursos');
    tablaCursos.iniciarTabla();

    
    
    
    $('#btn-nuevo-curso').on('click',function(e){
        $("#administracion-cursos").css('display', 'none')
        $("#administracion-cursos_nuevoCurso").css('display', 'block')
        console.log('clic en boton');
       
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

   

    $("#EliminarCursoAdminConfirm").on('click',function (e) {
      console.debug('clic en boton elimnar curso confirmacion');
      var curso=$("#idElementSeleccionAccion").val();
      let datos = { idCurso : curso};
      eventoPagina.enviarPeticionServidor('administracion-cursos','Administracion_Cursos/Eliminar-Curso',datos,function(respuesta){
          console.debug(respuesta);
          evento.mostrarMensaje('.messageAcciones', true, 'Se ha eliminado el curso.', 5000);
          location.reload();
      });
    });

   

    let tablaTemarios = null;
    tablaTemarios = new TablaBasica('tabla-cursos-temario');
    
    $("#wizard").bwizard({ validating: function (e, ui) {
      console.debug("VALIDATE");
      console.log(e,ui.index,ui.nextIndex);
      // $("#wizard").bwizard("abort");
      // $("#wizard").bwizard("back");
      //let infoTabla = tablaTemarios.validarNumeroFilas();
     // tablaTemarios.datosTabla()
     if(ui.nextIndex==1){
       console.debug("paso1-2");
     if (evento.validarFormulario('#formDatosNewCurso') ) { //&& infoTabla == true
     }
    }
    if(ui.nextIndex==2){
      console.debug("paso2-3");
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

  //tablaTemarios.limpiarTabla();
  
    let numItemsTemario = tablaTemarios.datosTabla();
    $filas_num=numItemsTemario.length;
    let datos = tablaTemarios.datosFila(this);
  

  console.debug(datos,"DATOS TABLA TEMARIOS", numItemsTemario,$filas_num,datos);
 
  $long=listTemario.length+1;
  $porcentaje=(100/$long).toFixed(2);
  $nombreTemario=$("#nombreTemario").val();
  console.debug($nombreTemario,$porcentaje,"DATOS tEMARIO1",listTemario)
  listTemario.push({'nombre':$nombreTemario,'porcentaje':$porcentaje});
  

  tablaTemarios.limpiartabla();

  listTemario.forEach(element => {
    element.porcentaje=$porcentaje;
    tablaTemarios.agregarDatosFila([
      element.nombre,
      element.porcentaje+'%',
      "<span><i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'  id='btn- AdminEliminarTemario'></i></spand>"
    
    ]);
});




  console.debug($nombreTemario,$porcentaje,"DATOS tEMARIO2",listTemario)
});

tablaTemarios.evento(function () {
  let numItemsTemario = tablaTemarios.datosTabla();
  var elim=tablaTemarios.eliminarFila(this);
  let datosTabla = tablaTemarios.datosTabla();
  
  console.debug(numItemsTemario,"eliminar",elim,"resto",datosTabla,tablaTemarios.datosFila(this));

  listTemario=[]

  if (datosTabla.length !== 0) {
    $long=datosTabla.length;
    $porcentaje=(100/$long).toFixed(2);
    let datos = tablaTemarios.datosFila(this);
    var info = $('#tabla-cursos-temario').DataTable().rows({search: 'applied'}).data();
    // datos.forEach(element => {
    //   listTemario.push({'nombre':element.nombre,'porcentaje':$porcentaje});
    // });
    console.debug(datosTabla,"ENTRE FOR", datosTabla.length, datos,info)

   for (let index = 0; index < datosTabla.length; index++) {
     const element = datosTabla[index];
     console.debug("DATOS",element,element[0])
     
     listTemario.push({'nombre':element[0],'porcentaje':$porcentaje});
     
   }
  }

  tablaTemarios.limpiartabla();

  listTemario.forEach(element => {
  
    tablaTemarios.agregarDatosFila([
      element.nombre,
      element.porcentaje+'%',
      "<span><i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'  id='btn- AdminEliminarTemario'></i></spand>"
    
    ]);
  });

  console.debug("FINAL",listTemario)


});





$("#file-upload-button").addClass("btn btn-success m-r-5 ");


let tablaParticipantes = null;
    tablaParticipantes = new TablaBasica('tabla-cursos-participantes');
    

let listPuesto=[];
$("#btn-nuevo-puestoParticipante").on('click',function(e){
    //modalSubirTemarios
    console.log("btn-nuevo-puestoParticipante")

    //  $nombrePuesto=$("#puesto").val();
   
    //  console.log($nombrePuesto,"DATOS tEMARIO1",listPuesto)
    //  listPuesto.push($nombrePuesto);
     
     
     //$datos['temario']=listPuesto;
     
   

  $('#modalValidateParticipantes').modal('show')


    let numItemsTemario = tablaParticipantes.datosTabla();
    $filas_num=numItemsTemario.length;
    let datos = tablaParticipantes.datosFila(this);

    

    var perfiles=$('#perfiles').val()
    console.debug('per',perfiles,'files',datos,"DATOS TABLA TEMARIOS", numItemsTemario,$filas_num,datos);

    
    $nombrePuesto=$("#puesto").val();
    console.debug($nombrePuesto,"DATOS tEMARIO1",listPuesto)
    listPuesto.push({'nombre':$nombrePuesto});


    tablaParticipantes.limpiartabla();

    listPuesto.forEach(element => {
      tablaParticipantes.agregarDatosFila([
        element.nombre,
        "<span><i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'  id='btn- AdminEliminarParticipant'></i></spand>"
      
      ]);
    });




    console.debug($nombrePuesto,"DATOS tEMARIO2",listPuesto)
     
});

tablaParticipantes.evento(function () {
  let numItemsTemario = tablaParticipantes.datosTabla();
  var elim=tablaParticipantes.eliminarFila(this);
  let datosTabla = tablaParticipantes.datosTabla();
  
  console.debug(numItemsTemario,"eliminar",elim,"resto",datosTabla,tablaParticipantes.datosFila(this));

  listPuesto=[]

  if (datosTabla.length !== 0) {
    
    let datos = tablaParticipantes.datosFila(this);
    var info = $('#tabla-cursos-temario').DataTable().rows({search: 'applied'}).data();
   
    console.debug(datosTabla,"ENTRE FOR", datosTabla.length, datos,info)

   for (let index = 0; index < datosTabla.length; index++) {
     const element = datosTabla[index];
     console.debug("DATOS",element,element[0])
     
     listPuesto.push({'nombre':element[0]});
     
   }
  }

  tablaParticipantes.limpiartabla();

  listPuesto.forEach(element => {
  
    tablaParticipantes.agregarDatosFila([
      element.nombre,
      "<span><i class='fa fa-trash' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'  id='btn- AdminEliminarParticipant'></i></spand>"
    
    ]);
  });

  console.debug("FINAL",listPuesto)


});


$("#btn-save-curso").on('click',function(e){
    //modalSubirTemarios
    console.log("btn-save-curso")

    var nombre=$("#nombreCurso").val();
    var url=$("#urlCurso").val();
    var descripcion=$("#textareaDescripcionCurso").val();

    if(nombre=='' || url=='' || descripcion==''){
      evento.mostrarMensaje('.messageAccionesWizard', false, 'Por favor acompleta los campos marcados con (*), que son obligatorios.', 3000);
      return false;
    }
    
    // let json={
    //   'curso':{
    //     img:$("#file-upload").val(),
    //     nombre:$("#nombreCurso").val(),
    //     url:$("#urlCurso").val(),
    //     descripcion:$("#textareaDescripcionCurso").val(),
    //     certificado:$("#certificadoCurso").val(),
    //     costo:$("#costoCurso").val(),
    //     },
    //     'temario':[
    //       {temario:'string',porcentaje:0},
    //       {temario:'string',porcentaje:0}
          
    //     ],
    //     'participantes':[3,9]
        
    // }

    var json={
      curso:{
        img:$('#inputImgCurso').val(),
        nombre:$("#nombreCurso").val(),
        url:$("#urlCurso").val(),
        descripcion:$("#textareaDescripcionCurso").val(),
        certificado:$("#certificadoCurso").val(),
        costo:$("#costoCurso").val(),
        },
      temario:{
            archivo: false,
            infoTabla:{}
            
        },
      participantes:part
    }


    var temas=[]
    let datosTabla = tablaTemarios.datosTabla();
    for (let index = 0; index < datosTabla.length; index++) {
      const element = datosTabla[index];
      console.debug("DATOS",element,element[0])
      
      temas.push([element[0],'',parseFloat(element[1])]);
      
//array_push(temas,[element[0],'',element[1]])
      
    }
    console.debug("temas",temas)
    json.temario.infoTabla=temas;

    var part=[]
    let datosTabla2 = tablaParticipantes.datosTabla();
    for (let index = 0; index < datosTabla2.length; index++) {
      const element = datosTabla2[index];
      console.debug("DATOS_PART",element,element[0])
      
      part.push([element[0]]);
      
    }
    console.debug("part",part)

    json.participantes=part;


   

    console.debug("DATOS_SAVE",json);
      

  //   if ($('#inputImgCurso').val() !== '') {
  //     file.enviarArchivos('#inputImgCurso', 'Administracion_Cursos/Nuevo-Curso', '', json, function (respuesta) {
  //       // if (respuesta !== 'otraImagen') {
  //       //     window.open(respuesta.ruta, '_blank');
  //       //     location.reload();
  //       // } else {
  //       //     evento.mostrarMensaje('.mensajeSolicitudPermisos', false, 'Hubo un problema con la imagen selecciona otra distinta.', 3000);
  //       // }
  //       if (!respuesta.success) {
  //         return;
  //     }
  //     });
  //   }else{

  //     eventoPagina.enviarPeticionServidor('administracion-cursos','Administracion_Cursos/Nuevo-Curso',json,function(respuesta){
  //       console.log(respuesta);
  //       if (!respuesta.success) {
  //         return;
  //     }
  //     $('#modalresponseSave').modal('show')
      

  //   });
  // }
  file.limpiar('#inputImgCurso');
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

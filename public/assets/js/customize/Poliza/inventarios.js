$(function() {
  var evento = new Base();
  var websocket = new Socket();
  var tabla = new Tabla();
  var select = new Select();

  websocket.socketMensaje();
  evento.horaServidor($("#horaServidor").val());
  evento.cerrarSesion();

  evento.mostrarAyuda("Ayuda_Proyectos");
  //Inicializa funciones de la plantilla
  App.init();

  initForm();

  function initForm() {
    $(".input-daterange").datepicker({
      todayHighlight: !0,
      language: "es-ES"
    });

    $("#submitButton").off("click");
    $("#submitButton").on("click", function() {
      loadFirstViewFilter(getFormFields());
    });
  }

  function getFormFields() {
    return {
      iniDate: $("#iniDate").val(),
      endDate: $("#endDate").val(),
      estatus: $("#statusList").val(),
      technician: $("#technicianList").val(),
      region: $("#regionList").val(),
      branch: $("#branchList").val(),
      areas: $("#areasList").val(),
      devices: $("#deviceList").val()
    };
  }

  function loadFirstViewFilter(values) {
    evento.enviarEvento(
      "/Poliza/Inventarios/loadInventoryView",
      values,
      "#filtersPanel",
      function(respuesta) {
        $("#firstViewContent")
          .empty()
          .append(respuesta.form);
        evento.cambiarDiv(
          "#filtersContent",
          "#firstViewContent",
          initFirstView(respuesta.init)
        );
      }
    );
  }

  function initFirstView(init) {
    if (init == "branchList") {
      initFirstViewBranches();
    }
  }

  function initFirstViewBranches() {
    tabla.generaTablaPersonal("#branchInventoriesTable");
    $("#branchInventoriesTable tbody").on("click", "tr", function() {
      let rowData = $("#branchInventoriesTable")
        .DataTable()
        .row(this)
        .data();
      let servicio = rowData[0];
      let sucursal = rowData[3];
      evento.enviarEvento(
        "/Poliza/Inventarios/loadInventoryDetails",
        { servicio: servicio, sucursal: sucursal },
        "#branchListPanel",
        function(respuesta) {
          $("#secondViewContent")
            .empty()
            .append(respuesta.form);
          evento.cambiarDiv(
            "#firstViewContent",
            "#secondViewContent",
            initInventoryDetails()
          );
        }
      );
    });
  }

  function initInventoryDetails() {
    tabla.generaTablaPersonal(".table-details");
    initBtnDetailsViewInventory();
    initBtnCountViewInventory();
  }

  function initBtnCountViewInventory() {
    $("#btnCountView").off();
    $("#btnCountView").on("click", function() {
      $("#detailsView, #btnCountView").hide();
      $("#countView, #btnDetailsView").show();
    });
  }

  function initBtnDetailsViewInventory() {
    $("#btnDetailsView").off();
    $("#btnDetailsView").on("click", function() {
      $("#countView, #btnDetailsView").hide();
      $("#detailsView, #btnCountView").show();
    });
  }
});

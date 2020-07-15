class Seguimiento55 {
  constructor() {
    this.evento = new Base();
    this.table = new Tabla();
    this.serviceId = null;
  }

  init(serviceId) {
    this.serviceId = serviceId;
    this.panelChangeListening();
  }

  panelChangeListening() {
    let instance = this;
    $('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
      var target = $(e.target).attr("href");
      switch (target) {
        case "#Seguimiento55":
          instance.showSOUpdateForm();
          break;
      }
    });
  }

  showSOUpdateForm() {
    let instance = this;
    let table = this.table;
    $("#Seguimiento55").empty();
    instance.evento.enviarEvento(
      "/Poliza/Seguimiento/ShowSOUpdateForm",
      { serviceId: this.serviceId },
      "#Seguimiento55",
      function (respuesta) {
        var printHtml = "";
        if (respuesta.code == 200) {
          printHtml = respuesta.html;
        } else {
          printHtml =
            `
            <div class="note note-warning">
                <h4>Warning!</h4>
                <p>` +
            respuesta.message +
            `   </p>
			</div>`;
        }
        $("#Seguimiento55").append(printHtml);
        table.generaTablaPersonal(
          "#updateSOInfoTable",
          null,
          null,
          true,
          false,
          [],
          null,
          null,
          false,
          true
        );
        instance.listeningStateChangeCheckbox();
        instance.listeningButtonSave();
      }
    );
  }

  triggerAllChanges() {
    var event = new Event("change");
    let impedimentsCheck = document.getElementsByClassName("impedimentCheck");
    var i = 0;
    for (i = 0; i < impedimentsCheck.length; i++) {
      impedimentsCheck[i].dispatchEvent(event);
    }

    let updateCheck = document.getElementsByClassName("updateCheck");
    for (i = 0; i < updateCheck.length; i++) {
      updateCheck[i].dispatchEvent(event);
    }
  }

  listeningButtonSave() {
    let instance = this;
    $("#saveSOUpdateInfo").off("click");
    $("#saveSOUpdateInfo").on("click", function () {
      var dataToSave = { serviceId: instance.serviceId, data: [] };
      let updateChecks = document.getElementsByClassName("updateCheck");
      var i = 0;
      for (i = 0; i < updateChecks.length; i++) {
        let inventoryId = updateChecks[i].getAttribute("data-id");
        var data = {};
        data.inventoryId = inventoryId;
        data.registryId = updateChecks[i].getAttribute("data-id-registry");
        if (updateChecks[i].checked) {
          data.updated = 1;
        } else {
          data.updated = 0;
        }

        data.impediments = instance.getCheckedImpediments(
          "impedimentCheck_" + inventoryId
        );

        dataToSave.data.push(data);
      }

      instance.evento.enviarEvento(
        "/Poliza/Seguimiento/SaveSOUpdateInfo",
        dataToSave,
        "#Seguimiento55",
        function (respuesta) {
          if (respuesta.code == 200) {
            instance.showSOUpdateForm();
          }
        }
      );
    });
  }

  getCheckedImpediments(className) {
    let impedimentsCheck = document.getElementsByClassName(className);
    var i = 0;
    var response = [];
    for (i = 0; i < impedimentsCheck.length; i++) {
      if (impedimentsCheck[i].checked) {
        response.push(impedimentsCheck[i].getAttribute("data-impediment-id"));
      }
    }

    return response;
  }

  listeningStateChangeCheckbox() {
    let instance = this;
    $(".updateCheck").on("change", function () {
      let check = this;
      let inventoryId = check.getAttribute("data-id");
      if (check.checked) {
        instance.disableEnabledChecks("impedimentCheck_" + inventoryId, true);
      } else {
        instance.disableEnabledChecks("impedimentCheck_" + inventoryId, false);
      }
    });

    $(".impedimentCheck").on("change", function () {
      let check = this;
      let inventoryId = check.getAttribute("data-id");
      if (check.checked) {
        instance.disableEnabledChecks("updateCheck_" + inventoryId, true);
      } else {
        let isCheckSomeImpediment = instance.checkIfSomeImpedimentIsChecked(
          "impedimentCheck_" + inventoryId
        );
        if (!isCheckSomeImpediment) {
          instance.disableEnabledChecks("updateCheck_" + inventoryId, false);
        }
      }
    });
    instance.triggerAllChanges();
  }

  disableEnabledChecks(className, disabled) {
    let impedimentsCheck = document.getElementsByClassName(className);
    var i = 0;
    for (i = 0; i < impedimentsCheck.length; i++) {
      impedimentsCheck[i].disabled = disabled;
    }
  }

  checkIfSomeImpedimentIsChecked(className) {
    let impedimentsCheck = document.getElementsByClassName(className);
    var i = 0;
    var response = false;
    for (i = 0; i < impedimentsCheck.length; i++) {
      if (impedimentsCheck[i].checked) {
        response = true;
      }
    }

    return response;
  }

  /*
    1. Se carga o no se carga imagen
    2. Impedimentos
    2.1.  Disco duro menor a 64Gb
    2.2.  No hay imagen para el equipo
    2.3.  eLearning sin enlace
    3. Aplica para todas las computadoras del complejo, sin tocar plasmas.
    */
}

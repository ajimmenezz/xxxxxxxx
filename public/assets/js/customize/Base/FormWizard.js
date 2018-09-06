//Constructor 
function FormWizard() {
    FormWizard.init();
}
//Herencia del objeto Base
FormWizard.prototype = new Base();
FormWizard.prototype.constructor = FormWizard;
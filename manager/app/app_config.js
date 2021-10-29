if(!alertify.myAlert){
  //define a new errorAlert base on alert
  alertify.dialog('myAlert',function factory(){
    return{
            build:function(){
                var errorHeader = '操作提示';
                this.setHeader(errorHeader);
            }
        };
    },true,'alert');
}

window.alert = alertify.myAlert;


alertify.set('notifier','delay', 3);
window.success = alertify.success;
window.error = alertify.error;
window.warning = alertify.warning;
window.message = alertify.message;
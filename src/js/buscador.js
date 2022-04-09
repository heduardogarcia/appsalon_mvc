document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
});

function iniciarApp(){
    buscarporFecha();
}
function buscarporFecha(){
    //console.log('desde buscar por fecha');
    const fechaInput = document.querySelector('#fecha');
    fechaInput.addEventListener('input', function(e){
        //console.log('nueva fecha');
        const fechaSeleccionada=e.target.value;
        //console.log(fechaSeleccionada);
        window.location=`?fecha=${fechaSeleccionada}`;
        
        
    });
    
}
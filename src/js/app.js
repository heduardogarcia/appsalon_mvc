let paso=1;
const pasoInicial=1;
const pasoFinal=3;
const cita={
    id:'',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}
document.addEventListener('DOMContentLoaded',function(){
    iniciarApp();
});

function iniciarApp(){
    mostrarSeccion();
    tabs();
    botonesPaginador();
    paginaSiguiente();
    paginaAnterior();
    consultarAPI();
    idCliente();
    nombreCliente();
    seleccionarFecha();
    seleccionarHora();
    mostrarResumen();
}

function mostrarSeccion(){
// console.log('Mostrar seccion');

    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior){
        seccionAnterior.classList.remove('mostrar');
    }
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');

    const tabAnterior= document.querySelector('.actual');
    if(tabAnterior){
        tabAnterior.classList.remove('actual');
    }

    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
    
}
function tabs(){
    const botones= document.querySelectorAll('.tabs button');
    botones.forEach( boton =>{
        boton.addEventListener('click', function(e){
            paso= parseInt(e.target.dataset.paso);

            mostrarSeccion();
            botonesPaginador();
         
            // if(paso===3){
            //     mostrarResumen();
            // }
            
            
        });
    })
    
}

function botonesPaginador(){
    const paginaAnterior= document.querySelector('#anterior');
    const paginaSiguiente= document.querySelector('#siguiente');
    if(paso===1){
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar')
    }
    else if(paso===3){
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');
        mostrarResumen();
    }
    else{
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');

    }
    mostrarSeccion();
}

function paginaAnterior(){
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click',function(){
        if(paso <= pasoInicial) return;
        paso--;
        botonesPaginador();
        
    })
}

function paginaSiguiente(){
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click',function(){
        if(paso>=pasoFinal) return;
        paso++;
        botonesPaginador();
        

    })
}

async function consultarAPI(){
    try {
        const url='http://localhost:3000/api/servicios';
        const resultado = await fetch(url);
        const servicios = await resultado.json();
       mostrarServicios(servicios);
        
    } catch (error) {
        console.log(error);
        
    }
}

function mostrarServicios(servicios){

    servicios.forEach( servicio =>{
        const{id, nombre,precio} =servicio;
        //console.log(id);
        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent=nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent=`$${precio}`;
        //console.log(nombreServicio);

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio=id;
        servicioDiv.onclick = function(){
            seleccionarServicio(servicio);
        }
        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(servicioDiv);
        //console.log(servicioDiv);
        
        
    });

    function seleccionarServicio(servicio){
        const {id} = servicio;// aqui esta la informacion del servicio seleccionado
        const {servicios} = cita; // aqui se almnacena todos los servicios contratados
        // comprobar si el servicio ya fue agregado
        const divServicio= document.querySelector(`[data-id-servicio="${id}"]`);
        if(servicios.some(agregado=>agregado.id===id)){
           // console.log('Ya esta agregado');
        //    Eliminarlo
            cita.servicios=servicios.filter(agregado=> agregado.id!==id);
            divServicio.classList.remove('seleccionado');
        }
        else{
            // agregarlo
            
            cita.servicios=[...servicios,servicio];     
            divServicio.classList.add('seleccionado');
        }
        console.log(cita);
           
    }
}
function idCliente(){
    cita.id = document.querySelector('#id').value;
   // console.log(cita.id);
    
}
function nombreCliente(){
    cita.nombre = document.querySelector('#nombre').value;
    //console.log(nombre);
    
}
function seleccionarFecha(){
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function(e) {
        //console.log(inputFecha.value);
       // console.log(e.target.value);
        const dia= new Date(e.target.value).getUTCDay();

        if([6,0].includes(dia)){
            e.target.value='';
            //console.log('Sabados y domingos no abrimos');
            mostrarAlerta('Fines de semana no permitidos','error','.formulario');

        }
        else{
            //console.log('correcto');
            cita.fecha=e.target.value;
           
        }
        console.log(dia);
        
        
    });
}
function seleccionarHora(){
    const inputHora=document.querySelector('#hora');
    inputHora.addEventListener('input', function(e){
        //console.log(e.target.value);
        const horaCita= e.target.value;
        const hora= horaCita.split(":")[0];
       //console.log(hora);
       if(hora<10 || hora >18){
        //console.log('Horas no validas');
        e.target.value='';
        mostrarAlerta('Hora no valida','error','.formulario');
        
       }
       else{
        //console.log('Hora valida');
        cita.hora=e.target.value;
        console.log(cita);
        
       }
        
        
    })
}
function mostrarAlerta(mensaje, tipo,elemento, desaparece=true){

    const alertaPrevia= document.querySelector('.alerta');
    if(alertaPrevia){
        alertaPrevia.remove();
    }
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);
    //console.log(alerta);
    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);
    if(desaparece){
        setTimeout(()=>{
            alerta.remove();
        },3000);
    }
    
}
function mostrarResumen(){
    const resumen= document.querySelector('.contenido-resumen');

    while(resumen.firstChild){
        resumen.removeChild(resumen.firstChild);
    }
    //console.log(cita);
    //console.log(Object.values(cita));
    if(Object.values(cita).includes('') || cita.servicios.length===0){
        mostrarAlerta('Faltan datos de servicio o fecha u hora','error','.contenido-resumen',false)
        return;
        
    }
    const {nombre,fecha, hora,servicios} =cita;

    const headingServicios = document.createElement('H3');
    headingServicios.textContent='Resumen de Servicios';
    resumen.appendChild(headingServicios);

    servicios.forEach(servicio=>{
        const {id,precio,nombre} = servicio;
        const contenedorServicio= document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent= nombre;

        const precioServicio= document.createElement('P');
        precioServicio.innerHTML=`<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
        

    })
    
    const headingCita = document.createElement('H3');
    headingCita.textContent='Resumen de Cita';
    resumen.appendChild(headingCita);

    const nombreCliente= document.createElement('P');
    nombreCliente.innerHTML=`<span>Nombre:</span> ${nombre}`;

    const fechaObj= new Date(fecha);
    const mes= fechaObj.getMonth();
    const dia= fechaObj.getDate();
    const year= fechaObj.getFullYear();
    const fechaUTC = new Date(Date.UTC(year, mes,dia));
    const opciones ={weekday: 'long', year: 'numeric',month:'long', day:'numeric'}
    const fechaFormateada= fechaUTC.toLocaleDateString('es-MX',opciones);

    const fechaCita= document.createElement('P');
    fechaCita.innerHTML=`<span>Fecha:</span> ${fechaFormateada}`;

    const horaCita= document.createElement('P');
    horaCita.innerHTML=`<span>Hora:</span> ${hora} horas`;

    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent='Reservar Cita'
    botonReservar.onclick = reservarCita;

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.appendChild(botonReservar);
}

async function reservarCita(){
    // console.log('Reservando Cita');
    const {nombre,fecha, hora,servicios,id} = cita;
    const idServicios= servicios.map(servicio => servicio.id);
    // console.log(idServicios);
    // return;
    

    const datos= new FormData();
    
    datos.append('fecha',fecha);
    datos.append('hora',hora);
    datos.append('usuarioid',id);
    datos.append('servicios',idServicios);
    // console.log([...datos]);
    // return;

    try {
        const url='http://localhost:3000/api/citas'
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });
        const resultado= await respuesta.json();
       console.log(resultado.resultado);
       if(resultado.resultado){
        Swal.fire({
            icon: 'success',
            title: 'Cita Creada',
            text: 'Tu cita fue creada correctamente',
            button:'OK'
            // footer: '<a href="">Why do I have this issue?</a>'
          }).then(()=>{
              setTimeout(()=>{
                  window.location.reload();
              },3000);
          })
       }
        
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error...',
            text: 'Hubo un error al guardar la cita!'
            
          })
    }
   // console.log(resultado.resultado);
    
  
  
    //console.log(...datos);
}   
    


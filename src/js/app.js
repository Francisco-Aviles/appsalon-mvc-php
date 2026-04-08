let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
});

function iniciarApp(){
    mostrarSeccion(); //Muestra y oculta las secciones
    tabs(); //Cambia la seccion cuando se preione el boton de tab
    botonesPaginador();
    paginaSiguiente();
    paginaAnterior();
    consultarAPI(); //Consulta la API en el backend de PHP

    idCliente();
    nombreCliente();// Añade el nombre del cliente al objeto de cita
    seleccionarFecha();//Añade la fecha de la cita en el objeto
    seleccionarHora(); //Añade la hora de la cita al objeto
    mostrarResumen();
}

function tabs(){
    const botones = document.querySelectorAll('.tabs button');
    botones.forEach(boton => {
        boton.addEventListener('click', tab);
    });
    
}

function tab(e){
    //Actualiza la variable con el paso en el que se encuentre
    paso = parseInt(e.target.dataset.paso);
    //Muestra y oculta la seccion y los botones
    mostrarSeccion();
    botonesPaginador();
}

function mostrarSeccion(){
    //Oculta la seccion que tenga la clase de mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior){
        seccionAnterior.classList.remove('mostrar');
    }

    //Agrega a la seccion seleccionada la clase de mostrar
    let pasoSeccion = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSeccion);
    seccion.classList.add('mostrar');

    //Quitar la clase de actual al tab anterior
    const tabAnterior = document.querySelector('.actual');
    if(tabAnterior){
        tabAnterior.classList.remove('actual');
    }

    //Resaltar el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
}

function botonesPaginador() {
    const btnAnterior = document.querySelector('#anterior');
    const btnSiguiente = document.querySelector('#siguiente');

    //Oculta o muestra los botones dependiendo de la seccion
    if(paso === 1){
        btnAnterior.classList.add('ocultar');
        btnSiguiente.classList.remove('ocultar');
    }else if(paso === 3){
        btnSiguiente.classList.add('ocultar');
        btnAnterior.classList.remove('ocultar');
        mostrarResumen();
    }else{
        btnSiguiente.classList.remove('ocultar');
        btnAnterior.classList.remove('ocultar');
    }

    //Muestra la seccion dependiendo del paso en donde se encuentre
    mostrarSeccion();
}

function paginaSiguiente() {
    const paginaAnterior = document.querySelector('#anterior');

    paginaAnterior.addEventListener('click', function() {
        if(paso <= pasoInicial) return;
        paso--;
        botonesPaginador();
    })
}

function paginaAnterior() {
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener("click", function() {
        if(paso >= pasoFinal) return;
        paso++;
        botonesPaginador();
    });
}
async function consultarAPI(){
    try {
        const url = `${location.origin}/api/servicios`;
        
        const resultado = await fetch(url);
        console.log(resultado);
        const servicios = await resultado.json();
        mostrarServicios(servicios);
        
    } catch (error) {
        console.error(error);
    }
}

function mostrarServicios(servicios) {
    servicios.forEach( servicio => {
        const {id, nombre, precio} = servicio;

        const nombreServicio = document.createElement('p');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('p');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$ ${precio}`;

        const servicioDIV = document.createElement('div');
        servicioDIV.classList.add('servicio');
        servicioDIV.dataset.idServicio = id;
        //Agregando la funcion cuando se de click
        servicioDIV.onclick =function(){  
            seleccionarServicio(servicio);
        } 

        servicioDIV.appendChild(nombreServicio);
        servicioDIV.appendChild(precioServicio);

        const contenedorServicios = document.querySelector('#servicios');
        contenedorServicios.appendChild(servicioDIV);
    });
}

function seleccionarServicio(servicio){
    const {id} = servicio;
    const {servicios} = cita;

    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    //Verificar si el servicio ya esta dentro del array de cita
    if( servicios.some( agregado => agregado.id === id)){
        //Filtra para eliminar el servicio que ya esta en la cita cuando se de otra vez clic
        cita.servicios = servicios.filter(agregado => agregado.id !== id);
        //Se elimina la clase
        divServicio.classList.remove('seleccionado');
    }else{
        //Se agrega el servicio a la cita
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
    }
}

function idCliente(){
    const idCliente = document.querySelector('#id').value;
    cita.id = idCliente;
}

function nombreCliente(){
    const nombreCliente = document.querySelector('#nombre').value;
    cita.nombre = nombreCliente;
}

function seleccionarFecha(){
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function(e) {
        const dia = new Date(e.target.value).getUTCDay();
        if( [6, 0].includes(dia)){
            e.target.value = '';
            mostrarAlerta('Fines de semana no permitidos', 'error', '.formulario');
        }else{
            cita.fecha = e.target.value;
        }
        
    });
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true){
    //Impide que se inserten varias alertas
    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia){
        //Elimina la alerta
        alertaPrevia.remove();
    }

    //Sino hay alerta se crea
    const alerta = document.createElement('div');
    const p = document.createElement('p');
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);
    p.textContent = mensaje;
    alerta.appendChild(p);

    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    if(desaparece){
        //Se elimina despues de 3 segundos
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }
}

function seleccionarHora(){
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function(e) {
        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];
        if(hora < 10 || hora >20){
            e.target.value = '';
            mostrarAlerta('Hora no válida', 'error', '.formulario');
        }else{
            cita.hora = e.target.value;
        }
    });
}

function mostrarResumen(){
    const resumen = document.querySelector('.seccion-resumen');

    while(resumen.firstChild){
        resumen.removeChild(resumen.firstChild);
    }

    if(Object.values(cita).includes('') || cita.servicios.length === 0){
        mostrarAlerta('Faltan datos de Servicios, Fecha u Hora', 'error', '#paso-3', false);
        return;
    }

    //Formatear el div de cita
    const {nombre, fecha, hora, servicios} = cita;

    //Heading para servicios en resumen
    const headingServicios = document.createElement('h3');
    headingServicios.textContent = 'Resumen de Servicios';
    resumen.appendChild(headingServicios);

    // Mostrar los servicios seleccionados
    servicios.forEach( servicio => {
        //Extraemos el nombre del objeto
        const {id, nombre, precio} = servicio; 
        const contenedorServicio = document.createElement('div');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('p');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('p');
        precioServicio.innerHTML = `<span>Hora: </span>$ ${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    });

    //Heading para cita en resumen
    const headingCita = document.createElement('h3');
    headingCita.textContent = 'Resumen de Cita';

    const nombreCliente = document.createElement('p');
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

    //Formatear la fecha en español
    const fechaobj = new Date(fecha);
    const dia = fechaobj.getDate() + 2; //Comienza en 0, pero como lo usamo dos veces se desfaza 2 dias por eso se le suma 2 para obtener la fecha actual
    const mes = fechaobj.getMonth(); //Comineza en 0
    const year = fechaobj.getFullYear();

    const fechaUTC = new Date( Date.UTC(year, mes, dia));

    const opciones = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'}
    const fechaFormateada = fechaUTC.toLocaleDateString('es-MX', opciones);
    

    const fechaCita = document.createElement('p');
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

    const horaCita = document.createElement('p');
    horaCita.innerHTML = `<span>Hora:</span> ${hora} Horas`;
    resumen.appendChild(headingCita);

    //Boton para reservar la cita
    const botonReservar = document.createElement('button');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar Cita';
    botonReservar.onclick = reservarCita;

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.appendChild(botonReservar);

}

async function reservarCita(){
    const {nombre, fecha, hora, servicios, id} = cita;

    const idServicios = servicios.map( servicio => servicio.id);

    // console.log(idServicios);
    
    //Esto actua como si fuera un submit de un formulario
    const datos = new FormData();
    datos.append('usuarioId', id);
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('servicios', idServicios);

    //para ver si la informacion es correcta podemos hacer uso de esta sintaxis
    // console.log([...datos]);
    try {
        //Peticion hacia la api
        const url = `${location.origin}/api/cita`;
    
        const respuesta = await fetch(url,{
            method: 'POST',
            body: datos
        });
    
        const resultado = await respuesta.json();
    
        console.log(resultado);
        if(resultado.resultado){
            Swal.fire({
                icon: "success",
                title: "Cita Creada",
                text: "Tu cita fue creada correctamente",
                button: "OK",
                confirmButtonColor: '#0da6f3'
            }).then(() => {
                setTimeout(() => {
                    location.reload();
                }, 3000);
            });
        }
    } catch (error) {
        Swal.fire({
            icon: "warning",
            title: "Aviso",
            text: "No se pudo guardar tu cita, intentalo nuevamente",
            button: "OK"
        }).then(() => {
            setTimeout(() => {
                location.reload();
            }, 3000);
        });
    }
}
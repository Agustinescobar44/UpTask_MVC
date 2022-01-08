//IIFE sirve para proteger variables y funciones en caso de tener multiples archivos con nombres similares
(function(){
    obetenerTareas();
    let tareas = [];
    let filtradas = []


    // boton para mostrar el Modal de agregar tarea
    const nuevaTareaBoton = document.querySelector('#agregar-tarea')
    nuevaTareaBoton.addEventListener('click',function(){
        mostrarFormulario();
    })

    const filtros = document.querySelectorAll('#filtros input[type="radio"]')

    filtros.forEach( radio =>{
        radio.addEventListener('input' , filtrarTareas)
    } )

    function filtrarTareas(e) {
        const filtro = e.target.value;

        if(filtro !== ""){
            filtradas = tareas.filter(tarea => tarea.estado === filtro)
        } else {
            filtradas = []
        }

        mostrarTareas()
    }

    async function obetenerTareas() {
        try{
            const url = "http://localhost:3000/api/tareas?url="+obtenerProyecto() 
            const respuesta = await fetch(url)
            const resultado = await respuesta.json();

            tareas = resultado.tareas;

            mostrarTareas();
            
        } catch(e){
            console.log(e);
        }

    }

    function mostrarTareas() {
        limpiarTareas();

        totalPendientes();
        totalCompletas();

        const arregloTareas = filtradas.length ? filtradas : tareas;

        const contenedorTareas = document.querySelector('.listado-tareas');
        const estados = {
            0:'Pendiente',
            1:'completa'
        };

        if(arregloTareas.length === 0){
            const textoNoTareas = document.createElement('li');
            textoNoTareas.textContent = "no hay tareas";
            textoNoTareas.classList.add('no-tareas');
            contenedorTareas.appendChild(textoNoTareas);

            return;
        }
        
        arregloTareas.forEach(tarea => {
            const contenedorTarea = document.createElement('li');
            contenedorTarea.dataset.tareaId = tarea.id;
            contenedorTarea.classList.add('tarea')

            const nombreTarea = document.createElement('P')
            nombreTarea.textContent = tarea.nombre;
            nombreTarea.ondblclick = function(){
                mostrarFormulario(true, {...tarea});
            }
            

            //botones
            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            const btnEstado = document.createElement('button');
            btnEstado.classList.add('estado-tarea');
            btnEstado.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstado.dataset.estadoTarea = tarea.estado;
            btnEstado.textContent = estados[tarea.estado];
            btnEstado.ondblclick = function(){
                cambiarEstadoTarea({...tarea});
            }

            const btnEliminar = document.createElement('button');
            btnEliminar.classList.add('eliminar-tarea');
            btnEliminar.dataset.idTarea = tarea.id;
            btnEliminar.textContent = "Eliminar Tarea";
            btnEliminar.ondblclick = function() {
                confirmarEliminarTarea({...tarea})
            }

            opcionesDiv.appendChild(btnEstado);
            opcionesDiv.appendChild(btnEliminar);

            //agregar elementos a la tarea
            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            contenedorTareas.appendChild(contenedorTarea);
        });
    }

    function totalPendientes() {
        const totalPendientes = tareas.filter(tarea => tarea.estado === "0");
        const pendientesRadio = document.querySelector('#pendientes');

        if (totalPendientes.length === 0){
            pendientesRadio.disabled = true;
        } else {
            pendientesRadio.disabled = false;
        }
    }
    function totalCompletas() {
        const totalCompletadas = tareas.filter(tarea => tarea.estado === "1");
        const completasRadio = document.querySelector('#completadas');

        if (totalCompletadas.length === 0){
            completasRadio.disabled = true;
        } else {
            completasRadio.disabled = false;
        }
    }

    function mostrarFormulario(editar = false, tarea) {

        const modal = document.createElement('DIV');
        modal.classList.add('modal')
        modal.innerHTML=` 
        <form class="formulario nueva-tarea">
            <legend>${editar? 'Editando una tarea' : 'Añadiendo una nueva tarea'}</legend>
            <div class="campo">
                <label>Nombre de la Tarea</label>
                <input
                    type="text"
                    name="tarea"
                    placeholder="${tarea ? 'Nuevo nombre' : 'Añade un nombre a la tarea'}"
                    id="tarea"
                    value="${tarea? tarea.nombre : ''}"
                />
            </div>
            <div class="opciones">
                <input
                    type="submit"
                    class="submit-nueva-tarea"
                    value="${editar? 'Actualizar tarea' : 'Añadir tarea'}"
                />
                <button type="button" class="cerrar-modal">Cancelar</button>
            </div>
        </form>`;

        setTimeout(() => {
            const formulario = document.querySelector('.formulario')
            formulario.classList.add('animar')
        }, 0);


        //delegation
        modal.addEventListener('click',e=>{
            e.preventDefault()
            
            if(e.target.classList.contains('cerrar-modal')){
                const formulario = document.querySelector('.formulario')
                formulario.classList.add('cerrar')
                setTimeout(() => {
                    modal.remove() 
                }, 600);
            } 
            else if(e.target.classList.contains('submit-nueva-tarea')){
                const nombreTarea = document.querySelector('#tarea').value.trim()
                if(nombreTarea === ''){
                    //mostrar alerta de error
                    mostrarAlerta(
                        "El nombre de la tarea es obligatorio", 
                        'error',            
                        document.querySelector('.formulario legend')
                    );
                    return;
                }

                if(editar){
                    tarea.nombre = nombreTarea;
                    actualizarTarea(tarea);
                } else {
                    agregarTarea(nombreTarea);
                }
            }
        })
        document.querySelector('.dashboard').appendChild(modal)
    }


    function mostrarAlerta(mensaje, tipo, referencia) {
        //eliminar las alertas previas
        const alertaPrevia = document.querySelector('.alerta')
        if(alertaPrevia) {
            alertaPrevia.remove()
        }

        //crear alerta
        const alerta = document.createElement('DIV')
        alerta.classList.add('alerta', tipo)
        alerta.textContent = mensaje;

        //agregar despues del legend
        referencia.parentElement.insertBefore(alerta,referencia.nextElementSibling)

        //eliminar alertas despues de 5 segs
        setTimeout(() => {
            alerta.remove();
        },3000);
    }

    async function agregarTarea(tarea) {
        // Construir la peticion
        const datos = new FormData();
        datos.append('nombre', tarea)
        datos.append('proyectoId', obtenerProyecto());

        try{
            const url = "http://localhost:3000/api/tarea" 
            const respuesta = await fetch(url,{
                method: 'POST',
                body: datos,
            })
            const resultado = await respuesta.json();

            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.formulario legend'));

            if(resultado.tipo === 'exito'){
                const modal= document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                }, 3000);

                // Agregar el objeto tarea al global de tareas
                const tareaObj = {
                    id : String(resultado.id),
                    nombre: tarea,
                    estado: "0",
                    proyectoId: resultado.proyectoId
                }
                
                tareas = [...tareas, tareaObj];
                
                mostrarTareas();

            }
            
        } catch(e){
            console.log(e);
        }
    }

    function cambiarEstadoTarea(tarea) {
        const nuevoEstado = tarea.estado === "1" ? "0" : "1"
        tarea.estado = nuevoEstado;
        actualizarTarea(tarea);
    }

    async function actualizarTarea(tarea) {

        const {estado, id, nombre} = tarea

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());

        // for(let valor of datos.values()) console.log(valor); la unica forma de cheuqear la peticion

        try {
            const url = 'http://localhost:3000/api/tarea/actualizar';

            const respuesta = await fetch(url,{
                method: 'POST',
                body: datos
            })
            const resultado = await respuesta.json();

            if(resultado.respuesta.tipo === 'exito'){
                Swal.fire(
                    resultado.respuesta.mensaje,
                    resultado.respuesta.mensaje,
                    'success'
                );

                const modal = document.querySelector('.modal');
                if(modal) modal.remove();

                //.map crea un nuevo arreglo e iteramos sobre cada elemento para no cambiar el arreglo original
                tareas = tareas.map(tareaMemoria =>{
                    if(tareaMemoria.id === id){
                        tareaMemoria.estado = estado
                        tareaMemoria.nombre = nombre
                    } 
                    return tareaMemoria;
                })

                mostrarTareas();
            }


        } catch (error) {
            console.log(error);
        }
    }

    function confirmarEliminarTarea(tarea) {
        Swal.fire({
            title: '¿Eliminar tarea?',
            showCancelButton: true,
            confirmButtonText: 'Si',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                eliminarTarea(tarea);
            }
          })
    }

    async function eliminarTarea(tarea){
        const {estado, id, nombre} = tarea

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());

        try {

            const url = '/api/tarea/eliminar'
            const respuesta = await fetch(url,{
                method: 'POST',
                body : datos
            })
            const resultado  = await respuesta.json();

            if(resultado.respuesta){
                // mostrarAlerta(
                //     'Tarea Eliminada correctamente', 
                //     resultado.tipo,
                //     document.querySelector('.contenedor-nueva-tarea')
                // )

                Swal.fire('Eliminado!' , resultado.mensaje , 'success')

                // filter tambien crea un arreglo nuevo y silve para sacar elementos dependiendo de un parametro
                //filtro todas las tareas que no tengan el mismo id al de la tarea a eliminar
                tareas = tareas.filter( tareaMemoria => tareaMemoria.id !== tarea.id)
                mostrarTareas();
            }

        } catch (error) {
            console.log(error);
        }
    }

    function obtenerProyecto() {
        const proyectoParams = new URLSearchParams(window.location.search)
        const proyecto = Object.fromEntries(proyectoParams.entries())
        return proyecto.url
    }

    function limpiarTareas() {
        const listadoTareas = document.querySelector('#listado-tareas');
        
        while(listadoTareas.firstChild) listadoTareas.removeChild(listadoTareas.firstChild);
    }

    
})();
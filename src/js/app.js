const mobileMenuBtn = document.querySelector('#mobile-menu');
const cerrarMenuBtn = document.querySelector('#cerrar-menu');
const sidebar = document.querySelector('.sidebar')

if(mobileMenuBtn){
    mobileMenuBtn.addEventListener('click', function () {
        sidebar.classList.add('mostrar')
    })
}
if(cerrarMenuBtn){
    cerrarMenuBtn.addEventListener('click', function () {
        sidebar.classList.add('ocultar')

        setTimeout(() => {
            sidebar.classList.remove('mostrar')
            sidebar.classList.remove('ocultar')
        }, 500);

    })
}

//Elimina la clase de mostrar en tamaño de tablet o mayor
window.addEventListener('resize', ()=>{
    const ancho = document.body.clientWidth;
    if(ancho >= 768){
        sidebar.classList.remove('mostrar')
    }
})
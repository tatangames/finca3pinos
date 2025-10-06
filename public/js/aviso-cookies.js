document.addEventListener("DOMContentLoaded", () => {

    console.log('Mostrar aviso de cookies');

    const botonAceptarCookies = document.getElementById('btn-aceptar-cookies');
    const avisoCookies = document.getElementById('aviso-cookies');
    const fondoAvisoCookies = document.getElementById('fondo-aviso-cookies');

    // Asegúrate de que dataLayer está definido y es un array
    window.dataLayer = window.dataLayer || [];

    // Comprueba si la aceptación de cookies ya ha sido registrada
    if (!localStorage.getItem('cookies-aceptadas')) {
        avisoCookies.classList.add('activo');
        fondoAvisoCookies.classList.add('activo');
        console.log('Mostrar aviso de cookies');
    } else {
        dataLayer.push({ 'event': 'cookies-aceptadas' });
        console.log('Cookies aceptadas previamente');
    }

    // Añadir un event listener al botón de aceptación de cookies
    botonAceptarCookies.addEventListener('click', () => {
        console.log('Aceptación de cookies');

        avisoCookies.classList.remove('activo');
        fondoAvisoCookies.classList.remove('activo');

        // Guardar la aceptación de cookies en localStorage
        localStorage.setItem('cookies-aceptadas', 'true');

        // Registrar el evento en dataLayer
        dataLayer.push({ 'event': 'cookies-aceptadas' });
    });
});

<?php

use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Route;

// ======================== BACKEND =============================
use App\Http\Controllers\Backend\Sistema\AdminAuthController;
use App\Http\Controllers\Backend\Roles\RolesController;
use App\Http\Controllers\Backend\Roles\PermisoController;
use App\Http\Controllers\Backend\Sistema\PerfilController;
use App\Http\Controllers\Controles\ControlRolController;
use App\Http\Controllers\Backend\Sistema\CategoriasController;
use App\Http\Controllers\Backend\Sistema\PaisesController;


// ======================== FRONTEND =============================
use App\Http\Controllers\Frontend\Sistema\UsuarioAuthController;
use App\Http\Controllers\Frontend\Sistema\FrontendController;
use App\Http\Controllers\Frontend\Sistema\DashboardController;
use App\Http\Controllers\Frontend\Sistema\PasswordResetController;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


use Illuminate\Support\Str;


// === RUTAS ADMIN SIN AUTH ===

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/',        [AdminAuthController::class, 'showLoginFormAdmin'])->name('login');
    Route::post('/logout', [AdminAuthController::class, 'logoutAdmin'])->name('logout');

    // formulario para ingresar el correo
    Route::get('/contrasena-reset', [AdminAuthController::class, 'showPasswordReset'])
        ->name('password.reset');

    // formulario con token desde el correo
    Route::get('/password/reset/{token}', [AdminAuthController::class, 'showResetForm'])
        ->name('password.reset.form');

    // enlace vencido o inválido
    Route::get('/password/enlace-invalido', [AdminAuthController::class, 'linkInvalid'])
        ->name('password.invalid');
});




// === RUTAS ADMIN CON AUTH ===

Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {

    // --- SIN PERMISOS VISTA 403 ---
    Route::get('/sin-permisos', [ControlRolController::class,'indexSinPermiso'])->name('no.permisos.index');

    // --- ROLES ---

    Route::get('/roles/index', [RolesController::class,'index'])->name('roles.index');
    Route::get('/roles/tabla', [RolesController::class,'tablaRoles']);


    Route::get('/roles/lista/permisos/{id}', [RolesController::class,'vistaPermisos']);
    Route::get('/roles/permisos/tabla/{id}', [RolesController::class,'tablaRolesPermisos']);
    Route::post('/roles/permiso/borrar', [RolesController::class, 'borrarPermiso']);
    Route::post('/roles/permiso/agregar', [RolesController::class, 'agregarPermiso']);
    Route::get('/roles/permisos/lista', [RolesController::class,'listaTodosPermisos']);
    Route::get('/roles/permisos-todos/tabla', [RolesController::class,'tablaTodosPermisos']);
    Route::post('/roles/borrar-global', [RolesController::class, 'borrarRolGlobal']);

    // --- PERMISOS ---
    Route::get('/permisos/index', [PermisoController::class,'index'])->name('permisos.index');
    Route::get('/permisos/tabla', [PermisoController::class,'tablaUsuarios']);
    Route::post('/permisos/nuevo-usuario', [PermisoController::class, 'nuevoUsuario']);
    Route::post('/permisos/info-usuario', [PermisoController::class, 'infoUsuario']);
    Route::post('/permisos/editar-usuario', [PermisoController::class, 'editarUsuario']);
    Route::post('/permisos/nuevo-rol', [PermisoController::class, 'nuevoRol']);
    Route::post('/permisos/extra-nuevo', [PermisoController::class, 'nuevoPermisoExtra']);
    Route::post('/permisos/extra-borrar', [PermisoController::class, 'borrarPermisoGlobal']);

    // --- CONTROL WEB ---
    Route::get('/panel', [ControlRolController::class,'indexRedireccionamiento'])->name('panel');


    // --- PERFIL ---
    Route::get('/perfil/index', [PerfilController::class, 'indexEditarPerfil'])->name('perfil');
    Route::post('/perfil/actualizar/todo', [PerfilController::class, 'editarUsuario']);


    // GALERIA
    Route::get('/galeria/index', [AdminAuthController::class, 'indexGaleria'])->name('galeria');
    Route::get('/galeria/index/tabla', [AdminAuthController::class, 'tablaGaleria']);
    Route::post('/galeria/posicion', [AdminAuthController::class,'actualizarPosicionGaleria']);
    Route::post('/galeria/nuevo', [AdminAuthController::class,'nuevaGaleria']);
    Route::post('/galeria/desactivar', [AdminAuthController::class,'desactivarGaleria']);
    Route::post('/galeria/activar', [AdminAuthController::class,'activarGaleria']);
    Route::post('/galeria/borrar', [AdminAuthController::class,'borrarGaleria']);
    Route::post('/galeria/informacion', [AdminAuthController::class,'informacionGaleria']);
    Route::post('/galeria/editar', [AdminAuthController::class,'editarGaleria']);

    // NUEVO IDIOMA
    // Mostrar formulario (region destino dinámica)
    Route::get('/idiomas', [AdminAuthController::class, 'indexIdiomas'])
        ->name('idiomas');

    Route::get('/idiomas/{region}', [AdminAuthController::class, 'indexIdiomas'])
        ->whereNumber('region')
        ->name('idiomas.region');

    // Guardar
    Route::post('/idiomas/guardar', [AdminAuthController::class, 'guardarIdiomas'])
        ->name('idiomas.guardar');


    // === CATEGORIAS ===
    Route::get('/categoria', [CategoriasController::class, 'indexCategorias'])
        ->name('categoria');
    Route::get('/categoria/index/tabla', [CategoriasController::class, 'tablaCategorias']);
    Route::post('/categoria/posicion', [CategoriasController::class,'actualizarPosicionCategorias']);
    Route::post('/categoria/nuevo', [CategoriasController::class,'nuevaCategoria']);
    Route::post('/categoria/desactivar', [CategoriasController::class,'desactivarCategoria']);
    Route::post('/categoria/activar', [CategoriasController::class,'activarCategoria']);
    Route::post('/categoria/informacion', [CategoriasController::class,'informacionCategoria']);
    Route::post('/categoria/editar', [CategoriasController::class,'editarCategoria']);


    // === PRODUCTOS ===
    Route::get('/producto/{idcategoria}', [CategoriasController::class, 'indexProductos'])
        ->name('producto');
    Route::get('/producto/index/tabla/{idcategoria}', [CategoriasController::class, 'tablaProductos']);
    Route::post('/producto/nuevo', [CategoriasController::class,'nuevoProducto']);
    Route::post('/producto/posicion', [CategoriasController::class,'actualizarPosicionProducto']);
    Route::post('/producto/desactivar', [CategoriasController::class,'desactivarProducto']);
    Route::post('/producto/activar', [CategoriasController::class,'activarProducto']);
    Route::post('/producto/informacion', [CategoriasController::class,'informacionProducto']);
    Route::post('/producto/editar', [CategoriasController::class,'editarProducto']);
    Route::post('/producto/editarimagen', [CategoriasController::class,'actualizarImagenProducto']);

    Route::get('/producto/presentacion/{idproducto}', [CategoriasController::class, 'indexProductosPresentacion']);
    Route::get('/producto/presentacion/index/tabla/{idproducto}', [CategoriasController::class, 'tablaProductosPresentacion']);
    Route::post('/producto/presentacion/posicion', [CategoriasController::class,'actualizarPosicionProductoPresentacion']);
    Route::post('/producto/presentacion/nuevo', [CategoriasController::class,'nuevoProductoPresentacion']);
    Route::post('/producto/presentacion/desactivar', [CategoriasController::class,'desactivarProductoPresentacion']);
    Route::post('/producto/presentacion/activar', [CategoriasController::class,'activarProductoPresentacion']);
    Route::post('/producto/presentacion/informacion', [CategoriasController::class,'informacionProductoPresentacion']);
    Route::post('/producto/presentacion/editar', [CategoriasController::class,'editarProductoPresentacion']);

    // PAIS

    Route::get('/paises', [PaisesController::class, 'vistaPaises'])->name('paises');
    Route::get('/paises/index/tabla', [PaisesController::class, 'tablaPaises']);
    Route::post('/paises/informacion', [PaisesController::class,'informacionPais']);
    Route::post('/paises/nuevo', [PaisesController::class,'registrarNuevoPais']);
    Route::post('/paises/editar', [PaisesController::class,'editarPais']);

    Route::get('/departamentos/index/{idpais}', [PaisesController::class, 'vistaDepartamentos']);
    Route::get('/departamentos/tabla/{idpais}', [PaisesController::class, 'tablaDepartamentos']);
    Route::post('/departamentos/informacion', [PaisesController::class,'informacionDepartamento']);
    Route::post('/departamentos/nuevo', [PaisesController::class,'registrarNuevoDepartamento']);
    Route::post('/departamentos/editar', [PaisesController::class,'editarDepartamento']);

    Route::get('/departamentos/index/{idpais}', [PaisesController::class, 'vistaDepartamentos']);
    Route::get('/departamentos/tabla/{idpais}', [PaisesController::class, 'tablaDepartamentos']);
    Route::post('/departamentos/informacion', [PaisesController::class,'informacionDepartamento']);
    Route::post('/departamentos/nuevo', [PaisesController::class,'registrarNuevoDepartamento']);
    Route::post('/departamentos/editar', [PaisesController::class,'editarDepartamento']);

    Route::get('/municipios/index/{idpais}', [PaisesController::class, 'vistaMunicipios']);
    Route::get('/municipios/tabla/{idpais}', [PaisesController::class, 'tablaMunicipios']);
    Route::post('/municipios/informacion', [PaisesController::class,'informacionMunicipio']);
    Route::post('/municipios/nuevo', [PaisesController::class,'registrarNuevoMunicipio']);
    Route::post('/municipios/editar', [PaisesController::class,'editarMunicipio']);



});





Route::middleware(['detect.country.locale'])->group(function () {

    Route::group([
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeViewPath','localizationRedirect','localeViewPath']
    ], function () {

        Route::get('/', [FrontendController::class, 'vistaIndex'])->name('user.index');


        Route::get(LaravelLocalization::transRoute('routes.our_coffee'), [FrontendController::class, 'vistaOurCoffee'])
            ->name('user.ourcoffee');

        Route::get(LaravelLocalization::transRoute('routes.products'), [FrontendController::class, 'vistaProducts'])
            ->name('user.products');

        Route::get(LaravelLocalization::transRoute('routes.gallery'), [FrontendController::class, 'vistaGallery'])
            ->name('user.gallery');

        Route::get(LaravelLocalization::transRoute('routes.contact'), [FrontendController::class, 'vistaContact'])
            ->name('user.contact');

        Route::post(LaravelLocalization::transRoute('routes.contact'), [FrontendController::class, 'send'])
            ->name('contact.send');

        Route::get(LaravelLocalization::transRoute('routes.login'), [UsuarioAuthController::class, 'showLoginFormUsuario'])
            ->name('user.login');

        // vista ingresar correo recuperacion
        Route::get(LaravelLocalization::transRoute('routes.passwordrequest'), [UsuarioAuthController::class, 'showIngresarCorreoForm'])
            ->name('user.password.request');

        Route::post('/login', [UsuarioAuthController::class, 'loginUsuario'])->name('user.login.process');
        Route::post('/logout', [UsuarioAuthController::class, 'logoutUsuario'])->name('user.logout');
        Route::post('/request-code', [UsuarioAuthController::class, 'solicitarCodigoCorreo']);
        Route::post('/register', [UsuarioAuthController::class, 'registroCliente']);


        // vista cambiar contrasena
        Route::get(LaravelLocalization::transRoute('routes.passwordreset'), [UsuarioAuthController::class, 'showResetPasswordForm'])
            ->name('user.password.reset.form');

        // vista token no valido
        Route::get(LaravelLocalization::transRoute('routes.tokeninvalid'), [UsuarioAuthController::class, 'showtokenInvalid'])
            ->name('user.token.novalid');

        // actualiza la contrasena del usuario
        Route::post(LaravelLocalization::transRoute('routes.updatepassword'), [PasswordResetController::class, 'resetPassword'])
        ->name('user.password.update');

        // vista ordenes
        Route::get(LaravelLocalization::transRoute('routes.orders'), [UsuarioAuthController::class, 'vistaMisOrdenes'])
            ->name('user.orders');

        // vista direcciones
        Route::get(LaravelLocalization::transRoute('routes.address'), [UsuarioAuthController::class, 'vistaMisDirecciones'])
            ->name('user.address');

        // guardar nueva direccion
        Route::post(LaravelLocalization::transRoute('routes.save_direction'), [UsuarioAuthController::class, 'guardarNuevaDireccion'])
            ->name('user.savenew.direction');

        // borrar direccion
        Route::post(LaravelLocalization::transRoute('routes.deletedirection'), [UsuarioAuthController::class, 'borrarDireccionCliente'])
        ->name('user.delete.direction');

        // hacer predeterminada una direccion
        Route::post(LaravelLocalization::transRoute('routes.directiondefault'), [UsuarioAuthController::class, 'direccionPorDefault'])
            ->name('user.direction.default');


        // vista nueva direccion
        Route::get(LaravelLocalization::transRoute('routes.newdirection'), [UsuarioAuthController::class, 'vistaNuevaDireccion'])
            ->name('user.new.direction');






    });

});

// CARGA IMAGENES PARA GALERIA SIN LOCALIZACION
Route::get('/galeria/cargar', [FrontendController::class, 'cargarGaleria'])
    ->name('galeria.cargar');





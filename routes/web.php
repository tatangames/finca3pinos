<?php

use Illuminate\Support\Facades\Route;

// ======================== BACKEND =============================
use App\Http\Controllers\Backend\Sistema\AdminAuthController;
use App\Http\Controllers\Backend\Roles\RolesController;
use App\Http\Controllers\Backend\Roles\PermisoController;
use App\Http\Controllers\Backend\Sistema\PerfilController;
use App\Http\Controllers\Controles\ControlRolController;


// ======================== FRONTEND =============================
use App\Http\Controllers\Frontend\Sistema\UsuarioAuthController;
use App\Http\Controllers\Frontend\Sistema\FrontendController;
use App\Http\Controllers\Frontend\Sistema\DashboardController;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;





/*
|--------------------------------------------------------------------------
| 1) ADMIN – fuera del prefijo {region}
|--------------------------------------------------------------------------
*/

/*Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/',        [AdminAuthController::class, 'showLoginFormAdmin'])->name('login');   // GET /admin
    Route::post('/login',  [AdminAuthController::class, 'loginAdmin'])->name('login.process');   // POST /admin/login
    Route::post('/logout', [AdminAuthController::class, 'logoutAdmin'])->name('logout');         // POST /admin/logout
});


// Rutas protegidas de admin (panel, etc.)
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    // --- CONTROL WEB ---
    Route::get('/panel', [ControlRolController::class,'indexRedireccionamiento'])->name('panel');

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

    // --- PERFIL ---
    Route::get('/perfil/index', [PerfilController::class,'indexEditarPerfil'])->name('perfil');
    Route::post('/perfil/actualizar/todot', [PerfilController::class, 'editarUsuario']);
});*/





Route::middleware(['detect.country.locale'])->group(function () {

    Route::group([
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeViewPath','localizationRedirect','localeViewPath']
    ], function () {
        Route::get('/', [FrontendController::class, 'vistaIndex'])->name('user.index');

        Route::get(LaravelLocalization::transRoute('routes.about'), [FrontendController::class, 'about'])->name('about');

        Route::get('/login',  [UsuarioAuthController::class, 'showLoginFormUsuario'])->name('user.login');
        Route::post('/login', [UsuarioAuthController::class, 'loginUsuario'])->name('user.login.process');

        Route::get('/dashboard', [DashboardController::class, 'vistaInicio'])->name('user.dashboard');
    });

});









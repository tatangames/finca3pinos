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







// ===================== BACKEND =====================================


// inicio de sesion para admin, editor, cliente
Route::get('/admin', [AdminAuthController::class, 'showLoginFormAdmin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'loginAdmin']);
Route::post('/admin/login/logout', [AdminAuthController::class, 'logoutAdmin'])->name('admin.logout');

// --- CONTROL WEB ---
Route::get('/panel', [ControlRolController::class,'indexRedireccionamiento'])->name('admin.panel');

// --- SIN PERMISOS VISTA 403 ---
Route::get('sin-permisos', [ControlRolController::class,'indexSinPermiso'])->name('no.permisos.index');


// --- ROLES ---
Route::get('/admin/roles/index', [RolesController::class,'index'])->name('admin.roles.index');
Route::get('/admin/roles/tabla', [RolesController::class,'tablaRoles']);
Route::get('/admin/roles/lista/permisos/{id}', [RolesController::class,'vistaPermisos']);
Route::get('/admin/roles/permisos/tabla/{id}', [RolesController::class,'tablaRolesPermisos']);
Route::post('/admin/roles/permiso/borrar', [RolesController::class, 'borrarPermiso']);
Route::post('/admin/roles/permiso/agregar', [RolesController::class, 'agregarPermiso']);
Route::get('/admin/roles/permisos/lista', [RolesController::class,'listaTodosPermisos']);
Route::get('/admin/roles/permisos-todos/tabla', [RolesController::class,'tablaTodosPermisos']);
Route::post('/admin/roles/borrar-global', [RolesController::class, 'borrarRolGlobal']);

// --- PERMISOS ---
Route::get('/admin/permisos/index', [PermisoController::class,'index'])->name('admin.permisos.index');
Route::get('/admin/permisos/tabla', [PermisoController::class,'tablaUsuarios']);
Route::post('/admin/permisos/nuevo-usuario', [PermisoController::class, 'nuevoUsuario']);
Route::post('/admin/permisos/info-usuario', [PermisoController::class, 'infoUsuario']);
Route::post('/admin/permisos/editar-usuario', [PermisoController::class, 'editarUsuario']);
Route::post('/admin/permisos/nuevo-rol', [PermisoController::class, 'nuevoRol']);
Route::post('/admin/permisos/extra-nuevo', [PermisoController::class, 'nuevoPermisoExtra']);
Route::post('/admin/permisos/extra-borrar', [PermisoController::class, 'borrarPermisoGlobal']);

// --- PERFIL ---
Route::get('/admin/perfil/index', [PerfilController::class,'indexEditarPerfil'])->name('admin.perfil');
Route::post('/admin/perfil/actualizar/todot', [PerfilController::class, 'editarUsuario']);






// ===================== FRONTEND =====================================






Route::get('/', [FrontendController::class,'vistaLogin'])->name('user.index');
Route::get('/login', [UsuarioAuthController::class,'showLoginFormUsuario'])->name('user.login');
Route::post('/login', [UsuarioAuthController::class, 'loginUsuario']);


Route::get('/dashboard', [DashboardController::class,'vistaInicio'])->name('user.dashboard');

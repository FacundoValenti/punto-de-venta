<?php

use App\Http\Controllers\categoriaController;
use App\Http\Controllers\clienteController;
use App\Http\Controllers\compraController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\logoutController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\panelController;
use App\Http\Controllers\presentacionController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\proveedoresController;
use App\Http\Controllers\ventaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
    //return redirect('/panel');
//});
//route::get('/',[homeController::class,'index'])->name('panel');

/* Esta ruta ya no hace falta se maneja todo desde el homecontroller */
//Route::view('/panel', 'panel.index')->name('panel');


Route::get('/', [homeController::class, 'index'])->name('panel');




Route::resource('categorias', CategoriaController::class);

Route::resource('presentaciones', presentacionController::class);

Route::resource('marcas', MarcaController::class);

Route::resource('productos', ProductoController::class);

route::resource('clientes',clienteController::class);

route::resource('proveedores', proveedoresController::class);

route::resource('compras',compraController::class);

route::resource('ventas', ventaController::class);

route::resource('panel', panelController::class);







Route::get('/login', [loginController::class,'index'])->name('login');
route::post('/login',[loginController::class, 'login']);
Route::get('/logout', [logoutController::class, 'logout'])->name('logout');

Route::get('/401', function () {
    return view('pages.401');
});

Route::get('/404', function () {
    return view('pages.404');
});

Route::get('/500', function () {
    return view('pages.500');
});

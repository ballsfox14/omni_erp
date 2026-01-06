<?php

use App\Http\Controllers\BancoController;
use App\Http\Controllers\TipoMovimientoController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CierreMensualController;
use App\Http\Controllers\EmpresaConfigController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Configuración de Empresa
    Route::get('/empresa-config', [EmpresaConfigController::class, 'edit'])->name('empresa-config.edit');
    Route::put('/empresa-config', [EmpresaConfigController::class, 'update'])->name('empresa-config.update');
    Route::delete('/empresa-config/logo', [EmpresaConfigController::class, 'deleteLogo'])->name('empresa-config.delete-logo');
    
    // Bancos
    Route::resource('bancos', BancoController::class);
    
    // Tipos de Movimiento
    Route::resource('tipos-movimiento', TipoMovimientoController::class);
    
    // Movimientos
    Route::resource('movimientos', MovimientoController::class);
    
    // RUTAS DE CIERRES MENSUALES
    Route::get('cierres-mensuales/desde-movimientos', [CierreMensualController::class, 'cierreDesdeMovimientos'])
        ->name('cierres-mensuales.desde-movimientos');
    
    Route::resource('cierres-mensuales', CierreMensualController::class)->except(['edit', 'update']);
    
    Route::post('cierres-mensuales/realizar', [CierreMensualController::class, 'realizarCierre'])
        ->name('cierres-mensuales.realizar');
    
    Route::post('cierres-mensuales/generar-consolidado-pdf', [CierreMensualController::class, 'generarConsolidadoPdf'])
        ->name('cierres-mensuales.generar-consolidado-pdf');
    
    Route::get('cierres-mensuales/{cierre}/pdf', [CierreMensualController::class, 'generarPdfIndividual'])
        ->name('cierres-mensuales.generar-pdf');
    
    Route::get('/api/calcular-cierre', [CierreMensualController::class, 'calcularCierre'])
        ->name('api.calcular-cierre');
    
    Route::get('/api/calcular-consolidado', [CierreMensualController::class, 'calcularConsolidado'])
        ->name('api.calcular-consolidado');
    
    Route::get('/api/verificar-cierre-existente', [CierreMensualController::class, 'verificarCierreExistente'])
        ->name('api.verificar-cierre-existente');
    
    Route::post('cierres-mensuales/verificar', [CierreMensualController::class, 'verificarCierre'])
        ->name('cierres-mensuales.verificar');
    
    Route::post('cierres-mensuales/apertura', [CierreMensualController::class, 'crearAperturaMes'])
        ->name('cierres-mensuales.apertura');
    
    // Reportes
    Route::get('/reportes/banco', [ReporteController::class, 'indexBanco'])->name('reportes.banco');
    
    Route::post('/reportes/banco/generar', [ReporteController::class, 'generarReporteBanco'])->name('reportes.banco.generar');
    
    Route::get('/reportes/consolidado', [ReporteController::class, 'indexConsolidado'])->name('reportes.consolidado');
    
    Route::post('/reportes/consolidado/generar', [ReporteController::class, 'generarReporteConsolidado'])->name('reportes.consolidado.generar');
    
    Route::get('/movimientos/reporte/consolidado', [MovimientoController::class, 'reporteConsolidado'])
        ->name('movimientos.reporte.consolidado');
        
    Route::get('/movimientos/reporte/banco/{banco}', [MovimientoController::class, 'reporteBanco'])
        ->name('movimientos.reporte.banco');
});

require __DIR__.'/auth.php';
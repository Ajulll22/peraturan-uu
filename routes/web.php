<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DraftController;
use App\Http\Controllers\HarmonisasiController;
use App\Http\Controllers\IndexController;
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


Route::get('/', [IndexController::class, 'index'])->name('index');

Route::resources([
    'archive' => ArchiveController::class,
    'draft' => DraftController::class,
    'harmonisasi' => HarmonisasiController::class,
]);

Route::middleware(['auth'])->group(function () {
    Route::resources([
        'account' => AccountController::class,
        'category' => CategoryController::class,
    ]);

    Route::get('account-data', [AccountController::class, 'accountData'])->name('account.data');
    Route::get('category-data', [CategoryController::class, 'categoryData'])->name('category.data');
    Route::get('show-pasal/{id}', [ArchiveController::class, 'showPasal'])->name('archive.show-pasal');
    Route::post('update-pasal/{id}', [ArchiveController::class, 'updatePasal'])->name('archive.update-pasal');
});

// ROUTE FOR DATATABLES DATA
Route::get('archive_data', [ArchiveController::class, 'getData'])->name('archive.data');
Route::get('archive_verify/{id}', [ArchiveController::class, 'verify'])->name('archive.verify');
Route::get('draft_data', [DraftController::class, 'hitungCousine'])->name('draft.data');
Route::get('draft-pasal', [DraftController::class, 'calcPasalSimilarity'])->name('draft.calc-pasal');

Route::get('archive_file_create', [ArchiveController::class, 'createFile'])->name('archive-file.create');

Route::post('archive_file_store', [ArchiveController::class, 'fileStore'])->name('archive-file.store');
Route::get('archive_file_process', [ArchiveController::class, 'fileProcess'])->name('archive-file.process');
Route::get('archive_file_store_confirmation', [ArchiveController::class, 'createFileConfirmation'])->name('archive-file.store-confirmation');

Route::get('harmonisasi-result', [HarmonisasiController::class, 'result'])->name('harmonisasi.result');
Route::get('harmonisasi-result-data', [HarmonisasiController::class, 'resultData'])->name('harmonisasi.result-data');
Route::get('harmonisasi-result-detail', [HarmonisasiController::class, 'resultDetail'])->name('harmonisasi.result-detail');
Route::get('harmonisasi-detail', [HarmonisasiController::class, 'harmonisasiDetail'])->name('harmonisasi.detail');
Route::get('harmonisasi-show', [HarmonisasiController::class, 'show'])->name('harmonisasi.show');
Route::get('harmonisasi-file_process', [HarmonisasiController::class, 'fileProcess'])->name('harmonisasi.file-process');
Route::post('harmonisasi-file_Store', [HarmonisasiController::class, 'fileStore'])->name('harmonisasi.file-store');
Route::post('harmonisasi-store_new', [HarmonisasiController::class, 'store_new'])->name('harmonisasi.store_new');



// EXPORT DATA
Route::get('pasal_export', [DraftController::class, 'exportDraft'])->name('draft.export-pasal-pdf');
Route::get('pasal_export_word', [DraftController::class, 'exportDraftToWord'])->name('draft.export-pasal-word');

require __DIR__ . '/auth.php';

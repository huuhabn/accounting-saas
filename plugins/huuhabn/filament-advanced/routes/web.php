<?php

use Illuminate\Support\Facades\Route;
use Lab404\Impersonate\Services\ImpersonateManager;

Route::get('impersonate/leave', function() {
    if(!app(ImpersonateManager::class)->isImpersonating()) {
        return redirect('/');
    }

    app(ImpersonateManager::class)->leave();

    return redirect(
        session()->pull('impersonate.back_to')
    );
})->name('impersonate.leave')->middleware(config('filament-advanced.impersonate.leave_middleware'));

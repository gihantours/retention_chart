<?php

use App\Http\Controllers\InsightController;
use Illuminate\Support\Facades\Route;


Route::get('insight/weekly-retention-data',     [InsightController::class, 'weeklyRetentionDataAction']);

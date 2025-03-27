<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\WorkspaceController;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');
//

Route::middleware('throttle:api')->group(function () {
    Route::get('/', function () {
        return [
            'success' => true,
            'version' => '1.0.0',
        ];
    });

    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('workspaces')->group(function () {
            Route::post('/', [WorkspaceController::class, 'store']);
            Route::post('/join', [WorkspaceController::class, 'join']);
            Route::get('/my', [WorkspaceController::class, 'getMyWorkspaces']);
            Route::get('/joined', [WorkspaceController::class, 'getJoinedWorkspaces']);
            Route::delete('/workspaces/{workspaceId}/leave', [WorkspaceController::class, 'leaveWorkspace']);
        });
    });


});

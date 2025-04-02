<?php

use App\Http\Controllers\API\AssignmentController;
use App\Http\Controllers\API\SubmissionController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\LikeController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:api')->group(function () {
    Route::get('/', function () {
        return [
            'success' => true,
            'version' => '1.0.0',
        ];
    });

    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']); //checked [return profile image]
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('profile')->group(function () {
            Route::post('/upload', [UserController::class, 'upload']); //checked
            Route::put('/', [UserController::class, 'update']);
            Route::delete('/', [UserController::class, 'destroy']);
        });

        Route::prefix('workspaces')->group(function () {
            Route::post('/', [WorkspaceController::class, 'store']);
            Route::post('/join', [WorkspaceController::class, 'join']);
            Route::get('/my', [WorkspaceController::class, 'getMyWorkspaces']);
            Route::get('/joined', [WorkspaceController::class, 'getJoinedWorkspaces']);
            Route::get('/{workspaceId}',[WorkspaceController::class,'getWorkspaceById']);
            Route::delete('/{workspaceId}/leave', [WorkspaceController::class, 'leaveWorkspace']);
            Route::get('/{workspaceId}/news', [NewsController::class, 'index']); //checked
            Route::get('/{workspaceId}/assignments', [AssignmentController::class, 'index']); //checked [student & teacher]
        });

        Route::prefix('assignments')->group(function () {
            Route::get('/{assignmentId}/submissions', [SubmissionController::class, 'index']); //checked
            Route::post('/', [AssignmentController::class, 'store']); //checked
            Route::get('/{assignmentId}', [AssignmentController::class, 'show']); //checked [student & teacher]
        });

        Route::prefix('submissions')->group(function () {
            // update score
            Route::post('/', [SubmissionController::class, 'update']); //checked
            Route::post('/submit', [SubmissionController::class, 'submit']); //checked
            Route::get('/{submissionId}', [SubmissionController::class, 'show']); //checked
        });

        Route::prefix('news')->group(function () {
            Route::post('/', [NewsController::class, 'store']); //checked
            Route::post('/{newsId}', [NewsController::class, 'update']); //check -------------[change method from PUT to POST]
            Route::delete('/{newsId}', [NewsController::class, 'destroy']); //checked
            Route::get('/{newsId}/comments', [CommentController::class, 'getCommentsByNewsId']);
        });

        Route::prefix('comments')->group(function () {
            Route::post('/', [CommentController::class, 'store']);
            Route::put('/{commentId}', [CommentController::class, 'update']);
            Route::delete('/{commentId}', [CommentController::class, 'destroy']);
        });

        Route::prefix('like')->group(function () {
            Route::post('/', [LikeController::class, 'like']);
            Route::delete('/{newsId}', [LikeController::class, 'unlike']);
        });

    });
});

<?php

use App\Http\Controllers\Api\AchievementCategoryController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InterventionController;
use App\Http\Controllers\Api\InterventionLogController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PointThresholdController;
use App\Http\Controllers\Api\Staff\StaffController;
use App\Http\Controllers\Api\Student\StudentController;
use App\Http\Controllers\Api\Student\StudentSelfController;
use App\Http\Controllers\Api\StudentAchievementController;
use App\Http\Controllers\Api\StudentViolationController;
use App\Http\Controllers\Api\ViolationCategoryController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/{notification}', [NotificationController::class, 'show']);
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);

    Route::middleware('role:staff')->group(function () {
        Route::apiResource('students', StudentController::class);
        Route::apiResource('staff', StaffController::class);

        Route::apiResource('violation-categories', ViolationCategoryController::class);
        Route::apiResource('achievement-categories', AchievementCategoryController::class);
        Route::apiResource('point-thresholds', PointThresholdController::class);

        Route::get('/student-violations', [StudentViolationController::class, 'index']);
        Route::post('/student-violations', [StudentViolationController::class, 'store']);
        Route::get('/student-violations/{studentViolation}', [StudentViolationController::class, 'show']);
        Route::patch('/student-violations/{studentViolation}/cancel', [StudentViolationController::class, 'cancel']);

        Route::get('/student-achievements', [StudentAchievementController::class, 'index']);
        Route::post('/student-achievements', [StudentAchievementController::class, 'store']);
        Route::get('/student-achievements/{studentAchievement}', [StudentAchievementController::class, 'show']);
        Route::patch('/student-achievements/{studentAchievement}/cancel', [StudentAchievementController::class, 'cancel']);

        Route::get('/interventions', [InterventionController::class, 'index']);
        Route::post('/interventions', [InterventionController::class, 'store']);
        Route::get('/interventions/{intervention}', [InterventionController::class, 'show']);
        Route::put('/interventions/{intervention}', [InterventionController::class, 'update']);

        Route::get('/intervention-logs', [InterventionLogController::class, 'index']);
        Route::post('/intervention-logs', [InterventionLogController::class, 'store']);
        Route::get('/intervention-logs/{interventionLog}', [InterventionLogController::class, 'show']);
    });

    Route::middleware('role:student')->prefix('me')->group(function () {
        Route::get('/profile', [StudentSelfController::class, 'profile']);
        Route::get('/violations', [StudentSelfController::class, 'violations']);
        Route::get('/achievements', [StudentSelfController::class, 'achievements']);
        Route::get('/interventions', [StudentSelfController::class, 'interventions']);
    });
});

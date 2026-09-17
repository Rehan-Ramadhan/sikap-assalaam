<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\NotificationController;

use App\Http\Controllers\Api\Staff\AchievementCategoryController;
use App\Http\Controllers\Api\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Api\Staff\InterventionController as StaffInterventionController;
use App\Http\Controllers\Api\Staff\PointThresholdController;
use App\Http\Controllers\Api\Staff\ReportController;
use App\Http\Controllers\Api\Staff\StudentAchievementController as StaffStudentAchievementController;
use App\Http\Controllers\Api\Staff\StudentController;
use App\Http\Controllers\Api\Staff\StudentViolationController as StaffStudentViolationController;
use App\Http\Controllers\Api\Staff\ViolationCategoryController;

use App\Http\Controllers\Api\Student\AchievementController as StudentAchievementController;
use App\Http\Controllers\Api\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Api\Student\InterventionController as StudentInterventionController;
use App\Http\Controllers\Api\Student\ViolationController as StudentViolationController;
use App\Http\Controllers\Api\Student\ReportController as StudentReportController;

use Illuminate\Support\Facades\Route;


// public
Route::post('/login', [AuthController::class, 'login']);

// authenticated
Route::middleware('auth:sanctum')->group(function () {

    // general;
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::get('/notifications', [NotificationController::class,'index',]);
    Route::patch('/notifications/{notification}/read', [NotificationController::class,'markAsRead',]);
    Route::patch('/notifications/read-all', [NotificationController::class,'markAllAsRead',]);
    Route::post('/logout', [AuthController::class,'logout',]);

    // staff
    Route::prefix('staff')->middleware('role:staff')->group(function () {
        // dashboard
        Route::get('/dashboard', [StaffDashboardController::class, 'index',]);
        // student
        Route::apiResource('students', StudentController::class);
        // violation categories
        Route::apiResource('violation-categories', ViolationCategoryController::class);
        // achievement categories
        Route::apiResource('achievement-categories', AchievementCategoryController::class);
        // point thresholds
        Route::apiResource('point-thresholds', PointThresholdController::class);
        // student violations
        Route::apiResource('violations', StaffStudentViolationController::class);
        Route::post('/violations/{studentViolation}/cancel', [StaffStudentViolationController::class, 'cancel',]);
        // student achievements
        Route::apiResource('achievements', StaffStudentAchievementController::class);
        // interventions
        Route::get('/interventions', [StaffInterventionController::class, 'index',]);
        Route::get('/interventions/{intervention}', [StaffInterventionController::class, 'show',]);
        Route::put('/interventions/{intervention}', [StaffInterventionController::class, 'update',]);
        Route::post('/interventions/{intervention}/logs', [StaffInterventionController::class, 'addLog',]);
        // reports
        Route::get('/reports/students', [ReportController::class, 'student']);
        Route::get('/reports/violations/excel', [ReportController::class, 'violationsExcel']);
        Route::get('/reports/violations/pdf', [ReportController::class, 'violationsPdf']);
        Route::get('/reports/achievements/excel', [ReportController::class, 'achievementsExcel']);
        Route::get('/reports/achievements/pdf', [ReportController::class, 'achievementsPdf']);
        Route::get('/reports/interventions/excel', [ReportController::class, 'interventionsExcel']);
        Route::get('/reports/interventions/pdf', [ReportController::class, 'interventionsPdf']);
    });

    // student
    Route::prefix('student')
        ->middleware('role:student')
        ->group(function () {
            // dashboard
            Route::get('/dashboard', [StudentDashboardController::class, 'index',]);
            // violations
            Route::get('/violations', [StudentViolationController::class, 'index',]);
            Route::get('/violations/{studentViolation}', [StudentViolationController::class, 'show',]);
            // achievements
            Route::get('/achievements', [StudentAchievementController::class, 'index',]);
            Route::get('/achievements/{studentAchievement}', [StudentAchievementController::class, 'show',]);
            // interventions
            Route::get('/interventions', [StudentInterventionController::class, 'index',]);
            Route::get('/interventions/{intervention}', [StudentInterventionController::class, 'show',]);
            // reports
            Route::get('/reports/violations/excel', [StudentReportController::class, 'violationsExcel']);
            Route::get('/reports/violations/pdf', [StudentReportController::class, 'violationsPdf']);
            Route::get('/reports/achievements/excel', [StudentReportController::class, 'achievementsExcel']);
            Route::get('/reports/achievements/pdf', [StudentReportController::class, 'achievementsPdf']);
            Route::get('/reports/interventions/excel', [StudentReportController::class, 'interventionsExcel']);
            Route::get('/reports/interventions/pdf', [StudentReportController::class, 'interventionsPdf']);
        });
});

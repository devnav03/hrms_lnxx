<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('employee-login', [ApiController::class, 'EmployeeLogin']);
Route::post('mark-attendance', [ApiController::class, 'MarkAttendance']);
Route::post('save-contact', [ApiController::class, 'SaveContact']);
Route::post('get-emp', [ApiController::class, 'GetEmp']);
Route::get('get-emp-decrypt/{$url}', [ApiController::class, 'GetEmpDecrypt']);
Route::post('fetch-requirement-details', [ApiController::class, 'FetchRequirementDetails']);
Route::any('today-attendance', [ApiController::class, 'TodayAttendance']);
Route::any('attendance-history', [ApiController::class, 'AttendanceHistory']);
Route::any('user-profile', [ApiController::class, 'UserProfile']);
Route::any('update-profile', [ApiController::class, 'updateProfile']);

Route::any('leave-type', [ApiController::class, 'leave_type']);
Route::any('leave-apply', [ApiController::class, 'leave_apply']);
Route::any('leaves-history', [ApiController::class, 'leaves_history']);
Route::any('leaves-details', [ApiController::class, 'leaves_details']);
Route::any('cancel-leave', [ApiController::class, 'cancel_leave']);
Route::any('change-password', [ApiController::class, 'change_password']);

Route::any('task-list', [ApiController::class, 'task_list']);
Route::any('project-activities', [ApiController::class, 'project_activities']);
Route::any('timesheet-status', [ApiController::class, 'timesheet_status']);
Route::any('submit-timesheet', [ApiController::class, 'submit_timesheet']);
Route::any('timesheet-history', [ApiController::class, 'timesheet_history']);
Route::any('timesheet-history-details', [ApiController::class, 'timesheet_history_details']);

Route::any('push-notification-list', [ApiController::class, 'push_notification_list']);
Route::any('acknowledge-notification', [ApiController::class, 'acknowledge_notification']);




















<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\superadmin\OrganizationController;
use App\Http\Controllers\Module\ModulesController;
use App\Models\Organisation;
use App\Models\FormEngineCategory;

use App\Http\Controllers\organization\employee\EmployeeController;
use App\Http\Controllers\organization\settings\SettingController;
use App\Http\Controllers\organization\reports\ReportsController;

Route::get('/clear', function() {

   Artisan::call('cache:clear');
   Artisan::call('config:clear');
   Artisan::call('config:cache');
   //Artisan::call('view:clear');
   return "All Cache & Config Cleared !!!!!";
});/*
|--------------------------------------------------------------------------
|  
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\user\employee\SelfServiceController;
use App\Http\Controllers\user\employee\UserEmployeeController;
use App\Http\Controllers\user\employee\UserBankController;
use App\Http\Controllers\user\employee\UserContactController;
use App\Http\Controllers\user\employee\UserEducationController;
use App\Http\Controllers\user\employee\UserCompanyController;
use App\Http\Controllers\user\employee\UserDocumentController;
use App\Http\Controllers\user\assets\UserAssetsController;


use App\Http\Controllers\organization\letter_master\UserLetterMasterController;
use App\Http\Controllers\organization\letter_master\UserOfficerSignatureController;
use App\Http\Controllers\organization\letter_master\UserLetterTemplateController;
use App\Http\Controllers\organization\letter_master\UserMapLetterTemplateController;
use App\Http\Controllers\organization\timesheet\TimesheetController;
use App\Http\Controllers\organization\project\ProjectController;

use App\Http\Controllers\organization\organisation_level\FormEngineController;

use App\Http\Controllers\organization\payroll_compensation\PayrollCompensationController;
use App\Http\Controllers\organization\service_desk\ServiceDeskController;
use App\Http\Controllers\organization\assets_management\AssetsManagementController;

/*
|--------------------------------------------------------------------------
|  
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\user\attendance\AttendanceController;
use App\Http\Controllers\user\leave\LeaveController;
use App\Http\Controllers\user\timesheet\UserTimesheetController;
use App\Http\Controllers\organization\master\OrganizationMaster;
use App\Http\Controllers\organization\employee\EmpController;


use App\Http\Controllers\organization\hiring_process\HiringProcessController;
use App\Http\Controllers\organization\offer_letter\OfferLetterController;
use App\Http\Controllers\organization\offer_letter\SendOfferLettersToCandidate;



/*

|--------------------------------------------------------------------------
|  
|--------------------------------------------------------------------------
*/
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

$pages = Organisation::select('user_name')->get();
foreach ($pages as $page) {
    $page = $page->user_name;
    Route::get($page, function () use ($page) {
        return view('auth.login');
    });
}
Route::any('reportSync', [App\Http\Controllers\Frontend\HomeController::class, 'reportSync'])->name('reportSync');
Route::any('push-notification-crone', [App\Http\Controllers\Frontend\HomeController::class, 'push_notification_crone'])->name('push-notification-crone');
$category = FormEngineCategory::select('name')->get();
Route::any('/', [App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('get-started');
foreach($category as $cat){
    $catname = str_replace(' ', '-', strtolower($cat->name));
    Route::post('save-'.$catname, [FormEngineController::class,'SaveForm']);
}
Route::post('save-updated-profile', [FormEngineController::class, 'SaveUpdatedProfile']);
Route::post('save-emp-updated-profile', [FormEngineController::class, 'SaveEmpUpdatedProfile']);
Route::any('leave-approve/{id}', [App\Http\Controllers\Frontend\HomeController::class, 'leave_approve'])->name('leave-approve');
Route::any('leave-reject/{id}', [App\Http\Controllers\Frontend\HomeController::class, 'leave_reject'])->name('leave-reject');
Route::any('offer-letter-accept/{id}', [App\Http\Controllers\Frontend\HomeController::class, 'offer_letter_accept'])->name('offer-letter-accept');
Route::any('offer-letter-reject/{id}', [App\Http\Controllers\Frontend\HomeController::class, 'offer_letter_reject'])->name('offer-letter-reject');

Route::any('are-you-sure-offer-letter-accept/{id}', [App\Http\Controllers\Frontend\HomeController::class, 'areyousure_offer_letteraccept'])->name('are-you-sure-offer-letter-accept');
Route::any('are-you-sure-offer-letter-reject/{id}', [App\Http\Controllers\Frontend\HomeController::class, 'areyousure_offer_letterreject'])->name('are-you-sure-offer-letter-reject');

Route::any('evisa-approved/{id}', [App\Http\Controllers\Frontend\HomeController::class, 'evisa_approved'])->name('evisa-approved');
Route::any('evisa-rejected/{id}', [App\Http\Controllers\Frontend\HomeController::class, 'evisa_rejected'])->name('evisa-rejected');

Route::any('candidate-profile/{id}', [App\Http\Controllers\Frontend\HomeController::class, 'candidate_profile'])->name('candidate-profile');

Route::get('getPro', [App\Http\Controllers\Frontend\HomeController::class, 'getPro'])->name('getPro');

Route::get('shift-day-crone', [App\Http\Controllers\Frontend\HomeController::class, 'shift_day_crone'])->name('shift_day_crone');

Route::get('holiday-calendar-crone', [App\Http\Controllers\Frontend\HomeController::class, 'holiday_calendar_crone'])->name('holiday-calendar-crone');


Auth::routes();
/* Super admin route   */
Route::middleware(['auth', 'user-access:superadmin'])->group(function () {
    Route::any('/add-organization', [OrganizationController::class, 'AddOrganization'])->name('add.organization');
   Route::any('organization-update', [OrganizationController::class, 'organizationUpdate'])->name('organization-update');
    Route::get('/delete-organization/{id}', [OrganizationController::class, 'DeleteOrganization']);
    Route::get('/update-organization/{id}', [OrganizationController::class, 'UpdateOrganization']);
    Route::middleware(['auth', 'user-access:organization'])->group(function () {
        Route::get('/module/index', [ModulesController::class, 'index'])->name('module.index');
    });
});
Route::get('log-out', [OrganizationController::class, 'log_out'])->name('log-out');
Route::get('user-log-out', [OrganizationController::class, 'user_log_out'])->name('user-log-out');
/* Organization route   */
Route::middleware(['auth', 'user-access:organization'])->group(function () {
    Route::any('add-source', [OrganizationMaster::class, 'AddSource'])->name('add.source');
    Route::get('add-source/{id}', [OrganizationMaster::class, 'AddSource']);
    Route::get('source-delete/{id}', [OrganizationMaster::class, 'DeleteSource']);

    Route::any('form-category-master', [OrganizationMaster::class, 'formCategoryMaster']);
    Route::any('form-category-master/{id}', [OrganizationMaster::class, 'formCategoryMaster']);
    Route::any('form-category-master-delete/{id}', [OrganizationMaster::class, 'deleteFormCategoryMaster']);

    // Notification Master route start
            Route::resource('notification', 'App\Http\Controllers\NotificationController', [
                'names' => [
                    'index'     => 'notification.index',
                    'create'    => 'notification.create',
                    'store'     => 'notification.store',
                    'edit'      => 'notification.edit',
                    'update'    => 'notification.update',
                ],
                'except' => ['show','destroy']
            ]);
            Route::any('notification/paginate/{page?}', ['as' => 'notification.paginate',
                'uses' => 'App\Http\Controllers\NotificationController@Paginate']);
            Route::any('notification/action', ['as' => 'notification.action',
                'uses' => 'App\Http\Controllers\NotificationController@Action']);
            Route::any('notification/toggle/{id?}', ['as' => 'notification.toggle',
                'uses' => 'App\Http\Controllers\NotificationController@Toggle']);
            Route::any('notication-type', [App\Http\Controllers\NotificationController::class, 'notication_type'])->name('notication-type');
            Route::any('notification-status', [App\Http\Controllers\NotificationController::class, 'notification_status'])->name('notification_status');
            Route::any('notification-history', [App\Http\Controllers\NotificationController::class, 'notification_history'])->name('notification-history');
            Route::any('notification-reports/{id}', [App\Http\Controllers\NotificationController::class, 'notification_reports'])->name('notification-reports');
    // Notification Master route end
    
    Route::any('emp-type-master', [OrganizationMaster::class, 'empTypeMaster']);
    Route::any('emp-type-master/{id}', [OrganizationMaster::class, 'empTypeMaster']);
    Route::any('emp-type-master-delete/{id}', [OrganizationMaster::class, 'deleteEmpTypeMaster']);

    Route::any('office-master', [OrganizationMaster::class, 'officeMaster']);
    Route::any('office-master/{id}', [OrganizationMaster::class, 'officeMaster']);
    Route::any('office-master-delete/{id}', [OrganizationMaster::class, 'deleteOfficeMaster']);

    Route::any('department-master', [OrganizationMaster::class, 'departmentMaster']);
    Route::any('department-master/{id}', [OrganizationMaster::class, 'departmentMaster']);
    Route::any('department-master-delete/{id}', [OrganizationMaster::class, 'deleteDepartmentMaster']);

    Route::any('add-shift', [OrganizationMaster::class, 'AddShift']);
    Route::any('shift-details', [OrganizationMaster::class, 'ShiftDetails']);
    Route::any('add-shift/{id}', [OrganizationMaster::class, 'AddShift']);
    Route::any('shift-master-delete/{id}', [OrganizationMaster::class, 'deleteShiftMaster']);

    Route::any('manual-mark-attendance', [OrganizationMaster::class, 'manualMarkAttendance']);
    Route::any('add-manual-mark-attendance', [OrganizationMaster::class, 'addManualMarkAttendance']);
    Route::any('add-missed-attend', [OrganizationMaster::class, 'addMissedPunch']);

    Route::any('/employee-master', [OrganizationMaster::class, 'EmployeeMaster'])->name('employee.master');

    Route::any('bank-master', [OrganizationMaster::class, 'BankMaster']);
    Route::any('bank-master/{id}', [OrganizationMaster::class, 'BankMaster']);
    Route::any('delete-bank/{id}', [OrganizationMaster::class, 'DeleteBank']);



    Route::any('leave-master', [OrganizationMaster::class, 'LeaveMaster']);
    Route::get('leave-master/{id}', [OrganizationMaster::class, 'LeaveMaster']);
    Route::get('leave-master-delete/{id}', [OrganizationMaster::class, 'deleteLeaveMaster']);
    Route::get('delete-list-leave/{id}', [OrganizationMaster::class, 'DeleteListLeave']);

    Route::any('add-position', [OrganizationMaster::class, 'AddPosition'])->name('add.position');
    Route::get('add-position/{id}', [OrganizationMaster::class, 'AddPosition']);
    Route::get('position-delete/{id}', [OrganizationMaster::class, 'DeletePosition']);
   Route::any('update-position', [OrganizationMaster::class, 'UpdatePosition'])->name('update-position');

    
    Route::any('add-notice-period', [OrganizationMaster::class, 'AddNoticePeriod'])->name('add.notice.period');
    Route::get('add-notice-period/{id}', [OrganizationMaster::class, 'AddNoticePeriod']);
    Route::get('notice-period-delete/{id}', [OrganizationMaster::class, 'DeleteNoticePeriod']);

    Route::any('add-educations', [OrganizationMaster::class, 'AddEducation'])->name('add.educations');
    Route::get('add-educations/{id}', [OrganizationMaster::class, 'AddEducation']);
    Route::get('educations-delete/{id}', [OrganizationMaster::class, 'DeleteEducation']);    

    Route::any('add-project', [OrganizationMaster::class, 'AddProject'])->name('add.project');
    Route::get('add-project/{id}', [OrganizationMaster::class, 'AddProject']);
    Route::get('project-delete/{id}', [OrganizationMaster::class, 'DeleteProject']);    

    Route::any('vanders', [OrganizationMaster::class, 'AddVander'])->name('add.vander');
    Route::get('vanders/{id}', [OrganizationMaster::class, 'AddVander']);
    Route::get('vander-delete/{id}', [OrganizationMaster::class, 'DeleteVander']); 

    Route::any('vanders-staff', [OrganizationMaster::class, 'AddVanderStaff'])->name('add.vanders-staff');
    Route::get('vanders-staff/{id}', [OrganizationMaster::class, 'AddVanderStaff']);
    Route::get('vanders-staff-delete/{id}', [OrganizationMaster::class, 'DeleteVanderStaff']); 

    Route::any('add-assign-task', [OrganizationMaster::class, 'AddAssignTask']);
    Route::any('add-assign-task/{id}', [OrganizationMaster::class, 'AddAssignTask']);
    Route::any('assign-task-delete/{id}', [OrganizationMaster::class, 'DeleteAssignTask']); 
    
    Route::any('template-master', [OrganizationMaster::class, 'TemplateMaster']);
    Route::any('report-master', [ReportsController::class, 'ReportMaster']);
    
    Route::any('header-footer-template-master', [OrganizationMaster::class, 'HeaderFooterTemplateMaster']);
    Route::get('header-footer-template-master/{id}', [OrganizationMaster::class, 'HeaderFooterTemplateMaster']);
    Route::any('header-footer-template-master-delete/{id}', [OrganizationMaster::class, 'deleteHeaderFooterTemplateMaster']);

    
    Route::any('add-email-template', [SettingController::class, 'addEmailTemplate']);
    Route::any('add-email-template/{id}', [SettingController::class, 'addEmailTemplate']);
    Route::any('email-template-delete/{id}', [SettingController::class, 'emailTemplateDelete']);

    Route::any('add-sms-template', [SettingController::class, 'addSMSTemplate']);
    Route::any('add-sms-template/{id}', [SettingController::class, 'addSMSTemplate']);
    Route::any('sms-template-delete/{id}', [SettingController::class, 'smsTemplateDelete']);

    Route::any('add-notification-template', [SettingController::class, 'addNotificationemplate']);
    Route::any('add-notification-template/{id}', [SettingController::class, 'addNotificationemplate']);
    Route::any('notification-template-delete/{id}', [SettingController::class, 'notificationTemplateDelete']);
    
    
    Route::get('employee-attendance', [EmployeeController::class, 'AttendanceDetails'])->name('employee.attendance');
    Route::get('/module/index', [ModulesController::class, 'index'])->name('module.index');
    Route::any('/employee-details', [EmployeeController::class, 'EmployeeDetails'])->name('employee.details');
    Route::any('/add-employees', [EmployeeController::class, 'AddEmployee'])->name('add.employees');

    Route::any('/add-letter', [UserLetterMasterController::class, 'AddLetter'])->name('add.letter');
    Route::any('/add-officer-signature', [UserOfficerSignatureController::class, 'AddOfficerSignature'])->name('add.officer-signature');
    Route::any('/add-letter-template', [UserLetterTemplateController::class, 'AddLetterTemplate'])->name('add.letter-template');
    Route::any('/add-map-letter-template', [UserMapLetterTemplateController::class, 'AddMapLetterTemplate'])->name('add.map-letter-template');

    Route::get('view-employee-attendance', [EmployeeController::class, 'viewEmployeeAttendanceDetails'])->name('view.employee-attendance');
    
   Route::any('approval-flow', [EmployeeController::class, 'ListApprovalFlow']);
  // Route::any('add-approval-flow', [EmployeeController::class, 'AddApprovalFlow']);
   Route::any('add-approval-flow/{id}', [EmployeeController::class, 'AddApprovalFlow']);

   Route::any('add-approval-flow/{id}', [EmployeeController::class, 'AddApprovalFlow']);
   Route::any('delete-leave-flow/{id}', [EmployeeController::class, 'DeleteLeaveFlow']);
   Route::any('delete-approval-authority/{id}/{flow_id}', [EmployeeController::class, 'DeleteApprovalAuthority']);
   Route::any('delete-flow/{id}', [EmployeeController::class, 'DeleteFlow']);

    Route::any('delete-leave-types/{id}', [EmployeeController::class, 'DeleteLeaveTypes']);

    Route::any('add-leave', [EmployeeController::class, 'addLeave']);
    Route::any('list-leave', [EmployeeController::class, 'listLeave']);
    Route::any('edit-leave/{id}', [EmployeeController::class, 'editLeave']);
    Route::any('update-leave/{id}', [EmployeeController::class, 'updateLeave']);
    Route::any('list-leave-status/{id}/{status_id}', [EmployeeController::class, 'listLeaveStatus']);
    

    Route::get('view-employee-leave', [EmployeeController::class, 'viewEmployeeLeaveDetails'])->name('view.employee-leave');
    Route::any('employee-leave-status', [EmployeeController::class, 'employeeLeaveStatus']);
    Route::any('approved-leave', [EmployeeController::class, 'employeeApprovedLeave']);
    Route::any('reject-leave', [EmployeeController::class, 'employeeRejectLeave']);


    Route::get('view-employee-timesheet', [TimesheetController::class, 'ViewEmployeeTimesheet'])->name('view.employee.timesheet');
    Route::any('employee-reporting', [EmployeeController::class, 'EmployeeReporting'])->name('employee.reporting');
    Route::any('employee-reporting/{id}', [EmployeeController::class, 'EmployeeReporting']);

    Route::any('add-emp-assign-project', [ProjectController::class, 'AddEmpProject'])->name('add.emp.project');
    Route::any('view-project-details', [ProjectController::class, 'ViewProjectDetails'])->name('view.project.details');

    Route::any('add-form-engine', [FormEngineController::class, 'AddFormEngine'])->name('add.form.engine');
    Route::post('save-form-engine',[FormEngineController::class,'SaveFormEngine']);
    Route::any('add-form', [FormEngineController::class, 'AddForm']);
    Route::any('add-form/{id}', [FormEngineController::class, 'AddForm']);
    Route::get('delete-form/{id}', [FormEngineController::class, 'DeleteForm']);

    Route::any('salary-head-master', [PayrollCompensationController::class, 'salaryHeadMaster']);
    Route::any('salary-head-master-edit/{id}', [PayrollCompensationController::class, 'salaryHeadMasterEdit'])->name('salary-head-master-edit');
    Route::any('salary-head-master-del/{id}', [PayrollCompensationController::class, 'salaryHeadMasterDel'])->name('salary-head-master-del');

    Route::any('holiday-calendar', [PayrollCompensationController::class, 'holidayCalendar'])->name('holiday-calendar');
    Route::any('holiday-calendar-edit/{id}', [PayrollCompensationController::class, 'holidayCalendarEdit'])->name('holiday-calendar-edit');
    Route::any('holiday-calendar-del/{id}', [PayrollCompensationController::class, 'holidayCalendarDel'])->name('holiday-calendar-del');


    Route::any('salary-history', [PayrollCompensationController::class, 'salaryHistory'])->name('salary-history');
    Route::any('salary-history-by-month/{id}', [PayrollCompensationController::class, 'salaryHistoryByMonth'])->name('salary-history-by-month');

    Route::any('export-salary-slip/{id}', [PayrollCompensationController::class, 'exportSalarySlip'])->name('export-salary-slip');


    Route::any('salary-generation', [PayrollCompensationController::class, 'salaryGeneration']);
    Route::any('view-salary-slip', [PayrollCompensationController::class, 'viewSalarySlip'])->name('view-salary-slip');

    Route::any('employee-salary-slip', [PayrollCompensationController::class, 'employeeSalarySlip'])->name('employee-salary-slip');

    Route::any('salary-approval-flow', [PayrollCompensationController::class, 'salaryApprovalFlow']);
    Route::any('incentive-compensation', [PayrollCompensationController::class, 'incentiveCompensation']);
    Route::any('advance-loan-deduction', [PayrollCompensationController::class, 'advanceLoanDeduction']);
    Route::any('investment-declaration', [PayrollCompensationController::class, 'investmentDeclaration']);
    Route::any('tax-computation', [PayrollCompensationController::class, 'taxComputation']);
    
    Route::any('assets-pending-request', [AssetsManagementController::class, 'assetsPendingRequest']);
    Route::any('update-assets-status/{id}', [AssetsManagementController::class, 'updateAssetsStatus']);
    Route::any('update-assets-status', [AssetsManagementController::class, 'updateAssetsStatus']);
    Route::any('assets-report', [AssetsManagementController::class, 'assetsReport']);
    Route::any('return-assets-report-status', [AssetsManagementController::class, 'returnAssetsReportStatus']);
    Route::any('add-assets-item', [AssetsManagementController::class, 'addAssetsItem']);
    Route::any('add-component', [AssetsManagementController::class, 'addComponent']);
    Route::any('view-assets-item', [AssetsManagementController::class, 'viewAssetsItem']);
    Route::any('assets-type', [AssetsManagementController::class, 'assetsType']);
    Route::any('assets-brand', [AssetsManagementController::class, 'assetsBrand']);
    Route::any('assets-our-vendor', [AssetsManagementController::class, 'assetsOurVendor']);
    Route::any('assets-inward-outward', [AssetsManagementController::class, 'assetsInwardOutward']);
    Route::any('outward-assets-list', [AssetsManagementController::class, 'outwardAssetsList']);
    Route::any('inward-assets-list', [AssetsManagementController::class, 'inwardAssetsList']);


    Route::any('help-manual', [ServiceDeskController::class, 'helpManual']);
    Route::any('access-to-variouse-form', [ServiceDeskController::class, 'accessToVariouseForm']);
    Route::any('lodging-tracking-suggetions', [ServiceDeskController::class, 'lodgingTrackingSuggetions']);
    Route::any('suggetions-management', [ServiceDeskController::class, 'suggetionsManagement']);


    Route::any('add-employeess', [EmpController::class, 'AddEmp']);
    Route::any('add-employeess/{id}', [EmpController::class, 'AddEmp']);
    Route::get('update-employeess/{from_cat_id}/{user_id}', [EmpController::class, 'UpdateEmp']);
    Route::get('delete-employees/{id}', [EmpController::class, 'DeleteEmployees']);
    
    Route::any('create-resource-requirement', [HiringProcessController::class, 'createResourceReq']);
    Route::get('requirement-details', [HiringProcessController::class, 'RequirementDetails']);
    Route::any('candidate-list', [HiringProcessController::class, 'CandidateList']);
    Route::post('candidate-change-status', [HiringProcessController::class, 'CandidateChangeStatus']);
    Route::any('shortlisted-candidate-list', [HiringProcessController::class, 'ShortlistedCandidateList']);
    Route::get('schedule-interview/{id}', [HiringProcessController::class, 'ScheduleInterview']);
    Route::post('save-interview', [HiringProcessController::class, 'SaveInterview']);
    Route::any('interview-hiring-status', [HiringProcessController::class, 'InterviewHiringStatus']);
    Route::get('update-hiring-status/{id}', [HiringProcessController::class, 'InterviewHiringStatus']);
    Route::get('delete-hiring-status/{id}', [HiringProcessController::class, 'DeleteHiringStatus']);
    Route::get('onboard-candidate-documents/{id}', [HiringProcessController::class, 'OnboardCandidateDocuments']);
    Route::any('interview-hiring-status-approval', [HiringProcessController::class, 'InterviewHiringStatusApproval']);
    Route::post('hiring-status-approval', [HiringProcessController::class, 'HiringStatusApproval']);
});

/*----------VIKAS CODE START HIRING PROCESS NEW RULES----------------*/

 Route::any('send-hiring-request-to-hr', [HiringProcessController::class, 'SendHiringRequestToHr']);
    Route::post('save-send-hiring-request-to-hr', [HiringProcessController::class, 'SaveSendHiringRequestToHr']);
    Route::any('hr-send-request-list', [HiringProcessController::class, 'HrSendRequestList']);
    Route::get('request-send-to-hr/{id}', [HiringProcessController::class, 'RequestSendToHr']);
    Route::post('save-request-send-to-hr', [HiringProcessController::class, 'SaveRequestSendToHr']);
    Route::any('candidate-common-profile-list', [HiringProcessController::class, 'CandidateCommonProfileList']);
    Route::get('candidate-common-profile-details/{id}', [HiringProcessController::class, 'CandidateCommonProfileDetails']);
    Route::any('track-hiring-status-list', [HiringProcessController::class, 'TrackHiringStatusList']);
    Route::any('prepare-candidate-offer-letter', [OfferLetterController::class, 'PrepareCandidateOfferLetter']);
    Route::post('save-prepare-candidate-offer-letter', [OfferLetterController::class, 'SavePrepareCandidateOfferLetter']);
    Route::any('prepared-candidate-offer-letter-list', [OfferLetterController::class, 'PreparedCandidateOfferLetterList']);
    Route::any('document-master', [HiringProcessController::class, 'DocumentMaster']);
    Route::any('add-document-master', [HiringProcessController::class, 'DocumentMaster']);
    Route::get('update-document-master/{id}', [HiringProcessController::class, 'DocumentMaster']);
    Route::get('delete-document-master/{id}', [HiringProcessController::class, 'DeleteDocumentMaster']);
    Route::get('candidate-common-profile-share-link/{id}', [HiringProcessController::class, 'CandidateCommonProfileShareLink']);

/*----------VIKAS CODE END HIRING PROCESS NEW RULES----------------*/

/* User route   */
    Route::middleware(['auth', 'user-access:user'])->group(function () {
    Route::get('attendance-list', [AttendanceController::class, 'AttendanceDetails'])->name('attendance.list');
    Route::any('take-leave', [LeaveController::class, 'TakeLeave'])->name('take.leave');
    Route::get('leave-history', [LeaveController::class, 'LeaveHistory'])->name('leave-history');
    Route::get('self/{url}', [SelfServiceController::class, 'Self']);



    Route::any('/add-employee', [UserEmployeeController::class, 'AddEmployee'])->name('add.employee');
    Route::any('/update-employee', [UserEmployeeController::class, 'UpdateEmployee']);
    Route::any('/add-contact', [UserContactController::class, 'AddContact'])->name('add.contact');
    Route::any('/update-contact', [UserContactController::class, 'UpdateContact']);
    Route::any('/add-education', [UserEducationController::class, 'AddEducation'])->name('add.education');
    Route::any('/update-education', [UserEducationController::class, 'UpdateEducation']);
    Route::any('/delete-education/{id}', [UserEducationController::class, 'DeleteEducation']);
    Route::any('/add-bank', [UserBankController::class, 'AddBank'])->name('add.bank');
    Route::any('/update-bank', [UserBankController::class, 'UpdateBank']);
    Route::any('/add-company', [UserCompanyController::class, 'AddCompany'])->name('add.company');
    Route::any('/update-company', [UserCompanyController::class, 'UpdateCompany']);
    Route::any('/delete-emp-company/{id}', [UserCompanyController::class, 'DeleteEmpCompany']);
    Route::any('/add-document', [UserDocumentController::class, 'AddDocument'])->name('add.document');
    Route::any('/update-document', [UserDocumentController::class, 'UpdateDocument']);
    Route::any('/delete-document/{id}', [UserDocumentController::class, 'DeleteDocument']);
    Route::any('/add-timesheet', [UserTimesheetController::class, 'AddTimesheet'])->name('add.timesheet');
    Route::any('/view-timesheet', [UserTimesheetController::class, 'ViewTimesheet'])->name('view.timesheet');
    Route::any('assets-request', [UserAssetsController::class, 'AssetsRequest']);
    Route::get('candidate-hiring-request', [SelfServiceController::class, 'CandidateHiringRequest']);
    Route::post('update-hiring-status', [SelfServiceController::class, 'UpdateHiringStatus']);
    

    // Route::any('/add-letter', [UserLetterMasterController::class, 'AddLetter'])->name('add.letter');
    // Route::any('/add-officer-signature', [UserOfficerSignatureController::class, 'AddOfficerSignature'])->name('add.officer-signature');
    // Route::any('/add-letter-template', [UserLetterTemplateController::class, 'AddLetterTemplate'])->name('add.letter-template');
    // Route::any('/add-map-letter-template', [UserMapLetterTemplateController::class, 'AddMapLetterTemplate'])->name('add.map-letter-template');
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
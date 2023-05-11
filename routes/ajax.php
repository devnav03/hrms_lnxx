<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AjaxController;

Route::prefix('ajax')->group(function () {
    Route::get('send-push-notification', [AjaxController::class, 'SendPushNotification']);
    Route::post('store-token', [AjaxController::class, 'StoreToken']);
    Route::post('check-email', [AjaxController::class, 'CheckEmail']);
    Route::post('check-username', [AjaxController::class, 'CheckUsername']);
    Route::post('get-state', [AjaxController::class,'GetState']);
    Route::post('get-assign-activity', [AjaxController::class,'GetAssignActivity']);
    Route::post('get-city', [AjaxController::class,'GetCity']);
    Route::post('get-activity', [AjaxController::class,'GetActivity']);
    Route::post('mark-attendance', [AjaxController::class,'MarkAttendance']);
    Route::post('get-leave-reason', [AjaxController::class,'GetLeaveReason']);
    Route::get('view-timesheet', [AjaxController::class,'ViewTimesheet']);
    
    /* list data   */
    Route::get('organisation-details', [AjaxController::class, 'OrganisationDetails']);
    Route::get('employee-details', [AjaxController::class, 'EmployeeDetails']);
    Route::get('employee-attendances', [AjaxController::class,'EmployeeAttendances']);
    Route::any('get-employee-attendance-details', [AjaxController::class,'GetEmployeeAttendanceDetails']);
    Route::get('all-employee-attendances', [AjaxController::class,'AllEmployeeAttendances']);
    Route::get('employee-leaves', [AjaxController::class,'EmployeeLeaves']);
    Route::post('get-timesheet-data', [AjaxController::class,'GetTimesheetData']);
    Route::get('get-source-masters', [AjaxController::class,'GetSourceMasters']);
    Route::post('get-status-source-masters', [AjaxController::class,'GetStatusSourceMasters']);
    Route::post('get-status-project', [AjaxController::class,'GetStatusProject']);
    Route::get('get-assign-task', [AjaxController::class,'GetAssignTask']);
    Route::any('get-notice-period', [AjaxController::class,'GetNoticeMasters']);
    Route::get('get-education-master', [AjaxController::class,'GetEducationMasters']);
    Route::any('get-employee-attendance-data', [AjaxController::class,'GetEmployeeAttendanceData']);
    
    Route::post('get-task-against-department', [AjaxController::class,'GetTaskByDepartment']);
    
    Route::any('get-employee-leave-data', [AjaxController::class,'GetEmployeeLeaveData']);
    Route::any('get-emp-approved-leave-data', [AjaxController::class,'GetEmpApprovedLeaveData']);
    Route::any('get-emp-reject-leave-data', [AjaxController::class,'GetEmpRejectLeaveData']);
    Route::any('view-emp-leave-leave-data', [AjaxController::class,'ViewEmpLeaveData']);
    
    Route::any('get-list-leave', [AjaxController::class, 'GetListLeave']);
    Route::any('search-employee-name', [AjaxController::class, 'searchEmpName']);
    
    /*----START VIKAS ROUTES-----*/
    Route::get('get-header-footer-template-masters', [AjaxController::class,'GetHeaderFooterTemplateMasters']);  
    Route::post('get-status-header-footer-template', [AjaxController::class, 'GetStatusHeaderFooterTemplate']);
    Route::any('view-header-footer-data', [AjaxController::class,'ViewHeaderFooterData']);
    /*-----END VIKAS ROUTES----*/

    Route::any('view-emp-timesheet', [AjaxController::class,'ViewEmpTimesheet']);
    Route::get('get-form-engine-masters', [AjaxController::class,'GetFormEngineMasters']);
    Route::get('get-office-masters', [AjaxController::class,'GetOfficeMasters']);
    Route::get('get-shift-masters', [AjaxController::class,'GetShiftMasters']);
    Route::get('get-leave-masters', [AjaxController::class,'GetLeaveMasters']);
    Route::any('get-emp-type-master', [AjaxController::class, 'GetEmpTypeMaster']);
    
    Route::any('email-template-status', [AjaxController::class, 'emailTemplateStatus']);
    Route::any('email-template-setting', [AjaxController::class, 'emailTemplateSetting']);
    Route::any('sms-template-setting', [AjaxController::class, 'smsTemplateSetting']);
    Route::any('notification-template-setting', [AjaxController::class, 'notificationTemplateSetting']);

    Route::any('sms-template-status', [AjaxController::class, 'smsTemplateStatus']);
    Route::any('notification-template-status', [AjaxController::class, 'notificationTemplateStatus']);
    Route::any('get-email-template-list', [AjaxController::class,'GetEmailTemplateList']);
    Route::post('get-status-email-template', [AjaxController::class,'GetStatusEmailTemplate']);
    Route::any('get-sms-template-list', [AjaxController::class,'GetSMSTemplateList']);
    
    Route::any('get-template-masters-data', [AjaxController::class,'getTemplateMastersData']);

    Route::post('get-status-sms-template', [AjaxController::class,'GetStatusSMSTemplate']);
    Route::any('get-notification-template-list', [AjaxController::class,'GetNotificationemplateList']);
    Route::post('get-status-notification-template', [AjaxController::class,'GetStatusNotificationemplate']);
    Route::post('header-template', [AjaxController::class,'HeaderTemplate']);
    Route::any('view-job-details', [AjaxController::class, 'ViewJobDetails']);
    Route::any('get-job-title', [AjaxController::class,'GetJobTitle']);
    Route::any('fetch-requirement-details', [AjaxController::class,'FetchRequirementDetails']);
    
    /* User list data   */
    Route::get('user-details', [AjaxController::class, 'UserDetails']);
    Route::get('user-contact', [AjaxController::class, 'UserContact']);
    Route::get('user-document', [AjaxController::class, 'UserDocument']);
    Route::get('user-education', [AjaxController::class, 'UserEducation']);
    Route::get('user-bank', [AjaxController::class, 'UserBank']);
    Route::get('user-company', [AjaxController::class, 'UserCompany']);
    Route::get('user-assets-request-list', [AjaxController::class, 'UserAssetsRequestList']);
    Route::any('view-assets-data', [AjaxController::class, 'viewAssetsData']);
    
    /* User Letter Management list data   */
    Route::get('user-letter-list', [AjaxController::class, 'UserLetterList']);
    Route::get('user-officer-signature-list', [AjaxController::class, 'UserOfficerSignatureList']);
    Route::get('user-letter-template-list', [AjaxController::class, 'UserLetterTemplateList']);
    Route::get('user-map-letter-template-list', [AjaxController::class, 'UserMapLetterTemplateList']);
    Route::get('get-reporting-list', [AjaxController::class, 'GetReportingList']);
    Route::get('get-parent-department/{id}', [AjaxController::class, 'GetParentDepartment']);
    Route::get('get-parent-position/{ofice_id}/{department_id}', [AjaxController::class, 'GetParentPosition']);
    Route::post('get-status-department', [AjaxController::class, 'GetStatusDepartment']);
    Route::post('get-status-office', [AjaxController::class, 'GetStatusOffice']);
    Route::post('get-status-position', [AjaxController::class, 'GetStatusPosition']);
    Route::post('get-status-notice', [AjaxController::class, 'GetStatusNotice']);
    Route::post('get-default-notice', [AjaxController::class, 'GetDefaultNotice']);
    Route::post('get-default-education', [AjaxController::class, 'GetDefaultEducation']);
    Route::post('get-status-form', [AjaxController::class, 'GetStatusForm']);
    Route::post('get-status-education', [AjaxController::class, 'GetStatusEducation']);
    Route::any('view-department-data', [AjaxController::class,'ViewDepartmentData']);
    Route::any('view-office-data', [AjaxController::class,'ViewOfficeData']);
    Route::any('get-department-name', [AjaxController::class,'GetDepartmentName']);
    
    Route::get('get-employee-all-details/{id}', [AjaxController::class, 'GetEmployeeAllDetails']);
    Route::post('get-letter-preview', [AjaxController::class, 'GetLetterPreview']);
    Route::get('get-project-list', [AjaxController::class, 'GetProjectMaster']);
    Route::post('save-project-activities', [AjaxController::class, 'SaveProjectActivities']);
    Route::get('get-activities-list/{id}', [AjaxController::class, 'GetActivitiesList']);
    Route::get('delete-project-activities/{id}', [AjaxController::class, 'DeleteProjectActivities']);
    Route::post('get-reporting', [AjaxController::class, 'GetReporting']);
    Route::post('get-designation', [AjaxController::class, 'GetDesignation']);
    Route::get('get-shift-type', [AjaxController::class,'GetShiftType']);
    
    Route::post('get-leave-type', [AjaxController::class,'GetLeaveType']);
    Route::post('get-employee-against-department', [AjaxController::class,'GetEmployeeByDepartment']);
    Route::post('get-employee-against-position', [AjaxController::class,'GetEmployeeByPosition']);
    
    Route::get('delete-employees/{id}', [AjaxController::class, 'DeleteEmployees']);
    Route::post('view-emp-assign-pro', [AjaxController::class,'ViewEmpAssignPro']);
    
    
/*    Route::post('get-leave', [AjaxController::class,'GetLeave']);
    Route::get('get-leave-flow', [AjaxController::class,'GetLeaveFlow']);
    Route::post('save-flow-name', [AjaxController::class,'SaveFlowName']);
    Route::post('save-approval-flow', [AjaxController::class,'SaveApprovalFlow']);
    Route::post('save-all-approval-flow', [AjaxController::class,'SaveAllApprovalFlow']);

    Route::post('save-authority-admin', [AjaxController::class,'SaveAuthorityAdmin']);
    Route::post('save-settings', [AjaxController::class,'SaveSettings']);
    Route::post('get-leave-flow-data', [AjaxController::class,'GetLeaveFlowData']);
    Route::post('get-flow-data', [AjaxController::class,'GetFlowData']);
    Route::post('get-leave-approval-flow-data', [AjaxController::class,'GetLeaveApprovalFlowData']); 
    Route::post('get-authority-approval-flow-data', [AjaxController::class,'GetAuthorityApprovalFlowData']); 
    Route::post('get-status-flow-data', [AjaxController::class, 'GetStatusFlowData']);*/


 
    Route::post('get-leave', [AjaxController::class,'GetLeave']);
    Route::get('get-leave-flow', [AjaxController::class,'GetLeaveFlow']);
    Route::post('save-flow-name', [AjaxController::class,'SaveFlowName']);
    Route::post('save-approval-flow', [AjaxController::class,'SaveApprovalFlow']);
    Route::post('save-all-approval-flow', [AjaxController::class,'SaveAllApprovalFlow']);

    Route::post('save-authority-admin', [AjaxController::class,'SaveAuthorityAdmin']);
    Route::post('save-settings', [AjaxController::class,'SaveSettings']);
    Route::post('get-leave-flow-data', [AjaxController::class,'GetLeaveFlowData']);
    Route::post('get-flow-data', [AjaxController::class,'GetFlowData']);
    Route::post('get-root-flow-data', [AjaxController::class,'GetRootFlowData']);

    /*-------NEW URL VIKAS 07-04-23-----------*/

    Route::post('get-flow-records', [AjaxController::class,'GetFlowRecords']);

    /*----End----*/
    Route::post('get-leave-approval-flow-data', [AjaxController::class,'GetLeaveApprovalFlowData']); 
    Route::post('get-authority-approval-flow-data', [AjaxController::class,'GetAuthorityApprovalFlowData']); 
    Route::post('get-status-flow-data', [AjaxController::class, 'GetStatusFlowData']);
    
    /*--===================START REPORT MASTER======================--*/
    Route::post('get-emp-office', [AjaxController::class, 'GetEmpOffice']);
    Route::post('get-emp-department', [AjaxController::class, 'GetEmpDepartment']);
    Route::post('get-daily-attendence', [AjaxController::class, 'GetDailyAttendence']);
    /*--=================== END REPORT MASTER ======================--*/
    
    /*----------------START VIKAS HIRING ROUTES (07-05-23)--------------*/
        
    Route::post('get-documents', [AjaxController::class,'GetDocuments']);
    Route::post('get-scanned-documents', [AjaxController::class,'GetScannedDocuments']);
    Route::get('get-candidate-all-hiring-details/{id}', [AjaxController::class, 'GetCandidateAllHiringDetails']);
    Route::post('get-candidate-name', [AjaxController::class,'GetCandidateName']);
    # Route::get('get-candidate-full-profile/{id}', [AjaxController::class,'GetCandidateFullProfile']);
    Route::post('upload-offer-letter-document', [AjaxController::class,'UploadOfferLetterDocument']);
    

    Route::post('upload-lc-mol', [AjaxController::class,'UploadLcMol']);
    Route::post('upload-lc-mol-signed-copy', [AjaxController::class,'uploadLcMolSignedCopy']);

    Route::post('upload-evisa-send-candidate', [AjaxController::class, 'UploadeVisaSendCandidate']);
    Route::post('upload-eid-send-candidate', [AjaxController::class, 'UploadEidSendCandidate']);
    

    Route::post('upload-medical-reports-send-candidate', [AjaxController::class, 'UploadMedicalReportsSendCandidate']);

    Route::post('upload-required-doc', [AjaxController::class,'UploadRequiredDoc']);
    Route::post('upload-signed-doc', [AjaxController::class,'UploadSignedDoc']);
    Route::post('upload-document-for-visa-approval', [AjaxController::class,'UploadDocumentForVisaApproval']);  

    Route::post('send-pro-request-for-lc-mol', [AjaxController::class,'SendProRequestForLcMol']); 

    Route::post('send-pro-request-for-evisa-process', [AjaxController::class, 'SendProRequestForEvisaProcess']);  

   Route::post('send-pro-request-for-eid-process', [AjaxController::class, 'SendProRequestForEidProcess']); 

    Route::post('send-medical-test-appointment', [AjaxController::class, 'SendMedicalTestAppointment']); 

/*----------------END VIKAS HIRING ROUTES (07-05-23)--------------*/

    Route::get('hiring-process-status/{id}', [AjaxController::class,'HiringProcessStatus']);
    Route::post('get-meeting-linkdata', [AjaxController::class,'GetMeetingLinkdata']);
    Route::post('upload-status-document', [AjaxController::class,'UploadStatusDocument']);
    Route::post('get-uploaded-document-status', [AjaxController::class,'GetUploadedDocumentStatus']);
    Route::post('remove-documet', [AjaxController::class,'RemoveDocumet']);
    Route::post('employee-against-user', [AjaxController::class,'EmployeeAgainstUser']);
    Route::post('update-users-status', [AjaxController::class,'UpdateUsersStatus']);
    
    Route::post('update-organization-status', [AjaxController::class,'UpdateOrganizationStatus']);
    Route::get('get-orgnisation-category/{id}', [AjaxController::class,'GetOrgnisationCategory']);
    Route::post('post-sortable', [AjaxController::class,'PostSortable']);
    Route::any('otp', [AjaxController::class,'OTPSend']);
    Route::any('generate', [AjaxController::class,'generate']);
});


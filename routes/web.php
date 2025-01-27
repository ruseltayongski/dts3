<?php

use App\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReleaseController;
use App\Http\Controllers\PrintLogsController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\TevController;
use App\Http\Controllers\BillsController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\OnlineController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\JustificationController;
use App\Http\Controllers\OfficeOrderController;
use App\Http\Controllers\ActivityWorksheetController;
use App\Http\Controllers\GeneralDocument;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\Feedback1Controller;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\MailLetterIncomingController;
use App\Http\Controllers\AppLeaveController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\AppendController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MaifController;
use Illuminate\Support\Facades\Session;

Route::get('document/trackMaif/{route_no}',[MaifController::class,'track'])->withoutMiddleware(['auth']);
Route::get('document/trackPO/{route_no}',[MaifController::class,'trackPO'])->withoutMiddleware(['auth']);
Route::get('document/dv_no/{dv_no}/{route_no}/{user}',[MaifController::class,'dv_no'])->withoutMiddleware(['auth']);
Route::get('document/ors_no/{ors_no}/{route_no}/{user}',[MaifController::class,'ors_no'])->withoutMiddleware(['auth']);
Route::get('document/paid/{route_no}/{user}',[MaifController::class,'paid'])->withoutMiddleware(['auth']);
Route::get('/login_jwt',[HomeController::class, 'jwt'])->withoutMiddleware(['auth']);
Route::get('/flush-session-pis',[HomeController::class, 'flushSessionPis'])->withoutMiddleware(['auth']);

Route::auth();

// Home
Route::get('/', [HomeController::class, 'index']);

// TEST EXTRACT EXCEL document logs
Route::get('excel', function () {
    $file_name = "logs.xls";
    return response()->view('logs.all')
        ->header("Content-Type", "application/xls")
        ->header("Content-Disposition", "attachment; filename=$file_name")
        ->header("Pragma", "no-cache")
        ->header("Expires", "0");
});

// Section Logs
Route::get('section-logs', function () {
    $file_name = "section_logs.xls";
    return response()->view('logs.all')
        ->header("Content-Type", "application/xls")
        ->header("Content-Disposition", "attachment; filename=$file_name")
        ->header("Pragma", "no-cache")
        ->header("Expires", "0");
});

Route::get('home', [HomeController::class, 'index']);
Route::get('home/chart', [HomeController::class, 'chart']);

Route::get('document', [DocumentController::class, 'index']);
Route::post('document', [DocumentController::class, 'search']);

// Route::get('document/accept', 'DocumentController@accept')->middleware('access');
Route::get('document/accept', [DocumentController::class, 'accept']);
Route::get('document/destroy/{route_no}', [DocumentController::class, 'cancelRequest']);

Route::get('document/route_no',[DocumentController::class,'route_no']); //joy
Route::post('document/accept', [DocumentController::class, 'saveDocument']); //for manual accepting
Route::get('document/accept/{id}', [DocumentController::class, 'updateDocument']); //for button accepting

Route::get('document/info/{route}', [DocumentController::class, 'show']);
Route::get('document/info/{route}/{doc_type}', [DocumentController::class, 'show']);
Route::get('document/removepending/{id}', [DocumentController::class, 'removePending']);
Route::get('document/removeOutgoing/{id}', [DocumentController::class, 'removeOutgoing']);
Route::get('document/removeIncoming/{id}', [DocumentController::class, 'removeIncoming']);
Route::get('document/track/{route_no}', [DocumentController::class, 'track']);
Route::get('document/list', [AdminController::class, 'allDocuments']);
Route::post('document/list', [AdminController::class, 'searchDocuments']);
Route::post('document/update', [DocumentController::class, 'update']);
Route::get('document/create/{type}', [DocumentController::class, 'formDocument']);
Route::post('document/create', [DocumentController::class, 'createDocument']);
Route::get('document/viewPending', [DocumentController::class, 'countPendingDocuments']);

Route::match(['GET', 'POST'], 'document/pending', [DocumentController::class, 'allPendingDocuments']);
Route::post('document/pending/return', [DocumentController::class, 'returnDocument']);
Route::post('document/pending/accept', [DocumentController::class, 'acceptDocument']);

Route::post('document/release', [ReleaseController::class, 'addRelease']);
Route::get('document/report/{id}', [ReleaseController::class, 'addReport']);
Route::get('document/report/{id}/{cancel}', [ReleaseController::class, 'addReport']);
Route::get('document/report/{id}/{cancel}/{status}', [ReleaseController::class, 'addReport']);

Route::get('document/alert/{level}/{id}', [ReleaseController::class, 'alert']);
Route::get('reported', [ReleaseController::class, 'viewReported']);

Route::get('getsections/{id}', [ReleaseController::class, 'getSections']);
Route::get('document/doctype/{doctype}', function ($doctype) {
    return DocumentController::docTypeName($doctype);
});
Route::get('document/doctype1/{route_no}',[MaifController::class, 'docType']);
// FOR ACCOUNTING SECTION
Route::get('accounting/accept', [AccountingController::class, 'accept']);
Route::post('accounting/accept', [AccountingController::class, 'save']);

//FOR BUDGET SECTION
Route::get('budget/accept', [BudgetController::class, 'accept']);
Route::post('budget/accept', [BudgetController::class, 'save']);

Route::get('document/filter', [FilterController::class, 'index']);
Route::post('document/filter', [FilterController::class, 'update']);

Route::get('document/received', [DocumentController::class, 'receivedDocument']);
Route::post('document/received', [DocumentController::class, 'receivedDocument']);

Route::get('document/logs', [DocumentController::class, 'logsDocument']);
Route::post('document/logs', [DocumentController::class, 'searchLogs']);
Route::get('document/section/logs', [DocumentController::class, 'sectionLogs']);
Route::post('document/section/logs', [DocumentController::class, 'searchSectionLogs']);

Route::get('form/salary', [SalaryController::class, 'index']);
Route::post('form/salary', [SalaryController::class, 'store']);

Route::get('form/tev', [TevController::class, 'index']);
Route::post('form/tev', [TevController::class, 'store']);

Route::get('form/bills', [BillsController::class, 'index']);
Route::post('form/bills', [BillsController::class, 'store']);

Route::get('pdf/v1/{size}/{orientation}', function ($size, $orientation) {
    $display = view("pdf.pdf", [
        'size' => $size,
        'orientation' => $orientation
    ]);
    $pdf = App::make('dompdf.wrapper');
    $pdf->loadHTML($display);
    return $pdf->setPaper($size, $orientation)->stream();
});

// PRINT LOGS
Route::get('pdf/track', [PrintLogsController::class, 'printTrack']);
Route::get('pdf/logs/{doc_type}', [PrintLogsController::class, 'printLogs']);

// PRINT REPORT
Route::get('report', [AdminController::class, 'report']);
Route::get('reportedDocuments/{year}', [AdminController::class, 'reportedDocuments']);


Route::get('report/logs/section', [PrintLogsController::class,'sectionLogs']);
Route::get('sectionTracking/{sectionId}/{year}/{month}', [PrintLogsController::class,'sectionTracking']);

// ONLINE
Route::get('online', [OnlineController::class, 'online']);

// LOGOUT
Route::get('logout', function () {
    $user = Auth::user();
    if (isset($user)) {
        $id = $user->id;
        SystemController::logDefault('Logged Out');
        Auth::logout();
        User::where('id', $id)->update(['status' => 0]);
    }
    Session::flush();
    return redirect('login');
});
// ->middleware('web')

// rusel
// PURCHASE REQUEST/REGULAR SUPPLY
Route::get('prr_supply_form', [PurchaseRequestController::class, 'prr_supply_form']);
Route::post('prr_supply_post', [PurchaseRequestController::class, 'prr_supply_post']);
Route::get('prr_supply_pdf', [PurchaseRequestController::class, 'prr_supply_pdf']);
Route::get('prr_supply_pdf/{paperSize}', [PurchaseRequestController::class, 'prr_supply_pdf']);
Route::get('prr_supply_page', [PurchaseRequestController::class, 'prr_supply_page']);
Route::post('prr_supply_update', [PurchaseRequestController::class, 'prr_supply_update']);
Route::get('prr_supply_history', [PurchaseRequestController::class, 'prr_supply_history']);
Route::get('prr_supply_append', [PurchaseRequestController::class, 'prr_supply_append']);
Route::post('prr_supply_remove', [PurchaseRequestController::class, 'prr_supply_remove']);
Route::get('prr_supply_info/{route_no}', [PurchaseRequestController::class, 'prr_supply_info']);

// PURCHASE REQUEST/REGULAR MEAL
Route::get('prr_meal_form', [PurchaseRequestController::class, 'prr_meal_form']);
Route::post('prr_meal_post', [PurchaseRequestController::class, 'prr_meal_post']);
Route::get('prr_meal_append', [PurchaseRequestController::class, 'prr_meal_append']);
Route::get('prr_meal_page', [PurchaseRequestController::class, 'prr_meal_page']);
Route::get('prr_meal_history', [PurchaseRequestController::class, 'prr_meal_history']);
Route::post('prr_meal_update', [PurchaseRequestController::class, 'prr_meal_update']);
Route::get('prr_meal_pdf', [PurchaseRequestController::class, 'prr_meal_pdf']);
Route::get('prr_meal_category', [PurchaseRequestController::class, 'prr_meal_category']);

// PURCHASE REQUEST/ADVANCE
Route::get('prCashAdvance', [PurchaseRequestController::class, 'prCashAdvance']);
Route::post('prCashAdvance', [PurchaseRequestController::class, 'savePrCashAdvance']);

// PURCHASE ORDER
Route::get('PurchaseOrder', [PurchaseOrderController::class, 'PurchaseOrder']);
Route::post('PurchaseOrder', [PurchaseOrderController::class, 'PurchaseOrderSave']);

// DIVISION
Route::get('division', [DivisionController::class, 'division']);
Route::get('addDivision', [DivisionController::class, 'addDivision']);
Route::post('addDivision', [DivisionController::class, 'addDivisionSave']);
Route::get('deleteDivision/{id}', [DivisionController::class, 'deleteDivision']);
Route::get('updateDivision/{id}/{head}', [DivisionController::class, 'updateDivision']);
Route::post('updateDivisionSave', [DivisionController::class, 'updateDivisionSave']);
Route::post('searchDivision', [DivisionController::class, 'searchDivision']);
Route::get('searchDivision', [DivisionController::class, 'searchDivisionSave']);

// SECTION
Route::get('section', [SectionController::class, 'section']);
Route::post('section', [SectionController::class, 'searchSection']);
Route::get('addSection', [SectionController::class, 'addSection']);
Route::post('addSection', [SectionController::class, 'addSectionSave']);
Route::get('deleteSection/{id}', [SectionController::class, 'deleteSection']);
Route::get('updateSection/{id}/{division}/{head}', [SectionController::class, 'updateSection']);
Route::post('updateSectionSave', [SectionController::class, 'updateSectionSave']);
Route::post('searchSection', [SectionController::class, 'searchSection']);
Route::get('searchSection', [SectionController::class, 'searchSectionSave']);

// CHECK SECTION
Route::get('checkSection', [SectionController::class, 'checkSection']);
Route::get('checkSectionUpdate', [SectionController::class, 'checkSectionUpdate']);

// CHECK DIVISION
Route::get('checkDivision', [DivisionController::class, 'checkDivision']);
Route::get('checkDivisionUpdate', [DivisionController::class, 'checkDivisionUpdate']);

// GET DESIGNATION
Route::get('getDesignation/{id}', [PurchaseRequestController::class, 'getDesignation']);

// APPOINTMENT
/*Route::get('appointment', 'AppointmentController@appointment');
Route::post('appointment', 'AppointmentController@appointmentSave');*/

// PR PDF
Route::get('pdf_pr', [PurchaseRequestController::class, 'prr_pdf']);

// CALENDAR
Route::get('calendar', function () {
    return view('calendar.calendar');
});
Route::get('calendar_form', function () {
    return view('calendar.calendar_form');
});
Route::post('calendar_save', [PurchaseRequestController::class, 'calendar']);
Route::get('calendar_event', function () {
    return \App\Calendar::all(['title', 'start', 'end', 'backgroundColor', 'borderColor']);
});


// Sending Email
Route::get('sendemail', function () {
    $data = array(
        'name' => "Learning Laravel",
    );

    \Illuminate\Support\Facades\Mail::send('emails.welcome', $data, function ($message) {
        $message->from('nevermoretayong@gmail.com', 'Learning Laravel');
        $message->to('ruseltayong@gmail.com')->subject('Learning Laravel test email');
    });

    return "Your email has been sent successfully";
});

// Routing Slip
Route::get('/form/routing/slip', [RoutingController::class, 'routing_slip']);
Route::post('/form/routing/slip', [RoutingController::class, 'create']);

// Incoming Letter
Route::match(['get', 'post'], '/form/incoming/letter', [MailLetterIncomingController::class, 'incoming_letter']);

// APP LEAVE CDO
Route::get('/form/application/leave', [AppLeaveController::class, 'index']);
Route::post('/form/application/leave', [AppLeaveController::class, 'create']);

// JUSTIFICATION LETTER
Route::match(['get', 'post'], '/form/justification/letter', [JustificationController::class, 'index']);

// OFFICE ORDER
Route::match(['get', 'post'], '/form/office-order', [OfficeOrderController::class, 'create']);

// ACTIVITY WORKSHEET
Route::get('/form/worksheet', [ActivityWorksheetController::class, 'index']);
Route::post('/form/worksheet', [ActivityWorksheetController::class, 'create']);

// GENERAL DOC
Route::match(['get', 'post'], 'general', [GeneralDocument::class, 'create']);

// CHANGE PASSWORD
Route::get('/change/password', [PasswordController::class, 'change_password']);
Route::post('/change/password', [PasswordController::class, 'save_changes']);

Route::get('/form/incoming/letter', [MailLetterIncomingController::class, 'incoming_letter']);
Route::get('/session', [DocumentController::class, 'session']);

// ADMIN CONTROLLER
// users
Route::get('users', [AdminController::class, 'users']);
Route::match(['get', 'post'], 'user/new', [AdminController::class, 'user_create']);
Route::match(['get', 'post'], 'user/edit', [AdminController::class, 'user_edit']);
Route::get('/get/section', [AdminController::class, 'section']);
Route::get('/search/user', [AdminController::class, 'search']);
Route::post('/user/remove', [AdminController::class, 'remove']);
Route::get('/check/user', [AdminController::class, 'check_user']);

// designation
Route::get('/designation', [DesignationController::class, 'index']);
Route::match(['get', 'post'], '/designation/create', [DesignationController::class, 'create']);
Route::match(['get', 'post'], '/edit/designation', [DesignationController::class, 'edit']);
Route::get('/search/designation', [DesignationController::class, 'search']);
Route::post('/remove/designation', [DesignationController::class, 'remove']);

// feedback
Route::post("sendFeedback", [Feedback1Controller::class, 'sendFeedback']);
Route::match(['get', 'post'], 'feedback', [FeedbackController::class, 'index']);
Route::match(['get', 'post'], 'users/feedback', [FeedbackController::class, 'view_feedback']);
Route::match(['get', 'post'], 'view-feedback', [FeedbackController::class, 'message']);
Route::get('feedback_ok', function () {
    return view('feedback.feedback_ok');
});
Route::post('feedback/action', [FeedbackController::class, 'action']);
Route::get('clear', function () {
    \Illuminate\Support\Facades\Session::flush();
    return redirect('/');
});

Route::get('modal', function () {
    return view('users.modal');
});

Route::get('welcome', function () {
    return view('welcome');
});

// Route::get('res', [PasswordController::class, 'change']);

// Route::get('/migrate', [SystemController::class, 'migrate']);

Route::get('temporary', function () {
    return \App\Dtr_calendar::get(['start'])[0]->start;
});

// TEST CONTROLLER
Route::get('test', [TestController::class, 'test']);
Route::get('append/appendOutgoingDocument/{id}/{route_no}', [AppendController::class, 'appendOutgoingDocument']);

// Route::get('document/csmc/track/{route_no}', [ApiController::class, 'index']);

// report release to
Route::get('count/{year}', [ReportController::class, 'countReleaseTo']);
Route::get('/documents_count', [ReportController::class, 'counter']);
//'reportedDocuments/{year}',

// select-section


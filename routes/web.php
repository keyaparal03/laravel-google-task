<?php

use App\Http\Controllers\GoogleController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

//     $client_id = '534077932058-6t4vag6e772ano6it4qfvh4kpjt2m504.apps.googleusercontent.com';
//             $client_secret = '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';
//             $redirect_uri = "http://127.0.0.1:8000/callback/google";
    
//             $client = new Google_Client();
//             $client->setApplicationName("google_task");
//             $client->setClientId($client_id);
//             $client->setClientSecret($client_secret);
//             $client->setRedirectUri($redirect_uri);
//             $client->setScopes("https://www.googleapis.com/auth/tasks");
//             $client->setScopes("https://www.googleapis.com/auth/tasks.readonly");
//             $client->setScopes(array(Google_Service_Tasks::TASKS_READONLY));

//             $authUrl = $client->createAuthUrl();
    
//             if (isset($_GET['code'])) {
//                 $client->fetchAccessTokenWithAuthCode($_GET['code']);
//                 $_SESSION['access_token'] = $client->getAccessToken();
//                 $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
//                 header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
//             }
    
//             if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
//                 $client->setAccessToken($_SESSION['access_token']);
//             }
    
//             //echo '<a href="' . $authUrl . '">link me</a>';
    
//             $service = new Google_Service_Tasks($client);
    
//             //echo '<pre>';
//             $service->tasklists->listTasklists();

// die;

    return view('welcome');
});
Route::get('auth/google', [GoogleController::class, 'signInwithGoogle']);
Route::get('callback/google', [GoogleController::class, 'callbackToGoogle']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::post('/add-tasklist', [TaskController::class, 'addTask'])->name('addTask');
Route::get('/add-tasklist', [TaskController::class, 'addTaskList']);
Route::get('/delete-tasklist', [TaskController::class, 'deleteTask']);



Route::post('/update-task-to-list', [TaskController::class, 'updateTask'])->name('updateTask');
Route::get('/update-task-to-list/{tasklistid}/{taskid}', [TaskController::class, 'update_task']);

Route::post('/add-task-to-list', [TaskController::class, 'saveTaskToList'])->name('saveTaskToList');
Route::get('/add-task-to-list/{tasklistid}', [TaskController::class, 'addTaskToList']);


Route::get('/tasklists', [TaskController::class, 'index']);
Route::get('/tasklist/{tasklistid}', [TaskController::class, 'tasklist']);


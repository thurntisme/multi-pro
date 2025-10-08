<?php

use App\Controllers\DatabaseController;
use App\Controllers\FileManagerController;
use App\Controllers\Football\FootballPlayerController;
use App\Controllers\Football\FootballUserController;
use App\Controllers\FootballController;
use App\Controllers\PdfReaderController;
use App\Core\Router;
use App\Controllers\LandingController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\TodoController;
use App\Controllers\NoteController;
use App\Controllers\HabitController;
use App\Controllers\IncomeController;
use App\Controllers\ExpenseController;
use App\Controllers\JournalController;
use App\Controllers\SubscriptionController;
use App\Controllers\PlanController;
use App\Controllers\CalendarController;
use App\Controllers\WorkoutController;
use App\Controllers\ProjectController;
use App\Controllers\TaskController;
use App\Controllers\ClientController;
use App\Controllers\EstimationController;
use App\Controllers\ResumeController;
use App\Controllers\ReportController;
use App\Controllers\MaintainWebController;
use App\Controllers\SeoChecklistController;
use App\Controllers\WebDevChecklistController;
use App\Controllers\WebSecureChecklistController;
use App\Controllers\LeadController;
use App\Controllers\WebsiteController;
use App\Controllers\SettingController;
use App\Controllers\NotificationsController;
use App\Controllers\PackageController;
use App\Controllers\ProfileController;

$router = new Router();

$router->get('/', [LandingController::class, 'index']);
$router->get('/login', [AuthController::class, 'renderLogin']);
$router->get('/register', [AuthController::class, 'renderRegister']);

// Dashboard routes
$router->get('/app', [DashboardController::class, 'index']);
$router->get('/app/todo', [TodoController::class, 'index']);
$router->get('/app/note', [NoteController::class, 'index']);
$router->get('/app/habit', [HabitController::class, 'index']);
$router->get('/app/income', [IncomeController::class, 'index']);
$router->get('/app/expense', [ExpenseController::class, 'index']);
$router->get('/app/journal', [JournalController::class, 'index']);
$router->get('/app/subscription', [SubscriptionController::class, 'index']);
$router->get('/app/plan', [PlanController::class, 'index']);
$router->get('/app/calendar', [CalendarController::class, 'index']);
$router->get('/app/workout', [WorkoutController::class, 'index']);
$router->get('/app/project', [ProjectController::class, 'index']);
$router->get('/app/task', [TaskController::class, 'index']);
$router->get('/app/client', [ClientController::class, 'index']);
$router->get('/app/estimation', [EstimationController::class, 'index']);
$router->get('/app/resume', [ResumeController::class, 'index']);
$router->get('/app/report', [ReportController::class, 'index']);
$router->get('/app/maintain-web', [MaintainWebController::class, 'index']);
$router->get('/app/seo-checklist', [SeoChecklistController::class, 'index']);
$router->get('/app/web-dev-checklist', [WebDevChecklistController::class, 'index']);
$router->get('/app/web-secure-checklist', [WebSecureChecklistController::class, 'index']);
$router->get('/app/lead', [LeadController::class, 'index']);
$router->get('/app/website', [WebsiteController::class, 'index']);
$router->get('/app/settings', [SettingController::class, 'index']);
$router->get('/app/notifications', [NotificationsController::class, 'index']);
$router->get('/app/package', [PackageController::class, 'index']);
$router->get('/app/profile', [ProfileController::class, 'index']);
$router->get('/app/pdf-reader', [PdfReaderController::class, 'index']);
$router->get('/app/file-manager', [FileManagerController::class, 'index']);
$router->get('/app/database', [DatabaseController::class, 'index']);
$router->get('/app/football-player', [FootballController::class, 'index']);

// API
$router->get('/api/football/market/list', [FootballPlayerController::class, 'index']);
$router->get('/api/football/club/players', [FootballPlayerController::class, 'getClubPlayers']);
$router->get('/api/football/team', [FootballPlayerController::class, 'getTeamInfo']);
$router->post('/api/football/item/player-new', [FootballPlayerController::class, 'generatePlayer']);
$router->get('/api/football/user', [FootballUserController::class, 'index']);

return $router;

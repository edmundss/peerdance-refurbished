<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlternateNameController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\ChoreographyController;
use App\Http\Controllers\CombinationController;
use App\Http\Controllers\DanceController;
use App\Http\Controllers\DifficultyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\WeeklyChallengeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\DanceFamilyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\SpotifyController;
use App\Http\Controllers\StepController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\WeeklyChallengeController as ControllersWeeklyChallengeController;

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
    return view('welcome');
});

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::prefix('admin')->name('admin.')->group(function () {
    // Route::get('user/datatable', ['as' => 'user.datatable', 'uses' => 'UserController@datatable']);
    Route::get('users/datatable', [AdminUserController::class, 'datatable'])->name('users.datatable');
    Route::resource('users', AdminUserController::class);

    Route::get('weeklyChallenge/datatable', [WeeklyChallengeController::class, 'datatable'])->name('weeklyChallenge.datatable');
    Route::resource('weeklyChallenge', WeeklyChallengeController::class);
});




Route::resource('alternate-names', AlternateNameController::class);

Route::resource('artists', ArtistController::class);

Route::resource('difficulties', DifficultyController::class);

// Route::get('choreographies/{id}/toggle', ['as' => 'choreographies.toggle', 'uses' => 'ChoreographyController@toggle']);
// Route::get('choreographies/select2', ['as' => 'choreographies.select2', 'uses' => 'ChoreographyController@select2']);
// Route::get('choreographies/datatable', ['as' => 'choreographies.datatable', 'uses' => 'ChoreographyController@datatable']);
Route::get('choreographies/{choreography}/toggle', [ChoreographyController::class, 'toggle'])->name('choreographies.toggle');
Route::get('choreographies/select2', [ChoreographyController::class, 'select2'])->name('choreographies.select2');
Route::get('choreographies/datatable', [ChoreographyController::class, 'datatable'])->name('choreographies.datatable');
Route::resource('choreographies', ChoreographyController::class);

// Route::get('combination/{id}/toggle', ['as' => 'combination.toggle', 'uses' => 'CombinationController@toggle']);
// Route::get('combination/toggle', ['as' => 'combination.toggle', 'uses' => 'CombinationController@user_toggle']);
// Route::get('combination/select2', ['as' => 'combination.select2', 'uses' => 'CombinationController@select2']);
// Route::get('combination/datatable', ['as' => 'combination.datatable', 'uses' => 'CombinationController@datatable']);
Route::get('combinations/{combination}/toggle', [CombinationController::class, 'toggle'])->name('combinations.toggle');
Route::get('combinations/toggle', [CombinationController::class, 'userToggle'])->name('combinations.user-toggle');
Route::get('combinations/select2', [CombinationController::class, 'select2'])->name('combinations.select2');
Route::get('combinations/datatable', [CombinationController::class, 'datatable'])->name('combinations.datatable');
Route::resource('combinations', CombinationController::class);

// Route::get('load_comments', array('as' => 'comments.load', 'uses' => 'CommentController@CommentFeed'));
// Route::resource('comment', 'CommentController');
Route::get('comments/load', [CommentController::class, 'load'])->name('comments.load');
Route::resource('comments', CommentController::class);

// Route::get('component/update_order', ['as' => 'component.update_order', 'uses' => 'ComponentController@update_order']);
// Route::get('component/{component}/delete', ['as' => 'component.delete', 'uses' => 'ComponentController@destroy']);
// Route::resource('component', 'ComponentController');
Route::get('components/update-order', [ComponentController::class, 'updateOrder'])->name('components.update-order');
Route::get('components/{component}/delete', [ComponentController::class, 'destroy'])->name('components.delete');
Route::resource('components', ComponentController::class);

// Route::resource('dance_family', 'DanceFamilyController');
Route::resource('dance-families', DanceFamilyController::class);

// Route::get('dance/{id}/toggle', ['as' => 'dance.toggle', 'uses' => 'DanceController@toggle']);
// Route::resource('dance', 'DanceController');
Route::get('dances/{dance}/toggle', [DanceController::class, 'toggle'])->name('dances.toggle');
Route::resource('dances', DanceController::class);


// Route::get('home/get_timeline_elements', ['as' =>'home.get_timeline_elements', 'uses' =>'HomeController@get_timeline_elements']);
// Route::get('/home', 'HomeController@index')->name('home');
Route::get('home/get-timeline-elements', [HomeController::class, 'getTimelineElements'])->name('home.get-timeline-elements');
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Route::resource('permission', 'PermissionController');
Route::resource('permissions', PermissionController::class);


// Route::get('role/update_permissions', array('as'=>'roles.update_permissions', 'uses' => 'RoleController@update_permissions'));
// Route::resource('role', 'RoleController');
Route::get('roles/update-permissions', [RoleController::class, 'updatePermissions'])->name('roles.update-permissions');
Route::resource('roles', RoleController::class);

// Route::get('spotify/song_search', ['as' => 'spotify.song_search', 'uses' => 'SpotifyController@song_search']);
// Route::get('spotify/play', ['as' => 'spotify.play', 'uses' => 'SpotifyController@play']);
// Route::get('spotify/get_playback_info', ['as' => 'spotify.get_playback_info', 'uses' => 'SpotifyController@get_playback_info']);
// Route::get('spotify_callback', ['as' => 'spotify.callback', 'uses' => 'SpotifyController@callback']);
// Route::get('spotify.initial_authorization', ['as' => 'spotify.initial_authorization', 'uses' => 'SpotifyController@initial_authorization']);
Route::get('spotify/song-search', [SpotifyController::class, 'songSearch'])->name('spotify.song-search');
Route::get('spotify/play', [SpotifyController::class, 'play'])->name('spotify.play');
Route::get('spotify/get-playback-info', [SpotifyController::class, 'getPlaybackInfo'])->name('spotify.get-playback-info');
Route::get('spotify/callback', [SpotifyController::class, 'callback'])->name('spotify.callback');
Route::get('spotify/initial-authorization', [SpotifyController::class, 'initialAuthorization'])->name('spotify.initial-authorization');

// Route::get('step/toggle', ['as' => 'step.toggle', 'uses' => 'StepController@user_toggle']);
// Route::get('step/datatable', ['as' => 'step.datatable', 'uses' => 'StepController@datatable']);
// Route::get('step/select2', ['as' => 'step.select2', 'uses' => 'StepController@select2']);
// Route::resource('step', 'StepController');
Route::get('steps/toggle', [StepController::class, 'userToggle'])->name('steps.user-toggle');
Route::get('steps/datatable', [StepController::class, 'datatable'])->name('steps.datatable');
Route::get('steps/select2', [StepController::class, 'select2'])->name('steps.select2');
Route::resource('steps', StepController::class);

// Route::get('song/datatable', ['as' => 'song.datatable', 'uses' => 'SongController@datatable']);
// Route::resource('song', 'SongController');
Route::get('songs/datatable', [SongController::class, 'datatable'])->name('songs.datatable');
Route::resource('songs', SongController::class);

// Route::get('users/update_role', array('as' => 'user.role_update', 'uses' => 'UserController@updateRoles'));
// Route::get('user/{user}/get_expertise', ['as' => 'user.get_expertise', 'uses' => 'UserController@get_expertise']);
// Route::post('user/{user}/upload_avatar', ['as' => 'user.upload_avatar', 'uses' => 'UserController@upload_avatar']);
// Route::post('user/{user}/upload_cover', ['as' => 'user.upload_cover', 'uses' => 'UserController@upload_cover']);
// Route::resource('user', 'UserController');
Route::get('users/update-role', [UserController::class, 'updateRoles'])->name('users.update-role');
Route::get('users/{user}/get-expertise', [UserController::class, 'getExpertise'])->name('users.get-expertise');
Route::post('users/{user}/upload-avatar', [UserController::class, 'uploadAvatar'])->name('users.upload-avatar');
Route::post('users/{user}/upload-cover', [UserController::class, 'uploadCover'])->name('users.upload-cover');
Route::resource('users', UserController::class);


// Route::post('video/component', ['as' => 'video.component', 'uses' => 'VideoController@component']);
// Route::resource('video', 'VideoController');
Route::post('videos/component', [VideoController::class, 'component'])->name('videos.component');
Route::resource('videos', VideoController::class);

// Route::resource('weeklyChallenge', 'WeeklyChallengeController');
Route::resource('weekly-challenges', ControllersWeeklyChallengeController::class);

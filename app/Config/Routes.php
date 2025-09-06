<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rute default homepage Anda
$routes->get('/', 'Home::index');

// Grup Rute untuk Modul Auth
$routes->group('auth', ['namespace' => 'App\Modules\Auth\Controllers'], function ($routes) {
    $routes->get('/', 'UserController::index');
    $routes->get('login', 'UserController::index');
    $routes->post('loginprocess', 'UserController::loginprocess');
    $routes->get('register', 'UserController::register');
    $routes->post('registerprocess', 'UserController::registerprocess');
    $routes->get('logout', 'UserController::logout');
    $routes->get('forgot-password', 'ForgotPasswordController::index');
    $routes->post('forgot-password/send', 'ForgotPasswordController::sendResetLink');
    $routes->get('reset-password', 'ForgotPasswordController::resetPassword');
    $routes->post('reset-password/update', 'ForgotPasswordController::updatePassword');
});

// Penting: Reset default namespace setelah grup modul Auth
$routes->setDefaultNamespace('App\Controllers');

// ===============================================
// Rute yang Dilindungi Filter
// ===============================================

// Rute untuk Dashboard
$routes->group('dashboard', ['filter' => 'auth', 'namespace' => 'App\Modules\Dashboard\Controllers'], function ($routes) {
    $routes->get('/', 'DashboardController::index');
});

// Admin Module
$routes->group('admin', ['filter' => ['auth', 'role:Admin'], 'namespace' => 'App\Modules\Admin\Controllers'], function ($routes) {
    // Manajemen Pengguna
    $routes->get('users', 'UsersController::index');
    $routes->post('users/store', 'UsersController::storeUser');
    $routes->post('users/update/(:num)', 'UsersController::update/$1');
    $routes->post('users/delete', 'UsersController::deleteUser');
    
    // Setting - Role
    $routes->get('setting/role', 'SettingController::role');
    $routes->post('setting/role/create', 'SettingController::createRole');
    $routes->post('setting/role/update', 'SettingController::updateRole');
    $routes->post('setting/role/delete', 'SettingController::deleteRole');
    
    // Setting - Permission & Pages
    $routes->get('setting/permission', 'SettingController::permission');
    $routes->post('setting/permission/update', 'SettingController::updatePermissions');
    $routes->post('setting/page/create', 'SettingController::createPage');
    $routes->post('setting/page/update', 'SettingController::updatePage');
    $routes->post('setting/page/delete', 'SettingController::deletePage');
});

// Modul Ruangan 
$routes->group('ruangan', ['filter' => ['auth', 'role:Admin'], 'namespace' => 'App\Modules\Ruangan\Controllers'], function ($routes) {
    $routes->get('/', 'RuanganController::index');
    $routes->get('create', 'RuanganController::create');
    $routes->post('post', 'RuanganController::post');
    $routes->get('edit/(:num)', 'RuanganController::edit/$1');
    $routes->post('update/(:num)', 'RuanganController::update/$1');
    $routes->delete('delete/(:num)', 'RuanganController::delete/$1');
});

// Modul Lokasi
$routes->group('lokasi', ['filter' => ['auth', 'role:Admin'], 'namespace' => 'App\Modules\Lokasi\Controllers'], function ($routes) {
    $routes->get('/', 'LokasiController::index');
    $routes->get('create', 'LokasiController::create');
    $routes->post('post', 'LokasiController::post');
    $routes->get('edit/(:num)', 'LokasiController::edit/$1');
    $routes->post('update/(:num)', 'LokasiController::update/$1');
    $routes->delete('delete/(:num)', 'LokasiController::delete/$1');
});

// Modules Mechanical 
$routes->group('mechanical', ['filter' => ['auth', 'role:Admin,ME'], 'namespace' => 'App\Modules\Mechanical\Controllers'], function ($routes) {
    $routes->get('/', 'Task::index');
    $routes->get('checklist/(:segment)', 'Task::checklist/$1');
    $routes->get('checklist_task/(:segment)', 'Task::checklist_task/$1');
    $routes->post('saveChecklist/(:segment)', 'Task::saveChecklist/$1');
    $routes->get('checklist_selesai', 'Task::checklistSelesai');
});

// Modules Takmir 
$routes->group('takmir', ['filter' => ['auth', 'role:Admin,Takmir'], 'namespace' => 'App\Modules\Takmir\Controllers'], function ($routes) {
    $routes->get('/', 'Task::index');
    $routes->get('checklist/(:segment)', 'Task::checklist/$1');
    $routes->get('checklist_task/(:segment)', 'Task::checklist_task/$1');
    $routes->post('saveChecklist/(:segment)', 'Task::saveChecklist/$1');
    $routes->get('checklist_selesai', 'Task::checklistSelesai');
});

// Modules Gardener 
$routes->group('gardener', ['filter' => ['auth', 'role:Admin,Gardener'], 'namespace' => 'App\Modules\Gardener\Controllers'], function ($routes) {
    $routes->get('/', 'Task::index');
    $routes->get('checklist/(:segment)', 'Task::checklist/$1');
    $routes->get('checklist_task/(:segment)', 'Task::checklist_task/$1');
    $routes->post('saveChecklist/(:segment)', 'Task::saveChecklist/$1');
    $routes->get('checklist_selesai', 'Task::checklistSelesai');
});

// Modules CS 
$routes->group('cs', ['filter' => ['auth', 'role:Admin,CS'], 'namespace' => 'App\Modules\CS\Controllers'], function ($routes) {
    $routes->get('/', 'Task::index');
    $routes->get('checklist/(:segment)', 'Task::checklist/$1');
    $routes->get('checklist_task/(:segment)', 'Task::checklist_task/$1');
    $routes->post('saveChecklist/(:segment)', 'Task::saveChecklist/$1');
    $routes->get('checklist_selesai', 'Task::checklistSelesai');
});

// Modul Pemetaan
$routes->group('pemetaan', ['filter' => ['auth', 'role:Admin'], 'namespace' => 'App\Modules\Pemetaan\Controllers'], function ($routes) {
    $routes->get('/', 'PemetaanController::index');
    $routes->get('create', 'PemetaanController::create');
    $routes->get('detail/(:num)/(:num)', 'PemetaanController::detail/$1/$2');
    $routes->get('edit/(:num)', 'PemetaanController::edit/$1');
    $routes->get('getRuanganByLokasi/(:any)', 'PemetaanController::getRuanganByLokasi/$1');
    $routes->get('getRuanganByNamaLokasi/(:any)', 'PemetaanController::getRuanganByNamaLokasi/$1');
    $routes->post('pemetaan/delete/(:num)', 'PemetaanController::delete/$1');
    $routes->post('pemetaan/update/(:num)', 'PemetaanController::update/$1');
    $routes->post('store', 'PemetaanController::store');
    $routes->post('storeAktivitas/(:num)/(:num)', 'PemetaanController::storeAktivitas/$1/$2');
    $routes->post('delete/(:num)', 'PemetaanController::delete/$1');
    $routes->post('deleteAktivitas/(:num)', 'PemetaanController::deleteAktivitas/$1');
    $routes->post('deletePemetaan/(:num)/(:num)', 'PemetaanController::deletePemetaan/$1/$2');
    $routes->post('update/(:num)', 'PemetaanController::update/$1');
    $routes->post('updateAktivitas/(:num)/(:num)', 'PemetaanController::updateAktivitas/$1/$2');
});

<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Layout::index');

// route kategori
$routes->get('/kategori', 'Kategori::index');
$routes->post('/kategori', 'Kategori::index');
$routes->get('/kategori/formTambah', 'Kategori::formTambah');
$routes->post('/kategori/formTambah', 'Kategori::formTambah');
$routes->post('/kategori/simpandata', 'Kategori::simpanData');
$routes->post('/kategori/hapus', 'Kategori::hapus');
$routes->post('/kategori/formEdit', 'Kategori::formEdit');
$routes->post('/kategori/updatedata', 'Kategori::updatedata');

// route satuan
$routes->get('/satuan', 'Satuan::index');
$routes->post('/satuan/ambilDataSatuan', 'Satuan::ambilDataSatuan');
$routes->post('/satuan/formTambah', 'Satuan::formTambah');
$routes->post('/satuan/simpandata', 'Satuan::simpanData');
$routes->post('/satuan/hapus', 'Satuan::hapus');
$routes->post('/satuan/formEdit', 'Satuan::formEdit');
$routes->post('/satuan/updatedata', 'Satuan::updatedata');

// route produk
$routes->get('/produk', 'Produk::index');
$routes->post('/produk', 'Produk::index');
$routes->get('/produk/ambilDataKategori', 'Produk::ambilDataKategori');
$routes->post('/produk/ambilDataKategori', 'Produk::ambilDataKategori');
$routes->get('/produk/ambilDataSatuan', 'Produk::ambilDataSatuan');
$routes->post('/produk/ambilDataSatuan', 'Produk::ambilDataSatuan');
$routes->get('/produk/formTambah', 'Produk::formTambah');
$routes->post('/produk/simpandata', 'Produk::simpandata');
$routes->get('/produk/formEdit/(:any)', 'Produk::formEdit/$1');
$routes->post('/produk/updatedata', 'Produk::updatedata');
$routes->post('/produk/hapus', 'Produk::hapus');

// route penjualan
$routes->get('/penjualan', 'Penjualan::index');
$routes->get('/penjualan/input', 'Penjualan::input');
$routes->post('/penjualan/buatFaktur', 'Penjualan::buatFaktur');
$routes->post('/penjualan/dataDetail', 'Penjualan::dataDetail');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

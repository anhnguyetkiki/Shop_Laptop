<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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

Route::get('/', ['as' => 'trang-chu', 'uses' => 'Usercontroller@getIndex']);

//chuyen ngon ngu
Route::get('language/{locale}', function ($locale) {
    Session::put('locale', $locale);
    return redirect()->back();
});

//dang nhap google
Route::get('/login-google', 'TaiKhoan_Controller@login_google');
Route::get('/google/callback', 'TaiKhoan_Controller@callback_google');

//loai san pham & chi tiet san pham & tim kiem & tat ca san pham
Route::get('loai-san-pham-{typesanpham}', ['as' => 'loaisanpham', 'uses' => 'Usercontroller@getLoaiSP']);
Route::get('tim-kiem', ['as' => 'timkiem', 'uses' => 'Usercontroller@postTimKiem']);
Route::post('tim-kiem', ['as' => 'timkiem', 'uses' => 'Usercontroller@postTimKiem']);
Route::post('/autocomplete-ajax', 'Usercontroller@autocomplete_ajax');
Route::get('chi-tiet-san-pham-{id}/{product_slug?}', ['as' => 'chitietsanpham', 'uses' => 'Usercontroller@getChitiet']);
Route::get('san-pham', ['as' => 'allproduct', 'uses' => 'Usercontroller@getAllproduct']);

//lien he & gioi thieu
Route::get('lien-he', ['as' => 'lienhe', 'uses' => 'Usercontroller@getLienHe']);
Route::post('lien-he', ['as' => 'lienhe', 'uses' => 'Usercontroller@postLienHe']);
Route::get('gioi-thieu', ['as' => 'gioithieu', 'uses' => 'Usercontroller@getGioiThieu']);

//ma giam gia
Route::post('/check-coupon', 'Usercontroller@check_coupon');

//gio hang
Route::get('add-to-cart/{id}', ['as' => 'themgiohang', 'uses' => 'Usercontroller@getAddToCart']);
Route::get('del-cart/{id}', ['as' => 'xoagiohang', 'uses' => 'Usercontroller@getDelCart']);

Route::get('shopping-cart', ['as' => 'shoppingcart', 'uses' => 'Usercontroller@getshoppingcart']);
Route::post('pay-order', ['as' => 'payorder', 'uses' => 'Usercontroller@pay_order']);

// Route::get('gio-hang-chi-tiet',['as'=>'chitietgiohang','uses'=>'Usercontroller@getGioHangChiTiet']);

//thich & so sanh
Route::get('wish-list', ['as' => 'wishlist', 'uses' => 'Usercontroller@getWishlist']);
Route::get('so-sanh', ['as' => 'sosanh', 'uses' => 'Usercontroller@getCompare']);
Route::post('/insert-rating', 'Usercontroller@insert_rating');

//dat hang
Route::get('dat-hang', ['as' => 'dathang', 'uses' => 'Usercontroller@getDatHang']);
Route::post('dat-hang', ['as' => 'dathang', 'uses' => 'Usercontroller@postDatHang']);

//tai khoan
Route::get('dang-nhap', ['as' => 'dangnhap', 'uses' => 'TaiKhoan_Controller@getDangNhap']);
Route::post('dang-nhap', ['as' => 'dangnhap', 'uses' => 'TaiKhoan_Controller@postDangNhap']);

Route::get('dang-ky', ['as' => 'dangky', 'uses' => 'TaiKhoan_Controller@getDangKy']);
Route::post('dang-ky', ['as' => 'dangky', 'uses' => 'TaiKhoan_Controller@postDangKy']);
Route::get('dang-xuat', ['as' => 'dangxuat', 'uses' => 'TaiKhoan_Controller@postDangXuat']);
Route::post('userUpdate1', ['as' => 'userupdate1', 'uses' => 'TaiKhoan_Controller@userUpdate1']);

//quen mat khau
Route::get('/quen-mat-khau', 'passController@quen_mat_khau');
Route::post('/recover-pass', 'passController@recover_pass');
Route::get('update-new-pass', ['as' => 'updatenewpass', 'uses' => 'passController@getupdate_new_pass']);
Route::post('update-new-pass', ['as' => 'updatenewpass', 'uses' => 'passController@postupdate_new_pass']);

// Route::get('vnpay',['as'=>'vnpay','uses'=>'Usercontroller@getVnpay']);
Route::post('vnpay-online', ['as' => 'vnpayonline', 'uses' => 'Usercontroller@postVnpay_online']);
Route::get('vnpay-return', ['as' => 'vnpayreturn', 'uses' => 'Usercontroller@getVnpay_return']);

/*---------------------------------------------------ADMIN---------------------------------------------------------------*/
Route::middleware('CheckLevel')->group(function () {

    //dashboard
    Route::get('index-admin', ['as' => 'trang-chu-admin', 'uses' => 'admincontroller@getIndexAdminDash']);
    Route::post('/filter-by-date', 'admincontroller@filter_by_date');
    Route::post('/dashboard-filter', 'admincontroller@dashboard_filter');
    Route::post('/days-order', 'admincontroller@days_order');

    //user
    Route::get('ql-nguoi-dung', ['as' => 'quanlynguoidung', 'uses' => 'AccountdController@getQL_NguoiDung']);
    Route::get('ql-nguoi-dung-user', ['as' => 'quanlynguoidung_user', 'uses' => 'AccountdController@getQL_NguoiDung_user']);
    Route::get('ql-nguoi-dung-ad', ['as' => 'quanlynguoidung_ad', 'uses' => 'AccountdController@getQL_NguoiDung_ad']);
    Route::get('user/{id}/delete', ['as' => 'delete', 'uses' => 'AccountdController@DelAdmin']);

    Route::post('update-user/{id}', ['as' => 'update_admin', 'uses' => 'AccountdController@postUpdateAdmin']);
    Route::post('add-ad', ['as' => 'addnew', 'uses' => 'AccountdController@AddAdmin']);
    Route::get('/active-user/{id}', 'AccountdController@active_user');
    Route::get('/unactive-user/{id}', 'AccountdController@unactive_user');

    //slide
    Route::get('ql-slide', ['as' => 'quanlyslide', 'uses' => 'SlideController@getQL_Slide']);
    Route::post('update-slide/{id}', ['as' => 'update_slide', 'uses' => 'SlideController@postUpdateSlide']);
    Route::post('add-ad-slide', ['as' => 'addnewslide', 'uses' => 'SlideController@AddAdmin_Slide']);
    Route::get('slide/{id}/delete', ['as' => 'deleteslide', 'uses' => 'SlideController@DelAdmin_Slide']);
    Route::get('/active-slide/{id}', 'SlideController@active_slide');
    Route::get('/unactive-slide/{id}', 'SlideController@unactive_slide');

    //thuong hieu san pham
    Route::get('ql-nsx', ['as' => 'quanlynsx', 'uses' => 'ProductTypeController@getQL_Nsx']);
    Route::post('add-ad-nsx', ['as' => 'addnewnsx', 'uses' => 'ProductTypeController@AddAdmin_NSX']);
    Route::get('nsx/{id}/delete', ['as' => 'deletensx', 'uses' => 'ProductTypeController@DelAdmin_NSX']);
    Route::post('update-nsx/{id}', ['as' => 'update_nsx', 'uses' => 'ProductTypeController@postUpdateNsx']);

    //loai ngon ngu
    // Route::get('ql-lang',['as'=>'quanlynn','uses'=>'admincontroller@getQL_NN']);
    // Route::post('add-lang',['as'=>'addnewnn','uses'=>'admincontroller@AddAdmin_NN']);
    // Route::get('lang/{id}/delete',['as'=>'deletenn','uses'=>'admincontroller@DelAdmin_NN']);
    // Route::post('update-lang/{id}',['as'=>'update_lang','uses'=>'admincontroller@postUpdateNn']);

    //san pham
    Route::get('ql-san-pham', ['as' => 'quanlysanpham', 'uses' => 'ProductController@getQL_Sanpham']);
    Route::post('add-ad-sp', ['as' => 'addnewsp', 'uses' => 'ProductController@AddAdmin_Sp']);
    Route::get('ql-san-pham/{id}/edit', 'ProductController@editSp');
    Route::post('ql-san-pham/update/{id}', 'ProductController@upproduct');
    Route::post('sp/{id}/delete', ['as' => 'deletensp', 'uses' => 'ProductController@DelAdmin_Sp']);
    Route::get('/active-sp/{id}', 'ProductController@active_sp');
    Route::get('/unactive-sp/{id}', 'ProductController@unactive_sp');
    //don hang
    Route::get('ql-don-hang', ['as' => 'donhang', 'uses' => 'OrderController@getDonHang']);
    Route::get('ql-don-hang-da-duyet', ['as' => 'donhang_daduyet', 'uses' => 'OrderController@getDonHang_daduyet']);
    Route::get('ql-don-hang-chua-duyet', ['as' => 'donhang_chuaduyet', 'uses' => 'OrderController@getDonHang_chuaduyet']);
    Route::get('ql-don-hang-huy', ['as' => 'donhang_huy', 'uses' => 'OrderController@getDonHang_huy']);
    Route::get('ql-don-hang-chi-tiet/{id}', ['as' => 'donhangchitiet', 'uses' => 'OrderController@getChiTietDonHang']);
    Route::post('ql-don-hang-chi-tiet/{id}', ['as' => 'donhangchitiet', 'uses' => 'OrderController@postChiTietDonHang']);
    Route::post('/update-order-qty', 'OrderController@update_order_qty');
    Route::get('dh/{id}', ['as' => 'deletedh', 'uses' => 'OrderController@DelAdmin_DonHang']);
    Route::get('/print-order/{checkout_code}', 'OrderController@print_order');
    // ql ma giam gia
    Route::get('ql-ma-giam-gia', ['as' => 'quanlycoupon', 'uses' => 'CouponController@getCoupon']);
    Route::post('add-ad-coupon', ['as' => 'addnewcoupon', 'uses' => 'CouponController@AddAdmin_Coupon']);
    Route::get('coupon/{id}/delete', ['as' => 'deletecoupon', 'uses' => 'CouponController@DelAdmin_Coupon']);
    Route::post('update-coupon/{id}', ['as' => 'update_coupon', 'uses' => 'CouponController@postUpdate_Coupon']);
    Route::get('/send-coupon', 'CouponController@send_coupon');
    Route::get('/active-coupon/{id}', 'CouponController@active_coupon');
    Route::get('/unactive-coupon/{id}', 'CouponController@unactive_coupon');

    Route::post('/export-excel-coupon', 'admincontroller@export_excel_coupon');
    Route::post('/import-excel-coupon', 'admincontroller@import_excel_coupon');

    // Route::post('/export-excel-lang','admincontroller@export_excel_lang');
    // Route::post('/import-excel-lang','admincontroller@import_excel_lang');

    Route::post('/export-excel-slide', 'admincontroller@export_excel_slide');
    Route::post('/import-excel-slide', 'admincontroller@import_excel_slide');

    Route::post('/export-excel-nsx', 'admincontroller@export_excel_nsx');
    Route::post('/import-excel-nsx', 'admincontroller@import_excel_nsx');

    Route::post('/export-excel-product', 'admincontroller@export_excel_product');

    //xuat don hang
    Route::post('/export-excel-don-hang', 'admincontroller@export_excel_dh');
    Route::post('/export-excel-don-hang-da-duyet', 'admincontroller@export_excel_dh_da_duyet');
    Route::post('/export-excel-don-hang-chua-duyet', 'admincontroller@export_excel_dh_chua_duyet');
    Route::post('/export-excel-don-hang-huy', 'admincontroller@export_excel_dh_huy');

    //nhap xuat tai khoan
    Route::post('/import-excel-account', 'admincontroller@import_account');
    Route::post('/export-excel-all-account', 'admincontroller@export_excel_all_account');
    Route::post('/export-excel-admin-account', 'admincontroller@export_admin_account');
    Route::post('/export-excel-user-account', 'admincontroller@export_excel_user_account');

    // Gallery
    Route::get('gallery/{id}/edit', 'GalleryController@edit')->name('gallelyedit');
    Route::post('gallery/load-data', 'GalleryController@store')->name('gallerystore');
    Route::post('gallery/add/{id}', 'GalleryController@add')->name('galleryadd');
    Route::post('gallery/update', 'GalleryController@update')->name('galleryupdate');
    Route::post('gallery/update-name', 'GalleryController@update_name')->name('galleryupdatename');
    Route::post('gallery/delete', 'GalleryController@delete')->name('gallerydelete');
});
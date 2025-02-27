<?php

namespace App\Http\Controllers;

use App\Exports\ExportAdminAccount;
use App\Exports\ExportAllAccount;
use App\Exports\ExportCoupon;
use App\Exports\ExportNsx;
use App\Exports\ExportOrder;

// use App\Exports\ExportPost;
// use App\Imports\ImportPost;
// use App\Imports\ImportProduct;
use App\Exports\ExportOrderApproved;
use App\Exports\ExportOrderCancel;
use App\Exports\ExportOrderUnapproved;
use App\Exports\ExportProduct;
use App\Exports\ExportSlide;
use App\Exports\ExportUserAccount;
use App\Imports\ImportAccount;
use App\Imports\ImportCoupon;
use App\Imports\ImportNsx;
use App\Imports\ImportSlide;
use App\Models\Statistical;
use App\Models\Visitors;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class admincontroller extends Controller
{

    public function getIndexAdminDash(Request $req)
    {
        if (Auth::check() && Auth::user()->level == 1) {

            $url_canonical = $req->url();
            // $user_ip_address = '192.168.1.42';

            $dauthangnay = Carbon::now('Asia/Ho_Chi_Minh')->startOfMonth()->toDateString();
            $dau_thangtruoc = Carbon::now('Asia/Ho_Chi_Minh')->subMonth()->startOfMonth()->toDateString();
            $cuoi_thangtruoc = Carbon::now('Asia/Ho_Chi_Minh')->subMonth()->endOfMonth()->toDateString();
            $sub365ngay = Carbon::now('Asia/Ho_Chi_Minh')->subdays(365)->toDateString();
            $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();

            //tong thang truoc
            $tong_thangtruoc = Visitors::whereBetween('date_visitor', [$dau_thangtruoc, $cuoi_thangtruoc])->get();
            $tong_thangtruoc_count = $tong_thangtruoc->count();

            //tong thang nay
            $tong_thangnay = Visitors::whereBetween('date_visitor', [$dauthangnay, $now])->get();
            $tong_thangnay_count = $tong_thangnay->count();

            //tong 1 nam
            $tong_motnam = Visitors::whereBetween('date_visitor', [$sub365ngay, $now])->get();
            $tong_motnam_count = $tong_motnam->count();

            //tat ca
            $tatca_count = Visitors::all()->count();

            return view('admin.Dashboard', compact('tatca_count', 'tong_thangtruoc_count', 'tong_thangnay_count', 'tong_motnam_count', 'url_canonical'));
        } else {
            return redirect()->route('trang-chu');
        }

    }

    public function filter_by_date(Request $req)
    {
        $data = $req->all();
        $from_date = $data['from_date'];
        $to_date = $data['to_date'];

        $get = Statistical::whereBetween('order_date', [$from_date, $to_date])->orderBy('order_date', 'ASC')->get();

        if (count($get) > 0) {
            foreach ($get as $key => $val) {
                $chart_data[] = array(
                    'period' => $val->order_date,
                    'order' => $val->total_order,
                    'sales' => $val->sales,
                    'profit' => $val->profit,
                    'quantity' => $val->quantity,
                );
            }
        } else {
            $chart_data[] = array(
                'period' => now()->format('Y-m-d'),
                'order' => 0,
                'sales' => 0,
                'profit' => 0,
                'quantity' => 0,
            );
        }

        echo $data = json_encode($chart_data);
    }

    public function dashboard_filter(Request $req)
    {
        $data = $req->all();
        // echo $today = Carbon::now('Asia/Ho_Chi_Minh');
        $dauthangnay = Carbon::now('Asia/Ho_Chi_Minh')->startOfMonth()->toDateString();
        $dau_thangtruoc = Carbon::now('Asia/Ho_Chi_Minh')->subMonth()->startOfMonth()->toDateString();
        $cuoi_thangtruoc = Carbon::now('Asia/Ho_Chi_Minh')->subMonth()->endOfMonth()->toDateString();

        $sub7ngay = Carbon::now('Asia/Ho_Chi_Minh')->subdays(7)->toDateString();
        $sub365ngay = Carbon::now('Asia/Ho_Chi_Minh')->subdays(365)->toDateString();

        $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();

        if ($data['dashboard_value'] == '7ngay') {
            $get = Statistical::whereBetween('order_date', [$sub7ngay, $now])->orderBy('order_date', 'ASC')->get();

        } elseif ($data['dashboard_value'] == 'thangtruoc') {
            $get = Statistical::whereBetween('order_date', [$dau_thangtruoc, $cuoi_thangtruoc])->orderBy('order_date', 'ASC')->get();

        } elseif ($data['dashboard_value'] == 'thangnay') {
            $get = Statistical::whereBetween('order_date', [$dauthangnay, $now])->orderBy('order_date', 'ASC')->get();

        } else {
            $get = Statistical::whereBetween('order_date', [$sub365ngay, $now])->orderBy('order_date', 'ASC')->get();

        }

        if (count($get) > 0) {

            foreach ($get as $key => $val) {
                $chart_data[] = array(
                    'period' => $val->order_date,
                    'order' => $val->total_order,
                    'sales' => $val->sales,
                    'profit' => $val->profit,
                    'quantity' => $val->quantity,
                );
            }
        } else {
            $chart_data[] = array(
                'period' => now()->format('Y-m-d'),
                'order' => 0,
                'sales' => 0,
                'profit' => 0,
                'quantity' => 0,
            );
        }
        echo $data = json_encode($chart_data);

    }

    public function days_order()
    {
        $sub30ngay = Carbon::now('Asia/Ho_Chi_Minh')->subdays(40)->toDateString();
        $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
        $get = Statistical::whereBetween('order_date', [$sub30ngay, $now])->orderBy('order_date', 'ASC')->get();

        if (count($get) > 0) {
            foreach ($get as $key => $val) {
                $chart_data[] = array(
                    'period' => $val->order_date,
                    'order' => $val->total_order,
                    'sales' => $val->sales,
                    'profit' => $val->profit,
                    'quantity' => $val->quantity,
                );
            }
        } else {
            $chart_data[] = array(
                'period' => now()->format('Y-m-d'),
                'order' => 0,
                'sales' => 0,
                'profit' => 0,
                'quantity' => 0,
            );
        }
        $data = json_encode($chart_data);
        echo $data;
    }

// -----------------------------------------------------------Excel---------------------------------------------------

    //coupon
    public function export_excel_coupon()
    {
        return Excel::download(new ExportCoupon, 'coupon.xlsx');
    }
    public function import_excel_coupon(Request $req)
    {

        $file = $req->file('file')->getRealPath();
        $import = new ImportCoupon;
        $import->import($file);

        if ($import->failures()->isNotEmpty()) {
            return back()->withFailures($import->failures());
        }

        return back()->with('thongbao', 'Cập nhật thành công!');
    }
// -----------------------------------------------------------Excel---------------------------------------------------
    //lang
    // public function export_excel_lang(){
    //     return Excel::download(new ExportPost , 'post.xlsx');
    // }
    // public function import_excel_lang(Request $req){
    //     if(Session::get('locale') == 'vi' || Session::get('locale') == null){
    //         $resuft_tb = trans('home_ad.importexcel', [], 'vi');

    //     }else{
    //         $resuft_tb = trans('home_ad.importexcel', [], 'en');
    //     }
    //     $file = $req->file('file')->getRealPath();
    //     $import = new ImportPost;
    //     $import->import($file);

    //     if ($import->failures()->isNotEmpty()) {
    //         return back()->withFailures($import->failures());
    //     }
    //     return back()->with('thongbao', ''.$resuft_tb.'');
    // }
    // -----------------------------------------------------------Excel---------------------------------------------------
    //slide
    public function export_excel_slide()
    {
        return Excel::download(new ExportSlide, 'slide.xlsx');
    }
    public function import_excel_slide(Request $req)
    {
        if (Session::get('locale') == 'vi' || Session::get('locale') == null) {
            $resuft_tb = trans('home_ad.importexcel', [], 'vi');

        } else {
            $resuft_tb = trans('home_ad.importexcel', [], 'en');
        }
        $file = $req->file('file')->getRealPath();
        $import = new ImportSlide;
        $import->import($file);

        if ($import->failures()->isNotEmpty()) {
            return back()->withFailures($import->failures());
        }

        return back()->with('thongbao', '' . $resuft_tb . '');
    }

    //nsx
    public function export_excel_nsx()
    {
        return Excel::download(new ExportNsx, 'type_products.xlsx');
    }
    public function import_excel_nsx(Request $req)
    {

        if (Session::get('locale') == 'vi' || Session::get('locale') == null) {
            $resuft_tb = trans('home_ad.importexcel', [], 'vi');

        } else {
            $resuft_tb = trans('home_ad.importexcel', [], 'en');
        }
        $file = $req->file('file')->getRealPath();
        $import = new ImportNsx;
        $import->import($file);

        if ($import->failures()->isNotEmpty()) {
            return back()->withFailures($import->failures());
        }

        return back()->with('thongbao', '' . $resuft_tb . '');
    }

    //san pham
    public function export_excel_product()
    {
        return Excel::download(new ExportProduct, 'products.xlsx');
    }

    //don hang
    public function export_excel_dh()
    {
        return Excel::download(new ExportOrder, 'Order.xlsx');
    }
    //don hang da duyet
    public function export_excel_dh_da_duyet()
    {
        return Excel::download(new ExportOrderApproved, 'OrderApproved.xlsx');
    }
    //don hang chua duyet
    public function export_excel_dh_chua_duyet()
    {
        return Excel::download(new ExportOrderUnapproved, 'OrderUnapproved.xlsx');
    }
    //don hang huy
    public function export_excel_dh_huy()
    {
        return Excel::download(new ExportOrderCancel, 'OrderCancel.xlsx');
    }

    // nhap xuat tai khoan
    public function import_account(Request $req)
    {

        if (Session::get('locale') == 'vi' || Session::get('locale') == null) {
            $resuft_tb = trans('home_ad.importexcel', [], 'vi');

        } else {
            $resuft_tb = trans('home_ad.importexcel', [], 'en');
        }
        $file = $req->file('file')->getRealPath();
        $import = new ImportAccount;
        $import->import($file);

        if ($import->failures()->isNotEmpty()) {
            return back()->withFailures($import->failures());
        }

        return back()->with('thongbao', '' . $resuft_tb . '');
    }
    public function export_excel_all_account()
    {
        return Excel::download(new ExportAllAccount, 'users.xlsx');
    }
    public function export_admin_account()
    {
        return Excel::download(new ExportAdminAccount, 'users_admin.xlsx');
    }
    public function export_excel_user_account()
    {
        return Excel::download(new ExportUserAccount, 'users_user.xlsx');
    }

}

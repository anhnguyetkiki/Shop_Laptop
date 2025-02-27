<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Statistical;
use Carbon\Carbon;
use DNS2D;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function getDonHang(Request $req)
    {
        if (Auth::check()) {

            $donhang = Bill::join('customer', 'customer.id', '=', 'bills.id_customer')->orderby('id_bill', 'DESC')->get();
            $url_canonical = $req->url();

            return view('admin.QL_donhang', compact('donhang', 'url_canonical'));

        } else {
            return redirect()->route('trang-chu');
        }
    }
    public function getDonHang_daduyet(Request $req)
    {
        if (Auth::check()) {

            $donhang_daduyet = Bill::join('customer', 'customer.id', '=', 'bills.id_customer')->where('status_bill', 1)->orderby('id_bill', 'desc')->get();
            $url_canonical = $req->url();

            return view('admin.QL_donhang_daduyet', compact('donhang_daduyet', 'url_canonical'));

        } else {
            return redirect()->route('trang-chu');
        }
    }
    public function getDonHang_chuaduyet(Request $req)
    {
        if (Auth::check()) {

            $donhang_chuaduyet = Bill::join('customer', 'customer.id', '=', 'bills.id_customer')->where('status_bill', 0)->orderby('id_bill', 'desc')->get();
            $url_canonical = $req->url();

            return view('admin.QL_donhang_chuaduyet', compact('donhang_chuaduyet', 'url_canonical'));

        } else {
            return redirect()->route('trang-chu');
        }
    }
    public function getDonHang_huy(Request $req)
    {
        if (Auth::check()) {

            $donhang_huy = Bill::join('customer', 'customer.id', '=', 'bills.id_customer')->where('status_bill', 2)->orderby('id_bill', 'desc')->get();
            $url_canonical = $req->url();

            return view('admin.QL_donhang_huy', compact('donhang_huy', 'url_canonical'));

        } else {
            return redirect()->route('trang-chu');
        }
    }

    public function DelAdmin_DonHang($id)
    {

        $bill = Bill::where('id_bill', $id)->first();

        Customer::where('id', $bill->id_customer)->first()->delete();

        $billdetail = BillDetail::where('id_bill', $bill->id_bill)->delete();

        Bill::where('id_bill', $id)->delete();

        return redirect()->back()->with('thongbao', 'Xóa thành công!');
    }

    public function getChiTietDonHang($id, Request $req)
    {
        if (Auth::check()) {

            $billdetaill = DB::select("SELECT bt.id_bill_detail, bt.id_bill, bt.id_product, bt.id_post_bill_detail, bt.order_code, bt.quantity,
        bt.unit_price,p.sub_image,p.image,p.hours_sale,p.date_sale, p.product_quantity ,p.id_post, post.sp_vi as sp_vi,  post.sp_en as sp_en
        FROM bill_detail bt, products p
        INNER JOIN post ON p.id_post = post.id_post
         WHERE bt.id_product=p.id AND id_bill=$id ");
            $url_canonical = $req->url();

            $thongtin_kh = Bill::join('customer', 'customer.id', '=', 'bills.id_customer')->where('id_bill', $id)->get();

            return view('admin.ChitietDH', compact('billdetaill', 'thongtin_kh', 'url_canonical'));
        } else {
            return redirect()->route('trang-chu');
        }
    }
    public function postChiTietDonHang($id, Request $req)
    {

        $qty_update = BillDetail::where('id_bill', $id)->where('order_code', $req->order_code)->first();
        // dd( $qty_update);
        $qty_update->quantity = $req->product_quantity_order;
        $qty_update->save();

        $total_update = Bill::where('id_bill', $id)->where('order_code', $req->order_code)->first();
        $total_update->total = $req->product_quantity_order * $qty_update->unit_price;
        $total_update->save();

        return redirect()->back();
    }

    public function update_order_qty(Request $req)
    {
        $data = $req->all();

        $bill = Bill::find($data['order_id']);
        $bill->status_bill = $data['order_status'];
        $bill->save();

        //order date
        $order_date = $bill->date_order;
        $statistic = Statistical::where('order_date', $order_date)->get();
        if ($statistic) {
            $statistic_count = $statistic->count();
        } else {
            $statistic_count = 0;
        }

        if ($bill->status_bill == 1) {
            //them
            $total_order = 0;
            $sales = 0;
            $profit = 0;
            $quantity = 0;

            foreach ($data['order_product_id'] as $key => $product_id) {
                $product = Product::find($product_id);
                $product_qty = $product->product_quantity;
                $product_soid = $product->product_soid;

                $product_price = $product->unit_price;
                $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();

                foreach ($data['quantity'] as $key2 => $qty) {
                    if ($key == $key2) {
                        $pro_remain = $product_qty - $qty;
                        $product->product_quantity = $pro_remain;
                        $product->product_soid = $product_soid + $qty;
                        $product->save();

                        //update doanh thu
                        $quantity += $qty;
                        $total_order += 1;
                        $sales += $product_price * $qty;
                        $profit = $sales - 1000;
                    }
                }
            }
            //update doanh so db
            if ($statistic_count > 0) {
                $statistic_update = Statistical::where('order_date', $order_date)->first();
                $statistic_update->sales = $statistic_update->sales + $sales;
                $statistic_update->profit = $statistic_update->profit + $profit;
                $statistic_update->quantity = $statistic_update->quantity + $quantity;
                $statistic_update->total_order = $statistic_update->total_order + $total_order;
                $statistic_update->save();

            } else {

                $statistic_new = new Statistical();
                $statistic_new->order_date = $order_date;
                $statistic_new->sales = $sales;
                $statistic_new->profit = $profit;
                $statistic_new->quantity = $quantity;
                $statistic_new->total_order = $total_order;
                $statistic_new->save();
            }
        } else if ($bill->status_bill == 0 || $bill->status_bill == 2) {
            foreach ($data['order_product_id'] as $key => $product_id) {
                $product = Product::find($product_id);
                $product_qty = $product->product_quantity;
                $product_soid = $product->product_soid;

                if ($product->product_soid != 0) {
                    foreach ($data['quantity'] as $key2 => $qty) {
                        if ($key == $key2) {
                            $pro_remain = $product_qty + $qty;
                            $product->product_quantity = $pro_remain;
                            $product->product_soid = $product_soid - $qty;
                            $product->save();
                        }
                    }
                }
            }
        }

    }

    public function print_order($checkout_code)
    {
        $billdetaill_print = BillDetail::where('order_code', $checkout_code)->join('post', 'post.id_post', 'bill_detail.id_post_bill_detail')->get();
        $bill_print = Bill::where('order_code', $checkout_code)->get();

        foreach ($billdetaill_print as $key => $bd) {
            $namepro = $bd->sp_vi;
        }

        $day = date('d');
        $month = date('m');
        $year = date('Y');

        $kh_print = Bill::join('customer', 'customer.id', '=', 'bills.id_customer')->where('order_code', $checkout_code)->first();

        $date_order_create = date_create($kh_print->date_order);
        if ($kh_print->payment == 'ATM') {
            $kq_pay = 'Chuyển khoản';
        } else {
            $kq_pay = 'Tiền mặt';
        }

        $tonghop = "$namepro - $kh_print->order_code";
        $qrcode = DNS2D::getBarcodeHTML($tonghop, 'QRCODE', 6.5, 5);
        $email = env('MAIL_USERNAME');
        $address = '1XX Bình Dương, TDM';
        $phone = '0773654031';

        $pdf = \PDF::loadView('admin.pdf', compact('kq_pay', 'billdetaill_print', 'bill_print', 'qrcode', 'date_order_create', 'kh_print', 'email', 'phone', 'address'));
        return $pdf->stream('' . $kh_print->order_code . '.pdf');

    }
}
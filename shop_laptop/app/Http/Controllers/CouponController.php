<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class CouponController extends Controller
{
    public function getCoupon(Request $req){

        if (Auth::check()) {

            $month_now = Carbon::now('Asia/Ho_Chi_Minh')->month;
            $day_now = Carbon::now('Asia/Ho_Chi_Minh')->day;
            $year_now = Carbon::now('Asia/Ho_Chi_Minh')->year;


            $coupon = Coupon::orderBy('coupon_id', 'desc')->get();
            $today =  Carbon::now('Asia/Ho_Chi_Minh')->format('d-m-Y');
            $coupon_send_new = Coupon::where('coupon_status', 0)->where('coupon_date_end', '>=', $today)->first();
            $url_canonical = $req->url();

            return view('admin.QL_coupon', compact('coupon', 'today', 'coupon_send_new','url_canonical','month_now','day_now','year_now'));


        } else {
            return redirect()->route('trang-chu');
        }
    }

    public function AddAdmin_Coupon(Request $req){
        $addcoupon = new Coupon();
        if(Session::get('locale') == 'vi' || Session::get('locale') == null){
            $this->validate($req,
            [
                'coupon_time'=>'required',
                'coupon_number'=>'required',
                'coupon_code'=>'required|unique:coupon,coupon_code',

            ],
            [
                'coupon_time.required'=>'Vui lòng nhập coupon_time',
                'coupon_number.required'=>'Vui lòng nhập coupon_number',
                'coupon_code.required'=>'Vui lòng nhập coupon_code',
                'coupon_code.unique'=>'coupon_code đã tồn tại',

            ]);
        }else{
            $this->validate($req,
            [
                'coupon_time'=>'required',
                'coupon_number'=>'required',
                'coupon_code'=>'required|unique:coupon,coupon_code',

            ]);
        }

        $addcoupon->coupon_name = $req->coupon_code;
        $addcoupon->coupon_qty = $req->coupon_time;
        $addcoupon->coupon_number = $req->coupon_number;
        $addcoupon->coupon_code = $req->coupon_code;
        $addcoupon->coupon_condition = $req->coupon_condition;
        $addcoupon->coupon_date_start = date_create($req->coupon_date_start)->format('d-m-Y');
        $addcoupon->coupon_date_end = date_create($req->coupon_date_end)->format('d-m-Y');
        $addcoupon->coupon_status = 0;

        $addcoupon->save();
        return redirect()->route('quanlycoupon')->with('thongbao', 'Thêm mới thành công!');
    }

    public function DelAdmin_Coupon($id)
    {
        $delcoupon = Coupon::where('coupon_id', $id)->delete();


        return redirect()->back()->with('thongbao', 'Xóa thành công!');
    }

    public function postUpdate_Coupon(Request $req, $id){
        if (Auth::check()) {
            if(Session::get('locale') == 'vi' || Session::get('locale') == null){
                $this->validate($req,
                [
                    'coupon_time'=>'required',
                    'coupon_number'=>'required',
                    'coupon_code'=>'required',

                ],
                [
                    'coupon_time.required'=>'Vui lòng nhập coupon_time',
                    'coupon_number.required'=>'Vui lòng nhập coupon_number',
                    'coupon_code.required'=>'Vui lòng nhập coupon_code',
                ]);
            }else{
                $this->validate($req,
                [
                    'coupon_time'=>'required',
                    'coupon_number'=>'required',
                    'coupon_code'=>'required',

                ]);
            }
            $update_cp = Coupon::where('coupon_id',$id)->first();
            $update_cp->coupon_name  = $req->coupon_name;
            $update_cp->coupon_qty  = $req->coupon_time;
            $update_cp->coupon_number  = $req->coupon_number;
            $update_cp->coupon_code  = $req->coupon_code;
            $update_cp->coupon_condition  = $req->coupon_condition;
            $update_cp->coupon_date_start = date_create($req->coupon_date_start)->format('d-m-Y');
            $update_cp->coupon_date_end = date_create($req->coupon_date_end)->format('d-m-Y');
            $update_cp->coupon_status  = $req=0;


            $update_cp->save();
            return redirect()->route('quanlycoupon')->with('thongbao', 'Cập nhật -'.$update_cp->coupon_name.'- thành công!');
        }else {
            return redirect()->route('trang-chu');
        }
    }

    public function send_coupon(){
        $user_coupon = User::where('level', 2)->get();
        $today =  Carbon::now('Asia/Ho_Chi_Minh')->format('d/m/Y');
        $coupon_send_new = Coupon::where('coupon_status', 0)->where('coupon_date_end', '>=', $today)->orderBy('coupon_id', 'desc')->first();
        $now_send = date('d-m-Y H:i:s');
        $to_email =  env('MAIL_USERNAME');
        $title_mail = "Mã Khuyến Mãi".' '.$now_send;

        $data = [];
        foreach ($user_coupon as $key => $send) {
            $data['email'][] = $send->email;
        }
        $coupon_array =  array(
            'coupon_send_new' => $coupon_send_new,
        );
        Mail::send('email.send_mail_coupon', ['coupon_array'=>$coupon_array]  ,function($message) use ($title_mail, $data, $to_email){
            $message->to($data['email'])->subject($title_mail);
            $message->from($to_email, $title_mail);
        });

        return redirect()->back()->with('thongbao', 'Gửi mã giảm giá thành công!');
    }

    public function active_coupon($id){
        Coupon::where('coupon_id',$id)->update(['coupon_status'=>0]);
        return redirect()->back();
    }
    public function unactive_coupon($id){
        Coupon::where('coupon_id',$id)->update(['coupon_status'=>1]);
        return redirect()->back();
    }

}
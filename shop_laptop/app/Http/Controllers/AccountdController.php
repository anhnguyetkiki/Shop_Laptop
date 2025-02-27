<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AccountdController extends Controller
{
    public function getQL_NguoiDung(Request $req){
        if (Auth::check() && Auth::user()->level == 1) {
            $user = User::all();
            $url_canonical = $req->url();

            return view('admin.QL_nguoidung', compact('user','url_canonical' ));
        }
        else{
            return redirect()->route('trang-chu');
        }

    }

    public function getQL_NguoiDung_user(Request $req){
        if (Auth::check() && Auth::user()->level == 1) {

            $taikhoan_user = User::where('level',2)->get();
            $url_canonical = $req->url();

            return view('admin.QL_nguoidung_user', compact('taikhoan_user','url_canonical'));
        }
        else{
            return redirect()->route('trang-chu');
        }

    }
    public function getQL_NguoiDung_ad(Request $req){
        if (Auth::check() && Auth::user()->level == 1) {

            $taikhoan_ad = User::where('level',1)->get();
            $url_canonical = $req->url();

            return view('admin.QL_nguoidung_ad', compact('taikhoan_ad', 'url_canonical'));
        }
        else{
            return redirect()->route('trang-chu');
        }

    }

    public function DelAdmin($id)
    {
        $user = User::where('id', $id)->delete();

        return redirect()->back()->with('thongbao', 'Xóa thành công!');
    }

    public function AddAdmin(Request $req){
        if(Session::get('locale') == 'vi' || Session::get('locale') == null){
            $this->validate($req,
                [
                    'email'=>'required|email|unique:users,email',
                    'password'=>'required|min:6|max:20',
                    'name'=>'required',
                    're_password'=>'required|same:password'

                ],
                [
                    'name.required'=>'Vui lòng nhập full name',

                    'email.required'=>'Vui lòng nhập email',
                    'email.email'=>'Email không đúng định dạng',
                    'email.unique'=>'Email đã được sử dụng',

                    'password.required'=>'Vui lòng nhập mật khẩu',
                    'password.min'=>'Mật khẩu ít nhất 6 ký tự',
                    'password.max'=>'Mật khẩu không quá 20 ký tự',

                    're_password.required'=>'Vui lòng nhập lại mật khẩu',
                    're_password.same'=>'Mật khẩu không giống nhau'

                ]);
        }else{
            $this->validate($req,
            [
                'email'=>'required|email|unique:users,email',
                'password'=>'required|min:6|max:20',
                'name'=>'required',
                're_password'=>'required|same:password'

            ]);
        }
        $user = new User();
        $user->full_name = $req->name;
        $user->email = $req->email;
        $user->password = Hash::make($req->password);
        $user->phone = $req->phone;
        $user->address = $req->adress;
        $user->level = $req->level;
        $user->save();
        return redirect()->back()->with('thongbao', 'Thêm mới thành công!');
    }



    public function postUpdateAdmin(Request $req,$id){
        if(Session::get('locale') == 'vi' || Session::get('locale') == null){
            $this->validate($req,
            [
                'email'=>'required|email',

                'name'=>'required',

            ],
            [
                'name.required'=>'Vui lòng nhập full name',

                'email.required'=>'Vui lòng nhập email',
                'email.email'=>'Email không đúng định dạng',

            ]);
        }else{
            $this->validate($req,
            [
                'email'=>'required|email',

                'name'=>'required',

            ]);
        }
        $user_up=User::where('id',$id)->first();
        $user_up->full_name = $req->name;
        $user_up->email = $req->email;
        if ($req->password) {
            $user_up->password = Hash::make($req->password);
        }else{
            $user_up->password =$user_up->password;
        }
        $user_up->phone = $req->phone;
        $user_up->address = $req->adress;
        $user_up->level = $req->level;

        $user_up->save();
        return redirect()->back()->with('thongbao', 'Cập nhật thành công!');
    }

    public function active_user($id){
        User::where('id',$id)->update(['level'=>2]);
        return redirect()->back();
    }
    public function unactive_user($id){
        User::where('id',$id)->update(['level'=>1]);
        return redirect()->back();
    }
}

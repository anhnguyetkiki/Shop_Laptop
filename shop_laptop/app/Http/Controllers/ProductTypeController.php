<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProductTypeController extends Controller
{
    public function getQL_Nsx(Request $req){
        if (Auth::check() && Auth::user()->level == 1) {
            $nsx = ProductType::orderBy('id', 'desc')->get();
            $url_canonical = $req->url();

            return view('admin.QL_Nsx', compact('nsx', 'url_canonical'));
        }else{
            return redirect()->route('trang-chu');
        }
    }

    public function DelAdmin_NSX($id, Request $req)
    {
        $image = ProductType::find($id);
        unlink(public_path('source/image/type_product/').$image->image);
        $image->delete();

        return redirect()->back()->with('thongbao', 'Xóa thành công!');
    }

    public function AddAdmin_NSX(Request $req){
        $nsx = new ProductType();
        if(Session::get('locale') == 'vi' || Session::get('locale') == null){
            $this->validate($req,
            [
                'name'=>'required',
                'image_file' => 'required|mimes:jpg,jpeg,png,gif|max:4096',

            ],
            [
                'name.required'=>'Vui lòng nhập tên',
                'image_file.required'=>'Vui lòng chọn hình',
                'image_file.mimes' => 'Chỉ chấp nhận hình thẻ với đuôi .jpg .jpeg .png .gif',
                'image_file.max' => 'Hình ảnh giới hạn dung lượng không quá 4M',


            ]);
        }else{
            $this->validate($req,
            [
                'name'=>'required',
                'image_file' => 'required|mimes:jpg,jpeg,png,gif|max:4096',

            ]);
        }

        $nsx->name_type  = $req->name;
        if ($req->hasFile('image_file')) {
            $file = $req->file('image_file');
            $get_name_img = $file->getClientOriginalName();
            $name_img = current(explode('.', $get_name_img));
            $new_img = $name_img.rand(0,99).'.'.$file->getClientOriginalExtension();
            $filename =time() .'.'. $new_img;
            $file->move(public_path('source/image/type_product'),$filename);
            $nsx->image = $filename;
        }


        $nsx->save();
        return redirect()->route('quanlynsx')->with('thongbao', 'Thêm mới thành công!');
    }



    public function postUpdateNsx(Request $req, $id){
        if(Session::get('locale') == 'vi' || Session::get('locale') == null){
            $this->validate($req,
            [
                'name'=>'required',
                'image' => 'mimes:jpg,jpeg,png,gif|max:4096',

            ],
            [
                'name.required'=>'Vui lòng nhập tên',
                // 'image.required'=>'Vui lòng chọn hình',
                'image.mimes' => 'Chỉ chấp nhận hình thẻ với đuôi .jpg .jpeg .png .gif',
                'image.max' => 'Hình ảnh giới hạn dung lượng không quá 4M',

            ]);
        }else{
            $this->validate($req,
            [
                'name'=>'required',
                'image' => 'mimes:jpg,jpeg,png,gif|max:4096',

            ]);
        }
        $nsx_update = ProductType::where('id',$id)->first();

        $nsx_update->name_type = $req->name;

        if ($req->hasFile('image')) {
            $getHA = ProductType::find($id);

            unlink(public_path('source/image/type_product/').$getHA->image);
            $file = $req->file('image');
            $get_name_img = $file->getClientOriginalName();
            $name_img = current(explode('.', $get_name_img));
            $new_img = $name_img.rand(0,99).'.'.$file->getClientOriginalExtension();
            $filename =time() .'.'. $new_img;
            $file->move(public_path('source/image/type_product'),$filename);
            $nsx_update->image = $filename;

        }


        $nsx_update->save();

        return redirect()->route('quanlynsx')->with('thongbao', 'Cập nhật thành công!');


    }
}

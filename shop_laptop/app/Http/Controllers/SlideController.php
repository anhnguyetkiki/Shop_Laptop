<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SlideController extends Controller
{
    public function getQL_Slide(Request $req){
        if (Auth::check() && Auth::user()->level == 1) {
            $slide = Slide::all();
            $url_canonical = $req->url();

            return view('admin.QL_slide', compact('slide', 'url_canonical'));
        }else{
            return redirect()->route('trang-chu');

        }
    }

    public function DelAdmin_Slide($id)
    {
        $slide = Slide::findOrfail($id);
        unlink(public_path('source/image/slide/').$slide->image);
        $slide->delete();

        return redirect()->back()->with('thongbao', 'Xóa thành công!');
    }

    public function AddAdmin_Slide(Request $req){
        $slide = new Slide();
        if(Session::get('locale') == 'vi' || Session::get('locale') == null){
            $this->validate($req,
            [
                // 'link_slide'=>'required',
                'image_file' => 'required|mimes:jpg,jpeg,png,gif|max:4096',

            ],
            [
                // 'link_slide.required'=>'Vui lòng nhập link',

                'image_file.required'=>'Vui lòng chọn hình',
                'image_file.mimes' => 'Chỉ chấp nhận hình thẻ với đuôi .jpg .jpeg .png .gif',
                'image_file.max' => 'Hình ảnh giới hạn dung lượng không quá 4M',

            ]);
        }else{
            $this->validate($req,
            [
                // 'link_slide'=>'required',
                'image_file' => 'required|mimes:jpg,jpeg,png,gif|max:4096',

            ]);
        }

        $slide->link = $req->link_slide;
        $slide->status_slide = $req->status_slide;

        if ($req->hasFile('image_file')) {
            $file = $req->file('image_file');
            $get_name_img = $file->getClientOriginalName();
            $name_img = current(explode('.', $get_name_img));
            $new_img = $name_img.rand(0,99).'.'.$file->getClientOriginalExtension();
            $filename =time() .'.'. $new_img;
            $file->move(public_path('source/image/slide'),$filename);
            $slide->image = $filename;
        }else{
            return $req;
            $slide->image = '';
        }
        $slide->status_slide = $req=0;


        $slide->save();
        return redirect()->back()->with('thongbao', 'Thêm mới thành công!');
    }


    public function postUpdateSlide(Request $req, $id){
        if (Auth::check() && Auth::user()->level == 1) {
            // dd($id);
            $slide_update = Slide::where('id',$id)->first();
            $slide_update->link = $req->link_slide;
            $slide_update->status_slide = $req->status_slide;

            if($req->hasFile('image')){
                if(Session::get('locale') == 'vi' || Session::get('locale') == null){
                    $this->validate($req,
                    [

                        'image' => 'mimes:jpg,jpeg,png,gif|max:4096',
                    ],
                    [

                        // 'image.required' => 'Vui lòng chọn hình',
                        'image.mimes' => 'Chỉ chấp nhận hình thẻ với đuôi .jpg .jpeg .png .gif',
                        'image.max' => 'Hình ảnh giới hạn dung lượng không quá 4M',
                    ]);
                }else{
                    $this->validate($req,
                    [

                        'image' => 'mimes:jpg,jpeg,png,gif|max:4096',
                    ]);
                }

            }
            if ($req->hasFile('image')) {
                $getHA = Slide::select('image')->where('id',$req->id)->first();
                unlink(public_path('source/image/slide/').$getHA->image);
                $file = $req->file('image');
                $get_name_img = $file->getClientOriginalName();
                $name_img = current(explode('.', $get_name_img));
                $new_img = $name_img.rand(0,99).'.'.$file->getClientOriginalExtension();
                $filename =time() .'.'. $new_img;
                $file->move(public_path('source/image/slide'),$filename);
                $slide_update->image = $filename;
            }
            $slide_update->save();

            return redirect()->route('quanlyslide')->with('thongbao', 'Cập nhật thành công!');
        }else{
            return redirect()->route('trang-chu');
        }

    }

    public function active_slide($id){
        Slide::where('id',$id)->update(['status_slide'=>1]);
        return redirect()->back();
    }
    public function unactive_slide($id){
        Slide::where('id',$id)->update(['status_slide'=>0]);
        return redirect()->back();
    }
}

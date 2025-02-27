<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function getQL_Sanpham(Request $req){

        $sanpham = Product::orderby('id', 'desc')->get();
        $type = ProductType::all();
        $url_canonical = $req->url();

        $sanpham1 = Product::join('post','post.id_post','products.id_post')->orderBy('id','desc')->get();

        $nameproduct = Post::orderby('id_post', 'asc')->get();
        $type = ProductType::orderby('id', 'desc')->get();


        return view('admin.QL_sanpham', compact('sanpham','sanpham1', 'type', 'nameproduct','url_canonical'));

    }


    public function DelAdmin_Sp($id, Request $request)
    {
        $sp1 = Product::findOrfail($id);
        $gallery = Gallery::where('gallery_product_id',$id)->get();
        if ($sp1) {
            foreach ($gallery as $value) {
                unlink(public_path('source/image/gallery/').$value->gallery_image);
            }
            unlink(public_path('source/image/product/').$sp1->image);
            $sp1->delete();

            return response()->json([
                'status'=>200,
                'message'=>'Cập nhật thành công!'
            ]);
        }else{
            return response()->json([
                'status'=>404,
                'message'=>'Không tìm thấy sản phẩm!'
            ]);
        }

    }

    public function AddAdmin_Sp(Request $request){

        if(Session::get('locale') == 'vi' || Session::get('locale') == null){
            $validator = Validator::make($request->all(),
            [
                'sp_vi'=>'required',
                'sp_en'=>'required',
                'unit_price'=>'required',
                'promotion_price'=>'required',
                'image_file' => 'mimes:jpg,jpeg,png,gif|max:4096',

            ],
            [
                'sp_vi.required'=>'Vui lòng nhập tên Vi',
                'sp_en.required'=>'Vui lòng nhập tên En',
                'unit_price.required'=>'Vui lòng nhập số tiền',
                'promotion_price.required'=>'Vui lòng nhập số tiền khuyến mãi',
                'image_file.mimes' => 'Chỉ chấp nhận hình thẻ với đuôi .jpg .jpeg .png .gif',
                'image_file.max' => 'Hình ảnh giới hạn dung lượng không quá 4M',
            ]);
        }else{
            $validator = Validator::make($request->all(),
            [
                'sp_vi'=>'required',
                'sp_en'=>'required',
                'unit_price'=>'required',
                'promotion_price'=>'required',
                'image_file' => 'mimes:jpg,jpeg,png,gif|max:4096',

            ]);
        }
        if ($validator->fails()) {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages(),
            ]);
        }else{
            $sp_update = new Product();
            $up_nn = new Post();

            $up_nn->sp_vi = $request->sp_vi;
            $up_nn->sp_en = $request->sp_en;
            $up_nn->description_vi = $request->description_vi;
            $up_nn->description_en = $request->description_en;
            $up_nn->product_slug = $request->slug;
            $up_nn->save();

            $sp_update->id_post  = $up_nn->id_post;
            $sp_update->unit_price = $request->unit_price;
            $sp_update->promotion_price = $request->promotion_price;
            $sp_update->new = $request->new;
            $sp_update->product_quantity = $request->quantity;
            $sp_update->product_soid = 0;
            $sp_update->id_type  = $request->type;
            $sp_update->date_sale  = $request->date_sale;
            $sp_update->hours_sale  = $request->hours_sale;

            if ($request->hasFile('image_file')) {
                $image = $request->file('image_file');
                $name = time().'_'.$image->getClientOriginalName();
                $image->move(public_path('source/image/product'),$name);
                $sp_update->image = $name;
            }
            $sp_update->save();

            return response()->json([
                'status'=>200,
                'message'=>'Cập nhật thành công!'
            ]);
        }
    }

    public function editSp($id,Request $request){
        $product = Product::findOrfail($id);
        if ($product) {
            $postshow = Post::where('id_post',$product->id_post)->first();
            return response()->json([
                'status'=>200,
                'product'=>$product,
                'postshow'=>$postshow,
            ]);
        }else{
            return response()->json([
                'status'=>400,
                'message'=>'Product Not Found',
            ]);
        }
    }
    public function upproduct(Request $request, $id){
        if(Session::get('locale') == 'vi' || Session::get('locale') == null){
            $validator = Validator::make($request->all(),
            [
                'sp_vi'=>'required',
                'sp_en'=>'required',
                'unit_price'=>'required',
                'promotion_price'=>'required',
                'image' => 'mimes:jpg,jpeg,png,gif|max:4096',

            ],
            [
                'sp_vi.required'=>'Vui lòng nhập tên Vi',
                'sp_en.required'=>'Vui lòng nhập tên En',
                'unit_price.required'=>'Vui lòng nhập số tiền',
                'promotion_price.required'=>'Vui lòng nhập số tiền khuyến mãi',
                'image.mimes' => 'Chỉ chấp nhận hình thẻ với đuôi .jpg .jpeg .png .gif',
                'image.max' => 'Hình ảnh giới hạn dung lượng không quá 4M',
            ]);
        }else{
            $validator = Validator::make($request->all(),
            [
                'sp_vi'=>'required',
                'sp_en'=>'required',
                'unit_price'=>'required',
                'promotion_price'=>'required',
                'image' => 'mimes:jpg,jpeg,png,gif|max:4096',

            ]);
        }
        if ($validator->fails()) {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages(),
            ]);
        }else{
            $sp_update = Product::findOrfail($id);
            $up_nn = Post::where('id_post',$sp_update->id_post)->first();

            $up_nn->sp_vi = $request->sp_vi;
            $up_nn->sp_en = $request->sp_en;
            $up_nn->description_vi = $request->description_vi;
            $up_nn->description_en = $request->description_en;
            $up_nn->product_slug = $request->slug;
            $up_nn->save();

            $sp_update->unit_price = $request->unit_price;
            $sp_update->promotion_price = $request->promotion_price;
            $sp_update->new = $request->new;
            $sp_update->product_quantity = $request->quantity;
            $sp_update->product_soid = $sp_update->product_soid;
            $sp_update->id_type  = $request->type;
            $sp_update->date_sale  = $request->date_sale;
            $sp_update->hours_sale  = $request->hours_sale;

            if ($request->hasFile('image_file')) {
                unlink(public_path('source/image/product/').$sp_update->image);
                $image = $request->file('image_file');
                $name = time().'_'.$image->getClientOriginalName();
                $image->move(public_path('source/image/product'),$name);
                $sp_update->image = $name;
            }
            $sp_update->save();

            return response()->json([
                'status'=>200,
                'message'=>'Cập nhật thành công!'
            ]);
        }
    }

    public function active_sp($id){
        Product::where('id',$id)->update(['new'=>0]);
        return redirect()->back();
    }
    public function unactive_sp($id){
        Product::where('id',$id)->update(['new'=>1]);
        return redirect()->back();
    }
}

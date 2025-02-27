<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\Product;
use Session;

class GalleryController extends Controller
{

    public function edit($id){
    	$pro_id = $id;
    	Session::put('gallery_session',$pro_id);

    	if (Session::get('gallery_session')) {
        $name_product = Product::where('id',Session::get('gallery_session'))->first();
        return view('admin.QL_Gallery',compact('pro_id','name_product'));
      }else{
        return view('admin.QL_Gallery',compact('pro_id'));
      }

    }
    public function store(Request $request){
      	$product_id = $request->pro_id;
      	$gallery = Gallery::where('gallery_product_id',$product_id)->get();

      	$gallery_count = $gallery->count();
      	$products = Product::find($product_id);

      	$output = '';
      	if ($gallery_count>0) {
          	$i = 0;
          	foreach ($gallery as $key => $value) {
              	$i++;
              	$output .='

              	<tr>
              	<td scope="row">'.$i.'</td>
                  <td contenteditable class="edit_gallery_name" data-gal_id="'.$value->gallery_id.'">'.$value->gallery_name .'</td>
                  <td>
                      <img src="'.url('source/image/gallery/'.$value->gallery_image).'"" width="100px" height="100px" class="img-thumbnail">';
                  if($products->image != $value->gallery_image){
              	$output .='
                      <input type="file" class="file_image form-control" style="width: 40%;" name="file" data-gal_id="'.$value->gallery_id.'" id="file-'.$value->gallery_id.'" accept="image/*" multiple="">';
                  }
              	$output .='
                  	</td>
                  	<td class="center" style="vertical-align: middle;">';
                  	if($products->image != $value->gallery_image){
                  	$output .='
                      <button type="button" data-gal_id="'.$value->gallery_id.'" class="btn btn-danger delete-gallery">Delete</button>';
                  	}else{
                      $output .='
                      <button type="button" disabled class="btn btn-danger delete-gallery">Delete</button>';
                  	}
              	$output .='
                  	</td>
              	</tr>';
        	}
      	}else{
          	$output .='
              	<tr>
                  	<td colspan="4" style="text-align:center;font-size:20px;color:red;font-weight:bold;">
                    	Sản phẩm chưa có ảnh
                  	</td>
              	</tr>';
      	}

      	return response()->json([
      		'data'=>$output
      	]);
    }
    public function add(Request $request,$id){
      $get_img = $request->file('file');

      if ($get_img) {
        foreach ($get_img as $key => $image) {
            $text = $image->getClientOriginalExtension();
            $name = rand(0,99).'_'.time().'_'.$image->getClientOriginalName();
            $image->move(public_path('source/image/gallery'),$name);
            $gallery = new Gallery();
            $gallery->gallery_name = $name;
            $gallery->gallery_image = $name;
            $gallery->gallery_product_id = $id;
            $gallery->save();
        }
      }
      return back();
    }
    public function update(Request $request){
         $get_img = $request->file('file');
         $up_id = $request->up_id;
         if ($get_img) {
            $text = $get_img->getClientOriginalExtension();
            $name = rand(0,99).'_'.time().'_'.$get_img->getClientOriginalName();
            $get_img->move('source/image/gallery',$name);
            $gallery = Gallery::find($up_id);
            unlink(public_path('source/image/gallery/').$gallery->gallery_image);
            $gallery->gallery_image = $name;

            $gallery->save();
         }
    }
    public function update_name(Request $request){
        $gal_id = $request->gal_id;
        $gal_text = $request->gal_text;
        $gallery = Gallery::find($gal_id);
        $gallery->gallery_name = $gal_text;
        $gallery->save();
    }
    public function delete(Request $request){
      	$del_id = $request->del_id;
      	$gallery = Gallery::find($del_id);
      	unlink(public_path('source/image/gallery/').$gallery->gallery_image);
      	$gallery->delete();
    }
}

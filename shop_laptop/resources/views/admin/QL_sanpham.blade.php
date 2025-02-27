 @extends('admin/Admin')
 @section('title-ad')
    {{ trans('home_ad.ql_sp') }}
@endsection
 @section('content-ad')
    <div class="card shadow mb-4" >
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ trans('home_ad.ql_sp') }}</h6>
        </div>
        <div style="margin-top: 25px; margin-bottom: 1px; margin-left: 22px">
            <table>
                <tr>
                    <button class="btn btn-outline-primary add" data-toggle="modal" type="button">
                        <i class="fa fa-plus" aria-hidden="true"></i> {{ trans('home_ad.add') }}
                    </button>
                </tr>
                <tr>
                    <button style="margin-left: 10px" type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#ExcelProduct"><i class="fas fa-file-excel"></i>
                        {{ trans('home_ad.import') }} / {{ trans('home_ad.export') }} Excel
                    </button>
                </tr>
            </table>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="scroll-table">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead style="text-align: center;">
                        <tr style="vertical-align: middle !important;">
                            <th>STT</th>
                            <th>{{ trans('Ql_sp.tensp') }}</th>
                            <th>Gallery</th>
                            <th>{{ trans('Ql_sp.soluong') }}</th>
                            <th>{{ trans('Ql_sp.gia') }}</th>
                            <th>{{ trans('Ql_sp.giauudai') }}</th>
                            <th>{{ trans('Ql_sp.hinhanh') }}</th>
                            <th>{{ trans('Ql_sp.hieusp') }}</th>
                            <th>{{ trans('Ql_sp.new_top') }}</th>
                            <th>{{ trans('Ql_sp.sua_xoa') }}</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>STT</th>
                            <th>{{ trans('Ql_sp.tensp') }}</th>
                            <th>Gallery</th>
                            <th>{{ trans('Ql_sp.soluong') }}</th>
                            <th>{{ trans('Ql_sp.gia') }}</th>
                            <th>{{ trans('Ql_sp.giauudai') }}</th>
                            <th>{{ trans('Ql_sp.hinhanh') }}</th>
                            <th>{{ trans('Ql_sp.hieusp') }}</th>
                            <th>{{ trans('Ql_sp.new_top') }}</th>
                            <th>{{ trans('Ql_sp.sua_xoa') }}</th>
                        </tr>
                    </tfoot>
                    <tbody style="text-align: center;">
                    @foreach($sanpham1 as $key => $sp)
                       <tr>
                            <td>{{$key+=1}}</td>
                            <td>
                                @if(config('app.locale') != 'vi') 
                                    {{$sp->sp_en}}
                                @else
                                    {{$sp->sp_vi}}
                                @endif
                            </td>
                            <td>
                                @php
                                    $gallery = App\Models\Gallery::where('gallery_product_id',$sp->id)->get()->count();
                                @endphp
                                @if ($gallery >= 5)
                                    <a class="btn btn-outline-success btn-sm" href="{{ route('gallelyedit',$sp->id) }}">Gallery ({{$gallery}})</a>
                                @elseif($gallery >= 3 && $gallery < 5)
                                    <a class="btn btn-outline-warning btn-sm" href="{{ route('gallelyedit',$sp->id) }}">Gallery ({{$gallery}})</a>
                                @else
                                    <a class="btn btn-outline-danger btn-sm" href="{{ route('gallelyedit',$sp->id) }}">Gallery ({{$gallery}})</a>
                                @endif
                            </td>
                            <td>{{$sp->product_quantity}}</td>
                            <td>{{number_format($sp->unit_price,0,',','.')}}</td>
                            <td>{{number_format($sp->promotion_price,0,',','.')}}</td>
                            <td><img src="{{ asset('source/image/product/'.$sp->image) }}" alt="" width="100px"></td>

                            <td>{{$sp->product_type->name_type}}</td>

                            <td>
                                <?php
                                   if($sp->new==1){
                                    ?>
                                    <a href="{{url('/active-sp/'.$sp->id)}}"><span class="far fa-thumbs-up"></span></a>
                                    <?php
                                     }else{
                                    ?>  
                                     <a href="{{url('/unactive-sp/'.$sp->id)}}"><span style="color: #e74a3b;" class="far fa-thumbs-down"></span></a>
                                    <?php
                                   }
                                  ?>
                            </td>
                            <td style="width: 100px">
                                <button class="btn btn-outline-primary edit" data-toggle="modal" data-id_pro="{{$sp->id}}" type="button"><i class="fas fa-pencil-alt"></i></button> 

                                <button class="btn btn-outline-danger delete" data-toggle="modal" data-id_pro="{{$sp->id}}" type="button"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>

                </table>
                </div>

        </div>


    </div>

     <!-- Import Export Excel -->
    <div class="modal" id="ExcelProduct">
        <div class="modal-dialog">
          <div class="modal-content">
          
            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">{{ trans('home_ad.import') }} / {{ trans('home_ad.export') }} Excel</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <div style="margin-top: 15px; margin-bottom: 10px; margin-left: 22px">
                    <table>
                        <tr>
                            <form action="{{url('/export-excel-product')}}" method="POST">
                                @csrf
                                <button class="btn btn-outline-success" type="submit" name="export_product">
                                    <i class="fas fa-file-export" aria-hidden="true"></i> {{ trans('home_ad.export') }} Excel
                                </button>
                            </form>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Modal footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
            
          </div>
        </div>
    </div>   

    <!-- Modal Add & Update -->
    <div class="modal fade" id="samepleModal" tabindex="-1" role="dialog" style="z-index: 1050; display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header text-write">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="ti-close">&times;</i></span>
                    </button>
                </div>
                <form method="post" enctype="multipart/form-data" id="sampleSubmit">
                    <input type="hidden" name="id_hidden" id="id_hidden" value="">
                    <div class="modal-body">
                        <div class="form-group">
                            <label style="font-weight: bold; color: #000">{{ trans('Ql_sp.tensp') }} Vi</label>
                            <input type="text" id="sp_vi" name="sp_vi" class="form-control"  onkeyup="ChangeToSlug();"/>

                            <label style="font-weight: bold; color: #000">{{ trans('Ql_sp.tensp') }} En</label>
                            <input type="text" id="sp_en" name="sp_en" class="form-control"  />
                            <label style="font-weight: bold; color: #000">Slug</label>
                            <input type="text" id="slug" name="slug" class="form-control"  />
                        </div>
                        <div class="form-group">
                            <label style="font-weight: bold; color: #000" >{{ trans('Ql_sp.mota') }} vi</label>
                            <textarea id="description_vi" class="form-control" name="description_vi" rows="5" cols="33"></textarea>
                        </div>
                        <div class="form-group">
                            <label style="font-weight: bold; color: #000" >{{ trans('Ql_sp.mota') }} en</label>
                            <textarea id="description_en" class="form-control" name="description_en" rows="5" cols="33"></textarea>
                        </div>
                        <div class="form-group">
                            <label style="font-weight: bold; color: #000" >{{ trans('Ql_sp.soluong') }}</label>
                            <input type="number" id="quantity" name="quantity" class="form-control"  value="1" />
                        </div>
                        <div class="form-group">
                            <label style="font-weight: bold; color: #000" >{{ trans('Ql_sp.gia') }}</label>
                            <input type="number" id="e_unit_price" name="unit_price" class="form-control"  />
                        </div>
                        <div class="form-group">
                            <label style="font-weight: bold; color: #000" >{{ trans('Ql_sp.giauudai') }}</label>
                            <input type="number" id="e_promotion_price" name="promotion_price" class="form-control" value="0" />
                        </div>
                        <div class="form-group">
                            <label style="font-weight: bold; color: #000" >Date Sale</label>
                            <input type="text" id="date_sale_product" name="date_sale" class="form-control" value="{{Carbon\Carbon::now()->format('Y/m/d')}}" />
                        </div>
                        <div class="form-group">
                            <label style="font-weight: bold; color: #000" >Hours Sake</label><br>
                             <input type="time" name="hours_sale" id="hours_sale" class="form-control" value="{{Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format('H:m')}}">
                        </div>
                         <div class="form-group" style="font-weight: bold; color: #000">
                            <label style="font-weight: bold; color: #000">{{ trans('Ql_sp.new_top') }}</label>
                            <select name="new" class="form-control" id="new">
                                <option value="1">New</option>
                                <option value="0">Not New</option>
                            </select>
                        </div>
                        <div class="form-group" style="font-weight: bold; color: #000">
                            <label style="font-weight: bold; color: #000">{{ trans('Ql_sp.hieusp') }}</label>
                            <select name="type" class="form-control" id="type_pro">
                                @foreach($type as $key=> $tpe)
                                    <option value="{{$tpe->id}}">{{$tpe->name_type}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label style="font-weight: bold; color: #000">{{ trans('Ql_sp.hinhanh') }}</label>
                            <input type="file" id="e_image" name="image_file" class="form-control" multiple onchange="ImageFileUrl()"/>
                            <div id="displayimg"></div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="icofont icofont-eye-alt"></i>Close</button>
                        <input type="hidden" name="action" id="action" />
                        <button type="submit"  class="btn btn-primary"><i class="icofont icofont-check-circled"></i>Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div> 

    <!-- Modal Del -->
    <div class="modal fade" id="showDel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Bạn muốn xóa?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Bạn muốn xóa "<span id="namedel"></span>"</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Huỷ bỏ</button>
                    <input type="hidden" name="id_hidden" id="id_hidden" value="">
                    <form method="post" id="deleteSubmit">
                        <button type="submit" class="btn btn-danger">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<style type="text/css">
    .col-md-4{
        height: calc(1.5em + .75rem + 2px);
        padding: .375rem .75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #6e707e;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #d1d3e2;
        border-radius: .35rem;
        transition: border-color .15s;
        margin-bottom: 1rem;
    }
    #displayimg img{
        margin-top: 10px;
        width: 200px;
    }
</style>
@endsection
@section('js')
<script type="text/javascript">
    function CKupdate(){
        for(instance in CKEDITOR.instance){
            CKEDITOR.instances['description_vi'].updateElement();
            CKEDITOR.instances['description_en'].updateElement();
        }
    }
    CKEDITOR.replace('description_en');
    CKEDITOR.replace('description_vi');

    $(document).ready(function (){
        // Show Add
        $('.add').click(function(e){
            $('#sampleSubmit')[0].reset();
            $('#samepleModal').modal('show');
            $('#action').val('add');
            $('.modal-title').text('Add Product');
            $('#displayimg').html('');
            CKupdate();
            $('#description_vi').text('');
            $('#description_en').text('');
            CKEDITOR.instances['description_vi'].setData(description_vi);
            CKEDITOR.instances['description_en'].setData(description_en);
        });
        // Show Edit
        $(document).on('click','.edit',function(e){
            e.preventDefault();
            var id = $(this).data('id_pro');
            $('#samepleModal').modal('show');

            $.ajax({
                type: 'get',
                url: 'ql-san-pham/'+id+'/edit',
                dataType: 'json',
                success:function(response){
                    if (response.status == 200) {
                        $('#action').val('edit');
                        $('.modal-title').text('Edit Product');
                        $('#id_hidden').val(id);
                        $('#sp_vi').val(response.postshow.sp_vi);
                        $('#sp_en').val(response.postshow.sp_en);
                        $('#slug').val(response.postshow.product_slug);
                        CKupdate();
                        $('#description_vi').text(response.postshow.description_vi);
                        $('#description_en').text(response.postshow.description_en);
                        CKEDITOR.instances['description_vi'].setData(description_vi);
                        CKEDITOR.instances['description_en'].setData(description_en);

                        $('#quantity').val(response.product.product_quantity);
                        $('#e_unit_price').val(response.product.unit_price);
                        $('#e_promotion_price').val(response.product.promotion_price);
                        $('#date_sale_product').val(response.product.date_sale);
                        $('#hours_sale').val(response.product.hours_sale);
                        $('#new').val(response.product.new);
                        $('#type_pro').val(response.product.id_type);
                        $('#displayimg').html('<img src="source/image/product/'+response.product.image+'" alt="" width="200px">');

                    }else{
                        toastr.error(response.message, 'Notification',{timeOut: 7000});
                    }
                }
            });
        });
        // Add & Update
        $(document).on('submit','#sampleSubmit',function(e){
            e.preventDefault();
            var id_pro = $('#id_hidden').val();
            var data = new FormData(this);
            var action_url = '';

            if($('#action').val() == 'add')
            {
                action_url = "{{route('addnewsp')}}";
            }

            if($('#action').val() == 'edit')
            {
                action_url = 'ql-san-pham/update/'+id_pro;
            }

            $.ajax({
                type: "post",
                url: action_url,
                data: data,
                contentType: false,
                processData: false,
                dataType: "json",
                success:function(response){
                    if (response.status == 200) {
                        $('#spUpdate').modal('hide');
                        location.reload();
                        toastr.success(response.message,'Notification');
                    }else{
                        $.each(response.errors, function(key, err_values){
                            toastr.error(err_values, 'Notification',{timeOut: 7000});
                        });
                    }
                }
            });

        });
        // Show Delete
        $(document).on('click','.delete',function(e){
            e.preventDefault();
            var id = $(this).data('id_pro');
            $('#showDel').modal('show');

            $.ajax({
                type: 'get',
                url: 'ql-san-pham/'+id+'/edit',
                dataType: 'json',
                success:function(response){
                    if (response.status == 200) {
                        $('#id_hidden').val(id);
                        $('#namedel').text(response.postshow.sp_vi);
                    }else{
                        toastr.error(response.message, 'Notification',{timeOut: 7000});
                    }
                }
            });
        });
        // Delete
        $(document).on('submit','#deleteSubmit',function(e){
            e.preventDefault();
            var id_pro = $('#id_hidden').val();

            $.ajax({
                type: "post",
                url: 'sp/'+id_pro+'/delete',
                dataType: "json",
                success:function(response){
                    if (response.status == 200) {
                        $('#showDel').modal('hide');
                        location.reload();
                        toastr.success(response.message,'Notification');
                    }else{
                        toastr.error(response.message, 'Notification',{timeOut: 7000});
                    }
                }
            });

        });
    });
</script>
@endsection
 @extends('admin/Admin')
 @section('title-ad')
    Gallery
@endsection
@section('content-ad')
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{route('galleryadd',$pro_id)}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="custom-file col-sm-8">
                    <input type="file" id="file" name="file[]" class="custom-file-input" accept="image/*" multiple="">
                    <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                    <div class="invalid-feedback">Example invalid custom file feedback</div>
                </div>
                
                <input type="submit" name="" class="btn btn-primary" value="Submit" style="margin-top: 0.5%;  margin-left: 3%;">
                <span id="error_gallery"></span>
            </form>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Gallery</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <input type="hidden" name="pro_id" class="pro_id" value="{{$pro_id}}">
                <table class="table table-striped" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col">STT</th>
                            <th scope="col">Name</th>
                            <th scope="col">Image</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="load_data">
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection
@section('js')
<script type="text/javascript">
    $(document).ready(function (){
        // load data
        load_gallery();
        function load_gallery(){
            var pro_id = $('.pro_id').val();

            $.ajax({
                type: 'post',
                url : '{{ route('gallerystore') }}',
                data:{pro_id:pro_id},
                dataType: 'json',
                success:function(response){
                    $('#load_data').html(response.data);
                }

            });
        }
        // Check Image
        $('#file').change(function(){
            var error = '';
            var files = $('#file')[0].files;
            // alert(files.length);
            if(files.length>4){
              error +='<p>Tối đa 4 ảnh</p>';
            }else if(files.length == ''){
              error +='<p>Vui lòng chọn ảnh</p>';
            }else if(files.size > 5000000){
              error +='<p>File ảnh không quá 5MB</p>';
            }

            if (error == '') {

            }else{
              $('#file').val('');
              toastr.error(error,'Notification');
              return false;
            }
        });
        // Update
        $(document).on('change','.file_image',function(){
            var up_id = $(this).data('gal_id');
            var image = document.getElementById('file-'+up_id).files[0];
            var form_data = new FormData();
            form_data.append("file",document.getElementById('file-'+up_id).files[0]);
            form_data.append("up_id",up_id);

            $.ajax({
                url : '{{route('galleryupdate')}}',
                method: 'POST',
                data:form_data,
                contentType:false,
                cache:false,
                processData:false,
                success:function(data){
                    load_gallery();
                    toastr.success('Cập nhật thành công','Notification');
                }

            });
        });
        // Delete
        $(document).on('click','.delete-gallery',function(){
            var del_id = $(this).data('gal_id');

            if (confirm('Bạn chắc chắn muốn xóa?')) {
              $.ajax({
                type: 'post',
                url : '{{route('gallerydelete')}}',
                data:{del_id:del_id},
                success:function(data){
                    load_gallery();
                    toastr.success('Xóa thành công','Notification');
                }

              });
            }
        });
        // Update Name
        $(document).on('blur','.edit_gallery_name',function(){
            var gal_id = $(this).data('gal_id');
            var gal_text = $(this).text();
            $.ajax({
              url : '{{route('galleryupdatename')}}',
              method: 'POST',
              data:{gal_id:gal_id,gal_text:gal_text},
              success:function(data){
                load_gallery();
                toastr.success('Cập nhật thành công','Notification');
              }

            });
        });
    });
</script>
@endsection
@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        {{-- ========<upload images> --}}
        <div id="upload-part">
            <input type="file" name="multiple_files[]" id="multiple_files" multiple/>
            <span class="text-muted">Only .jpg, .png, .gif file allowed</span>
            <span id="error_multiple_files"></span>
        </div>
        {{-- ========</upload images> --}}
        <div class="col-md-8">
            <div class="card" id="outer-container">
                <div class="card-header" id="outer-container-header">Gallery</div>

                <div class="card-body" id="outer-container-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row justify-content-center" id="main-container">
                        {{-- <div class="card" id="photo-container">
                            <div class="card-body" id="photo-body">
                                <img src="{{URL('/images/dog.jpg')}}" id="photo-og"> 
                            </div>
                            <div class="card-footer" id="photo-footer">
                                <button class="btn btn-danger">Delete</button>
                            </div>
                        </div>
                        <div class="card" id="photo-container">
                            <div class="card-body" id="photo-body">
                                <img src="{{URL('/images/dog2.jpg')}}" id="photo-og"> 
                            </div>
                            <div class="card-footer"  id="photo-footer">
                                <button class="btn btn-danger">Delete</button>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    // alert("hello");
    load_image_data();

    function load_image_data(){
        $.ajax({
            url:"/fetch",
            method:"POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:function(data){
                $('#main-container').html(data);
                // console.log(data);
            },
            error : function(request, status, error) {
                var val = request.responseText;
                console.log("error:"+val);
            }
        });
    }

    $('#multiple_files').change(function(){
        var error_images = '';
        var form_data = new FormData();
        var files = $('#multiple_files')[0].files;
        if(files.length > 10) {
            error_images += 'You cannot select more than 10 files';
        } else {
            for( var i = 0; i < files.length; i++) {
                var name = document.getElementById("multiple_files").files[i].name;
                var ext = name.split('.').pop().toLowerCase();
                if(jQuery.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                    error_images += '<p>Tnvalid ' + i + ' File</p>';
                } else{
                    form_data.append('file[]', document.getElementById('multiple_files').files[i]);
                }
            }
        }
        if (error_images == '') {
            $.ajax({
                url: '/upload',
                method: 'POST',
                data: form_data,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function(){
                    $('#error_multiple_files').html("<br><label class='text-primary'>Uploading...</label>")
                },
                success: function(){
                    console.log('success');
                    $('#error_multiple_files').html("<br><label class='text-success'>Uploaded</label>")
                    load_image_data();
                },
                error : function(request, status, error) {
                    var val = request.responseText;
                    console.log("error:"+val);
                }
            });
        } else {
            $('#multiple_files').val('');
            $('#error_multiple_files').html("<span class='text-danger'>"+error_images+"</span")
            return false;
        }
    });

    $(document).on('click', '.delete-button', function(){
        var img_id = $(this).attr("id");
        console.log(img_id);
        if(confirm("Are you sure you want to remove it?")){
            $.ajax({
                url: "/delete",
                method: 'POST',
                data: {img_id:img_id},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(data){
                    if(data==1){
                        // alert("Image removed");
                        load_image_data();
                    } else{
                        // alert("failed");
                    }
                    
                },
                error : function(request, status, error) {
                    var val = request.responseText;
                    console.log("error"+val);
                }
            });
        }
    });

    // $(document).on('click', '.download-button', function(){
    //     var img_id = $(this).attr("id");
    //     console.log(img_id);
    //     $.ajax({
    //         url: "/download",
    //         method: 'POST',
    //         data: {img_id:img_id},
    //         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    //         success: function(data){
    //             return data;
    //         },
    //         error : function(request, status, error) {
    //             var val = request.responseText;
    //             console.log("error"+val);
    //         }
    //     });
    // });
});
</script>

@endsection

@extends('layouts.admin.master')

@section('page')
Employee Create
@endsection

@push('css')
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div id="success_message"></div>

            <div id="error_message"></div>

            <div class="card card-default">
                <div class="card-header">@yield('page')</div>

                <div class="card-body">
                    <form action="" method="post" id="employee_post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="control-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                        </div>

                        <div class="form-group row">
                            <label for="email" class="control-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>

                        <div class="form-group row">
                            <label for="designation" class="control-label">Designation</label>
                            <input type="text" name="designation" id="designation" class="form-control">
                        </div>

                        <div class="form-group row">
                            <label for="salary" class="control-label">Salary</label>
                            <input type="text" name="salary" id="salary" class="form-control">
                        </div>

                        <div class="form-group col-md-12">
                            <label for="address" class="control-label">Address</label>
                            <textarea name="address" id="address" class="textarea" placeholder="Place some text here" style="width: 100%; height: 400px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="control-label">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control">
                        </div>

                        <div class="form-group row">
                            <label for="country" class="control-label">Country</label>
                            <input type="text" name="country" id="country" class="form-control">
                        </div>

                        <div class="form-group row">
                            <label for="city" class="control-label">City</label>
                            <input type="text" name="city" id="city" class="form-control">
                        </div>

                        <div class="form-group row">
                            <label for="zip_code" class="control-label">Zip Code</label>
                            <input type="text" name="zip_code" id="zip_code" class="form-control">
                        </div>


                        <div class="form-group col-md-12">
                            <label for="image">Image</label>
                            <input type="file" name="image" id="image" class="form-control">
                            <br>
                            <div id="image-holder"></div>
                        </div>

                        <div class="form-group">
                            <a href="{{ route('employee') }}" class="btn btn-warning">Back</a>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $("#image").on('change', function () {

            if (typeof (FileReader) != "undefined") {

                var image_holder = $("#image-holder");
                image_holder.empty();

                var reader = new FileReader();
                reader.onload = function (e) {
                    $("<img />", {
                        "src": e.target.result,
                        "class": "thumb-image",
                        "width": "100px",
                        "height": "100px"
                    }).appendTo(image_holder);

                }
                image_holder.show();
                reader.readAsDataURL($(this)[0].files[0]);
            } else {
                alert("This browser does not support FileReader.");
            }
        });

        $(document).ready(function () {

            $("#employee_post").on("submit",function (e) {
                e.preventDefault();

                var formData = new FormData( $("#employee_post").get(0));

                $.ajax({
                    url : "{{ route('employee.store') }}",
                    type: "post",
                    data: formData,
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {

                        if(data.flash_message_success) {
                            $('#success_message').html('<div class="alert alert-success">\n' +
                                '<button class="close" data-dismiss="alert">×</button>\n' +
                                '<strong>Success! '+data.flash_message_success+'</strong> ' +
                                '</div>');
                        }else {

                            $('#error_message').html('<div class="alert alert-error">\n' +
                                '<button class="close" data-dismiss="alert">×</button>\n' +
                                '<strong>Error! '+data.error+'</strong>' +
                                '</div>');
                        }

                        $("form").trigger("reset");

                        $('.form-group').find('.valids').hide();
                    }
                });
            })
        })
    </script>
@endpush
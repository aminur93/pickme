@extends('layouts.admin.master')

@section('page')
Employee Edit
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

                <div class="card-body" id="edit_form_body">
                    <form action="" method="post" id="employee_edit" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" id="employee_id" value="{{ $employee->id }}">

                        <div class="form-group row">
                            <label for="name" class="control-label">Name</label>
                            <input type="text" value="{{ $employee->name }}" name="name" id="name" class="form-control">
                        </div>

                        <div class="form-group row">
                            <label for="email" class="control-label">Email</label>
                            <input type="email" value="{{ $employee->email }}" name="email" id="email" class="form-control">
                        </div>

                        <div class="form-group row">
                            <label for="designation" class="control-label">Designation</label>
                            <input type="text" value="{{ $employee->designation }}" name="designation" id="designation" class="form-control">
                        </div>

                        <div class="form-group row">
                            <label for="salary" class="control-label">Salary</label>
                            <input type="text" value="{{ $employee->salary }}" name="salary" id="salary" class="form-control">
                        </div>

                        <div class="form-group col-md-12">
                            <label for="address" class="control-label">Address</label>
                            <textarea name="address" id="address" class="textarea" placeholder="Place some text here" style="width: 100%; height: 400px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ $employee->address }}</textarea>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="control-label">Phone</label>
                            <input type="text" value="{{ $employee->phone }}" name="phone" id="phone" class="form-control">
                        </div>

                        <div class="form-group row">
                            <label for="country" class="control-label">Country</label>
                            <input type="text" value="{{ $employee->country }}" name="country" id="country" class="form-control">
                        </div>

                        <div class="form-group row">
                            <label for="city" class="control-label">City</label>
                            <input type="text" value="{{ $employee->city }}" name="city" id="city" class="form-control">
                        </div>

                        <div class="form-group row">
                            <label for="zip_code" class="control-label">Zip Code</label>
                            <input type="text" value="{{ $employee->zip_code }}" name="zip_code" id="zip_code" class="form-control">
                        </div>


                        <div class="form-group col-md-12">
                            <label for="image">Image</label>
                            <input type="file" name="image" id="image" class="form-control">
                            <input type="hidden" class="form-control" name="current_image" value="{{$employee->image}}">
                            <br>

                            @if (!empty($employee->image))
                                <div>
                                    <img src="{{ asset('assets/admin/uploads/medium/'.$employee->image) }}" alt="" width="100px">

                                    <a rel="{{ $employee->id }}" rel1="/employee/delete_image" class="text-danger" id="image_delete">Remove Image</a>
                                </div>
                            @else
                                <div id="image-holder"></div>
                            @endif

                        </div>

                        <div class="form-group">
                            <a href="{{ route('employee') }}" class="btn btn-warning">Back</a>
                            <button type="submit" class="btn btn-success">Edit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

    <script>
        $(document).ready(function () {
            $("#image_delete").on("click",function (e) {
                e.preventDefault();

                var id = $(this).attr('rel');
                var deleteFunction = $(this).attr('rel1');

                swal({
                        title: "Are You Sure?",
                        text: "You will not be able to recover this record again",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Yes, Delete It"
                    },
                    function(){
                        $.ajax({
                            type: "GET",
                            url: deleteFunction+'/'+id,
                            data: {id:id},
                            success: function (data) {

                                if(data.flash_message_success) {
                                    $('#success_message').html('<div class="alert alert-success">\n' +
                                        '<button class="close" data-dismiss="alert">×</button>\n' +
                                        '<strong>Success! '+data.flash_message_success+'</strong> ' +
                                        '</div>');
                                }

                                editForm();
                            }
                        });
                    });
            })
        })

        function editForm() {
            $('#edit_form_body').load(' #edit_form_body');
        }

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
            $("#employee_edit").on("submit",function (e) {
                e.preventDefault();

                var id = $("#employee_id").val();

                var formData = new FormData( $("#employee_edit").get(0));

                $.ajax({
                    url: "{{ route('employee.update','') }}/"+id,
                    type: "post",
                    data: formData,
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if(data.flash_message_success) {
                            $('#success_message').html(' <div class="alert alert-success alert-block">\n' +
                                '                <button type="button" class="close" data-dismiss="alert">x</button>\n' +
                                '               <strong>' + data.flash_message_success + '</strong>\n' +
                                '            </div>');
                        }else {

                            $('#error_message').html(' <div class="alert alert-danger alert-block">\n' +
                                '                <button type="button" class="close" data-dismiss="alert">x</button>\n' +
                                '               <strong>' + data.error + '</strong>\n' +
                                '            </div>');
                        }

                        $("form").trigger("reload");
                    }
                });
            })
        })
    </script>
@endpush
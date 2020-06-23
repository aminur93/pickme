@extends('layouts.admin.master')

@section('page')
Users Edit
@endsection

@push('css')
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div id="success_message"></div>

            <div id="error_message"></div>

            <div class="card card-primary">
                <div class="card-header">@yield('page')</div>

                <div class="card-body">
                    <form action="" method="post" id="user_edit">
                        @csrf

                        <input type="hidden" id="user_id" value="{{ $data->id }}">

                        <div class="form-group row">
                            <label for="name" class="control-label">Name</label>
                            <input type="text" value="{{ $data->name }}" name="name" id="name" class="form-control">
                        </div>

                        <div class="form-group row">
                            <label for="email" class="control-label">Email</label>
                            <input type="email" value="{{ $data->email }}" name="email" id="email" class="form-control">
                        </div>

                        <div class="form-group row">
                            <label for="password" class="control-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>

                        <div class="form-group row">
                            <label for="role" class="control-label">Role</label>
                            <select name="role" id="role" class="form-control">
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" @if($data->role_id == $role->id) selected @endif>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <a href="{{ route('user') }}" class="btn btn-warning">Back</a>
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

            $("#user_edit").on("submit",function (e) {
                e.preventDefault();

                var id = $("#user_id").val();

                var formData = $("#user_edit").serializeArray();

                $.ajax({
                    url : "{{ route('user.update','') }}/"+id,
                    type: "post",
                    data: $.param(formData),
                    dataType: "json",
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

                        $("form").trigger("reload");

                        $('.form-group').find('.valids').hide();
                    }
                });
            })
        })
    </script>
@endpush
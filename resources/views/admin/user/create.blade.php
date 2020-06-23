@extends('layouts.admin.master')

@section('page')
Users Create
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
                    <form action="" method="post" id="user_post">
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
                            <label for="password" class="control-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>

                        <div class="form-group row">
                            <label for="role" class="control-label">Role</label>
                            <select name="role" id="role" class="form-control">
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <a href="{{ route('user') }}" class="btn btn-warning">Back</a>
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
        $(document).ready(function () {

            $("#user_post").on("submit",function (e) {
                e.preventDefault();

                var formData = $("#user_post").serializeArray();

                $.ajax({
                    url : "{{ route('user.store') }}",
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

                        $("form").trigger("reset");

                        $('.form-group').find('.valids').hide();
                    }
                });
            })
        })
    </script>
@endpush
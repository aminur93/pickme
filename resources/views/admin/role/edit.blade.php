@extends('layouts.admin.master')

@section('page')
Roles Edit
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
                    <form action="" method="post" id="role_edit">
                        @csrf

                        <input type="hidden" id="role_id" value="{{ $role->id }}">

                        <div class="form-group row">
                            <label for="name" class="control-label">Name</label>
                            <input type="text" value="{{ $role->name }}" name="name" id="name" class="form-control">
                        </div>

                        <strong>Permission:</strong>
                        <br/>

                        @foreach($permission->chunk(2) as $chunk)
                            <div class="row">
                                @foreach($chunk as $add)
                                    <div class="col-md-6">
                                        <label>
                                            <input type="checkbox" name="permission[]" value="{{ $add->id }}" @if(in_array($add->id, $rolePermissions)) checked @endif> {{ $add->name }}
                                        </label>
                                        <br/>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        <div class="form-group">
                            <a href="{{ route('role') }}" class="btn btn-warning">Back</a>
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

            $("#role_edit").on("submit",function (e) {
                e.preventDefault();

                var id = $("#role_id").val();

                var formData = $("#role_edit").serializeArray();

                $.ajax({
                    url : "{{ route('role.update','') }}/"+id,
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
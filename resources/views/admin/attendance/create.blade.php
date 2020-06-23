@extends('layouts.admin.master')

@section('page')
Attendance Create
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
                    <form action="" method="post" id="attendance_post">
                        @csrf

                        <div class="form-group col-md-4">
                            <label for="name" class="control-label">Date</label>
                            <input type="date" name="date" id="date" class="form-control">
                        </div>

                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Attendance</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($employee as $value)
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" readonly value="{{ $value->name }}" class="form-control">
                                            <input type="hidden" name="employee_id[]" id="employee_id" value="{{ $value->id }}" >
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="checkbox" class="employee_att" name="attendance[]" id="attendance-{{ $value->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input readonly type="text" id="result-{{ $value->id }}" name="status[]" class="form-control">
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="form-group">
                            <a href="{{ route('attendance') }}" class="btn btn-warning">Back</a>
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

        $(document).on('click','.employee_att',function () {

            var id = $(this).attr('id');
            var idSplit = id.split('-');

            var first = idSplit[0];
            var second = idSplit[1];

            //alert(second);

            var att = $("#attendance-" + second).prop("checked");
            //console.log(att);


            if(att == true)
            {
                $("#result-" + second).val('Present');
            }else {
                $("#result-" + second).val('Absent');
            }
        });

        $(document).ready(function () {

            $("#attendance_post").on("submit",function (e) {
                e.preventDefault();

                var formData = $("#attendance_post").serializeArray();

                $.ajax({
                    url : "{{ route('attendance.store') }}",
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
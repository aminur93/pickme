@extends('layouts.admin.master')

@section('page')
Attendance View
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
                    <form action="" method="post" id="attendance_edit">
                        @csrf

                        <div class="form-group col-md-4">
                            <label for="name" class="control-label">Date</label>
                            <input type="date" value="{{ $dates }}" name="date" id="date" class="form-control">
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
                                @foreach($attendance as $att)
                                    @if ($value->id == $att->emplyee_id)
                                        

                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" readonly value="{{ $value->name }}" class="form-control">
                                            <input type="hidden" name="employee_id[]" id="employee_id" value="{{ $value->id }}" >
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="checkbox" @if($value->id == $att->emplyee_id && $att->attendance == 1) checked @endif class="employee_att" name="attendance[]" id="attendance-{{ $value->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input readonly type="text" value="{{ $att->status }}" id="result-{{ $value->id }}" name="status[]" class="form-control">
                                        </div>
                                    </td>
                                </tr>
                                    @endif
                                    @endforeach
                            @endforeach
                            </tbody>
                        </table>

                        <div class="form-group">
                            <a href="{{ route('attendance') }}" class="btn btn-warning">Back</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('js')
@endpush
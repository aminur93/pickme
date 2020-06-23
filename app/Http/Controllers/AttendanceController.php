<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Employee_attendance;
use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\Facades\DataTables;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:attendance-list|attendance-create|attendance-edit|attendance-delete', ['only' => ['index','store']]);
        $this->middleware('permission:attendance-create', ['only' => ['create','store']]);
        $this->middleware('permission:attendance-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:attendance-delete', ['only' => ['destroy']]);
    }
    
    public function index()
    {
        return view('admin.attendance.index');
    }
    
    public function create()
    {
        $employee = Employee::get();
        return view('admin.attendance.create',compact('employee'));
    }
    
    public function getData()
    {
        $att = DB::table('employee_attendances')
                ->select('employee_attendances.id','employee_attendances.date')
                ->groupBy('employee_attendances.date')
                ->get();
        //dd($att);
    
        return DataTables::of($att)
            ->addIndexColumn()
            ->editColumn('action', function ($att) {
                $return = "<div class=\"btn-group\">";
                if (!empty($att->date))
                {
                    $return .= "
                            <a href=\"/attendance/edit/$att->date\" style='margin-right: 5px' class=\"btn btn-sm btn-warning\"><i class='fa fa-eye'></i></a>
                            ||
                              <a rel=\"$att->date\" rel1=\"delete-attendance\" href=\"javascript:\" style='margin-right: 5px' class=\"btn btn-sm btn-danger deleteRecord \"><i class='fa fa-trash'></i></a>
                                  ";
                }
                $return .= "</div>";
                return $return;
            })
            ->rawColumns([
                'action'
            ])
            ->make(true);
    }
    
    public function store(Request $request)
    {
        if ($request->isMethod('post'))
        {
           
                $employee_id = $request->employee_id;
                $date = $request->date;
                $attendance = $request->attendance;
               
                $status = $request->status;
                
                $count = count($employee_id);
                
                //dd($attendance);
                
                for ($i=0; $i<$count; $i++)
                {
//                    $data = array(
//                        'emplyee_id' => $employee_id[$i],
//                        'date' => $date,
//                        'attendance' => $attendance[$i],
//                        'status' => $status[$i]
//                    );
                    
                    $att = new Employee_attendance();

                    $att->emplyee_id = $employee_id[$i];
                    $att->date = $date;
                    //$att->attendance = $attendance[$i];
                    if (!empty($attendance[$i]))
                    {
                        $att->attendance = 1;
                    }else{
                        $att->attendance = 0;
                    }
                    $att->status = $status[$i];

                    $att->save();
    
                    //$insertData[] = $data;
                }
                
                //Employee_attendance::insert($insertData);
                
                return response()->json([
                    'flash_message_success' => 'Attendance Added Successfully'
                ],200);
                
        }
    }
    
    public function edit($date)
    {
        $dates = $date;
        
        $employee = Employee::get();
        
        $attendance = Employee_attendance::where('date',$date)->get();
        
        return view('admin.attendance.edit',compact('employee','attendance','dates'));
    }
    
    public function destroy($date)
    {
        $atte = Employee_attendance::where('date',$date)->get();
        
        //$atte->delete();
        
        foreach ($atte as $value)
        {
            $value->delete();
        }
    
        return response()->json([
            'flash_message_success' => 'Attendance Deleted Successfully'
        ],200);
    }
}

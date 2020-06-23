<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\Facades\DataTables;
use Image;

class EmployeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:employee-list|employee-create|employee-edit|employee-delete', ['only' => ['index','store']]);
        $this->middleware('permission:employee-create', ['only' => ['create','store']]);
        $this->middleware('permission:employee-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:employee-delete', ['only' => ['destroy']]);
    }
    
    public function index()
    {
        return view('admin.employee.index');
    }
    
    public function create()
    {
        return view('admin.employee.create');
    }
    
    public function getData()
    {
        $employee = Employee::get();
        
        return DataTables::of($employee)
            ->addIndexColumn()
            ->addColumn('image',function ($employee){
                $url=asset("assets/admin/uploads/medium/$employee->image");
                return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
            })
            ->editColumn('action', function ($employee) {
                $return = "<div class=\"btn-group\">";
                if (!empty($employee->name))
                {
                    $return .= "
                            <a href=\"/employee/edit/$employee->id\" style='margin-right: 5px' class=\"btn btn-sm btn-warning\"><i class='fa fa-edit'></i></a>
                            ||
                              <a rel=\"$employee->id\" rel1=\"delete-employee\" href=\"javascript:\" style='margin-right: 5px' class=\"btn btn-sm btn-danger deleteRecord \"><i class='fa fa-trash'></i></a>
                                  ";
                }
                $return .= "</div>";
                return $return;
            })
            ->rawColumns([
                'image','action'
            ])
            ->make(true);
    }
    
    public function store(Request $request)
    {
        if ($request->isMethod('post'))
        {
            
    
                if($request->hasFile('image')){
        
                    $image_tmp = $request->file('image');
                    if($image_tmp->isValid()){
                        $extenson = $image_tmp->getClientOriginalExtension();
                        $filename = rand(111,99999).'.'.$extenson;
            
                    
                        $medium_image_path = public_path().'/assets/admin/uploads/large/'.$filename;
                        $submedium_image_path = public_path().'/assets/admin/uploads/medium/'.$filename;
            
                        //Resize Image
                        Image::make($image_tmp)->resize(1000,529)->save($medium_image_path);
                        Image::make($image_tmp)->resize(720,540)->save($submedium_image_path);
            
                        //store product image in data table
            
            
                    }
                }
                
                // Step 1 : Create Permission
               $employee = new Employee();
               
               $employee->name = $request->name;
               $employee->email = $request->email;
               $employee->designation = $request->designation;
               $employee->salary = $request->salary;
               $employee->address = $request->address;
               $employee->phone = $request->phone;
               $employee->country = $request->country;
               $employee->city = $request->city;
               $employee->zip_code = $request->zip_code;
               $employee->image = $filename;
               
               $employee->save();
            
                DB::commit();
            
                return response()->json([
                    'flash_message_success' => 'Employee Added Successfully'
                ],200);
                
        }
    }
    
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        
        return view('admin.employee.edit',compact('employee'));
    }
    
    public function update(Request $request,$id)
    {
        if ($request->isMethod('post'))
        {
            
            if($request->hasFile('image')){
            
                $image_tmp = $request->file('image');
                if($image_tmp->isValid()){
                    $extenson = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extenson;
                
                
                    $medium_image_path = public_path().'/assets/admin/uploads/large/'.$filename;
                    $submedium_image_path = public_path().'/assets/admin/uploads/medium/'.$filename;
                
                    //Resize Image
                    Image::make($image_tmp)->resize(1000,529)->save($medium_image_path);
                    Image::make($image_tmp)->resize(720,540)->save($submedium_image_path);
                    
                }
            }else {
                $filename = $request->current_image;
            }
        
            // Step 1 : Create Permission
            $employee = Employee::findOrFail($id);
        
            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->designation = $request->designation;
            $employee->salary = $request->salary;
            $employee->address = $request->address;
            $employee->phone = $request->phone;
            $employee->country = $request->country;
            $employee->city = $request->city;
            $employee->zip_code = $request->zip_code;
            
            $employee->image = $filename;
           
        
            $employee->save();
        
            DB::commit();
        
            return response()->json([
                'flash_message_success' => 'Employee Updated Successfully'
            ],200);
        
        }
    }
    
    public function delete_image($id)
    {
        $employee = Employee::where('id',$id)->first();
        
        $large_path = public_path().'/assets/admin/uploads/large/'.$employee->image;
        $medium_path = public_path().'/assets/admin/uploads/medium/'.$employee->image;

        if ($employee->image)
        {

            unlink($large_path);
            unlink($medium_path);

        }
    
        $employee->update(['image'=>null]);
        
        return response()->json([
            'flash_message_success' => 'Employee Image Deleted Successfully'
        ],200);
    }
    
    public function destroy($id)
    {
        $employee = Employee::findorFail($id);
        
        $large_path = public_path().'/assets/admin/uploads/large/'.$employee->image;
        $medium_path = public_path().'/assets/admin/uploads/medium/'.$employee->image;
        
        if ($employee->image)
        {
            unlink($large_path);
            unlink($medium_path);
        }
        
        $employee->delete();
    
        return response()->json([
            'flash_message_success' => 'Employee Deleted Successfully'
        ],200);
    }
}

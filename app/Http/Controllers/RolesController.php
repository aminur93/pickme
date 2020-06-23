<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use DB;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
        $this->middleware('permission:role-create', ['only' => ['create','store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }
    
    public function index()
    {
        return view('admin.role.index');
    }
    
    public function create()
    {
        $permission = Permission::get();
        return view('admin.role.create',compact('permission'));
    }
    
    public function getData()
    {
        $role = DB::table('roles')
                    ->select(
                        'roles.id',
                        'roles.name as rname',
                         DB::raw('group_concat(permissions.name) as pname')
                    )
                    ->join('role_has_permissions','roles.id','=','role_has_permissions.role_id')
                    ->join('permissions','role_has_permissions.permission_id','=','permissions.id')
                    ->groupBy('role_has_permissions.role_id')
                    ->get();
        
        //dd($role);
    
        return DataTables::of($role)
            ->addIndexColumn()
            ->editColumn('action', function ($role) {
                $return = "<div class=\"btn-group\">";
                if (!empty($role->rname))
                {
                    $return .= "
                            <a href=\"/role/edit/$role->id\" style='margin-right: 5px' class=\"btn btn-sm btn-warning\"><i class='fa fa-edit'></i></a>
                            ||
                              <a rel=\"$role->id\" rel1=\"delete-role\" href=\"javascript:\" style='margin-right: 5px' class=\"btn btn-sm btn-danger deleteRecord \"><i class='fa fa-trash'></i></a>
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
            DB::beginTransaction();
        
            try{
                // Step 1 : Create Role
                $role = Role::create(['name' => $request->name]);
                $role->syncPermissions($request->input('permission'));
            
                DB::commit();
    
                return response()->json([
                    'flash_message_success' => 'Role Added Successfully'
                ],200);
            
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollback();
    
                $error = $e->getMessage();
    
                return response()->json([
                    'error' => $error
                ],200);
            }
        }
    }
    
    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
    
        return view('admin.role.edit',compact('role','permission','rolePermissions'));
    }
    
    public function update(Request $request,$id)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();
        
            try{
                // Step 1 : Update Role
                $role = Role::findOrFail($id);
                $role->name = $request->name;
                $role->update();
            
                $role->syncPermissions($request->input('permission'));
            
                DB::commit();
    
                return response()->json([
                    'flash_message_success' => 'Role Added Successfully'
                ],200);
            
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollback();
                
                $error = $e->getMessage();
    
                return response()->json([
                    'error' => $error
                ],200);
            }
        }
    }
    
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        
        DB::table('role_has_permissions')->where('role_id',$id)->delete();
        
        $role->delete();
    
        return response()->json([
            'flash_message_success' => 'Role Deleted Successfully'
        ],200);
        
    }
}

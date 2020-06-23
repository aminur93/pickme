<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
    
    public function index()
    {
        return view('admin.user.index');
    }
    
    public function create()
    {
        $roles = Role::all();
        return view('admin.user.create',compact('roles'));
    }
    
    public function getData()
    {
        $user = DB::table('users')
                    ->select(
                        'users.id',
                        'users.name as uname',
                        'roles.name as rname'
                    )
                    ->join('model_has_roles','users.id','=','model_has_roles.model_id')
                    ->join('roles','model_has_roles.role_id','=','roles.id')
                    ->get();
        //dd($user);
    
        return DataTables::of($user)
            ->addIndexColumn()
            ->editColumn('action', function ($user) {
                $return = "<div class=\"btn-group\">";
                if (!empty($user->uname))
                {
                    $return .= "
                            <a href=\"/user/edit/$user->id\" style='margin-right: 5px' class=\"btn btn-sm btn-warning\"><i class='fa fa-edit'></i></a>
                            ||
                              <a rel=\"$user->id\" rel1=\"delete-user\" href=\"javascript:\" style='margin-right: 5px' class=\"btn btn-sm btn-danger deleteRecord \"><i class='fa fa-trash'></i></a>
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
                $input = $request->all();
                $input['password'] = Hash::make($input['password']);
            
            
                $user = User::create($input);
                $user->assignRole($request->role);
            
                DB::commit();
    
                return response()->json([
                    'flash_message_success' => 'User Added Successfully'
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
        $data = DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'model_has_roles.role_id'
            )
            ->leftJoin('model_has_roles','users.id','=','model_has_roles.model_id')
            ->leftJoin('roles','model_has_roles.role_id','=','roles.id')
            ->where('users.id',$id)
            ->first();
        //dd($data);die;
        $roles = Role::all();
        
        return view('admin.user.edit',compact('data','roles'));
    }
    
    public function update(Request $request,$id)
    {
        if ($request->isMethod('post'))
        {
            DB::beginTransaction();
        
            try{
                // Step 1 : Update Users
                $input = $request->all();
                if(!empty($input['password'])){
                    $input['password'] = Hash::make($input['password']);
                }else{
                    $input = array_except($input,array('password'));
                }
            
            
                $user = User::find($id);
                $user->update($input);
                DB::table('model_has_roles')->where('model_id',$id)->delete();
            
            
                $user->assignRole($request->role);
            
            
                DB::commit();
    
                return response()->json([
                    'flash_message_success' => 'User Updated Successfully'
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
        $user = User::findorFail($id);
        
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        
        $user->delete();
    
        return response()->json([
            'flash_message_success' => 'User Deleted Successfully'
        ],200);
    }
}

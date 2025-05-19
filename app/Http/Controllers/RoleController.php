<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index(){
        return response()->json(Role::all());
    }
    public function show(Request $id){
        $role = Role::find($id);
        if(!$role){
            return response()->json(['error' => 'Role not found'], 404);
        }
        return response()->json($role);

    }
}

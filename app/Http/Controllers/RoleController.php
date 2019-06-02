<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Transformer\ApiJsonTransformer;

class RoleController extends Controller
{
    private $apiTransformer;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ApiJsonTransformer $apiTransformer) {
        $this->apiTransformer = $apiTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $roles = Role::all();
        return $this->apiTransformer->successResponse($roles);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //The rules
        $rules = [
            'name'     => 'required|max:255',
        ];
        //validate the request
       $this->validate($request, $rules);
        //instantiate the Role
        $role = new Role();
        $role->name = $request->input('name');
        //Save the role
        $role->save();
        //Return the new role
        return $this->apiTransformer->successResponse($role, ApiJsonTransformer::HTTP_CREATED);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($roleId) {
        //get role with the given roleId
        $role = Role::findOrFail($roleId);
        return $this->apiTransformer->successResponse($role);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $roleId) {
        //The rules
        $rules = [
            'name'     => 'max:255',
        ];
        //validate the request
       $this->validate($request, $rules);
        //get role with the given roleId
        $role = Role::findOrFail($roleId);
        //Check if the request has name
        if ($request->has('name')) {
            $role->name    = $request->input('name');
        }
        //Check if anything changed in role
        if ($role->isClean()) {
            return $this->apiTransformer->errorResponse('You must specify a new value to update', ApiJsonTransformer::HTTP_UNPROCESSABLE_ENTITY);
        }
        //Save the role
        $role->save();
        //Return the new role
        return $this->apiTransformer->successResponse($role, ApiJsonTransformer::HTTP_CREATED);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($roleId) {
        //get role with the given roleId
        $role = Role::findOrFail($roleId);
        $role->delete();
        //Return the new role
        return $this->apiTransformer->successResponse($role);
    }
}

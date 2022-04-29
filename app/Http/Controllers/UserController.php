<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\RolePermissions;
use App\Models\RoleType;
use App\Models\User;
use App\Models\UserPermissions;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::orderBy('id', 'desc')->get();
        $permissions = RolePermissions::all();
        $role_types = RoleType::all();

        $data = [
            'users' => $users,
            'permissions' => $permissions,
            'role_types' => $role_types
        ];

        return view('welcome', $data);
    }

    /**
     * Search users in the database.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        //
        if($request->query != '') {
            $keywords = $request->keywords;
            $users = User::query()
            ->where('firstname', 'LIKE', "%{$keywords}%")
            ->orWhere('lastname', 'LIKE', "%{$keywords}%")
            ->orWhere('email', 'LIKE', "%{$keywords}%")
            ->orderBy('id', 'desc')
            ->get();
        } else {
            $users = User::orderBy('id', 'desc')->get();
        }
        
        if($users) {

            $users = UserResource::collection($users);
            $response = [
                "status" => 200,
                "success" => true,
                "message" => "Data fetched!",
                "users" => $users
            ];
        } else 
        {
            $response = [
                "status" => 200,
                "success" => false,
                "message" => "No data found!",
            ];
        }

        return response()->json($response, $response['status']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {

            $validation = Validator::make($request->all(), [
                'employee_id' => 'required|unique:users',
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required|unique:users',
                'role_type_id' => 'required',
                'username' => 'required|unique:users',
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password'
            ]);

            if (!$validation->fails()) {

                $user = new User();
                $user->employee_id = $request->employee_id;
                $user->username = $request->username;
                $user->firstname = $request->firstname;
                $user->lastname = $request->lastname;
                $user->email = $request->email;
                $user->mobile_number = $request->mobile_number;
                $user->password = Hash::make($request->password);
                $user->role_type_id = $request->role_type_id;

                $user->save();

                $index = 0;
                foreach ($request->permissions as $permission) {

                    $userPermission = new UserPermissions();
                    $userPermission->user_id = $user->id;
                    $userPermission->permission_id = $permission;
                    $userPermission->read_mode = isset($request?->permission_read[$index]) ? 1 : 0;
                    $userPermission->write_mode = isset($request?->permission_write[$index]) ? 1 : 0;
                    $userPermission->delete_mode = isset($request?->permission_delete[$index]) ? 1 : 0;

                    $userPermission->save();

                    $index++;
                }

                $response = [
                    "status" => 200,
                    "success" => true,
                    "message" => "User has been created successfully!",
                    "user" => UserResource::make($user)
                ];
            } else {
                $response = [
                    "status" => 200,
                    "success" => false,
                    "error" => [
                        "message" => "All required fields must be filled correctly!"
                    ],
                    "errors" => $validation->errors()
                ];
            }
        } catch (\Exception $ex) {

            $response = [
                "status" => 500,
                "success" => false,
                "error" => [
                    "message" => "Error occured: Unable to create user"
                ]
            ];
        }

        return response()->json($response, $response['status']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try {

            $user = User::find($id);
            $user = UserResource::make($user);

            $response = [
                "status" => 200,
                "success" => true,
                "message" => "User details fetched successfully!",
                'user' => $user
            ];
        } catch (Exception $ex) {

            $response = [
                "status" => 500,
                "success" => false,
                'error' => [
                    "message" => "Unable to fetch user details, please try again!"
                ]
            ];
        }

        return response()->json($response, $response['status']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        try {

            $validation = Validator::make($request->all(), [
                'id' => 'required',
                'employee_id' => 'required',
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required',
                'role_type_id' => 'required',
                'username' => 'required',
            ]);

            if (!$validation->fails()) {

                $user = User::where('id', $request->id)->first();

                if ($user) {

                    // $user->employee_id = $request->employee_id;
                    $user->username = $request->username;
                    $user->firstname = $request->firstname;
                    $user->lastname = $request->lastname;
                    // $user->email = $request->email;
                    $user->mobile_number = $request->mobile_number;
                    if ($request->password != '') {
                        $user->password = Hash::make($request->password);
                    }
                    $user->role_type_id = $request->role_type_id;

                    $user->save();

                    $index = 0;
                    foreach ($request->permissions as $permission) {

                        $userPermission = UserPermissions::where('user_id', $user->id)
                            ->where('permission_id', $permission)
                            ->first();

                        if ($userPermission) {

                            $userPermission->read_mode = isset($request?->permission_read[$index]) ? 1 : 0;
                            $userPermission->write_mode = isset($request?->permission_write[$index]) ? 1 : 0;
                            $userPermission->delete_mode = isset($request?->permission_delete[$index]) ? 1 : 0;

                            $userPermission->save();
                        }

                        $index++;
                    }

                    $response = [
                        "status" => 200,
                        "success" => true,
                        "message" => "User details has been updated successfully!",
                        "user" => UserResource::make($user)
                    ];
                } else {
                    $response = [
                        "status" => 200,
                        "success" => false,
                        'error' => [
                            "message" => "Can't update this user, details does not exist!"
                        ]
                    ];
                }
            } else {
                $response = [
                    "status" => 200,
                    "success" => false,
                    "error" => [
                        "message" => "All required fields must be filled correctly!"
                    ],
                    "errors" => $validation->errors()
                ];
            }
        } catch (\Exception $ex) {

            $response = [
                "status" => 500,
                "success" => false,
                "error" => [
                    "message" => "Error occured: Unable to update user datils"
                ]
            ];
        }

        return response()->json($response, $response['status']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {

            DB::table("users")->where('id', $id)->delete();
            DB::table("user_permissions")->where('user_id', $id)->delete();

            $response = [
                "status" => 200,
                "success" => true,
                "message" => "User has been delete successfully!"
            ];
        } catch (Exception $ex) {

            $response = [
                "status" => 500,
                "success" => false,
                'error' => [
                    "message" => "Unable to delete user, please try again!"
                ]
            ];
        }

        return response()->json($response, $response['status']);
    }
}

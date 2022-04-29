@extends('shared.layout')

@section('content')
    <div class="bhr__main">
        <div class="col-md-12 mb-3 text-end">
            <button class="btn btn-success bhr__btn" onclick="addUser();">
                Add User
            </button>
        </div>

        <div class="bhr__card">
            <div class="bhr__card-header ps-3 pe-3 bg-white">
                <div class="d-flex justify-content-between">
                    <h5 class="bhr__card-title mt-3 p-0">List Users</h5>
                    <div class="form-group">
                        <div class="input-icon input-icon-right">
                            <input type="text" class="form-control" onkeyup="searchUser(this);" placeholder="Search...">
                            <span>
                                <i data-feather="search" style="color: #dcdcdc" class="icon-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div id="pageMessage" class="mt-1 mb-1"></div>
            </div>
            <div class="bhr__card-body">
                <div class="table-responsive">
                    <table class="table bhr__table mb-0">
                        <thead class="bhr__thead pt-2 pb-2">
                            <tr>
                                <th>Name</th>
                                <th></th>
                                <th>Created Date</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="bhr__tbody" id="users-list">
                            @foreach($users as $user)
                            <tr id="RowID{{ $user->id }}">
                                <td>
                                    <div class="d-flex">
                                        <div class="bhr__user-image">
                                            <img src="/assets/images/avatar-{{$user->id}}.jpg" alt="">
                                        </div>
                                        <div class="bhr__user-details">
                                            <strong>{{ $user->firstname .' '. $user->lastname }}</strong>
                                            <p class="text-muted p-0 m-0">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="bhr__user-access">
                                        @if($user->role_type->permission_id == 1)
                                        <span class="badge p-2 bg-red">
                                            {{ $user->role_type->permission?->name }}
                                        </span>
                                        @elseif($user->role_type->permission_id == 2)
                                        <span class="badge p-2 bg-primary">
                                            {{ $user->role_type->permission?->name }}
                                        </span>
                                        @elseif($user->role_type->permission_id == 3)
                                        <span class="badge p-2 bg-light">
                                            {{ $user->role_type->permission?->name }}
                                        </span>
                                        @elseif($user->role_type->permission_id == 4)
                                        <span class="badge p-2 bg-green">
                                            {{ $user->role_type->permission?->name }}
                                        </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <p class="mt-2 mb-0">
                                    {{ date('d M, Y', strtotime($user->created_at)); }}
                                    <p>
                                </td>
                                <td>
                                    <p class="mt-2 mb-0">
                                    {{ $user->role_type->name }}
                                    </p>
                                </td>
                                <td>
                                    <div class="bhr__actions mt-2">
                                        <a href="/api/user/{{ $user->id }}" class="edit-user me-2"> <i class="fa fa-edit icon-sm"></i> </a>
                                        <a href="/api/user/delete/{{ $user->id }}" row-id="#RowID{{ $user->id }}" class="delete-user"> <i class="fa fa-trash-alt icon-sm"></i> </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <form action="/api/add-user" method="post" id="UserForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="message"></div>
                        <input type="hidden" name="id" value="">
                        <div class="form-details-box">
                            <div class="form-group">
                                <input type="text" name="employee_id" 
                                validate-field="true" validation-message="Employee ID is required *"
                                placeholder="Employee ID *" class="form-control" />
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="firstname" 
                                        validate-field="true" validation-message="Firstname cannot be empty *"
                                        placeholder="Firstname *" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="lastname"
                                        validate-field="true" validation-message="Lastname cannot be empty *" 
                                        placeholder="Lastname *" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="email" name="email" 
                                        validate-field="true" validation-message="Email is required *"
                                        placeholder="Email ID *" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" name="mobile_number" placeholder="Mobile No" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="role_type_id" class="form-control custom-select" id="role"
                                        validate-field="true" validation-message="Kindly select a role type *">
                                            <option value="" selected>Select Role Type</option>
                                            @foreach ($role_types as $role_type)
                                                <option value="{{ $role_type->id }}">{{ $role_type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" name="username" placeholder="Username *" class="form-control"
                                        validate-field="true" validation-message="Username cannot be empty *" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="password" name="password" placeholder="Password *" 
                                        class="form-control" autocomplete="off" validate-field="true" 
                                        validation-message="Password cannot be empty *" id="Password" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="password" name="confirm_password" placeholder="Confirm Password *"
                                        validate-field="true" validation-message="Kindly confirm password *" autocomplete="off"
                                        validate-confirm-password="#Password" class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-permission-table">
                            <div class="table-responsive">
                                <table class="table bhr__table">
                                    <thead class="bhr__thead pt-2 pb-2">
                                        <tr>
                                            <th>Module Permission</th>
                                            <th>Read</th>
                                            <th>Write</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bhr__tbody">
                                        @foreach ($permissions as $permission)
                                            <tr>
                                                <td>
                                                    {{ $permission->name }}
                                                    <input type="hidden" name="permissions[]"  value="{{ $permission->id }}">
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="flexCheckCheckedRead{{ $permission->id }}" name="permission_read[]" checked>
                                                        <label class="form-check-label" for="flexCheckCheckedRead"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="flexCheckCheckedWrite{{ $permission->id }}" name="permission_write[]" 
                                                            {{ ($permission->id == 1 || $permission->id == 4) ? 'checked' : null }}>
                                                        <label class="form-check-label" for="flexCheckCheckedWrite{{ $permission->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="flexCheckCheckedDelete{{ $permission->id }}" name="permission_delete[]" 
                                                            {{ ($permission->id == 1 || $permission->id == 4) ? 'checked' : null }}>
                                                        <label class="form-check-label" for="flexCheckCheckedDelete{{ $permission->id }}"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach()
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-primary me-3 p-1 px-3 add-user-btn">Add User</button>
                        <a type="button" class="btn bg-white text-secondary" data-bs-dismiss="modal">Close</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

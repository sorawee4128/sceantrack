<?php

namespace App\Http\Controllers;

use App\Http\Requests\Roles\StoreRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()->can('manage roles'), 403, 'คุณไม่มีสิทธิ์เข้าถึงเมนูนี้');

        $roles = Role::query()
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->q.'%'))
            ->with('permissions')
            ->paginate(15)
            ->withQueryString();

        return view('roles.index', compact('roles'));
    }

    public function create(Request $request)
    {
        abort_unless($request->user()->can('manage roles'), 403, 'คุณไม่มีสิทธิ์เข้าถึงเมนูนี้');

        return view('roles.create', [
            'permissions' => Permission::orderBy('name')->get(),
        ]);
    }

    public function store(StoreRoleRequest $request)
    {
        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);
        $role->syncPermissions($request->input('permissions', []));

        return redirect()->route('roles.index')->with('success', 'บันทึก role เรียบร้อยแล้ว');
    }

    public function edit(Request $request, Role $role)
    {
        abort_unless($request->user()->can('manage roles'), 403, 'คุณไม่มีสิทธิ์เข้าถึงเมนูนี้');
        $role->load('permissions');

        return view('roles.edit', [
            'role' => $role,
            'permissions' => Permission::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->input('permissions', []));

        return redirect()->route('roles.edit', $role)->with('success', 'อัปเดต role เรียบร้อยแล้ว');
    }
}

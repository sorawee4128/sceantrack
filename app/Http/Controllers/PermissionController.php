<?php

namespace App\Http\Controllers;

use App\Http\Requests\Permissions\StorePermissionRequest;
use App\Http\Requests\Permissions\UpdatePermissionRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()->can('manage permissions'), 403, 'คุณไม่มีสิทธิ์เข้าถึงเมนูนี้');

        $permissions = Permission::query()
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->q.'%'))
            ->paginate(20)
            ->withQueryString();

        return view('permissions.index', compact('permissions'));
    }

    public function create(Request $request)
    {
        abort_unless($request->user()->can('manage permissions'), 403, 'คุณไม่มีสิทธิ์เข้าถึงเมนูนี้');
        return view('permissions.create');
    }

    public function store(StorePermissionRequest $request)
    {
        Permission::create(['name' => $request->name, 'guard_name' => 'web']);

        return redirect()->route('permissions.index')->with('success', 'บันทึก permission เรียบร้อยแล้ว');
    }

    public function edit(Request $request, Permission $permission)
    {
        abort_unless($request->user()->can('manage permissions'), 403, 'คุณไม่มีสิทธิ์เข้าถึงเมนูนี้');
        return view('permissions.edit', compact('permission'));
    }

    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $permission->update(['name' => $request->name]);

        return redirect()->route('permissions.edit', $permission)->with('success', 'อัปเดต permission เรียบร้อยแล้ว');
    }
}

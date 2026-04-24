<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $users = User::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->string('q');
                $query->where(function ($qBuilder) use ($q) {
                    $qBuilder->where('name', 'like', "%{$q}%")
                        ->orWhere('full_name', 'like', "%{$q}%")
                        ->orWhere('username', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%");
                });
            })
            ->with('roles')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $this->authorize('create', User::class);

        return view('users.create', [
            'roles' => Role::orderBy('name')->get(),
            'permissions' => Permission::orderBy('name')->get(),
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->safe()->except(['role', 'permissions']));
        $user->assignRole($request->string('role')->value());
        $user->syncPermissions($request->input('permissions', []));

        return redirect()->route('users.index')->with('success', 'บันทึกผู้ใช้งานเรียบร้อยแล้ว');
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        $user->load('roles', 'permissions');

        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        $user->load('roles', 'permissions');

        return view('users.edit', [
            'user' => $user,
            'roles' => Role::orderBy('name')->get(),
            'permissions' => Permission::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $data = $request->safe()->except(['role', 'permissions']);
        if (blank($request->password)) {
            unset($data['password']);
        }

        $user->update($data);
        $user->syncRoles([$request->string('role')->value()]);
        $user->syncPermissions($request->input('permissions', []));

        return redirect()->route('users.edit', $user)->with('success', 'อัปเดตผู้ใช้งานเรียบร้อยแล้ว');
    }

    public function toggle(User $user)
    {
        $this->authorize('update', $user);
        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('success', 'อัปเดตสถานะผู้ใช้งานเรียบร้อยแล้ว');
    }
}

<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * List users + agregasi donasi (sum & count), dengan pencarian & pagination.
     * Aman dari ONLY_FULL_GROUP_BY (withSum/withCount).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        // Role yang diizinkan untuk assignment (ambil dari Spatie bila ada)
        $roles = Role::query()->orderBy('name')->pluck('name')->all();
        $allowedRoles = $roles ?: ['admin', 'user', 'loket', 'superadmin']; // fallback

        $users = User::query()
            ->when($q !== '', function ($qb) use ($q) {
                $qb->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            // Agregasi aman per-row (tanpa GROUP BY users.*)
            ->withSum(['donations as donated_sum' => function ($d) {
                $d->where('status', 'settlement');
            }], 'amount')
            ->withCount(['donations as donated_cnt' => function ($d) {
                $d->where('status', 'settlement');
            }])
            ->orderByRaw('COALESCE(donated_sum, 0) DESC')
            ->paginate(15)
            ->withQueryString();

        return view('superadmin.users.index', [
            'users'        => $users,
            'q'            => $q,
            'allowedRoles' => $allowedRoles,
        ]);
    }

    /**
     * Detail user + daftar transaksi (donations) + agregat settlement.
     * Pastikan variabel ke Blade bernama 'user' agar tidak Undefined $user.
     *
     * @param  \App\Models\User          $user
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function show(User $user, Request $request)
    {
        $donations = Donation::query()
            ->with(['campaign:id,title,slug'])
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        $agg = Donation::query()
            ->where('user_id', $user->id)
            ->where('status', 'settlement')
            ->selectRaw('COALESCE(SUM(amount), 0) AS total, COUNT(*) AS cnt')
            ->first();

        return view('superadmin.users.show', [
            'user'             => $user,
            'donations'        => $donations,
            'totalSettlement'  => (int) ($agg->total ?? 0),
            'countSettlement'  => (int) ($agg->cnt ?? 0),
        ]);
    }

    /**
     * Create user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $roles = Role::query()->pluck('name')->all();
        $allowed = $roles ?: ['admin', 'user', 'loket', 'superadmin'];

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:120'],
            'email'    => ['required', 'email', 'max:160', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'string', Rule::in($allowed)],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->syncRoles([$data['role']]);

        return back()->with('ok', 'User berhasil dibuat.');
    }

    /**
     * Update user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User          $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $roles = Role::query()->pluck('name')->all();
        $allowed = $roles ?: ['admin', 'user', 'loket', 'superadmin'];

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:120'],
            'email'    => ['required', 'email', 'max:160', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'string', Rule::in($allowed)],
        ]);

        $user->name  = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();
        $user->syncRoles([$data['role']]);

        return back()->with('ok', 'User berhasil diupdate.');
    }

    /**
     * Delete user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('err', 'Tidak bisa menghapus akun Anda sendiri.');
        }

        if ($user->hasRole('superadmin')) {
            $countSuperadmin = User::role('superadmin')->count();
            if ($countSuperadmin <= 1) {
                return back()->with('err', 'Tidak bisa menghapus superadmin terakhir.');
            }
        }

        $user->delete();

        return back()->with('ok', 'User berhasil dihapus.');
    }
}

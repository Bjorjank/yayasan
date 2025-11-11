<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // List users + agregasi donasi (sum & count), dengan pencarian
    public function index(Request $request)
    {
        $q = trim((string)$request->query('q', ''));
        $roles = Role::query()->orderBy('name')->pluck('name')->all();
        $allowedRoles = $roles ?: ['superadmin','admin','user','loket'];

        $users = User::query()
            ->leftJoin('donations as d', function($join){
                $join->on('d.user_id', '=', 'users.id')
                     ->where('d.status', '=', 'settlement');
            })
            ->when($q !== '', function($builder) use ($q) {
                $builder->where(function($w) use ($q) {
                    $w->where('users.name', 'like', "%{$q}%")
                      ->orWhere('users.email', 'like', "%{$q}%");
                });
            })
            ->groupBy('users.id')
            ->select('users.*')
            ->selectRaw('COALESCE(SUM(d.amount),0) as donated_sum')
            ->selectRaw('COUNT(d.id) as donated_cnt')
            ->orderByDesc('donated_sum')
            ->paginate(15)
            ->withQueryString();

        return view('superadmin.users.index', compact('users','q','allowedRoles'));
    }

    // Detail user + daftar transaksi (donations)
    public function show(User $user, Request $request)
    {
        $donations = Donation::with(['campaign:id,title,slug'])
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        // hitung agregat settlement saja
        $agg = Donation::where('user_id', $user->id)
            ->where('status','settlement')
            ->selectRaw('COALESCE(SUM(amount),0) as total, COUNT(*) as cnt')
            ->first();

        return view('superadmin.users.show', [
            'u' => $user,
            'donations' => $donations,
            'totalSettlement' => (int)($agg->total ?? 0),
            'countSettlement' => (int)($agg->cnt ?? 0),
        ]);
    }

    // Create user (modal)
    public function store(Request $request)
    {
        $roles = Role::query()->pluck('name')->all();
        $allowed = $roles ?: ['superadmin','admin','user','loket'];

        $data = $request->validate([
            'name'     => 'required|string|max:120',
            'email'    => 'required|email|max:160|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|string|in:'.implode(',', $allowed),
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->syncRoles([$data['role']]);

        return back()->with('ok','User berhasil dibuat.');
    }

    // Update user (modal)
    public function update(Request $request, User $user)
    {
        $roles = Role::query()->pluck('name')->all();
        $allowed = $roles ?: ['superadmin','admin','user','loket'];

        $data = $request->validate([
            'name'     => 'required|string|max:120',
            'email'    => 'required|email|max:160|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role'     => 'required|string|in:'.implode(',', $allowed),
        ]);

        $user->name  = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        $user->syncRoles([$data['role']]);

        return back()->with('ok','User berhasil diupdate.');
    }

    // Delete user (modal konfirmasi)
    public function destroy(User $user)
    {
        // cegah hapus diri sendiri
        if (auth()->id() === $user->id) {
            return back()->with('err','Tidak bisa menghapus akun Anda sendiri.');
        }

        // cegah hapus superadmin terakhir
        if ($user->hasRole('superadmin')) {
            $countSuperadmin = User::role('superadmin')->count();
            if ($countSuperadmin <= 1) {
                return back()->with('err','Tidak bisa menghapus superadmin terakhir.');
            }
        }

        $user->delete();
        return back()->with('ok','User berhasil dihapus.');
    }
}

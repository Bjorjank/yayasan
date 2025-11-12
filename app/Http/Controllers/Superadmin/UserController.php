<?php
declare(strict_types=1);

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Throwable;

final class UserController extends Controller
{
    /**
     * List users + agregasi donasi (sum & count), dengan pencarian.
     */
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $roles = Role::query()->orderBy('name')->pluck('name')->all();
        $allowedRoles = $roles ?: ['superadmin','admin','user','loket'];

        $users = User::query()
            ->leftJoin('donations as d', function ($join) {
                $join->on('d.user_id', '=', 'users.id')
                     ->where('d.status', '=', 'settlement');
            })
            ->when($q !== '', function ($builder) use ($q) {
                $builder->where(function ($w) use ($q) {
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

        return view('superadmin.users.index', [
            'users'        => $users,
            'q'            => $q,
            'allowedRoles' => $allowedRoles,
        ]);
    }

    /**
     * Detail user + daftar transaksi.
     */
    public function show(User $user, Request $request): View
    {
        $donations = Donation::with(['campaign:id,title,slug'])
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        $agg = Donation::where('user_id', $user->id)
            ->where('status', 'settlement')
            ->selectRaw('COALESCE(SUM(amount),0) as total, COUNT(*) as cnt')
            ->first();

        return view('superadmin.users.show', [
            'u'               => $user,
            'donations'       => $donations,
            'totalSettlement' => (int)($agg->total ?? 0),
            'countSettlement' => (int)($agg->cnt ?? 0),
        ]);
    }

    /**
     * Create user.
     */
    public function store(Request $request): RedirectResponse
    {
        $roles = Role::query()->pluck('name')->all();
        $allowed = $roles ?: ['superadmin','admin','user','loket'];

        $v = Validator::make($request->all(), [
            'name'     => ['required','string','max:120', Rule::unique('users','name')],
            'email'    => ['required','email','max:160', Rule::unique('users','email')],
            'password' => ['required','string','min:8','confirmed'],
            'role'     => ['required','string','in:'.implode(',', $allowed)],
        ]);

        if ($v->fails()) {
            return back()
                ->withErrors($v)
                ->withInput()
                ->with('form', 'create'); // biar modal create bisa dibuka lagi
        }

        $data = $v->validated();

        try {
            DB::transaction(function () use ($data) {
                $user = User::create([
                    'name'     => $data['name'],
                    'email'    => $data['email'],
                    'password' => Hash::make($data['password']),
                ]);
                $user->syncRoles([$data['role']]);
            });

            return back()->with('ok', 'User berhasil dibuat.');
        } catch (Throwable $e) {
            report($e);
            return back()
                ->withInput()
                ->with('form', 'create')
                ->with('err', 'Terjadi kesalahan saat membuat user. Coba lagi.');
        }
    }

    /**
     * Update user (abaikan dirinya sendiri pada aturan unique).
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $roles = Role::query()->pluck('name')->all();
        $allowed = $roles ?: ['superadmin','admin','user','loket'];

        $v = Validator::make($request->all(), [
            'name'     => ['required','string','max:120', Rule::unique('users','name')->ignore($user->id)],
            'email'    => ['required','email','max:160', Rule::unique('users','email')->ignore($user->id)],
            'password' => ['nullable','string','min:8','confirmed'],
            'role'     => ['required','string','in:'.implode(',', $allowed)],
        ]);

        if ($v->fails()) {
            return back()
                ->withErrors($v)
                ->withInput()
                ->with('form', 'edit')
                ->with('edit_id', $user->id);
        }

        $data = $v->validated();

        try {
            DB::transaction(function () use ($user, $data) {
                $user->name  = $data['name'];
                $user->email = $data['email'];
                if (!empty($data['password'])) {
                    $user->password = Hash::make($data['password']);
                }
                $user->save();
                $user->syncRoles([$data['role']]);
            });

            return back()->with('ok', 'User berhasil diupdate.');
        } catch (Throwable $e) {
            report($e);
            return back()
                ->withInput()
                ->with('form', 'edit')
                ->with('edit_id', $user->id)
                ->with('err', 'Terjadi kesalahan saat mengupdate user. Coba lagi.');
        }
    }

    /**
     * Hapus user (cegah hapus diri sendiri & superadmin terakhir).
     */
    public function destroy(User $user): RedirectResponse
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

        try {
            $user->delete();
            return back()->with('ok', 'User berhasil dihapus.');
        } catch (Throwable $e) {
            report($e);
            return back()->with('err', 'User tidak dapat dihapus karena terkait data lain.');
        }
    }

    /**
     * AJAX pre-check duplikat (name/email), dukung ignore_id untuk edit.
     * GET /superadmin/users/check-unique?name=...&email=...&ignore_id=123
     */
    public function checkUnique(Request $request)
    {
        $name     = trim((string) $request->query('name', ''));
        $email    = trim((string) $request->query('email', ''));
        $ignoreId = (int) $request->query('ignore_id', 0);

        $dupes = ['name' => false, 'email' => false];

        if ($name !== '') {
            $q = User::query()->where('name', $name);
            if ($ignoreId) $q->where('id', '!=', $ignoreId);
            $dupes['name'] = $q->exists();
        }

        if ($email !== '') {
            $q = User::query()->where('email', $email);
            if ($ignoreId) $q->where('id', '!=', $ignoreId);
            $dupes['email'] = $q->exists();
        }

        return response()->json(['dupes' => $dupes]);
    }
}

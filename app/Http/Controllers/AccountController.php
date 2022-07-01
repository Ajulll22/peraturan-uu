<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class AccountController extends Controller
{
    use NavigationList;

    public function index()
    {
        // PAGE SETUP
        $pageTitle = 'Admin';
        $active = 'Admin';
        $breadCrumbs = [
            'bx-icon' => 'bx bx-notepad',
            'list' => [
                ['title' => 'Admin', 'url' => route('archive.index')],
            ]

        ];

        return view('pages.account.index', [
            'user' => Auth::user(),
            'pageTitle' => $pageTitle,
            'active' => $active,
            'breadCrumbs' => $breadCrumbs,
            'navs' => $this->NavigationList(),
        ]);
    }

    public function accountData()
    {
        $data = User::select(['id', 'name', 'email', 'role']);

        return DataTables::of($data)
            ->addColumn('account-actions', function ($row) {
                return view('components.data-table.account-action', compact(['row']));
            })
            ->rawColumns(['account-actions',])
            ->make(true);
    }

    public function create()
    {
        // PAGE SETUP
        $pageTitle = 'Admin';
        $active = 'Admin';
        $breadCrumbs = [
            'bx-icon' => 'bx bx-notepad',
            'list' => [
                ['title' => 'Admin', 'url' => route('account.index')],
            ]
        ];

        $roles = [
            'admin',
            'verifikator',
            'user'
        ];

        return view('pages.account.create', [
            'user' => Auth::user(),
            'pageTitle' => $pageTitle,
            'active' => $active,
            'breadCrumbs' => $breadCrumbs,
            'navs' => $this->NavigationList(),
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        $hashedPassword = Hash::make($request->password);

        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $hashedPassword,
            'role' => $request->role,
        ]);


        return redirect(route('account.index'))->with('success', 'Pengguna baru berhasil ditambahkan!');
    }

    public function show($id)
    {
        // PAGE SETUP
        $pageTitle = 'Admin';
        $active = 'Admin';
        $breadCrumbs = [
            'bx-icon' => 'bx bx-notepad',
            'list' => [
                ['title' => 'Admin', 'url' => route('account.index')],
            ]
        ];

        // REQUIRED DATA
        $account = User::find($id);
        $roles = [
            'admin',
            'verifikator',
            'user',
        ];

        return view('pages.account.show', [
            'user' => Auth::user(),
            'pageTitle' => $pageTitle,
            'active' => $active,
            'breadCrumbs' => $breadCrumbs,
            'navs' => $this->NavigationList(),
            'account' => $account,
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);

        try {
            $hashedPassword = Hash::make($request->password);

            $account = User::find($id);
            $account
                ->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => $hashedPassword,
                    'role' => $request->role,
                ]);
        } catch (Exception $e) {
            return redirect(route('account.index'))->with('failed', 'Something wrong!');
        }

        return redirect(route('account.index'))->with('success', 'Akun berhasil diperbarui!');
    }

    public function destroy($id)
    {
        try {
            User::find($id)->delete();
        } catch (Exception $e) {
            return redirect(route('account.index'))->with('failed', 'Something wrong!');
        }

        return redirect(route('account.index'))->with('success', 'Akun berhasil dihapus!');
    }
}

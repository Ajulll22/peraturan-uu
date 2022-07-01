<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
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

        return view('pages.category.index', [
            'user' => Auth::user(),
            'pageTitle' => $pageTitle,
            'active' => $active,
            'breadCrumbs' => $breadCrumbs,
            'navs' => $this->NavigationList(),
        ]);
    }

    public function categoryData()
    {
        $data = Category::select(['kategori_id', 'nama_kategori',]);

        return DataTables::of($data)
            ->addColumn('category-actions', function ($row) {
                return view('components.data-table.category-action', compact(['row']));
            })
            ->rawColumns(['category-actions'])
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

        // REQUIRED DATA

        return view('pages.category.create', [
            'user' => Auth::user(),
            'pageTitle' => $pageTitle,
            'active' => $active,
            'breadCrumbs' => $breadCrumbs,
            'navs' => $this->NavigationList(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required',
        ]);


        Category::insert([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect(route('category.index'))->with('success', 'Pengguna baru berhasil ditambahkan!');
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
        $category = Category::find($id);

        return view('pages.category.show', [
            'user' => Auth::user(),
            'pageTitle' => $pageTitle,
            'active' => $active,
            'breadCrumbs' => $breadCrumbs,
            'navs' => $this->NavigationList(),
            'category' => $category,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required',
        ]);

        try {
            $category = Category::find($id);
            $category
                ->update([
                    'nama_kategori' => $request->nama_kategori,
                ], [
                    'timestamps' => false,
                ]);
        } catch (Exception $e) {
            return redirect(route('category.index'))->with('failed', 'Something wrong!');
        }

        return redirect(route('category.index'))->with('success', 'Rumpun berhasil diperbarui!');
    }

    public function destroy($id)
    {
        try {
            Category::find($id)->delete();
        } catch (Exception $e) {
            return redirect(route('category.index'))->with('failed', 'Something wrong!');
        }

        return redirect(route('category.index'))->with('success', 'Rumpun berhasil dihapus!');
    }
}

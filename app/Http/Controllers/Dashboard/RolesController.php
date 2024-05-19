<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RolesController extends Controller
{
    protected $prefix = 'dashboard.user-management.roles.';

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('roles-read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user = Auth::user();
        if ($request->ajax()) {
            $roles = Role::query();
            return datatables()->of($roles)
                ->addColumn('hak akses', function ($role) {
                    $permissions =  $role->getPermissionNames();
                    $output = '';
                    foreach ($permissions as $permission) {
                        $output .= '<div class="badge badge-light-success fw-bold my-1 mx-1">' . $permission . '</div> ';
                    }
                    return $output;
                })
                ->addColumn('action', function ($item) {
                    return view('dashboard.user-management.roles.actions', compact('item'));
                })
                ->rawColumns(['hak akses'])
                ->toJson();
        }
        return view($this->prefix . 'index', compact('user'));
    }

    // public function index(RolesDataTable $dataTable)
    // {
    //     return $dataTable->render($this->prefix . 'index');
    // }

    public function json(Request $request)
    {
        abort_if(Gate::denies('roles-read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $roles = Role::query();
        return DataTables::of($roles)
            ->addColumn('hak akses', function ($role) {
                $permissions =  $role->getPermissionNames();
                $output = '';
                foreach ($permissions as $permission) {
                    $output .= '<div class="badge badge-light-success fw-bold my-1 mx-1">' . $permission . '</div> ';
                }
                return $output;
            })
            ->rawColumns(['hak akses'])
            ->toJson();
        // return $info;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('roles-create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::orderBy('name', 'ASC')->get()->pluck('name', 'name');

        return view($this->prefix . 'create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('roles-create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $rules = [
            'name' => 'required',
        ];

        $messages = [
            'required.name' => 'Kolom nama peran wajib diisi!',
        ];

        $this->validate($request, $rules, $messages);

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->givePermissionTo($request->permissions);
        }

        return redirect()->route('dashboard.roles.index')->with(['success' => 'Peran berhasil ditambah']);
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('roles-edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $role = Role::findOrFail($id);
        $permissions = Permission::orderBy('name')->get()->pluck('name', 'name');

        return view($this->prefix . 'edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('roles-edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $request->validate(['name' => 'required']);
        $role = Role::findById($id);
        $role->update($request->except('permission'));

        if ($request->has('permissions')) {
            $role->syncPermissions($request->input('permissions'));
        }
        return redirect()->route('dashboard.roles.index')->with(['success' => 'Peran berhasil diubah']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('roles-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $role = Role::findOrFail($id);
        if ($role->delete()) {
            return response()->json(['status' => 'success', 'message' => 'Peran berhasil dihapus']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Peran gagal dihapus']);
        }
    }

    public function bulkDelete(Request $request)
    {
        abort_if(Gate::denies('roles-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $ids = $request->ids;
        $roles = Role::whereIn('id', $ids);
        if ($roles->delete()) {
            return response()->json(['status' => 'success', 'message' => 'Data yang dipilih berhasil dihapus']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Data yang dipilih gagal dihapus']);
        }
    }
}

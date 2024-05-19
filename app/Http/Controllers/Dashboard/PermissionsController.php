<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

class PermissionsController extends Controller
{
    protected $prefix = 'dashboard.user-management.permissions.';

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
        abort_if(Gate::denies('permissions-read'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = User::find(auth()->id());

        if ($request->ajax()) {
            $permissions = Permission::query()->orderBy('id', 'asc');
            return DataTables::of($permissions)
                ->addColumn('action', function ($item) {
                    return view('dashboard.user-management.permissions.actions', compact('item'));
                })
                ->toJson();
        }
        // $permissions = Permission::orderBy('name', 'ASC')->get();
        // return DataTables::of($permissions)->make(true);
        return view($this->prefix . 'index', compact('user'));
    }

    public function json(Request $request)
    {
        abort_if(Gate::denies('permissions-read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $permissions = Permission::select('id', 'name')->get();
        return DataTables::of($permissions)->toJson();
        // return $info;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('permissions-create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $rules = [
            'akses'     => 'required',
            'opsi'      => 'required'
        ];

        $messages = [
            'required' => 'Kolom :attribute wajib diisi!'
        ];

        $this->validate($request, $rules, $messages);


        foreach ($request->opsi as $option) {
            $name = $request->akses . '-' . $option;
            $permissions = new Permission();
            $permissions->name = $name;
            $permissions->save();

            // give all new created permission to superadmin
            $role = Role::where('name', 'Superadmin')->first();
            $role->givePermissionTo($name);
        }



        return back()->with(['success' => 'Hak akses berhasil ditambah']);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('permissions-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $permission = Permission::findOrFail($id);

        if ($permission->delete()) {
            return response()->json(['status' => 'success', 'message' => 'Hak Akses berhasil dihapus']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Hak Akses gagal dihapus']);
        }
    }

    public function bulkDelete(Request $request)
    {
        abort_if(Gate::denies('permissions-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $ids = $request->ids;
        $permissions = Permission::whereIn('id', $ids);
        if ($permissions->delete()) {
            return response()->json(['status' => 'success', 'message' => 'Data yang dipilih berhasil dihapus']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Data yang dipilih gagal dihapus']);
        }
    }
}

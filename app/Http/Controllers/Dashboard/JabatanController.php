<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\KelasJabatan;
use App\Models\SatuanKerja;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

class JabatanController extends Controller
{
    protected $prefix = 'dashboard.master.jabatan.';

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('master-jabatan-read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $kepSatuan = Jabatan::where('unit_kerja_id', null)->get();
        return view($this->prefix . 'index', compact('kepSatuan'));
    }

    public function json()
    {
        abort_if(Gate::denies('master-jabatan-read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $jabatan = Jabatan::with('parent');
        return DataTables::of($jabatan)
            ->addColumn('jabatan_induk', function ($jabatan) {
                if ($jabatan->parent()->exists()) {
                    return $jabatan->parent->nama_jabatan;
                } else {
                    return '';
                }
            })
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        abort_if(Gate::denies('master-jabatan-create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $jabatan = null;
        $unit = null;
        $kelas = KelasJabatan::all();
        $jenis = [
            [
                'nama' => 'Struktural',
                'value' => 'ST',
            ],
            [
                'nama' => 'Fungsional Umum',
                'value' => 'FU',
            ],
            [
                'nama' => 'Fungsional Pelaksana',
                'value' => 'FP',
            ]
        ];

        // dd($jenis);

        if (isset($request->unit_id) && isset($request->jabatan_id)) {
            $jabatan = Jabatan::where('id', $request->jabatan_id)->first();
            $unit = UnitKerja::where('id', $request->unit_id)->first();
        }
        return view($this->prefix . 'create', compact('jabatan', 'unit', 'kelas', 'jenis'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('master-jabatan-create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // dd($request->all());
        $rules = [
            'nama' => 'required',
            'unit_kerja_id' => 'required|numeric',
            'parent_id' => 'required|numeric',
            'jenis' => 'required|in:FU,ST,FP',
            'kelas' => 'required',
        ];

        $messages = [
            'nama.required' => 'Kolom nama wajib diisi!',
        ];

        $this->validate($request, $rules, $messages);

        $jabatan = Jabatan::create([
            'nama_jabatan' => $request->nama,
            'parent_id' => $request->parent_id,
            'unit_kerja_id' => $request->unit_kerja_id,
            'kelas_jabatan_id' => $request->kelas,
            'jenis' => $request->jenis,
        ]);

        if ($jabatan) {
            return redirect()->route('dashboard.jabatan.index')->with(['success' => 'Jabatan berhasil ditambah']);
        }
        return redirect()->route('dashboard.jabatan.index')->with(['error' => 'Jabatan gagal ditambahkan']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('master-jabatan-edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $myJabatan = Jabatan::findOrFail($id);
        $kelas = KelasJabatan::all();
        $jenis = [
            [
                'nama' => 'Struktural',
                'value' => 'ST',
            ],
            [
                'nama' => 'Fungsional Umum',
                'value' => 'FU',
            ],
            [
                'nama' => 'Fungsional Pelaksana',
                'value' => 'FP',
            ]
        ];

        return view($this->prefix . 'edit', compact('kelas', 'jenis', 'myJabatan'));
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
        abort_if(Gate::denies('master-jabatan-edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rules = [
            'nama' => 'required',
            // 'unit_kerja_id' => 'required|numeric',
            // 'parent_id' => 'required|numberic',
            'jenis' => 'required|in:FU,ST,FP',
            'kelas' => 'required',
        ];
        $messages = [
            'nama.required' => 'Kolom nama wajib diisi!',
        ];

        $this->validate($request, $rules, $messages);

        $jabatan = Jabatan::findOrFail($id);

        $jabatan = $jabatan->update([
            'nama_jabatan' => $request->nama,
            // 'parent_id' => $request->parent_id,
            // 'unit_kerja_id' => $request->unit_kerja_id,
            'kelas_jabatan_id' => $request->kelas,
            'jenis' => $request->jenis,
        ]);

        if ($jabatan) {
            return redirect()->route('dashboard.jabatan.index')->with(['success' => 'Jabatan berhasil ditambah']);
        }
        return redirect()->route('dashboard.jabatan.index')->with(['error' => 'Jabatan gagal ditambahkan']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('master-jabatan-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $jabatan = Jabatan::findOrFail($id);
        if ($jabatan->children()->exists()) {
            return response()->json(['status' => 'error', 'message' => 'Mohon untuk menghapus posisi jabatan yang berada di bawah jabatan ini']);
        }
        if ($jabatan->delete()) {
            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Data gagal dihapus']);
        }
    }

    public function bulkDelete(Request $request)
    {
        abort_if(Gate::denies('master-jabatan-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $ids = $request->ids;
        $jabatan = Jabatan::whereIn('id', $ids);
        if ($jabatan->delete()) {
            return response()->json(['status' => 'success', 'message' => 'Data yang dipilih berhasil dihapus']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Data yang dipilih gagal dihapus']);
        }
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TandaTangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TandaTanganController extends Controller
{

    protected $prefix = 'dashboard.tandatangan.';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = TandaTangan::query();

            return datatables()->of($data)
                ->addColumn('nama', function ($row) {
                    return $row->nama;
                })
                ->addColumn('nip', function ($row) {
                    return $row->nip;
                })
                ->addColumn('jabatan', function ($row) {
                    return $row->jabatan;
                })
                ->addColumn('action', function ($item) {
                    return view($this->prefix . 'actions', compact('item'));
                })
                ->toJson();
        }
        return view($this->prefix . 'index');
    }

    public function create()
    {
        return view($this->prefix . 'create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'nama' => 'required',
            'nip' => 'required',
            'jabatan' => 'required',
        ];


        $this->validate($request, $rules);

        try {


            $data = TandaTangan::create([
                'nama' => $request->nama,
                'nip' => $request->nip,
                'jabatan' => $request->jabatan,
            ]);

            return redirect()->route($this->prefix . 'index')->with('success', 'Berhasil menambahkan item');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->route($this->prefix . 'index')->with('error', 'Gagal menambahkan item');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = TandaTangan::findOrFail($id);
        return view($this->prefix . 'show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = TandaTangan::findOrFail($id);
        return view($this->prefix . 'edit', compact('data'));
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
        $tandaTangan = TandaTangan::where('id', $id)->first();

        $rules = [
            'nama' => 'required',
            'nip' => 'required',
            'jabatan' => 'required',
        ];

        $this->validate($request, $rules);

        try {

            $tandaTangan->update([
                'nama' => $request->nama ?? $tandaTangan->nama,
                'nip' => $request->nip ?? $tandaTangan->nip,
                'jabatan' => $request->jabatan ?? $tandaTangan->jabatan,
            ]);

            $tandaTangan->touch();

            return redirect()->route('dashboard.tandatangan.index')->with('success', 'Berhasil mengupdate data');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->route('dashboard.tandatangan.index')->with('error', 'Gagal mengupdate data');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tandaTangan = TandaTangan::findOrFail($id);
        if ($tandaTangan->delete()) {

            return response()->json(['status' => 'success', 'message' => 'Item berhasil dihapus']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Item gagal dihapus']);
        }
    }
}

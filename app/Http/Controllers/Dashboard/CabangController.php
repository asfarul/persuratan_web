<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CabangController extends Controller
{

    protected $prefix = 'dashboard.cabang.';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('cabang-read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user = Auth::user();
        if ($request->ajax()) {
            $cabang = Cabang::query();
            return datatables()->of($cabang)
                ->addColumn('action', function ($item) {
                    return view('dashboard.cabang.actions', compact('item'));
                })
                ->toJson();
        }
        return view($this->prefix . 'index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('cabang-create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        abort_if(Gate::denies('cabang-create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $rules = [
            'nama' => 'required',
            // 'alias' => 'required',
            'kode' => 'required',
            'alamat' => 'required',
        ];

        $messages = [
            'required.nama' => 'Kolom nama wajib diisi!',
            'required.kode' => 'Kolom kode cabang wajib diisi!',
            // 'required.alias' => 'Kolom alias wajib diisi!',
            'required.alamat' => 'Kolom alamat wajib diisi!',
        ];

        $this->validate($request, $rules, $messages);

        $cabang = Cabang::create($request->all());

        return redirect()->route($this->prefix . 'index')->with(['success' => 'Cabang berhasil ditambah']);
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
        abort_if(Gate::denies('cabang-edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $cabang = Cabang::findOrFail($id);

        return view($this->prefix . 'edit', compact('cabang'));
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
        abort_if(Gate::denies('cabang-edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $rules = [
            'nama' => 'required',
            // 'alias' => 'required',
            'kode' => 'required',
            'alamat' => 'required',
        ];

        $messages = [
            'required.nama' => 'Kolom nama wajib diisi!',
            'required.kode' => 'Kolom kode cabang wajib diisi!',
            // 'required.alias' => 'Kolom alias wajib diisi!',
            'required.alamat' => 'Kolom alamat wajib diisi!',
        ];

        $this->validate($request, $rules, $messages);

        try {
            $cabang = Cabang::findOrFail($id);
            $cabang->update([
                'nama' => $request->nama ?? $cabang->nama,
                'alias' => $request->alias ?? $cabang->alias,
                'kode' => $request->kode ?? $cabang->kode,
                'alamat' => $request->alamat ?? $cabang->alamat,
            ]);
            return redirect()->route($this->prefix . 'index')->with(['success' => 'Cabang berhasil diubah']);
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->route($this->prefix . 'index')->with(['error' => 'Terjadi kesalahan, gagal mengubah data']);
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
        abort_if(Gate::denies('cabang-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $cabang = Cabang::findOrFail($id);
        if ($cabang->delete()) {
            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Data gagal dihapus']);
        }
    }
}

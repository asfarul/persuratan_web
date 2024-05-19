<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\SmartTVEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\SlideshowResource;
use App\Models\Slideshow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class SlideshowsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $prefix = 'dashboard.slideshows.';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('slideshows-read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $slideshows = Slideshow::query();
            if ($request->has('status') && $request->status != '') {
                $slideshows->where('is_active', $request->status);
            }
            return datatables()->of($slideshows)
                ->addColumn('judul', function ($slideshow) {

                    return $slideshow->judul;
                })
                ->addColumn('file', function ($slideshow) {
                    $linkFile = ' <a href="' . asset('storage/' . $slideshow->file) . '"
                    class="text-gray-800 text-hover-primary mb-1">Lihat File</a>';
                    return $linkFile;
                })
                ->addColumn('tipe', function ($slideshow) {
                    return strtoupper($slideshow->type);
                })
                ->addColumn('status', function ($slideshow) {
                    $status = '<div class="badge badge-light-danger fw-bold my-1 mx-1">Nonaktif</div> ';
                    if ($slideshow->is_active) {
                        $status = '<div class="badge badge-light-success fw-bold my-1 mx-1">Aktif</div> ';
                    }
                    return $status;
                })
                ->addColumn('kategori', function ($item) {
                    // dd($item->user->pegawai);
                    $kategori = 'BANK KALBAR';
                    if ($item->is_syariah) $kategori = 'BANK KALBAR SYARIAH';
                    return $kategori;
                })
                ->addColumn('action', function ($item) {
                    return view($this->prefix . 'actions', compact('item'));
                })
                ->rawColumns(['status', 'file'])
                ->toJson();
        }
        return view($this->prefix . 'index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('slideshows-create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        abort_if(Gate::denies('slideshows-create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $rules = [
            'name' => 'required',
            'file' => 'required|max:20000|mimes:jpg,png,webp,webm,mkv,mp4,gif',
        ];

        $messages = [
            'name.required' => 'Nama tidak boleh kosong',
            'max' => 'Ukuran gambar tidak boleh lebih dari 200MB',
            'mimes' => 'Format file harus: jpg, jpeg, png, webp, webm, mp4, mkv, gif.'
        ];

        $this->validate($request, $rules, $messages);

        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '.' . $file->extension();
                $filePath = 'files/slideshows/' . $fileName;
                $storedImage = Storage::putFileAs('public/files/slideshows', $file, $fileName);
            } else {
                $filePath = null;
            }

            $slideshow = Slideshow::create([
                'judul' => $request->name,
                'deskripsi' => $request->deskripsi,
                'file' => $filePath,
                'type' => $request->type,
                'is_active' => $request->is_active == 1 ? true : false,
                'is_syariah' => $request->is_syariah,
                'created_by' => auth()->id(),
            ]);

            if ($slideshow) {
                $messageData = [
                    'category' => 'SLIDESHOW',
                    'method' => 'CREATE',
                    'data' => new SlideshowResource($slideshow),
                ];
                broadcast(new SmartTVEvent($messageData))->toOthers();
            }

            return redirect()->route($this->prefix . 'index')->with('success', 'Berhasil menambahkan item slideshow');
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
        abort_if(Gate::denies('slideshows-edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $slideshow = Slideshow::where('id', $id)->first();
        return view($this->prefix . 'edit', compact('slideshow'));
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
        abort_if(Gate::denies('slideshows-edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $slideshow = Slideshow::where('id', $id)->first();

        $rules = [
            'name' => 'required',
            'file' => 'nullable|max:20000|mimes:jpg,png,webp,webm,mkv,mp4,gif',
        ];

        $messages = [
            'name.required' => 'Nama tidak boleh kosong',
            'file.required' => 'File tidak boleh kosong',
            'max' => 'Ukuran gambar tidak boleh lebih dari 200MB',
            'mimes' => 'Format file harus: jpg, jpeg, png, webp, webm, mp4, mkv, gif.'
        ];

        $this->validate($request, $rules, $messages);

        try {
            // jika ada foto
            if ($request->hasFile('file')) {
                if ($slideshow->file && Storage::exists($slideshow->file)) {
                    Storage::delete($slideshow->file);
                }

                $file = $request->file('file');
                $fileName = time() . '.' . $file->extension();
                $filePath = 'files/slideshows/' . $fileName;
                $storedFile = Storage::putFileAs('public/files/slideshows', $file, $fileName);
            } else {
                $filePath = $slideshow->file;
            }

            $slideshow->update([
                'judul' => $request->name ?? $slideshow->judul,
                'deskripsi' => $request->deskripsi ?? $slideshow->deskripsi,
                'type' => $request->type ?? $slideshow->type,
                'is_active' => $request->is_active ?? $slideshow->is_active,
                'is_syariah' => $request->is_syariah ?? $slideshow->is_syariah,
                'file' => $filePath ?? $request->foto,
                'last_updated_by' => auth()->id(),
            ]);

            $slideshow->touch();

            $messageData = [
                'category' => 'SLIDESHOW',
                'method' => 'UPDATE',
                'data' => new SlideshowResource($slideshow),
            ];

            broadcast(new SmartTVEvent($messageData))->toOthers();

            return redirect()->route('dashboard.slideshows.index')->with('success', 'Berhasil mengupdate data slideshow');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->route('dashboard.slideshows.index')->with('error', 'Gagal mengupdate data slideshow');
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
        abort_if(Gate::denies('slideshows-delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $slideshow = Slideshow::findOrFail($id);
        if ($slideshow->delete()) {
            $messageData = [
                'category' => 'SLIDESHOW',
                'method' => 'DELETE',
                'data' => [
                    'id' => $id,
                ],
            ];

            broadcast(new SmartTVEvent($messageData))->toOthers();
            return response()->json(['status' => 'success', 'message' => 'Item berhasil dihapus']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Item gagal dihapus']);
        }
    }
}

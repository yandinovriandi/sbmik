<?php

namespace App\Http\Controllers;

use App\Models\Mikrotik;
use App\Repositories\HotspotRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RouterOS\Exceptions\BadCredentialsException;
use RouterOS\Exceptions\ClientException;
use RouterOS\Exceptions\ConfigException;
use RouterOS\Exceptions\ConnectException;
use RouterOS\Exceptions\QueryException;
use Yajra\DataTables\Facades\DataTables;

class MikrotikController extends Controller
{
    private HotspotRepository $hotspotRepository;
    public function __construct(HotspotRepository $hotspotRepository)
    {
        $this->hotspotRepository = $hotspotRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = auth()->user()->mikrotiks()->where('user_id',auth()->user()->id)->latest();
        if (request()->ajax()){
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('host', function ($data) {
                    return '<span class="badge badge-warning" data-toggle="tooltip" data-original-title="Host Mikrotik">'.$data->host.'</span>';
                })->editColumn('username', function ($data) {
                    return '<span class="badge badge-info" data-toggle="tooltip" data-original-title="Username">'.$data->username.'</span>';
                })->editColumn('password', function ($data) {
                    $password = $data->password; // Mengambil nilai password dari objek $data
                    $showDigits = 4; // Ganti dengan jumlah digit yang ingin ditampilkan

                    $displayPassword = substr($password, 0, -$showDigits) . str_repeat('*', $showDigits); // Mengubah beberapa digit terakhir password menjadi bintang
                    return '<span class="badge badge-primary" data-toggle="tooltip" data-original-title="Password">'.$displayPassword.'</span>'; // Mengembalikan tampilan password yang sudah dimodifikasi
                })->editColumn('port', function ($data) {
                    return '<span class="badge badge-success" data-toggle="tooltip" data-original-title="Port Mikrotik">'.$data->port.'</span>';
                })->addColumn('action',function ($data){
                    return view('mikrotik.partials._actions')->with('data', $data);
                })->rawColumns(['host','username','password','port','action'])
                ->make(true);
        }
        return view('mikrotik.index');

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mikrotik.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'host' => 'required',
            'username' => 'required|string|max:255',
            'password' => 'required|string',
            'port' => 'required|numeric',
        ]);

       auth()->user()->mikrotiks()->create([
            'name' => $name = $request->name,
            'slug' => str($name . '-' . Str::random(6))->slug(),
            'host' => $request->host,
            'username' => $request->username,
            'password' => $request->password,
            'port' => $request->port
        ]);

        return redirect()->route('mikrotik.index')->with('success', 'Data berhasil disimpan');
    }


    /**
     * Display the specified resource.
     */
    public function show(Mikrotik $mikrotik)
    {
        return view('mikrotik.show',[
            'mikrotik' => $mikrotik
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mikrotik $mikrotik)
    {
        return view('mikrotik.edit',[
            'mikrotik' => $mikrotik
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mikrotik $mikrotik)
    {
        $request->validate([
            'name' => 'required',
            'host' => 'required',
            'username' => 'required',
            'password' => 'required',
            'port' => 'required',
        ]);

        $mikrotik->update([
            'name' => $request->name,
            'host' => $request->host,
            'username' => $request->username,
            'password' => $request->password,
            'port' => $request->port
        ]);

        return redirect()->route('mikrotik.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mikrotik $mikrotik)
    {
        $mikrotik->delete();
    }

}

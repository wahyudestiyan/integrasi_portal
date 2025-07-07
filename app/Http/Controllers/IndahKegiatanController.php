<?php

namespace App\Http\Controllers;

use App\Services\IndahKegiatanService;
use App\Models\IndahKegiatan;
use App\Models\Msvar;
use App\Models\Msind;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Http\Request; 


class IndahKegiatanController extends Controller
{
    // Menampilkan daftar kegiatan
   public function index(Request $request)
{
    // Default tahun = tahun lalu (pelaporan tahun sebelumnya)
    $defaultTahun = now()->year - 1;

    // Ambil tahun dari query, jika tidak ada gunakan default
    $selectedTahun = $request->input('tahun', $defaultTahun);

    $query = IndahKegiatan::query();

    // Filter berdasarkan pencarian teks
    if ($request->filled('q')) {
        $search = $request->q;
        $query->where(function ($qBuilder) use ($search) {
            $qBuilder->where('tahun', 'like', "%$search%")
                     ->orWhere('produsen_data_name', 'like', "%$search%")
                     ->orWhere('judul_kegiatan', 'like', "%$search%");
        });
    }

    // Filter berdasarkan tahun (default: tahun lalu)
    if (!empty($selectedTahun)) {
        $query->where('tahun', $selectedTahun);
    }

    // Ambil data dengan pagination
    $kegiatan = $query->orderBy('created_at', 'desc')->paginate(10);
    $kegiatan->appends($request->all());

    // Buat daftar tahun dari 2019 sampai tahun sekarang
    $tahunList = range(2022, now()->year);

    // Jika AJAX, hanya render baris dan pagination
    if ($request->ajax()) {
        return response()->json([
            'rows' => view('indah_kegiatan.partials.table_rows', compact('kegiatan'))->render(),
            'pagination' => $kegiatan->links('pagination::bootstrap-5')->toHtml(),
        ]);
    }

    return view('indah_kegiatan.index', compact('kegiatan', 'tahunList', 'selectedTahun'));
}




  public function showAllMsvar(Request $request, $id)
{
    $kegiatan = IndahKegiatan::findOrFail($id);

    $query = Msvar::where('id_mskeg', $kegiatan->id_keg)
                ->orderBy('created_at', 'desc');

    if ($request->has('q')) {
        $q = $request->q;
        $query->where('nama', 'like', '%' . $q . '%');
    }

    $msvars = $query->get();

    // Jika AJAX, kirim HTML row-nya saja
    if ($request->ajax()) {
    $html = '';
    foreach ($msvars as $index => $var) {
        $html .= '<tr>
            <td>' . ($index + 1) . '</td>
            <td>' . ucwords(strtolower(str_replace('_', ' ', $var->nama))) . '</td>
            <td>' . ucwords(strtolower(str_replace('_', ' ', $var->alias))) . '</td>
            <td>' . ucwords(strtolower(str_replace('_', ' ', $var->produsen_data_name))) . '</td>
            <td>' . ucwords(strtolower(str_replace('_', ' ', $var->requested_by))) . '</td>
            <td><span class="badge bg-' . ($var->status == 'APPROVED' ? 'success' : 'warning') . '">' . ($var->status == 'APPROVED' ? 'Disetujui' : 'Menunggu') . '</span></td>
            <td><a href="' . route('msvar.show', $var->id) . '" class="btn btn-sm btn-info">Lihat Detail</a></td>
        </tr>';
    }

    if ($msvars->isEmpty()) {
        $html = '<tr><td colspan="9" class="text-center">Belum ada Metadata Variabel.</td></tr>';
    }

    return response($html);
}


    return view('indah_kegiatan.index-msvar', compact('msvars', 'kegiatan'));
}


 public function showAllMsind(Request $request, $id)
{
    $kegiatan = IndahKegiatan::findOrFail($id);

    $query = Msind::where('id_mskeg', $kegiatan->id_keg)
                ->orderBy('created_at', 'desc');

    if ($request->has('q')) {
        $q = $request->q;
        $query->where('nama', 'like', '%' . $q . '%');
    }

    $msinds = $query->get();

    if ($request->ajax()) {
        $html = '';
        foreach ($msinds as $index => $ind) {
            $html .= '<tr>
                <td>' . ($index + 1) . '</td>
                <td>' . ucwords(strtolower(str_replace("_", " ", $ind->nama))) . '</td>
                <td>' . ucwords(strtolower(str_replace("_", " ", $ind->produsen_data_name))) . '</td>
                <td>' . ucwords(strtolower(str_replace("_", " ", $ind->requested_by))) . '</td>
                <td><span class="badge bg-' . ($ind->status == 'APPROVED' ? 'success' : 'warning') . '">' . ($ind->status == 'APPROVED' ? 'Disetujui' : 'Menunggu') . '</span></td>
                <td><a href="' . route('msind.show', $ind->id) . '" class="btn btn-sm btn-info">Lihat Detail</a></td>
            </tr>';
        }

        if ($msinds->isEmpty()) {
            $html = '<tr><td colspan="9" class="text-center">Belum ada Metadata Indikator.</td></tr>';
        }

        return response($html);
    }

    return view('indah_kegiatan.index-msind', compact('msinds', 'kegiatan'));
}



    // Menampilkan detail salah satu kegiatan
    public function show($id)
    {
        $kegiatan = IndahKegiatan::with(['variabels', 'indikator'])->findOrFail($id);
        return view('indah_kegiatan.show', compact('kegiatan'));
    }

    public function showMsvar($id)
    {
        $msvar = Msvar::findOrFail($id);
        $kegiatan = IndahKegiatan::where('id_keg', $msvar->id_mskeg)->first(); // ambil kegiatan berdasarkan id_mskeg
        return view('indah_kegiatan.msvar', compact('msvar','kegiatan'));
}

    public function showMsind($id)
    {
        $msind = Msind::findOrFail($id);
        $kegiatan = IndahKegiatan::where('id_keg', $msind->id_mskeg)->first(); // ambil kegiatan berdasarkan id_mskeg
        return view('indah_kegiatan.msind', compact('msind','kegiatan'));
}



    // (Opsional) Fungsi lainnya seperti sinkronisasi dari API, hapus, edit, dll bisa ditambahkan di sini
 public function sync(Request $request)
    {
        $tahun = $request->input('tahun'); // ambil dari query string, misal ?tahun=2025

        try {
            $result = IndahKegiatanService::fetchKegiatan($tahun);

            if ($result) {
                return redirect()->back()->with('success', 'Sinkronisasi berhasil dilakukan.');
            } else {
                return redirect()->back()->with('warning', 'Sinkronisasi selesai, tetapi tidak ada data baru yang disimpan.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

public function syncMsvar($id)
{
    $kegiatan = IndahKegiatan::findOrFail($id);
    IndahKegiatanService::fetchAndStoreMsvar($kegiatan->id_keg, $kegiatan->id_keg);

    return redirect()->back()->with('success', 'Sinkronisasi MsVar berhasil untuk ID ' . $kegiatan->id_keg);
}

public function syncMsind($id)
{
    $kegiatan = IndahKegiatan::findOrFail($id);
    IndahKegiatanService::fetchAndStoreMsind($kegiatan->id_keg, $kegiatan->id_keg);

    return redirect()->back()->with('success', 'Sinkronisasi MsInd berhasil untuk ID ' . $kegiatan->id_keg);
}



public function downloadPdfkeg($id)
    {
  
        $kegiatan = IndahKegiatan::findOrFail($id);
        $pdf = Pdf::loadView('indah_kegiatan.cetak-keg', compact('kegiatan'))
                  ->setPaper('a4', 'portrait'); // Set paper size and orientation
        return $pdf->stream('metadata-kegiatan-' . $kegiatan->id . '.pdf');

    }

    
public function downloadPdfvar($id)
    {
         $msvar = Msvar::findOrFail($id);
    $kegiatan = IndahKegiatan::where('id_keg', $msvar->id_mskeg)->first();

    $pdf = Pdf::loadView('indah_kegiatan.cetak-var', compact('msvar', 'kegiatan'))
              ->setPaper('a4', 'portrait');

    return $pdf->stream('metadata-variabel-' . $msvar->id . '.pdf');
    }

    
public function downloadPdfind($id)
    {
        $msind = Msind::findOrFail($id);
    $kegiatan = IndahKegiatan::where('id_keg', $msind->id_mskeg)->first();

    $pdf = Pdf::loadView('indah_kegiatan.cetak-ind', compact('msind', 'kegiatan'))
              ->setPaper('a4', 'portrait');

    return $pdf->stream('metadata-indikator-' . $msind->id . '.pdf');
}
}
<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Jurusan;
use App\Models\User;
use App\Models\AbsensiSekolah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminCon extends Controller
{
    public function dashboard(): Response
    {
        $hariIni = now()->toDateString();

        $distribusi = DB::table('kelas as k')
            ->leftJoin('siswa as s', 's.id_kelas', '=', 'k.id_kelas')
            ->leftJoin('absensi_sekolah as a', 'a.id_siswa', '=', 's.id_siswa')
            ->select(
                'k.nama_kelas',
                DB::raw("COALESCE(SUM(CASE WHEN a.status = 'hadir' THEN 1 ELSE 0 END), 0) as hadir"),
                DB::raw("COALESCE(SUM(CASE WHEN a.status = 'izin' THEN 1 ELSE 0 END), 0) as izin"),
                DB::raw("COALESCE(SUM(CASE WHEN a.status = 'sakit' THEN 1 ELSE 0 END), 0) as sakit"),
                DB::raw("COALESCE(SUM(CASE WHEN a.status = 'alfa' THEN 1 ELSE 0 END), 0) as alfa"),
                DB::raw('COUNT(DISTINCT s.id_siswa) as total'), // total siswa per kelas
            )
            ->whereDate('a.tanggal', $hariIni) // filter hanya absensi hari ini
            ->orWhereNull('a.tanggal') // biar kelas tetap tampil walau belum ada absen
            ->groupBy('k.nama_kelas')
            ->get();

        // 🔹 Rekap absensi semua siswa per hari
        $rekapHarian = DB::table('absensi_sekolah')->selectRaw('DATE(tanggal) as tanggal')->selectRaw("SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) as hadir")->selectRaw("SUM(CASE WHEN status = 'izin' THEN 1 ELSE 0 END) as izin")->selectRaw("SUM(CASE WHEN status = 'sakit' THEN 1 ELSE 0 END) as sakit")->selectRaw("SUM(CASE WHEN status = 'alfa' THEN 1 ELSE 0 END) as alfa")->groupBy('tanggal')->orderBy('tanggal', 'ASC')->get();

        return Inertia::render('Admin/Dashboard', [
            'totalUsers' => User::count(),
            'totalSiswa' => User::where('role', 'user')->count(),
            'totalGuru' => User::where('role', 'guru')->count(),
            'totalAdmin' => User::where('role', 'admin')->count(),
            'totalabsen' => AbsensiSekolah::where('status', 'hadir')->count(),
            'totalizin' => AbsensiSekolah::where('status', 'izin')->count(),
            'totalsakit' => AbsensiSekolah::where('status', 'sakit')->count(),
            'totalalfa' => AbsensiSekolah::where('status', 'alfa')->count(),
            'absen' => AbsensiSekolah::whereBetween('jam_masuk', ['06:40:00', '06:50:00'])->count(),
            'warning' => AbsensiSekolah::whereBetween('jam_masuk', ['06:51:00', '07:00:00'])->count(),
            'telat' => AbsensiSekolah::whereBetween('jam_masuk', ['07:01:00', '09:00:00'])->count(),
            'alfa' => AbsensiSekolah::whereBetween('jam_masuk', ['09:01:00', '24:00:00'])->count(),

            // Data chart
            'distribusi' => $distribusi, // tetap ada
            'rekapHarian' => $rekapHarian, // ✅ tambahan
        ]);
    }

    // Users section Start
    // Menampilkan Data User

    public function indexs()
    {
        // Ambil data user dengan pagination (5 per halaman)
        $users = DB::table('users')->select('id', 'name', 'email', 'role')->paginate(5);

        return Inertia::render('Admin/manageuser', [
            'users' => $users,
        ]);
    }
    // Menampilkan Form Tambah Guru
    public function tambahs()
    {
        return inertia('Admin/tambahuser', []);
    }

    // Menyimpan Data Guru Yang Dikirim dari Form Tambah Guru
    public function stores(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string|in:admin,guru,user',
            'password' => 'required|string|min:6',
        ]);

        // Simpan ke tabel users
        DB::table('users')->insert([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Redirect balik ke halaman data user
        return redirect()->route('user')->with('success', 'Data user berhasil ditambahkan!');
    }

    // Menampilkan Form Edit Guru
    public function edits($id)
    {
        $user = DB::table('users')->where('id', $id)->first();

        // Hapus ini jika tidak diperlukan, atau ganti nama variable
        // $users = \App\Models\User::select(['id', 'name', 'email', 'role'])->get();

        return inertia('Admin/edituser', [
            'user' => $user, // single user untuk diedit
            // 'users' => $users,  // hapus atau ganti nama jika diperlukan
        ]);
    }

    // Memperbarui Data Guru Yang Dikirim dari Form Edit Guru
    public function updates(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|in:Admin,guru,user',
        ]);

        $updated = DB::table('users')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ]);

        if ($updated) {
            return redirect()->route('user')->with('success', 'Data user berhasil diperbarui!');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui data!');
        }
    }

    public function password()
    {
        $users = User::all();
        return inertia('Admin/gantipassword', [
            'users' => $users,
        ]);
    }

    public function updatepass(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = User::findOrFail($request->id);

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect('/user')->with('success', 'Password user berhasil diubah!');
    }

    // Menghapus Data users
    public function destroys($id)
    {
        DB::table('users')->where('id', operator: $id)->delete();
        return redirect('/user');
    }
    // Users Section End

    // Guru Section Start
    // Menampilkan Data Guru
    public function index()
    {
        $guru = DB::table('guru')->join('mapel', 'guru.id_mapel', '=', 'mapel.id_mapel')->select('guru.id_guru', 'guru.nama', 'guru.nip', 'guru.email', 'mapel.nama_mapel as mapel')->paginate(5);
        return Inertia::render('Admin/guru', ['guru' => $guru]);
    }

    // Menampilkan Form Tambah Guru
    public function tambah()
    {
        $users = \App\Models\User::where('role', 'guru')->select('id', 'name', 'email')->get();
        $mapel = \App\Models\Mapel::all(['id_mapel', 'nama_mapel']); // ambil user yang ada
        return inertia('Admin/tambahguru', [
            'users' => $users,
            'mapel' => $mapel,
        ]);
    }

    // Menyimpan Data Guru Yang Dikirim dari Form Tambah Guru
    public function store(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id', // id harus ada di tabel users
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|unique:guru,nip',
            'email' => 'required|email',
            'id_mapel' => 'required|exists:mapel,id_mapel',
        ]);

        // Simpan ke tabel guru
        DB::table('guru')->insert($validated);

        // Redirect balik ke halaman data guru
        return redirect()->route('guru')->with('success', 'Data guru berhasil ditambahkan!');
    }

    // Menampilkan Form Edit Guru
    public function edit($id)
    {
        $guru = DB::table('guru')->where('id_guru', $id)->first(); // gunakan first()
        $users = \App\Models\User::where('role', 'guru')->select('id', 'name', 'email')->get();
        $mapel = \App\Models\Mapel::all(['id_mapel', 'nama_mapel']);

        return inertia('Admin/editguru', [
            'guru' => $guru,
            'users' => $users,
            'mapel' => $mapel,
        ]);
    }

    // Memperbarui Data Guru Yang Dikirim dari Form Edit Guru
    public function update(Request $request, $id)
    {
        // Validasi data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:20',
            'email' => 'required|email',
            'id_mapel' => 'required|exists:mapel,id_mapel',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $updated = DB::table('guru')
            ->where('id_guru', $id)
            ->update([
                'user_id' => $request->user_id,
                'nama' => $request->nama,
                'nip' => $request->nip,
                'email' => $request->email,
                'id_mapel' => $request->id_mapel,
                'foto' => $request->foto ? $request->file('foto')->store('foto_guru', 'public') : null, // Simpan file jika ada
            ]);

        if ($updated) {
            return redirect()->route('guru')->with('success', 'Data guru berhasil diperbarui!');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui data!');
        }
    }

    // Menghapus Data Guru
    public function destroy($id)
    {
        DB::table('guru')->where('id_guru', operator: $id)->delete();
        return redirect('/guru');
    }
    // Guru Section End

    // Jadwal Section Start
    // Menampilkan Data Jadwal
    public function jadwal()
    {
        $jadwal = DB::table('jadwal')->join('mapel', 'jadwal.id_mapel', '=', 'mapel.id_mapel')->join('guru', 'jadwal.id_guru', '=', 'guru.id_guru')->join('kelas', 'jadwal.id_kelas', '=', 'kelas.id_kelas')->select('jadwal.id_jadwal', 'jadwal.id_mapel', 'jadwal.id_guru', 'jadwal.id_kelas', 'jadwal.hari','jadwal.lantai','ruang', 'jadwal.jam_mulai', 'jadwal.jam_selesai', 'mapel.nama_mapel as mapel', 'guru.nama as guru', 'kelas.nama_kelas as kelas')->paginate(5);
        return Inertia::render('Admin/jadwal', ['jadwal' => $jadwal]);
    }

    // Menampilkan Form Tambah Jadwal
    public function tambahd()
    {
        $jadwal = \App\Models\Jadwal::all(['hari','lantai','ruang', 'jam_mulai', 'jam_selesai']); // ambil user yang ada
        $mapel = \App\Models\Mapel::all(['id_mapel', 'nama_mapel']); // ambil user yang ada
        $guru = \App\Models\guru::all(['id_guru', 'nama']); // ambil user yang ada
        $kelas = \App\Models\kelas::all(['id_kelas', 'nama_kelas']); // ambil user yang ada
        return inertia('Admin/tambahjadwal', [
            'jadwal' => $jadwal,
            'mapel' => $mapel,
            'guru' => $guru,
            'kelas' => $kelas,
        ]);
    }

    // Menyimpan    Data Jadwal Yang Dikirim dari Form Tambah Jadwal
    public function stored(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'id_guru' => 'required|string|exists:guru,id_guru',
            'id_mapel' => 'required|exists:mapel,id_mapel',
            'id_kelas' => 'required|string|exists:kelas,id_kelas',
            'hari' => 'required|string',
            'lantai' => 'required|string',
            'ruang' => 'required|string',
            'jam_mulai' => 'required|string',
            'jam_selesai' => 'required|string',
        ]);

        // Simpan ke tabel Jadwal
        DB::table('jadwal')->insert($validated);

        // Redirect balik ke halaman data Jadwal
        return redirect()->route('jadwal')->with('success', 'Data guru berhasil ditambahkan!');
    }

    // Menampilkan form edit jadwal
    public function editd($id)
    {
        $jadwal = DB::table('jadwal')->where('id_jadwal', $id)->first();
        $mapel = Mapel::all(['id_mapel', 'nama_mapel']);
        $guru = Guru::all(['id_guru', 'nama']);
        $kelas = Kelas::all(['id_kelas', 'nama_kelas']);

        return inertia('Admin/editjadwal', [
            'jadwal' => $jadwal,
            'guru' => $guru,
            'mapel' => $mapel,
            'kelas' => $kelas,
        ]);
    }

    // Memperbarui data jadwal
    public function updated(Request $request, $id)
    {
        $validated = $request->validate([
            'id_guru' => 'required|string|exists:guru,id_guru',
            'id_mapel' => 'required|exists:mapel,id_mapel',
            'id_kelas' => 'required|string|exists:kelas,id_kelas',
            'hari' => 'required|string',
            'lantai' => 'required|string',
            'ruang' => 'required|string',
            'jam_mulai' => 'required|string',
            'jam_selesai' => 'required|string',
        ]);

        DB::table('jadwal')->where('id_jadwal', $id)->update($validated);

        return redirect()->route('jadwal')->with('success', 'Data jadwal berhasil diperbarui!');
    }

    // Menghapus Data Jadwal
    public function destroyd($id)
    {
        DB::table('jadwal')->where('id_jadwal', operator: $id)->delete();
        return redirect('/jadwal');
    }
    // Jadwal Section End

    // Siswa X Section Start
    // Menampilkan Data Siswa Kelas X RPL
    public function siswax(Request $request)
    {
        // Ambil daftar kelas unik di tingkat X RPL
        $kelasList = DB::table('siswa')
            ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->where('kelas.tingkat_kelas', 'like', '1%')
            ->pluck('kelas.nama_kelas')
            ->unique()
            ->values();

        // Ambil filter kelas dari query string
        $selectedKelas = $request->input('kelas');

        // Query siswa
        $siswax = DB::table('siswa')
            ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->join('jurusan', 'siswa.id_jurusan', '=', 'jurusan.id_jurusan')
            ->select('siswa.id_siswa', 'siswa.nama', 'siswa.email', 'kelas.nama_kelas as kelas', 'jurusan.nama_jurusan as jurusan')
            ->where('kelas.tingkat_kelas', 'like', '1%')
            ->when($selectedKelas, function ($query, $kelas) {
                return $query->where('kelas.nama_kelas', $kelas);
            })
            ->paginate(5)
            ->appends($request->query());

        return Inertia::render('Admin/xrpl', [
            'siswa' => $siswax,
            'kelasList' => $kelasList,
            'selectedKelas' => $selectedKelas,
        ]);
    }

    // Menampilkan Form Tambah Siswa Kelas X RPL
    public function tambahx()
    {
        $users = \App\Models\User::where('role', 'user')->select('id', 'name', 'email')->get();
        $kelas = \App\Models\kelas::all(['id_kelas', 'nama_kelas']);
        $jurusan = \App\Models\Jurusan::all(['id_jurusan', 'nama_jurusan']);
        return inertia('Admin/tambahx', [
            'users' => $users,
            'kelas' => $kelas,
            'jurusan' => $jurusan,
        ]);
    }
    // Menyimpan Data Siswa Kelas X RPL Yang Dikirim dari Form Tambah Siswa
    public function storex(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id', // id harus ada di tabel users
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
        ]);

        // Simpan ke tabel siswa
        DB::table('siswa')->insert($validated);

        // Redirect balik ke halaman data siswa X RPL
        return redirect()->route('siswax')->with('success', 'Data Siswa X RPL berhasil ditambahkan!');
    }

    // Menampilkan Form Edit Siswa Kelas X RPL
    public function editx($id)
    {
        $siswa = DB::table('siswa')->where('id_siswa', $id)->first(); // gunakan first()
        $users = \App\Models\User::where('role', 'user')->select('id', 'name', 'email')->get();
        $kelas = \App\Models\kelas::all(['id_kelas', 'nama_kelas']);
        $jurusan = \App\Models\Jurusan::all(['id_jurusan', 'nama_jurusan']);

        return inertia('Admin/editx', [
            'siswa' => $siswa,
            'users' => $users,
            'kelas' => $kelas,
            'jurusan' => $jurusan,
        ]);
    }

    // Memperbarui Data Siswa X RPL Yang Dikirim dari Form Edit Siswa X RPL
    public function updatex(Request $request, $id)
    {
        // Validasi data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
        ]);

        $update = DB::table('siswa')
            ->where('id_siswa', $id)
            ->update([
                'user_id' => $request->user_id,
                'nama' => $request->nama,
                'email' => $request->email,
                'id_kelas' => $request->id_kelas,
                'id_jurusan' => $request->id_jurusan,
            ]);

        if ($update) {
            return redirect()->route('siswax')->with('success', 'Data siswa X RPL berhasil diperbarui!');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui data!');
        }
    }

    // Menghapus Data Siswa Kelas X RPL
    public function destroyx($id)
    {
        DB::table('siswa')->where('id_siswa', operator: $id)->delete();
        return redirect('/siswax');
    }
    // Siswa X Section End

    // Siswa XI Section Start
    // Menampilkan Data Siswa Kelas X RPL
public function siswaxi(Request $request)
    {
        // Ambil daftar kelas unik di tingkat X RPL
        $kelasList = DB::table('siswa')
            ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->where('kelas.tingkat_kelas', 'like', '2%')
            ->pluck('kelas.nama_kelas')
            ->unique()
            ->values();

        // Ambil filter kelas dari query string
        $selectedKelas = $request->input('kelas');

        // Query siswa
        $siswaxi = DB::table('siswa')
            ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->join('jurusan', 'siswa.id_jurusan', '=', 'jurusan.id_jurusan')
            ->select('siswa.id_siswa', 'siswa.nama', 'siswa.email', 'kelas.nama_kelas as kelas', 'jurusan.nama_jurusan as jurusan')
            ->where('kelas.tingkat_kelas', 'like', '2%')
            ->when($selectedKelas, function ($query, $kelas) {
                return $query->where('kelas.nama_kelas', $kelas);
            })
            ->paginate(5)
            ->appends($request->query());

        return Inertia::render('Admin/xirpl', [
            'siswa' => $siswaxi,
            'kelasList' => $kelasList,
            'selectedKelas' => $selectedKelas,
        ]);
    }

    // Menampilkan Form Tambah Siswa Kelas X RPL
    public function tambahxi()
    {
        $users = \App\Models\User::where('role', 'user')->select('id', 'name', 'email')->get();
        $kelas = \App\Models\kelas::all(['id_kelas', 'nama_kelas']);
        $jurusan = \App\Models\Jurusan::all(['id_jurusan', 'nama_jurusan']);
        return inertia('Admin/tambahxi', [
            'users' => $users,
            'kelas' => $kelas,
            'jurusan' => $jurusan,
        ]);
    }

    // Menyimpan Data Siswa Kelas XI RPL Yang Dikirim dari Form Tambah Siswa
    public function storexi(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id', // id harus ada di tabel users
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
        ]);

        // Simpan ke tabel siswa
        DB::table('siswa')->insert($validated);

        // Redirect balik ke halaman data siswa X RPL
        return redirect()->route('siswaxi')->with('success', 'Data Siswa X RPL berhasil ditambahkan!');
    }

    // Menampilkan Form Edit Siswa Kelas X RPL
    public function editxi($id)
    {
        $siswa = DB::table('siswa')->where('id_siswa', $id)->first(); // gunakan first()
        $users = \App\Models\User::where('role', 'user')->select('id', 'name', 'email')->get();
        $kelas = \App\Models\kelas::all(['id_kelas', 'nama_kelas']);
        $jurusan = \App\Models\Jurusan::all(['id_jurusan', 'nama_jurusan']);

        return inertia('Admin/editxi', [
            'siswa' => $siswa,
            'users' => $users,
            'kelas' => $kelas,
            'jurusan' => $jurusan,
        ]);
    }

    // Memperbarui Data Siswa X RPL Yang Dikirim dari Form Edit Siswa X RPL
    public function updatexi(Request $request, $id)
    {
        // Validasi data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
        ]);

        $update = DB::table('siswa')
            ->where('id_siswa', $id)
            ->update([
                'user_id' => $request->user_id,
                'nama' => $request->nama,
                'email' => $request->email,
                'id_kelas' => $request->id_kelas,
                'id_jurusan' => $request->id_jurusan,
            ]);

        if ($update) {
            return redirect()->route('siswaxi')->with('success', 'Data siswa X RPL berhasil diperbarui!');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui data!');
        }
    }

    // Menghapus Data Siswa Kelas X RPL
    public function destroyxi($id)
    {
        DB::table('siswa')->where('id_siswa', operator: $id)->delete();
        return redirect('/siswaxi');
    }
    // Siswa XI Section End

    // Siswa XII Section Start
    // Menampilkan Data Siswa Kelas X RPL
    public function siswaxii(Request $request)
    {
        // Ambil daftar kelas unik di tingkat X RPL
        $kelasList = DB::table('siswa')
            ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->where('kelas.tingkat_kelas', 'like', '3%')
            ->pluck('kelas.nama_kelas')
            ->unique()
            ->values();

        // Ambil filter kelas dari query string
        $selectedKelas = $request->input('kelas');

        // Query siswa
        $siswaxi = DB::table('siswa')
            ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->join('jurusan', 'siswa.id_jurusan', '=', 'jurusan.id_jurusan')
            ->select('siswa.id_siswa', 'siswa.nama', 'siswa.email', 'kelas.nama_kelas as kelas', 'jurusan.nama_jurusan as jurusan')
            ->where('kelas.tingkat_kelas', 'like', '3%')
            ->when($selectedKelas, function ($query, $kelas) {
                return $query->where('kelas.nama_kelas', $kelas);
            })
            ->paginate(5)
            ->appends($request->query());

        return Inertia::render('Admin/xrpl', [
            'siswa' => $siswaxi,
            'kelasList' => $kelasList,
            'selectedKelas' => $selectedKelas,
        ]);
    }

    // Menampilkan Form Tambah Siswa Kelas X RPL
    public function tambahxii()
    {
        $users = \App\Models\User::where('role', 'user')->select('id', 'name', 'email')->get();
        $kelas = \App\Models\kelas::all(['id_kelas', 'nama_kelas']);
        $jurusan = \App\Models\Jurusan::all(['id_jurusan', 'nama_jurusan']);
        return inertia('Admin/tambahxii', [
            'users' => $users,
            'kelas' => $kelas,
            'jurusan' => $jurusan,
        ]);
    }
    // Menampilkan Form Edit Siswa Kelas X RPL
    public function editxii($id)
    {
        $siswa = DB::table('siswa')->where('id_siswa', $id)->first(); // gunakan first()
        $users = \App\Models\User::where('role', 'user')->select('id', 'name', 'email')->get();
        $kelas = \App\Models\kelas::all(['id_kelas', 'nama_kelas']);
        $jurusan = \App\Models\Jurusan::all(['id_jurusan', 'nama_jurusan']);

        return inertia('Admin/editxii', [
            'siswa' => $siswa,
            'users' => $users,
            'kelas' => $kelas,
            'jurusan' => $jurusan,
        ]);
    }
    public function updatexii(Request $request, $id)
    {
        // Validasi data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
        ]);

        $update = DB::table('siswa')
            ->where('id_siswa', $id)
            ->update([
                'user_id' => $request->user_id,
                'nama' => $request->nama,
                'email' => $request->email,
                'id_kelas' => $request->id_kelas,
                'id_jurusan' => $request->id_jurusan,
            ]);

        if ($update) {
            return redirect()->route('siswaxii')->with('success', 'Data siswa X RPL berhasil diperbarui!');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui data!');
        }
    }

    public function destroyxii($id)
    {
        DB::table('siswa')->where('id_siswa', operator: $id)->delete();
        return redirect('/siswaxii');
    }
    // Siswa XII Section End

    // Aksi Cepat
    public function create()
    {
        $users = \App\Models\User::where('role', 'user')->select('id', 'name', 'email')->get();
        $kelas = \App\Models\kelas::all(['id_kelas', 'nama_kelas']);
        $jurusan = \App\Models\Jurusan::all(['id_jurusan', 'nama_jurusan']);
        return inertia('Admin/tambahsiswa', [
            'users' => $users,
            'kelas' => $kelas,
            'jurusan' => $jurusan,
        ]);
    }
    // Mapel section Start
    // Menampilkan Data Mapel
    public function indexm()
    {
        $mapel = DB::table('mapel')->select('id_mapel', 'nama_mapel')->paginate(5);
        return Inertia::render('Admin/mapel', ['mapel' => $mapel]);
    }

    // Menampilkan Form Tambah Mapel
    public function tambahm()
    {
        return inertia('Admin/tambahmapel', []);
    }

    // Menyimpan Data Mapel Yang Dikirim dari Form Tambah Mapel
    public function storem(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:255',
        ]);

        // Simpan ke tabel Mapel
        DB::table('mapel')->insert([
            'nama_mapel' => $validated['nama_mapel'],
        ]);

        // Redirect balik ke halaman data Mapel
        return redirect()->route('mapel')->with('success', 'Data user berhasil ditambahkan!');
    }

    // Menampilkan Form Edit Mapel
    public function editm($id)
    {
        $mapel = DB::table('mapel')->where('id_mapel', $id)->first();

        return inertia('Admin/editmapel', [
            'mapel' => $mapel,
        ]);
    }

    // Memperbarui Data Mapel Yang Dikirim dari Form Edit Mapel
    public function updatem(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:255',
        ]);

        $updated = DB::table('mapel')
            ->where('id_mapel', $id)
            ->update([
                'nama_mapel' => $request->nama_mapel,
            ]);

        if ($updated) {
            return redirect()->route('mapel')->with('success', 'Data user berhasil diperbarui!');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui data!');
        }
    }

    // Menghapus Data Mapel
    public function destroym($id)
    {
        DB::table('mapel')->where('id_mapel', operator: $id)->delete();
        return redirect('/mapel');
    }
    // Mapel Section End

    // Kelas section Start
    // Menampilkan Data Kelas
    public function indexk()
    {
        $kelas = DB::table('kelas')->join('jurusan', 'kelas.id_jurusan', '=', 'jurusan.id_jurusan')->join('guru', 'kelas.id_wali_kelas', '=', 'guru.id_guru')->select('kelas.id_kelas', 'kelas.tingkat_kelas', 'kelas.total_siswa', 'kelas.nama_kelas', 'jurusan.nama_jurusan as jurusan', 'guru.nama as guru')->paginate(5);
        return Inertia::render('Admin/kelas', ['kelas' => $kelas]);
    }

    // Menampilkan Form Tambah Kelas
    public function tambahk()
    {
        $kelas = \App\Models\kelas::all(['id_kelas', 'nama_kelas', 'tingkat_kelas', 'total_siswa']); // ambil user yang ada
        $guru = \App\Models\guru::all(['id_guru', 'nama']); // ambil user yang ada
        $jurusan = \App\Models\jurusan::all(['id_jurusan', 'nama_jurusan']); // ambil user yang ada
        return inertia('Admin/tambahkelas', [
            'kelas' => $kelas,
            'guru' => $guru,
            'jurusan' => $jurusan,
        ]);
    }

    // Menyimpan Data Kelas Yang Dikirim dari Form Tambah Kelas
    public function storek(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'tingkat_kelas' => 'required|string|max:255',
            'total_siswa' => 'required|string|max:255',
            'id_guru' => 'required|exists:guru,id_guru',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
        ]);

        // Simpan ke tabel Kelas
        DB::table('kelas')->insert([
            'nama_kelas' => $validated['nama_kelas'],
            'tingkat_kelas' => $validated['tingkat_kelas'],
            'total_siswa' => $validated['total_siswa'],
            'id_wali_kelas' => $validated['id_guru'],
            'id_jurusan' => $validated['id_jurusan'],
        ]);

        // Redirect balik ke halaman data Kelas
        return redirect()->route('kelas')->with('success', 'Data Kelas berhasil ditambahkan!');
    }

    // Menampilkan Form Edit Kelas
    public function editk($id)
    {
        $kelas = DB::table('kelas')->where('id_kelas', $id)->first(); // gunakan first()
        $guru = \App\Models\guru::all(['id_guru', 'nama']); // ambil user yang ada
        $jurusan = \App\Models\Jurusan::all(['id_jurusan', 'nama_jurusan']); // ambil user yang ada
        return inertia('Admin/editkelas', [
            'kelas' => $kelas,
            'guru' => $guru,
            'jurusan' => $jurusan,
        ]);
    }

    // Memperbarui Data Kelas Yang Dikirim dari Form Edit Kelas
    public function updatek(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'tingkat_kelas' => 'required|string|max:255',
            'total_siswa' => 'required|string|max:255',
            'id_guru' => 'required|exists:guru,id_guru',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
        ]);

        $updated = DB::table('kelas')
            ->where('id_kelas', $id)
            ->update([
                'nama_kelas' => $request->nama_kelas,
                'tingkat_kelas' => $request->tingkat_kelas,
                'total_siswa' => $request->total_siswa,
                'id_wali_kelas' => $request->id_guru,
                'id_jurusan' => $request->id_jurusan,
            ]);

        if ($updated) {
            return redirect()->route('kelas')->with('success', 'Data user berhasil diperbarui!');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui data!');
        }
    }

    // Menghapus Data Kelas
    public function destroyk($id)
    {
        DB::table('kelas')->where('id_kelas', operator: $id)->delete();
        return redirect('/kelas');
    }
    // Kelas Section End

    // Jurusan section Start
    // Menampilkan Data Jurusan
    public function indexj()
    {
        $jurusan = DB::table('jurusan')->select('id_jurusan', 'nama_jurusan')->paginate(5);
        return Inertia::render('Admin/jurusan', ['jurusan' => $jurusan]);
    }

    // Menampilkan Form Tambah Jurusan
    public function tambahj()
    {
        return inertia('Admin/tambahjurusan', []);
    }

    // Menyimpan Data Jurusan Yang Dikirim dari Form Tambah Jurusan
    public function storej(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'nama_jurusan' => 'required|string|max:255',
        ]);

        // Simpan ke tabel Jurusan
        DB::table('jurusan')->insert([
            'nama_jurusan' => $validated['nama_jurusan'],
        ]);

        // Redirect balik ke halaman data Jurusan
        return redirect()->route('jurusan')->with('success', 'Data user berhasil ditambahkan!');
    }

    // Menampilkan Form Edit Jurusan
    public function editj($id)
    {
        $jurusan = DB::table('jurusan')->where('id_jurusan', $id)->first();

        return inertia('Admin/editjurusan', [
            'jurusan' => $jurusan,
        ]);
    }

    // Memperbarui Data Jurusan Yang Dikirim dari Form Edit Jurusan
    public function updatej(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_jurusan' => 'required|string|max:255',
        ]);

        $updated = DB::table('jurusan')
            ->where('id_jurusan', $id)
            ->update([
                'nama_jurusan' => $request->nama_jurusan,
            ]);

        if ($updated) {
            return redirect()->route('jurusan')->with('success', 'Data user berhasil diperbarui!');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui data!');
        }
    }

    // Menghapus Data Jurusan
    public function destroyj($id)
    {
        DB::table('jurusan')->where('id_jurusan', operator: $id)->delete();
        return redirect('/jurusan');
    }
    // Jurusan Section End
}

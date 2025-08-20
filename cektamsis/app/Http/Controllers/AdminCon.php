<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminCon extends Controller
{
    public function dashboard(): Response
    {
        return Inertia::render('Dashboard', [
        'totalUsers' => User::count(),
        'totalSiswa' => User::where('role', 'user')->count(),
        'totalGuru'  => User::where('role', 'guru')->count(),
        'totalAdmin' => User::where('role', 'admin')->count(),
        ]);
    }


    // Users section Start
    // Menampilkan Data User
    public function indexs()
    {
        $user = DB::table('users')->select('id', 'name', 'email', 'role',)->get();
        return Inertia::render('manageuser', ['users' => $user]);
    }

    // Menampilkan Form Tambah Guru
    public function tambahs()
    {
        return inertia('tambahuser', [
        ]);
    }

    // Menyimpan Data Guru Yang Dikirim dari Form Tambah Guru
   public function stores(Request $request)
{
    // Validasi
    $validated = $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'role'     => 'required|string|in:admin,guru,user',
        'password' => 'required|string|min:6',
    ]);

    // Simpan ke tabel users
    DB::table('users')->insert([
        'name'       => $validated['name'],
        'email'      => $validated['email'],
        'role'       => $validated['role'],
        'password'   => Hash::make($validated['password']),
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

    return inertia('edituser', [
        'user' => $user,  // single user untuk diedit
        // 'users' => $users,  // hapus atau ganti nama jika diperlukan
    ]);
}

    // Memperbarui Data Guru Yang Dikirim dari Form Edit Guru
public function updates(Request $request, $id)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,',
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

    // Menghapus Data users
    public function destroys($id)
    {
        DB::table('users')->where('id', operator: $id)->delete();
        return redirect('/user');
    }
    // Users Section End


    // Admin Section Start
    // Menampilkan Data Guru
    public function index()
    {
        $guru = DB::table('guru')->join('kelas', 'guru.id_kelas', '=', 'kelas.id_kelas')->join('mapel', 'guru.id_mapel', '=', 'mapel.id_mapel')->select('guru.id_guru', 'guru.nama', 'guru.email', 'kelas.nama_kelas as kelas', 'mapel.nama_mapel as mapel')->get();
        return Inertia::render('guru', ['guru' => $guru]);
    }

    // Menampilkan Form Tambah Guru
    public function tambah()
    {
        $users = \App\Models\User::where('role', 'guru')->select('id', 'name', 'email')->get();
        $kelas = \App\Models\kelas::all(['id_kelas', 'nama_kelas']); // ambil user yang ada
        $mapel = \App\Models\Mapel::all(['id_mapel', 'nama_mapel']); // ambil user yang ada
        return inertia('tambahguru', [
            'users' => $users,
            'kelas' => $kelas,
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
            'email' => 'required|email',
            'id_kelas' => 'required|exists:kelas,id_kelas',
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
        $users = \App\Models\User::all(['id', 'name', 'email']);
        $kelas = \App\Models\kelas::all(['id_kelas', 'nama_kelas']);
        $mapel = \App\Models\Mapel::all(['id_mapel', 'nama_mapel']);

        return inertia('editguru', [
            'guru' => $guru,
            'users' => $users,
            'kelas' => $kelas,
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
            'email' => 'required|email',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_mapel' => 'required|exists:mapel,id_mapel',
        ]);

        $updated = DB::table('guru')
            ->where('id_guru', $id)
            ->update([
                'user_id' => $request->user_id,
                'nama' => $request->nama,
                'email' => $request->email,
                'id_kelas' => $request->id_kelas,
                'id_mapel' => $request->id_mapel,
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
    // Admin Section End

    // Siswa X Section Start
    // Menampilkan Data Siswa Kelas X RPL
    public function siswax()
    {
        $siswax = DB::table('siswa')
            ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->join('jurusan', 'siswa.id_jurusan', '=', 'jurusan.id_jurusan')
            ->select('siswa.id_siswa', 'siswa.nama', 'siswa.email', 'kelas.nama_kelas as kelas', 'jurusan.nama_jurusan as jurusan')
            ->where('kelas.nama_kelas', '=', 'X RPL') // Filter hanya kelas X RPL
            ->get();

        return Inertia::render('xrpl', ['siswa' => $siswax]);
    }

    // Menampilkan Form Tambah Siswa Kelas X RPL
    public function tambahx()
    {
        $users = \App\Models\User::where('role', 'user')->select('id', 'name', 'email')->get();
        $kelas = \App\Models\kelas::all(['id_kelas', 'nama_kelas']);
        $jurusan = \App\Models\Jurusan::all(['id_jurusan', 'nama_jurusan']);
        return inertia('tambahx', [
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

        return inertia('editx', [
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

}

<?php

namespace App\Http\Controllers;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Jurusan;
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
            'totalGuru' => User::where('role', 'guru')->count(),
            'totalAdmin' => User::where('role', 'admin')->count(),
        ]);
    }

    // Users section Start
    // Menampilkan Data User
    public function indexs()
    {
        $user = DB::table('users')->select('id', 'name', 'email', 'role')->get();
        return Inertia::render('manageuser', ['users' => $user]);
    }

    // Menampilkan Form Tambah Guru
    public function tambahs()
    {
        return inertia('tambahuser', []);
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

        return inertia('edituser', [
            'user' => $user, // single user untuk diedit
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
    // Siswa X Section End

     // Siswa XI Section Start
    // Menampilkan Data Siswa Kelas X RPL
    public function siswaxi()
    {
        $siswa = DB::table('siswa')
            ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->join('jurusan', 'siswa.id_jurusan', '=', 'jurusan.id_jurusan')
            ->select('siswa.id_siswa', 'siswa.nama', 'siswa.email', 'kelas.nama_kelas as kelas', 'jurusan.nama_jurusan as jurusan')
            ->where('kelas.nama_kelas', '=', 'XI RPL') // Filter hanya kelas X RPL
            ->get();

        return Inertia::render('xirpl', ['siswa' => $siswa]);
    }

    // Menampilkan Form Tambah Siswa Kelas X RPL
    public function tambahxi()
    {
        $users = \App\Models\User::where('role', 'user')->select('id', 'name', 'email')->get();
        $kelas = \App\Models\kelas::all(['id_kelas', 'nama_kelas']);
        $jurusan = \App\Models\Jurusan::all(['id_jurusan', 'nama_jurusan']);
        return inertia('tambahxi', [
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

        return inertia('editxi', [
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
    public function siswaxii()
    {
        $siswa = DB::table('siswa')
            ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->join('jurusan', 'siswa.id_jurusan', '=', 'jurusan.id_jurusan')
            ->select('siswa.id_siswa', 'siswa.nama', 'siswa.email', 'kelas.nama_kelas as kelas', 'jurusan.nama_jurusan as jurusan')
            ->where('kelas.nama_kelas', '=', 'XII RPL') // Filter hanya kelas X RPL
            ->get();

        return Inertia::render('xiirpl', ['siswa' => $siswa]);
    }

    // Menampilkan Form Tambah Siswa Kelas X RPL
    public function tambahxii()
    {
        $users = \App\Models\User::where('role', 'user')->select('id', 'name', 'email')->get();
        $kelas = \App\Models\kelas::all(['id_kelas', 'nama_kelas']);
        $jurusan = \App\Models\Jurusan::all(['id_jurusan', 'nama_jurusan']);
        return inertia('tambahxii', [
            'users' => $users,
            'kelas' => $kelas,
            'jurusan' => $jurusan,
        ]);
    }
    // Menyimpan Data Siswa Kelas XI RPL Yang Dikirim dari Form Tambah Siswa
    public function storexii(Request $request)
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
        return redirect()->route('siswaxii')->with('success', 'Data Siswa X RPL berhasil ditambahkan!');
    }

    // Menampilkan Form Edit Siswa Kelas X RPL
    public function editxii($id)
    {
        $siswa = DB::table('siswa')->where('id_siswa', $id)->first(); // gunakan first()
        $users = \App\Models\User::where('role', 'user')->select('id', 'name', 'email')->get();
        $kelas = \App\Models\kelas::all(['id_kelas', 'nama_kelas']);
        $jurusan = \App\Models\Jurusan::all(['id_jurusan', 'nama_jurusan']);

        return inertia('editxii', [
            'siswa' => $siswa,
            'users' => $users,
            'kelas' => $kelas,
            'jurusan' => $jurusan,
        ]);
    }

    // Memperbarui Data Siswa X RPL Yang Dikirim dari Form Edit Siswa X RPL
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
            return redirect()->route('siswaxi')->with('success', 'Data siswa X RPL berhasil diperbarui!');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui data!');
        }
    }

    // Menghapus Data Siswa Kelas X RPL
    public function destroyxii($id)
    {
        DB::table('siswa')->where('id_siswa', operator: $id)->delete();
        return redirect('/siswaxii');
    }
    // Siswa XII Section End

    // Mapel section Start
    // Menampilkan Data Mapel
    public function indexm()
    {
        $mapel = DB::table('mapel')->select('id_mapel', 'nama_mapel')->get();
        return Inertia::render('mapel', ['mapel' => $mapel]);
    }

    // Menampilkan Form Tambah Mapel
    public function tambahm()
    {
        return inertia('tambahmapel', []);
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

        return inertia('editmapel', [
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
        $kelas = DB::table('kelas')->join('jurusan', 'kelas.id_jurusan', '=', 'jurusan.id_jurusan')->select('kelas.id_kelas', 'kelas.nama_kelas', 'jurusan.nama_jurusan as jurusan')->get();
        return Inertia::render('kelas', ['kelas' => $kelas]);
    }

    // Menampilkan Form Tambah Kelas
    public function tambahk()
    {
        $kelas = \App\Models\kelas::all(['id_kelas', 'nama_kelas']); // ambil user yang ada
        $jurusan = \App\Models\Jurusan::all(['id_jurusan', 'nama_jurusan']); // ambil user yang ada
        return inertia('tambahkelas', [
            'kelas' => $kelas,
            'jurusan' => $jurusan,
        ]);
    }

    // Menyimpan Data Kelas Yang Dikirim dari Form Tambah Kelas
    public function storek(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
        ]);

        // Simpan ke tabel Kelas
        DB::table('kelas')->insert([
            'nama_kelas' => $validated['nama_kelas'],
            'id_jurusan' => $validated['id_jurusan'],
        ]);

        // Redirect balik ke halaman data Kelas
        return redirect()->route('kelas')->with('success', 'Data Kelas berhasil ditambahkan!');
    }

    // Menampilkan Form Edit Kelas
    public function editk($id)
    {
        $kelas = DB::table('kelas')->where('id_kelas', $id)->first(); // gunakan first()
        $jurusan = \App\Models\Jurusan::all(['id_jurusan', 'nama_jurusan']); // ambil user yang ada
        return inertia('editkelas', [
            'kelas' => $kelas,
            'jurusan' => $jurusan,
        ]);
    }

    // Memperbarui Data Kelas Yang Dikirim dari Form Edit Kelas
    public function updatek(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
        ]);

        $updated = DB::table('kelas')
            ->where('id_kelas', $id)
            ->update([
                'nama_kelas' => $request->nama_kelas,
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
        $jurusan = DB::table('jurusan')->select('id_jurusan', 'nama_jurusan')->get();
        return Inertia::render('jurusan', ['jurusan' => $jurusan]);
    }

    // Menampilkan Form Tambah Jurusan
    public function tambahj()
    {
        return inertia('tambahjurusan', []);
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

        return inertia('editjurusan', [
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

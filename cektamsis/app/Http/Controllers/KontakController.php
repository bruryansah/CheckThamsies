<?php

namespace App\Http\Controllers;

use App\Models\Kontak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class KontakController extends Controller
{
    /**
     * Display the contact page
     */
    public function index()
    {
        return Inertia::render('Kontak');
    }

    /**
     * Store a new contact message
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'pesan' => 'required|string|max:2000',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'subject.required' => 'Subjek wajib diisi.',
            'subject.string' => 'Subjek harus berupa teks.',
            'subject.max' => 'Subjek maksimal 255 karakter.',
            'pesan.required' => 'Pesan wajib diisi.',
            'pesan.string' => 'Pesan harus berupa teks.',
            'pesan.max' => 'Pesan maksimal 2000 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Terdapat kesalahan dalam pengisian form.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create new contact record
            Kontak::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'subject' => $request->subject,
                'pesan' => $request->pesan,
            ]);

            return back()->with('success', 'Pesan berhasil dikirim!');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Display all contact messages (for admin)
     */
    public function admin()
    {
        $kontaks = Kontak::orderBy('created_at', 'desc')->paginate(10);
        
        return Inertia::render('Admin/Kontak', [
            'kontaks' => $kontaks
        ]);
    }

    /**
     * Mark contact as read (for admin)
     */
    public function markAsRead(Kontak $kontak)
    {
        $kontak->update(['is_read' => true]);
        
        return response()->json([
            'success' => true,
            'message' => 'Pesan telah ditandai sebagai dibaca.'
        ]);
    }

    /**
     * Delete contact message (for admin)
     */
    public function destroy(Kontak $kontak)
    {
        $kontak->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dihapus.'
        ]);
    }
}
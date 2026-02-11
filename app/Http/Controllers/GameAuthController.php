<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class GameAuthController extends Controller
{
    protected $database;

    public function __construct()
    {
        // Setup koneksi Firebase
        $factory = (new Factory)
            ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $this->database = $factory->createDatabase();
    }

    // API Login untuk Construct 2
    public function loginApi(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required', // Nomor Absen
            'password' => 'required',
        ]);

        $no_absen = $request->username;
        $password = $request->password;

        // Cek ke Firebase: users/{no_absen}
        $reference = $this->database->getReference('users/' . $no_absen);
        $snapshot = $reference->getSnapshot();

        if ($snapshot->exists()) {
            $userData = $snapshot->getValue();

            // Cek Password
            if (isset($userData['password']) && $userData['password'] == $password) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login Berhasil',
                    'data' => [
                        'nama' => $userData['nama'] ?? 'Siswa',
                        'no_absen' => $no_absen
                    ]
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Password Salah!'
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Nomor Absen tidak ditemukan!'
            ]);
        }
    }
}

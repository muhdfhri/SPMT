<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // Add this import

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'mahasiswa']);
    }

    /**
     * Show the profile form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->studentProfile ?? new StudentProfile();
        
        return view('mahasiswa.profile.edit', compact('user', 'profile'));
    }

    /**
     * Update the profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'nim' => 'required|string|max:20',
            'jurusan' => 'required|string|max:100',
            'universitas' => 'required|string|max:100',
            'cv' => 'nullable|mimes:pdf|max:2048',
            'transcript' => 'nullable|mimes:pdf|max:2048',
        ]);
        
        $profile = $user->studentProfile ?? new StudentProfile(['user_id' => $user->id]);
        
        $profile->nim = $request->nim;
        $profile->jurusan = $request->jurusan;
        $profile->universitas = $request->universitas;
        
        if ($request->hasFile('cv')) {
            if ($profile->cv_path) {
                Storage::delete($profile->cv_path);
            }
            $profile->cv_path = $request->file('cv')->store('cv');
        }
        
        if ($request->hasFile('transcript')) {
            if ($profile->transcript_path) {
                Storage::delete($profile->transcript_path);
            }
            $profile->transcript_path = $request->file('transcript')->store('transcripts');
        }
        
        $profile->save();
        
        return redirect()->route('mahasiswa.profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
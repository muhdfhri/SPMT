<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
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
     * Show the list of certificates.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Use direct query instead of relationship method
        $certificates = Certificate::where('user_id', Auth::id())
            ->with('internship')
            ->get();
        
        return view('mahasiswa.certificates.index', compact('certificates'));
    }

    /**
     * Download a certificate.
     *
     * @param  \App\Models\Certificate  $certificate
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Certificate $certificate)
    {
        // Check if the certificate belongs to the authenticated user
        if ($certificate->user_id !== Auth::id()) {
            abort(403);
        }
        
        return response()->download(storage_path('app/' . $certificate->certificate_path));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use App\Models\StudentEducation;
use App\Models\StudentExperience;
use App\Models\StudentAward;
use App\Models\StudentSkill;
use App\Models\StudentFamilyMember;
use App\Models\StudentDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class StudentProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = StudentProfile::with([
            'educations', 
            'experiences', 
            'awards', 
            'skills', 
            'familyMembers', 
            'documents'
        ])->firstOrCreate(['user_id' => $user->id], [
            'full_name' => $user->name,
        ]);
        
        // Calculate profile completion percentage and booleans
        $completion = $profile->calculateCompletionPercentage();

        // Add: Paginated reports for the report tab (5 per page)
        $reports = $user->reports()->with('attachments')->latest()->paginate(5);
        
        return view('mahasiswa.profile.index', array_merge(
            compact('profile', 'user', 'reports'),
            $completion
        ));
    }
    
    public function updatePersonalInfo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|string|max:255',
                'nik' => 'required|string|size:16',
                'birth_place' => 'required|string|max:255',
                'birth_date' => 'required|date',
                'address' => 'required|string',
                'about_me' => 'nullable|string',
                'phone' => 'required|string|max:15',
            ], [
                'full_name.required' => 'Nama lengkap harus diisi',
                'nik.required' => 'NIK harus diisi',
                'nik.size' => 'NIK harus 16 digit',
                'birth_place.required' => 'Tempat lahir harus diisi',
                'birth_date.required' => 'Tanggal lahir harus diisi',
                'address.required' => 'Alamat harus diisi',
                'phone.required' => 'Nomor HP harus diisi',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Mulai transaksi database
            DB::beginTransaction();
            
            // Update profil
            $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
            $profile->update($validator->validated());
            
            // Update nomor telepon user jika ada
            if ($request->has('phone')) {
                Auth::user()->update(['phone' => $request->phone]);
            }
            
            // Hitung ulang persentase kelengkapan
            $completion = $profile->calculateCompletionPercentage();
            
            // Commit transaksi
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Informasi pribadi berhasil diperbarui',
                'completion_percentage' => $completion['percentage'],
                'is_personal_complete' => $completion['is_personal_complete']
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating profile: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function storeEducation(Request $request)
    {
        \Log::info('Store Education Request', $request->all());

        $data = $request->json()->all();
        if (!isset($data['is_current'])) {
            $data['is_current'] = false;
        }

        $validator = Validator::make($data, [
            'institution_name' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'field_of_study' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|required_unless:is_current,true|after_or_equal:start_date',
            'is_current' => 'boolean',
            'gpa' => 'nullable|numeric|min:0|max:4',
            'description' => 'nullable|string'
        ], [
            'end_date.required_unless' => 'Tanggal selesai harus diisi jika bukan pendidikan saat ini',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai'
        ]);

        if ($validator->fails()) {
            \Log::error('Store Education Validation Failed', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $user = Auth::user();
            $profile = StudentProfile::where('user_id', $user->id)->firstOrFail();

            $validatedData = $validator->validated();
            $education = new StudentEducation();
            $education->student_profile_id = $profile->id;
            $education->institution_name = $validatedData['institution_name'];
            $education->degree = $validatedData['degree'];
            $education->field_of_study = $validatedData['field_of_study'];
            $education->start_date = $validatedData['start_date'];
            $education->end_date = $validatedData['end_date'] ?? null;
            $education->is_current = $validatedData['is_current'];
            $education->gpa = $validatedData['gpa'] ?? null;
            $education->description = $validatedData['description'] ?? null;
            $education->save();

            $profile->calculateCompletionPercentage();

            return response()->json([
                'success' => true,
                'message' => 'Pendidikan berhasil ditambahkan',
                'education' => $education,
                'completion_percentage' => $profile->profile_completion_percentage,
                'is_academic_complete' => $profile->is_academic_complete
            ]);
        } catch (\Exception $e) {
            \Log::error('Error storing education: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan data pendidikan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function updateEducation(Request $request, $id)
    {
        // Debug log request
        \Log::info('Update Education Request', $request->all());

        $data = $request->all();
        if (!isset($data['is_current'])) {
            $data['is_current'] = 0;
        }

        $validator = Validator::make($data, [
            'institution_name' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'field_of_study' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|required_unless:is_current,1|after_or_equal:start_date',
            'is_current' => 'boolean',
            'gpa' => 'nullable|numeric|min:0|max:4',
            'description' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $education = $profile->educations()->findOrFail($id);
        $education->update($request->all());
        $profile->calculateCompletionPercentage();
        
        return response()->json([
            'success' => true,
            'message' => 'Pendidikan berhasil diperbarui',
            'education' => $education,
            'completion_percentage' => $profile->profile_completion_percentage,
            'is_academic_complete' => $profile->is_academic_complete
        ]);
    }
    
    public function deleteEducation($id)
    {
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $education = $profile->educations()->findOrFail($id);
        $education->delete();
        $profile->calculateCompletionPercentage();
        
        return response()->json([
            'success' => true,
            'message' => 'Pendidikan berhasil dihapus',
            'completion_percentage' => $profile->profile_completion_percentage,
            'is_academic_complete' => $profile->is_academic_complete
        ]);
    }
    
    public function storeExperience(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_current' => 'boolean',
            'description' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $experience = $profile->experiences()->create($request->all());
        $profile->calculateCompletionPercentage();
        
        return response()->json([
            'success' => true,
            'message' => 'Pengalaman berhasil ditambahkan',
            'experience' => $experience,
            'completion_percentage' => $profile->profile_completion_percentage,
            'is_academic_complete' => $profile->is_academic_complete
        ]);
    }
    
    public function updateExperience(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_current' => 'boolean',
            'description' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $experience = $profile->experiences()->findOrFail($id);
        $experience->update($request->all());
        $profile->calculateCompletionPercentage();
        
        return response()->json([
            'success' => true,
            'message' => 'Pengalaman berhasil diperbarui',
            'experience' => $experience,
            'completion_percentage' => $profile->profile_completion_percentage,
            'is_academic_complete' => $profile->is_academic_complete
        ]);
    }
    
    public function deleteExperience($id)
    {
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $experience = $profile->experiences()->findOrFail($id);
        $experience->delete();
        $profile->calculateCompletionPercentage();
        
        return response()->json([
            'success' => true,
            'message' => 'Pengalaman berhasil dihapus',
            'completion_percentage' => $profile->profile_completion_percentage,
            'is_academic_complete' => $profile->is_academic_complete
        ]);
    }
    
    
    public function storeAward(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'date_received' => 'required|date',
            'description' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $award = $profile->awards()->create($request->all());
        $profile->calculateCompletionPercentage();
        
        return response()->json([
            'success' => true,
            'message' => 'Penghargaan berhasil ditambahkan',
            'award' => $award,
            'completion_percentage' => $profile->profile_completion_percentage,
            'is_academic_complete' => $profile->is_academic_complete
        ]);
    }
    
    public function updateAward(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'date_received' => 'required|date',
            'description' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $award = $profile->awards()->findOrFail($id);
        $award->update($request->all());
        $profile->calculateCompletionPercentage();
        
        return response()->json([
            'success' => true,
            'message' => 'Penghargaan berhasil diperbarui',
            'award' => $award,
            'completion_percentage' => $profile->profile_completion_percentage,
            'is_academic_complete' => $profile->is_academic_complete
        ]);
    }
    
    public function deleteAward($id)
    {
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $award = $profile->awards()->findOrFail($id);
        $award->delete();
        $profile->calculateCompletionPercentage();
        
        return response()->json([
            'success' => true,
            'message' => 'Penghargaan berhasil dihapus',
            'completion_percentage' => $profile->profile_completion_percentage,
            'is_academic_complete' => $profile->is_academic_complete
        ]);
    }
    
    public function storeSkill(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'proficiency_level' => 'required|in:beginner,intermediate,advanced,expert',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $skill = $profile->skills()->create($request->all());
        $profile->calculateCompletionPercentage();
        
        return response()->json([
            'success' => true,
            'message' => 'Keahlian berhasil ditambahkan',
            'skill' => $skill,
            'completion_percentage' => $profile->profile_completion_percentage,
            'is_academic_complete' => $profile->is_academic_complete
        ]);
    }
    
    public function updateSkill(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'proficiency_level' => 'required|in:beginner,intermediate,advanced,expert',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $skill = $profile->skills()->findOrFail($id);
        $skill->update($request->all());
        $profile->calculateCompletionPercentage();
        
        return response()->json([
            'success' => true,
            'message' => 'Keahlian berhasil diperbarui',
            'skill' => $skill,
            'completion_percentage' => $profile->profile_completion_percentage,
            'is_academic_complete' => $profile->is_academic_complete
        ]);
    }
    
    public function deleteSkill($id)
    {
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $skill = $profile->skills()->findOrFail($id);
        $skill->delete();
        $profile->calculateCompletionPercentage();
        
        return response()->json([
            'success' => true,
            'message' => 'Keahlian berhasil dihapus',
            'completion_percentage' => $profile->profile_completion_percentage,
            'is_academic_complete' => $profile->is_academic_complete
        ]);
    }
    
    public function storeFamilyMember(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'relationship' => 'required|in:father,mother,sibling,spouse,child,other',
            'name' => 'required|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_emergency_contact' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $familyMember = $profile->familyMembers()->create($request->all());
        $profile->calculateCompletionPercentage();
        
        return response()->json([
            'success' => true,
            'message' => 'Anggota keluarga berhasil ditambahkan',
            'family_member' => $familyMember,
            'completion_percentage' => $profile->profile_completion_percentage,
            'is_family_complete' => $profile->is_family_complete
        ]);
    }
    
    public function updateFamilyMember(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'relationship' => 'required|in:father,mother,sibling,spouse,child,other',
            'name' => 'required|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_emergency_contact' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $familyMember = $profile->familyMembers()->findOrFail($id);
        $familyMember->update($request->all());
        $profile->calculateCompletionPercentage();
        
        return response()->json([
            'success' => true,
            'message' => 'Anggota keluarga berhasil diperbarui',
            'family_member' => $familyMember,
            'completion_percentage' => $profile->profile_completion_percentage,
            'is_family_complete' => $profile->is_family_complete
        ]);
    }
    
    public function deleteFamilyMember($id)
    {
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $familyMember = $profile->familyMembers()->findOrFail($id);
        $familyMember->delete();
        $profile->calculateCompletionPercentage();
        
        return response()->json([
            'success' => true,
            'message' => 'Anggota keluarga berhasil dihapus',
            'completion_percentage' => $profile->profile_completion_percentage,
            'is_family_complete' => $profile->is_family_complete
        ]);
    }
    
    public function storeDocument(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:cv,transcript,id_card,certificate,recommendation_letter,other',
            'file' => 'required|file|max:10240', // Max 10MB
            'description' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        
        // Check if document of this type already exists
        $existingDocument = $profile->documents()->where('type', $request->type)->first();
        
        // If document exists, delete the old file
        if ($existingDocument) {
            if (Storage::disk('public')->exists($existingDocument->file_path)) {
                Storage::disk('public')->delete($existingDocument->file_path);
            }
            $existingDocument->delete();
        }
        
        $file = $request->file('file');
        $originalFilename = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();
        
        // Generate a unique filename
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        // Store the file
        $path = $file->storeAs('student_documents/' . Auth::id(), $filename, 'public');
        
        // Create document record
        $document = $profile->documents()->create([
            'type' => $request->type,
            'file_path' => $path,
            'original_filename' => $originalFilename,
            'file_size' => $fileSize,
            'mime_type' => $mimeType,
            'description' => $request->description,
        ]);
        
        $profile->calculateCompletionPercentage();
        
        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil diunggah',
            'document' => $document,
            'completion_percentage' => $profile->profile_completion_percentage,
            'is_documents_complete' => $profile->is_documents_complete
        ]);
    }
    
    public function updateDocument(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:cv,transcript,id_card,certificate,recommendation_letter,other',
            'file' => 'nullable|file|max:10240', // Max 10MB
            'description' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $document = $profile->documents()->findOrFail($id);
        
        $updateData = [
            'type' => $request->type,
            'description' => $request->description,
        ];
        
        // If a new file is uploaded
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalFilename = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();
            
            // Generate a unique filename
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Delete the old file if it exists
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
            
            // Store the new file
            $path = $file->storeAs('student_documents/' . Auth::id(), $filename, 'public');
            
            // Update document data
            $updateData['file_path'] = $path;
            $updateData['original_filename'] = $originalFilename;
            $updateData['file_size'] = $fileSize;
            $updateData['mime_type'] = $mimeType;
        }
        
        $document->update($updateData);
        $profile->calculateCompletionPercentage();
        
        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil diperbarui',
            'document' => $document,
            'completion_percentage' => $profile->profile_completion_percentage,
            'is_documents_complete' => $profile->is_documents_complete
        ]);
    }
    
    public function deleteDocument($id)
    {
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $document = $profile->documents()->findOrFail($id);
        
        // Delete the file if it exists
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }
        
        $document->delete();
        $profile->calculateCompletionPercentage();
        
        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil dihapus',
            'completion_percentage' => $profile->profile_completion_percentage,
            'is_documents_complete' => $profile->is_documents_complete
        ]);
    }
    
    public function downloadDocument($id)
    {
        $document = StudentProfile::where('user_id', Auth::id())->firstOrFail()->documents()->findOrFail($id);
        
        // Check if user owns this document
        if ($document->student_profile_id !== Auth::user()->studentProfile->id) {
            abort(403);
        }
        
        return Storage::disk('public')->download(
            $document->file_path,
            $document->original_filename
        );
    }
    
    /**
     * Tampilkan pratinjau dokumen di browser
     */
    public function viewDocument($id)
    {
        $document = StudentProfile::where('user_id', Auth::id())->firstOrFail()->documents()->findOrFail($id);
        
        // Check if user owns this document
        if ($document->student_profile_id !== Auth::user()->studentProfile->id) {
            abort(403);
        }
        
        $path = storage_path('app/public/' . $document->file_path);
        $mime = mime_content_type($path);
        
        return response()->file($path, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $document->original_filename . '"'
        ]);
    }
    
    public function updateFamilyInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'father_name' => 'required|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'father_address' => 'nullable|string',
            'mother_name' => 'required|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',
            'mother_address' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        
        // Update or create father
        $father = $profile->familyMembers()->updateOrCreate(
            ['relationship' => 'father'],
            [
                'name' => $request->father_name,
                'occupation' => $request->father_occupation,
                'phone_number' => $request->father_phone,
                'address' => $request->father_address,
                'is_emergency_contact' => true
            ]
        );
        
        // Update or create mother
        $mother = $profile->familyMembers()->updateOrCreate(
            ['relationship' => 'mother'],
            [
                'name' => $request->mother_name,
                'occupation' => $request->mother_occupation,
                'phone_number' => $request->mother_phone,
                'address' => $request->mother_address,
                'is_emergency_contact' => true
            ]
        );
        
        $profile->calculateCompletionPercentage();
        
        return response()->json([
            'success' => true,
            'message' => 'Informasi keluarga berhasil diperbarui',
            'completion_percentage' => $profile->profile_completion_percentage,
            'is_family_complete' => $profile->is_family_complete
        ]);
    }
    
    public function updateGuardianInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'has_guardian' => 'nullable|boolean',
            'guardian_name' => 'nullable|required_if:has_guardian,1|string|max:255',
            'guardian_nik' => 'nullable|string|max:16',
            'guardian_relationship' => 'nullable|required_if:has_guardian,1|string|max:255',
            'guardian_occupation' => 'nullable|string|max:255',
            'guardian_address' => 'nullable|string',
            'guardian_phone' => 'nullable|required_if:has_guardian,1|string|max:20',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        
        // Set has_guardian to false if not provided
        $data = $validator->validated();
        if (!isset($data['has_guardian'])) {
            $data['has_guardian'] = false;
        }
        
        // If has_guardian is false, clear all guardian fields
        if ($data['has_guardian'] === false) {
            $data['guardian_name'] = null;
            $data['guardian_nik'] = null;
            $data['guardian_relationship'] = null;
            $data['guardian_occupation'] = null;
            $data['guardian_address'] = null;
            $data['guardian_phone'] = null;
        }
        
        $profile->update($data);
        $profile->calculateCompletionPercentage();
        
        return response()->json([
            'success' => true,
            'message' => 'Informasi wali berhasil diperbarui',
            'completion_percentage' => $profile->profile_completion_percentage,
            'is_family_complete' => $profile->is_family_complete
        ]);
    }
    
    public function storeSibling(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'age' => 'required|integer|min:0|max:100',
            'education' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        
        // Create sibling as a family member with relationship 'sibling'
        $sibling = $profile->familyMembers()->create([
            'relationship' => 'sibling',
            'name' => $request->name,
            'gender' => $request->gender,
            'age' => $request->age,
            'education' => $request->education,
            'occupation' => $request->occupation,
        ]);
        
        $profile->calculateCompletionPercentage();
        
        return response()->json([
            'success' => true,
            'message' => 'Saudara kandung berhasil ditambahkan',
            'sibling' => $sibling,
            'completion_percentage' => $profile->profile_completion_percentage,
            'is_family_complete' => $profile->is_family_complete
        ]);
    }
    
    public function updateSibling(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'age' => 'required|integer|min:0|max:100',
            'education' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $sibling = $profile->familyMembers()->where('relationship', 'sibling')->findOrFail($id);
        
        $sibling->update([
            'name' => $request->name,
            'gender' => $request->gender,
            'age' => $request->age,
            'education' => $request->education,
            'occupation' => $request->occupation,
        ]);
        
        $profile->calculateCompletionPercentage();
        
        return response()->json([
            'success' => true,
            'message' => 'Saudara kandung berhasil diperbarui',
            'sibling' => $sibling,
            'completion_percentage' => $profile->profile_completion_percentage,
            'is_family_complete' => $profile->is_family_complete
        ]);
    }
    
    public function deleteSibling($id)
    {
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $sibling = $profile->familyMembers()->where('relationship', 'sibling')->findOrFail($id);
        
        $sibling->delete();
        $profile->calculateCompletionPercentage();
        
        return response()->json([
            'success' => true,
            'message' => 'Saudara kandung berhasil dihapus',
            'completion_percentage' => $profile->profile_completion_percentage,
            'is_family_complete' => $profile->is_family_complete
        ]);
    }
    
    public function getProfileCompletionStatus()
    {
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $profile->calculateCompletionPercentage();
        
        return response()->json([
            'success' => true,
            'completion_percentage' => $profile->profile_completion_percentage,
            'is_personal_complete' => $profile->is_personal_complete,
            'is_academic_complete' => $profile->is_academic_complete,
            'is_family_complete' => $profile->is_family_complete,
            'is_documents_complete' => $profile->is_documents_complete,
            'is_profile_complete' => $profile->isProfileComplete()
        ]);
    }

    public function updateDocuments(Request $request)
    {
        $profile = Auth::user()->studentProfile;
        
        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found'
            ], 404);
        }

        $request->validate([
            'documents.*.file' => 'required|file|max:2048',
            'documents.*.type' => 'required|string',
            'documents.*.description' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->documents as $type => $document) {
                if ($request->hasFile("documents.{$type}.file")) {
                    $file = $request->file("documents.{$type}.file");
                    $originalFilename = $file->getClientOriginalName();
                    $filename = time() . '_' . $originalFilename;
                    
                    // Store the file
                    $path = $file->storeAs('documents/' . $profile->id, $filename, 'public');
                    
                    // Delete old document if exists
                    $oldDocument = $profile->documents()->where('type', $type)->first();
                    if ($oldDocument) {
                        Storage::disk('public')->delete($oldDocument->file_path);
                        $oldDocument->delete();
                    }
                    
                    // Create new document record
                    $profile->documents()->create([
                        'type' => $type,
                        'description' => $document['description'],
                        'file_path' => $path,
                        'original_filename' => $originalFilename,
                        'mime_type' => $file->getMimeType(),
                        'file_size' => $file->getSize()
                    ]);
                }
            }

            // Update profile completion percentage
            $profile->calculateCompletionPercentage();
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Documents updated successfully',
                'profile' => $profile->fresh(['documents'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update documents: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadProfilePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $profile = Auth::user()->studentProfile;
        if (!$profile) {
            return response()->json(['success' => false, 'message' => 'Profil tidak ditemukan'], 404);
        }

        $file = $request->file('photo');
        $filename = 'profile_' . $profile->id . '_' . md5(time() . $file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('profile_photos', $filename, 'public');

        // Delete old photo if exists
        if ($profile->profile_photo && \Storage::disk('public')->exists($profile->profile_photo)) {
            \Storage::disk('public')->delete($profile->profile_photo);
        }

        $profile->profile_photo = $path;
        $profile->save();

        $photoUrl = asset('storage/' . $path);
        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diunggah',
            'photo_url' => $photoUrl,
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => [
                'required',
                'min:8',
                'confirmed',
                // At least 1 uppercase, 1 lowercase, 1 digit, 1 symbol
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,}$/'
            ],
        ], [
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'new_password.min' => 'Password minimal 8 karakter.',
            'new_password.regex' => 'Password minimal 8 karakter, mengandung huruf besar, huruf kecil, angka, dan simbol.'
        ]);

        $user = Auth::user();
        if (!\Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password saat ini salah.'
            ], 422);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah.'
        ]);
    }
}
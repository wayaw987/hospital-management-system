<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\PatientEditRequest;
use App\Models\PatientRequest;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            // Admin bisa lihat semua pasien
            $patients = Patient::latest()->get();
        } else {
            // User hanya bisa lihat pasien dengan nama sendiri
            $patients = Patient::where('name', Auth::user()->name)->latest()->get();
        }
        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role === 'admin') {
            // Admin bisa langsung buat pasien
            return view('patients.create');
        } else {
            // User harus request approval dulu
            return view('patients.request-create');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role === 'admin') {
            // Admin bisa langsung buat pasien
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:patients,email',
                'phone' => 'required|string|max:20',
                'address' => 'required|string',
                'birth_date' => 'required|date',
                'gender' => 'required|in:male,female',
                'blood_type' => 'required|string|max:5',
                'emergency_contact_name' => 'required|string|max:255',
                'emergency_contact_phone' => 'required|string|max:20',
                'insurance_number' => 'nullable|string|max:255',
                'medical_history' => 'nullable|string',
            ]);

            Patient::create($request->all());
            return redirect()->route('patients.index')
                ->with('success', 'Data pasien berhasil ditambahkan.');
        } else {
            // User harus request approval dulu
            return $this->storePatientRequest($request);
        }
    }

    /**
     * Store patient request for approval
     */
    public function storePatientRequest(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'blood_type' => 'required|string|max:5',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'insurance_number' => 'nullable|string|max:255',
            'medical_history' => 'nullable|string',
        ]);

        // Cek apakah user sudah punya request pending
        $existingRequest = PatientRequest::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'Anda sudah memiliki request yang sedang menunggu persetujuan.');
        }

        PatientRequest::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'blood_type' => $request->blood_type,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'insurance_number' => $request->insurance_number,
            'medical_history' => $request->medical_history,
        ]);

        return redirect()->route('patients.index')
            ->with('success', 'Request penambahan pasien berhasil diajukan. Menunggu persetujuan admin.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        if (Auth::user()->role === 'admin') {
            // Admin bisa langsung edit
            return view('patients.edit', compact('patient'));
        } else {
            // User harus request approval
            return redirect()->route('patients.request-edit', $patient->id);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        // Cek apakah user adalah admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('patients.index')->with('error', 'Akses ditolak. Hanya admin yang dapat mengedit pasien.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:patients,email,' . $patient->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'blood_type' => 'required|string|max:5',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'insurance_number' => 'nullable|string|max:255',
            'medical_history' => 'nullable|string',
            'allergies' => 'nullable|string',
        ]);

        $patient->update($request->all());

        return redirect()->route('patients.index')
            ->with('success', 'Data pasien berhasil diperbarui.');
    }

    /**
     * Show form to request edit for patient (for non-admin users)
     */
    public function requestEdit(Patient $patient)
    {
        // Cek apakah user bukan admin
        if (Auth::user()->role === 'admin') {
            return redirect()->route('patients.edit', $patient->id);
        }
        
        return view('patients.request-edit', compact('patient'));
    }

    /**
     * Store edit request for patient
     */
    public function storeEditRequest(Request $request, Patient $patient)
    {
        // Cek apakah user bukan admin
        if (Auth::user()->role === 'admin') {
            return redirect()->route('patients.edit', $patient->id);
        }
        
        $request->validate([
            'requested_changes' => 'required|array',
            'reason' => 'required|string|max:500'
        ]);

        PatientEditRequest::create([
            'patient_id' => $patient->id,
            'requested_by' => Auth::id(),
            'requested_changes' => $request->requested_changes,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return redirect()->route('patients.index')
            ->with('success', 'Permintaan edit pasien berhasil diajukan dan akan direview oleh admin.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        // Cek apakah user adalah admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('patients.index')->with('error', 'Akses ditolak. Hanya admin yang dapat menghapus pasien.');
        }
        
        $patient->delete();

        return redirect()->route('patients.index')
            ->with('success', 'Data pasien berhasil dihapus.');
    }
}

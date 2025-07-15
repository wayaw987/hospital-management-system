<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::latest()->get();
        return view('doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Cek apakah user adalah admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('doctors.index')->with('error', 'Akses ditolak. Hanya admin yang dapat menambah dokter.');
        }
        
        return view('doctors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Cek apakah user adalah admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('doctors.index')->with('error', 'Akses ditolak. Hanya admin yang dapat menambah dokter.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email',
            'phone' => 'required|string|max:20',
            'specialist' => 'required|string|max:255',
            'address' => 'required|string',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'license_number' => 'required|string|unique:doctors,license_number',
            'experience_years' => 'required|integer|min:0',
            'consultation_fee' => 'required|numeric|min:0',
            'education' => 'required|string',
        ]);

        Doctor::create($request->all());

        return redirect()->route('doctors.index')
            ->with('success', 'Data dokter berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        return view('doctors.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        // Cek apakah user adalah admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('doctors.index')->with('error', 'Akses ditolak. Hanya admin yang dapat mengedit dokter.');
        }
        
        return view('doctors.edit', compact('doctor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        // Cek apakah user adalah admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('doctors.index')->with('error', 'Akses ditolak. Hanya admin yang dapat mengedit dokter.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email,' . $doctor->id,
            'phone' => 'required|string|max:20',
            'specialist' => 'required|string|max:255',
            'address' => 'required|string',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'license_number' => 'required|string|unique:doctors,license_number,' . $doctor->id,
            'experience_years' => 'required|integer|min:0',
            'consultation_fee' => 'required|numeric|min:0',
            'education' => 'required|string',
        ]);

        $doctor->update($request->all());

        return redirect()->route('doctors.index')
            ->with('success', 'Data dokter berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        // Cek apakah user adalah admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('doctors.index')->with('error', 'Akses ditolak. Hanya admin yang dapat menghapus dokter.');
        }
        
        $doctor->delete();

        return redirect()->route('doctors.index')
            ->with('success', 'Data dokter berhasil dihapus.');
    }
}

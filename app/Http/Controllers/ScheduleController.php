<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedules = Schedule::with('doctor')->latest()->get();
        return view('schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Cek apakah user adalah admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('schedules.index')->with('error', 'Akses ditolak. Hanya admin yang dapat menambah jadwal dokter.');
        }
        
        $doctors = Doctor::all();
        $dayOptions = Schedule::getDayOptions();
        return view('schedules.create', compact('doctors', 'dayOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Cek apakah user adalah admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('schedules.index')->with('error', 'Akses ditolak. Hanya admin yang dapat menambah jadwal dokter.');
        }
        
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_number' => 'required|string|max:20',
            'is_available' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_available'] = $request->has('is_available') ? 1 : 0;

        Schedule::create($data);

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        $schedule->load('doctor');
        return view('schedules.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        // Cek apakah user adalah admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('schedules.index')->with('error', 'Akses ditolak. Hanya admin yang dapat mengedit jadwal dokter.');
        }
        
        $doctors = Doctor::all();
        $dayOptions = Schedule::getDayOptions();
        return view('schedules.edit', compact('schedule', 'doctors', 'dayOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        // Cek apakah user adalah admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('schedules.index')->with('error', 'Akses ditolak. Hanya admin yang dapat mengedit jadwal dokter.');
        }
        
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_number' => 'required|string|max:20',
            'is_available' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_available'] = $request->has('is_available') ? 1 : 0;

        $schedule->update($data);

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        // Cek apakah user adalah admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('schedules.index')->with('error', 'Akses ditolak. Hanya admin yang dapat menghapus jadwal dokter.');
        }
        
        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }
}

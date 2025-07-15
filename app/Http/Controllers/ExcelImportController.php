<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Schedule;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelImportController extends Controller
{
    public function index()
    {
        return view('import.index');
    }

    private function readExcelFile($filePath)
    {
        try {
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $data = $worksheet->toArray();
            
            // Remove header row
            array_shift($data);
            
            return $data;
        } catch (\Exception $e) {
            throw new \Exception("Error reading Excel file: " . $e->getMessage());
        }
    }

    private function readCSVFile($filePath)
    {
        $data = array_map('str_getcsv', file($filePath));
        // Remove header row
        array_shift($data);
        return $data;
    }

    public function importDoctors(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls|max:2048',
        ]);

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();
            $extension = $file->getClientOriginalExtension();
            
            // Read file based on extension
            if ($extension === 'csv') {
                $data = $this->readCSVFile($path);
            } else {
                $data = $this->readExcelFile($path);
            }
            
            $imported = 0;
            $skipped = 0;
            
            foreach ($data as $row) {
                if (count($row) >= 8) { // Ensure minimum required columns
                    try {
                        Doctor::create([
                            'name' => $row[0],
                            'email' => $row[1],
                            'phone' => $row[2],
                            'specialist' => $row[3],
                            'address' => $row[4],
                            'birth_date' => $row[5],
                            'gender' => $row[6],
                            'license_number' => $row[7],
                            'experience_years' => isset($row[8]) ? (int)$row[8] : 0,
                            'consultation_fee' => isset($row[9]) ? (float)$row[9] : 0,
                            'education' => isset($row[10]) ? $row[10] : '',
                            'is_active' => true,
                        ]);
                        $imported++;
                    } catch (\Exception $e) {
                        // Skip row if there's an error (e.g., duplicate email)
                        $skipped++;
                        continue;
                    }
                }
            }

            $message = "Berhasil mengimpor $imported data dokter.";
            if ($skipped > 0) {
                $message .= " ($skipped data dilewati karena duplikat atau error)";
            }

            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

    public function importPatients(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls|max:2048',
        ]);

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();
            $extension = $file->getClientOriginalExtension();
            
            // Read file based on extension
            if ($extension === 'csv') {
                $data = $this->readCSVFile($path);
            } else {
                $data = $this->readExcelFile($path);
            }
            
            $imported = 0;
            $skipped = 0;
            
            foreach ($data as $row) {
                if (count($row) >= 6) { // Ensure minimum required columns
                    try {
                        Patient::create([
                            'name' => $row[0],
                            'email' => isset($row[1]) && !empty($row[1]) ? $row[1] : null,
                            'phone' => $row[2],
                            'address' => $row[3],
                            'birth_date' => $row[4],
                            'gender' => $row[5],
                            'blood_type' => isset($row[6]) ? $row[6] : null,
                            'allergies' => isset($row[7]) ? $row[7] : null,
                            'medical_history' => isset($row[8]) ? $row[8] : null,
                            'emergency_contact_name' => isset($row[9]) ? $row[9] : '',
                            'emergency_contact_phone' => isset($row[10]) ? $row[10] : '',
                            'insurance_number' => isset($row[11]) ? $row[11] : null,
                            'is_active' => true,
                        ]);
                        $imported++;
                    } catch (\Exception $e) {
                        // Skip row if there's an error
                        $skipped++;
                        continue;
                    }
                }
            }

            $message = "Berhasil mengimpor $imported data pasien.";
            if ($skipped > 0) {
                $message .= " ($skipped data dilewati karena duplikat atau error)";
            }

            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

    public function importSchedules(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls|max:2048',
        ]);

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();
            $extension = $file->getClientOriginalExtension();
            
            // Read file based on extension
            if ($extension === 'csv') {
                $data = $this->readCSVFile($path);
            } else {
                $data = $this->readExcelFile($path);
            }
            
            $imported = 0;
            $skipped = 0;
            
            foreach ($data as $row) {
                if (count($row) >= 5) { // Ensure minimum required columns
                    try {
                        // Find doctor by name or email
                        $doctor = Doctor::where('name', $row[0])
                                        ->orWhere('email', $row[0])
                                        ->first();
                        
                        if ($doctor) {
                            Schedule::create([
                                'doctor_id' => $doctor->id,
                                'day_of_week' => strtolower($row[1]),
                                'start_time' => $row[2],
                                'end_time' => $row[3],
                                'room_number' => $row[4],
                                'is_available' => true,
                                'notes' => isset($row[5]) ? $row[5] : null,
                            ]);
                            $imported++;
                        } else {
                            $skipped++;
                        }
                    } catch (\Exception $e) {
                        // Skip row if there's an error
                        $skipped++;
                        continue;
                    }
                }
            }

            $message = "Berhasil mengimpor $imported jadwal dokter.";
            if ($skipped > 0) {
                $message .= " ($skipped data dilewati karena dokter tidak ditemukan atau error)";
            }

            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }
}

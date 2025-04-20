<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hall;
use App\Models\Cinema;
use App\Models\Seat;

class HallController extends Controller
{
  
    public function index()
        {
            $halls = Hall::with(['cinema', 'seats', 'showtimes'])->latest()->get();
            return view('halls.index', compact('halls'));
        }


    public function create()
        {
            $cinemas = Cinema::all();
            return view('halls.create', compact('cinemas'));
        }

    public function store(Request $request)
        {
            $request->validate([
                'cinema_id' => 'required|exists:cinemas,id',
                'name' => 'required|string|max:255',
                'seat_row_count' => 'required|integer|min:1',
                'seat_column_count' => 'required|integer|min:1',
                'number_of_vip_seats' => 'required|integer|min:0',
                'number_of_standard_seats' => 'required|integer|min:0',
            ]);

            $total_capacity = $request->seat_row_count * $request->seat_column_count;
            $total_requested_seats = $request->number_of_vip_seats + $request->number_of_standard_seats;

            if ($total_requested_seats > $total_capacity) {
                return back()->withErrors(['total' => 'Total VIP + Standard seats exceed hall capacity.'])->withInput();
            }

            // Create hall
            $hall = Hall::create([
                'cinema_id' => $request->cinema_id,
                'name' => $request->name,
                'seat_row_count' => $request->seat_row_count,
                'seat_column_count' => $request->seat_column_count,
            ]);

            $vipCount = $request->number_of_vip_seats;
            $standardCount = $request->number_of_standard_seats;
            $generated = 0;

            for ($row = 1; $row <= $request->seat_row_count; $row++) {
                for ($col = 1; $col <= $request->seat_column_count; $col++) {

                    if ($vipCount > 0) {
                        $type = 'VIP';
                        $vipCount--;
                    } elseif ($standardCount > 0) {
                        $type = 'Standard';
                        $standardCount--;
                    } else {
                        $type = 'Not Assigned';
                    }

                    Seat::create([
                        'hall_id' => $hall->id,
                        'seat_row' => $row,
                        'seat_column' => $col,
                        'seat_type' => $type,
                    ]);
                }
            }


            return redirect()->route('halls.index')->with('success', 'Hall and seats created successfully.');
        }

        public function destroy($id)
            {
                $hall = Hall::findOrFail($id);

                // Delete all related seats
                $hall->seats()->delete();

                // Delete the hall
                $hall->delete();

                return redirect()->route('halls.index')->with('success', 'Hall and its seats deleted successfully.');
            }
            
        public function viewSeats($hallId)
            {
                $hall = Hall::with('seats')->findOrFail($hallId);
                return view('halls.view_seats', compact('hall'));
            }
            
        public function edit($id)
            {
                $hall = Hall::findOrFail($id);
                $cinemas = Cinema::all();
                return view('halls.edit', compact('hall', 'cinemas'));
            }

public function update(Request $request, $id)
{
    $request->validate([
        'cinema_id' => 'required|exists:cinemas,id',
        'name' => 'required|string|max:255',
        'seat_row_count' => 'required|integer|min:1',
        'seat_column_count' => 'required|integer|min:1',
    ]);

    $hall = Hall::findOrFail($id);

    $layoutChanged = $hall->seat_row_count != $request->seat_row_count ||
                     $hall->seat_column_count != $request->seat_column_count;

    $hall->update([
        'cinema_id' => $request->cinema_id,
        'name' => $request->name,
        'seat_row_count' => $request->seat_row_count,
        'seat_column_count' => $request->seat_column_count,
    ]);

    // ⚠️ Optional: Warn if layout changed but seats weren't updated
    if ($layoutChanged) {
        return redirect()->route('halls.index')
            ->with('warning', 'Hall updated, but seat layout changed. Please manually update seats if needed.');
    }

    return redirect()->route('halls.index')->with('success', 'Hall updated successfully.');
}

}

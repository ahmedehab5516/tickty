<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class StaffController extends Controller
{
    public function index()
    {
        // Get the currently authenticated staff user
        $staff = Auth::user();

        // Fetch the company_id directly from the user (assumes company_id exists on users table)
        $companyId = $staff->company_id;

        // Fetch tasks assigned to the logged-in staff, filtered by the company
        $assignedTasks = Assignment::where('staff_id', $staff->id)
                                   ->whereHas('task', function ($query) use ($companyId) {
                                       $query->where('company_id', $companyId);
                                   })
                                   ->with('task')
                                   ->get();

        return view('staff.dashboard', compact('assignedTasks'));
    }

    public function completeTask($id)
    {
        $task = Assignment::findOrFail($id);

        if ($task->staff_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $task->update(['is_completed' => true]);

        return redirect()->back()->with('success', 'Task marked as completed!');
    }

    public function profile()
    {
        $staff = Auth::user();
        return view('staff.profile', compact('staff'));
    }



        
public function updateProfile(Request $request, $id)
{
    // Validate the incoming request
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $id, // Ensures the email is unique except for the current user
        'phone' => 'required|string|max:20',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Optionally handle image upload
    ]);

    // Find the Super user by ID
    $staff = User::findOrFail($id);

    // Update the Super user's profile
    $staff->name = $request->name;
    $staff->email = $request->email;
    $staff->phone = $request->phone;

    // Handle profile image upload (if any)
    if ($request->hasFile('profile_image')) {
        // Delete the old profile image if it exists
        if ($staff->profile_image) {
            $oldImagePath = public_path('storage/' . $staff->profile_image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath); // Delete the old image
            }
        }

        // Store the new image
        $imagePath = $request->file('profile_image')->store('profile_images', 'public');
        // Update the profile image path in the database
        $staff->profile_image = $imagePath;
    }

    // Save the updated data
    $staff->save();

    // Redirect back to the profile page with a success message
    return redirect()->route('staff.profile')->with('success', 'Profile updated successfully!');
}



}

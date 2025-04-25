<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subscription;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SuperAdminController extends Controller
{
    public function index()
    {
        return view('superadmin.dashboard');
    }

    public function createAdminForm()
    {
        return view('superadmin.manage_admins.create');
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone|regex:/^01[0-9]{9}$/', // Validate phone starts with 01 and is 11 digits
            'salary' => 'required|numeric|min:0',  // Salary validation
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'salary' => $request->salary,  // Store the salary
            'password' => Hash::make('74108520'), // Default password
            'role_id' => 2,  // Admin role
        ]);

        return redirect()->route('superadmin.manage_admins.index')->with('success', 'Admin created successfully.');
    }

    public function viewAdmins()
    {
        $admins = User::where('role_id', 2)->get();  // Fetch all admins
        return view('superadmin.manage_admins.index', compact('admins'));
    }

    public function viewUsers()
    {
        $users = User::where('role_id', 1)->get();  // Fetch all regular users
        return view('superadmin.manage_users.index', compact('users'));
    }

    // Show form for updating admin profile
      public function editAdmin($id)
      {
         $admin = User::findOrFail($id);  // Fetch the admin by ID
         return view('superadmin.manage_admins.edit', compact('admin'));  // Pass the admin data to the edit view
      }


    // Handle updating admin data
 public function updateAdmin(Request $request, $id)
{
    // Validate the incoming data
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $id,  // Ignore the current admin email
        'phone' => 'required|string|max:20|unique:users,phone,' . $id,  // Ignore the current admin phone number
        'salary' => 'nullable|numeric|min:0',  // Optional salary field
    ]);

    // Find the admin to update
    $admin = User::findOrFail($id);

    // Update the admin details
    $admin->update([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'salary' => $request->salary,  // Update salary
    ]);

    // Redirect with success message
    return redirect()->route('superadmin.manage_admins.index')->with('success', 'Admin updated successfully!');
}

    // Handle deleting admin
public function deleteAdmin($id)
{
    $admin = User::findOrFail($id);  // Find the admin by ID
    $admin->delete();  // Delete the admin

    return redirect()->route('superadmin.manage_admins.index')->with('success', 'Admin deleted successfully!');
}


  public function profile()
{
    $superAdmin = Auth::user(); // Get the authenticated super admin user
    return view('superadmin.profile', compact('superAdmin'));
}




    
public function updateProfile(Request $request, $id)
{
    // Validate the incoming request
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $id, // Ensures the email is unique except for the current admin
        'phone' => 'required|string|max:20',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Optionally handle image upload
    ]);

    // Find the Super Admin by ID
    $superadmin = User::findOrFail($id);

    // Update the Super Admin's profile
    $superadmin->name = $request->name;
    $superadmin->email = $request->email;
    $superadmin->phone = $request->phone;

    // Handle profile image upload (if any)
    if ($request->hasFile('profile_image')) {
        // Delete the old profile image if it exists
        if ($superadmin->profile_image) {
            $oldImagePath = public_path('storage/' . $superadmin->profile_image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath); // Delete the old image
            }
        }

        // Store the new image
        $imagePath = $request->file('profile_image')->store('profile_images', 'public');
        // Update the profile image path in the database
        $superadmin->profile_image = $imagePath;
    }

    // Save the updated data
    $superadmin->save();

    // Redirect back to the profile page with a success message
    return redirect()->route('superadmin.profile')->with('success', 'Profile updated successfully!');
}



 // Show subscription settings for the super admin
    public function subscriptionSettings()
    {
        $superAdmin = Auth::user();

        // Check if the logged-in user is a super admin (production company)
        if ($superAdmin->role_id !== 3) { // Assuming role_id 3 is for Super Admin
            return redirect()->route('home')->with('error', 'Access denied.');
        }

        // Fetch the subscription for the super admin's company
        $subscription = Subscription::where('company_id', $superAdmin->company_id)->first();

        return view('superadmin.subscription_settings', compact('subscription'));
    }

    // Update the subscription details (e.g., renew or change plan)
    public function updateSubscription(Request $request)
    {
        $request->validate([
            'plan_type' => 'required|string',
            'subscription_end' => 'required|date',
            'payment_status' => 'required|in:paid,pending',
            'cinema_count' => 'required|integer',
        ]);

        $superAdmin = Auth::user();

        // Fetch the subscription of the logged-in super admin's company
        $subscription = Subscription::where('company_id', $superAdmin->company_id)->first();
        
        if (!$subscription) {
            return redirect()->route('superadmin.subscription_settings')->with('error', 'Subscription not found.');
        }

        // Update the subscription details
        $subscription->update([
            'plan_type' => $request->plan_type,
            'subscription_end' => $request->subscription_end,
            'payment_status' => $request->payment_status,
            'cinema_count' => $request->cinema_count,
        ]);

        return redirect()->route('superadmin.subscription_settings')->with('success', 'Subscription updated successfully.');
    }





  public function showCreateForm()
    {
        return view('developer.create_super_admin');
    }

    // Store the Super Admin in the database
    public function storeSuperAdmin(Request $request)
    {
        // Validate the form inputs
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|string|email|max:255|unique:companies,company_email',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:15',
        ]);

        // Create the Super Admin company (production company)
        $company = Company::create([
            'company_name' => $request->company_name,
            'company_email' => $request->company_email,
            'company_phone' => $request->company_phone,
        ]);

        // Create the Super Admin user account
        $superAdmin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make('74108520'), // Default password, you can change this
            'role_id' => 3, // Super Admin role (assuming 3 is the role for Super Admin)
            'company_id' => $company->id, // Link to the company
        ]);

        // Redirect to the developer dashboard or home
        return redirect()->route('home')->with('success', 'Super Admin created successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User; // Example model, replace with your actual model
use App\Models\Movie; // Example model, replace with your actual model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
   protected function index(){
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
         ]);

         User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2,
         ]);

         return redirect()->route('superadmin.dashboard')->with('success', 'Admin created successfully.');
      }


      public function viewAdmins()
         {
            // If using numeric role_id:
            $admins = User::where('role_id', 2)->get();

            // If using string role:
            // $admins = User::where('role', 'admin')->get();

            return view('superadmin.manage_admins.index', compact('admins'));
         }


      public function viewUsers()
         {
            // If using numeric role_id:
            $users = User::where('role_id', 1)->get();

            // If using string role:
            // $admins = User::where('role', 'admin')->get();

            return view('superadmin.manage_users.index', compact('users'));
         }
   




}

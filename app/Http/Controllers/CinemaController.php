<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Company;
use Illuminate\Http\Request;

class CinemaController extends Controller
{
    public function index()
    {
        $cinemas = Cinema::with('company')->latest()->get();
        return view('cinemas.index', compact('cinemas'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('cinemas.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'owner_company_id' => 'required|exists:companies,id',
        ]);

        Cinema::create($request->all());

        return redirect()->route('cinemas.index')->with('success', 'Cinema created successfully.');
    }

    public function edit($id)
    {
        $cinema = Cinema::findOrFail($id);
        $companies = Company::all();
        return view('cinemas.edit', compact('cinema', 'companies'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'owner_company_id' => 'required|exists:companies,id',
        ]);

        $cinema = Cinema::findOrFail($id);
        $cinema->update($request->all());

        return redirect()->route('cinemas.index')->with('success', 'Cinema updated successfully.');
    }

    public function destroy($id)
    {
        $cinema = Cinema::findOrFail($id);
        $cinema->delete();

        return redirect()->route('cinemas.index')->with('success', 'Cinema deleted successfully.');
    }
}

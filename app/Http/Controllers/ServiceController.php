<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index()
    {
        // $customers = User::where('role', 'customer')->get();
        $services = Service::all();

        return view('services.index', compact('services'));
    }

    public function create()
    {
        return view('services.add');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'price' => 'required|integer|min:0',
            'description' => 'required|string',
            'photo' => 'nullable',
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('services', 'public');
            $validated['photo'] = $photoPath;
        }

        Service::create($validated);

        return redirect()->route('services.index')->with('success', 'Service berhasil ditambahkan!');
    }

    public function show($id)
    {
        $service = Service::findOrFail($id);
        return response()->json([
            'id' => $service->id,
            'price' => $service->price,
            'description' => $service->description,
            'photo_url' => $service->photo ? Storage::url($service->photo) : null,
        ]);
    }

    public function update(Request $request, $id)
    {
        $service = Service::find($id);
        $validated = $request->validate([
            'price' => 'required|numeric',
            'description' => 'required|string',
            'photo' => 'nullable'
        ]);

        if ($request->hasFile('photo')) {
            if ($service->photo && Storage::disk('public')->exists($service->photo)) {
                Storage::disk('public')->delete($service->photo);
            }
            $path = $request->file('photo')->store('services', 'public');
            $validated['photo'] = $path;
        }

        $service->update($validated);

        return response()->json(['message' => 'Service updated successfully.']);
    }

    public function destroy($id)
    {
        $service = Service::find($id);
        // dd($service);
        if ($service->photo && Storage::disk('public')->delete($service->photo)) {
            Storage::delete('public/' . $service->photo);
        }
        // dd($service->photo, Storage::delete('public/' . $service->photo));

        $service->delete();

        // return redirect()->route('services.index')->with('success', 'Service added successfully!');
        return response()->json(['message' => 'Service deleted successfully.']);
    }
}

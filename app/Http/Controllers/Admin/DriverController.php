<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Http\Requests\Driver\StoreDriverRequest;
use App\Http\Requests\Driver\UpdateDriverRequest;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $query = Driver::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $drivers = $query->latest()->paginate(10);

        return view('admin.drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('admin.drivers.create');
    }

    public function store(StoreDriverRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('drivers', 'public');
        }

        Driver::create($data);

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver berhasil ditambahkan.');
    }

    public function show(Driver $driver)
    {
        return view('admin.drivers.show', compact('driver'));
    }

    public function edit(Driver $driver)
    {
        return view('admin.drivers.edit', compact('driver'));
    }

    public function update(UpdateDriverRequest $request, Driver $driver)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($driver->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($driver->photo);
            }
            $data['photo'] = $request->file('photo')->store('drivers', 'public');
        }

        $driver->update($data);

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver berhasil diperbarui.');
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver berhasil dihapus.');
    }
} 
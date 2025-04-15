<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannedIp;
use Illuminate\Http\Request;

class BannedIpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bannedIps = BannedIp::all();
        return view('admin.banned-ips.index', compact('bannedIps'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banned-ips.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip|unique:banned_ips',
            'reason' => 'nullable|string',
        ]);

        BannedIp::create($request->all());

        return redirect()->route('admin.banned-ips.index')
            ->with('success', 'Banned IP added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //Not needed
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bannedIp = BannedIp::findOrFail($id);
        return view('admin.banned-ips.edit', compact('bannedIp'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'ip_address' => 'required|ip|unique:banned_ips,ip_address,' . $id,
            'reason' => 'nullable|string',
        ]);

        $bannedIp = BannedIp::findOrFail($id);
        $bannedIp->update($request->all());

        return redirect()->route('admin.banned-ips.index')
            ->with('success', 'Banned IP updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bannedIp = BannedIp::findOrFail($id);
        $bannedIp->delete();

        return redirect()->route('admin.banned-ips.index')
            ->with('success', 'Banned IP deleted successfully.');
    }
}

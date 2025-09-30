<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function edit()
    {
        $settings = Settings::first();
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = Settings::first();

        $validated = $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'name' => 'required|max:255',
            'tagline' => 'nullable|string|max:255',
            'favicon' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',            
            'gst_number' => [
                'nullable',
                'string',
                'size:15',
                'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[0-9A-Z]{1}Z[0-9A-Z]{1}$/',
                Rule::unique('settings', 'gst_number')->ignore($settings?->id),
            ],
            'address' => 'nullable|string|max:500',
            'location' => 'nullable|url|max:255',
            'contact_number' => 'nullable|string|max:20|regex:/^(?:\+91[-\s]?)?[6-9]\d{9}$/',
            'whatsapp_number' => 'nullable|string|max:20|regex:/^(?:\+91[-\s]?)?[6-9]\d{9}$/',
            'copyright' => 'nullable|string|max:255',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',            
            'instagram' => 'nullable|url|max:255',                  
            'linkedin' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'authorized_signatory' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        foreach (['logo', 'favicon', 'authorized_signatory'] as $field) {
            if ($request->hasFile($field)) {

                if (!empty($settings?->$field)) {
                    Storage::disk('public')->delete('uploads/' . $settings->$field);
                }

                $path = $request->file($field)->store('uploads', 'public');

                $validated[$field] = basename($path);
            }
        }

        if ($settings) {
            $settings->update($validated);
        } else {
            $settings = Settings::create($validated);
        }

        return redirect()->route('settings.edit')
            ->with('success', 'Settings Updated Successfully');
    }

    
    
}

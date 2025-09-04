<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $admin = auth('admin')->user();
        return view('admin.profile.edit', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = auth('admin')->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:50|unique:admins,username,' . $admin->id,
            'email' => 'required|email|max:255|unique:admins,email,' . $admin->id,
            'avatar' => 'nullable|image|max:2048',
        ]);

        // Handle avatar removal
        if ($request->boolean('remove_avatar')) {
            if ($admin->avatar_path) {
                Storage::disk('public')->delete($admin->avatar_path);
            }
            $data['avatar_path'] = null;
        } elseif ($request->hasFile('avatar')) {
            // Upload regular file
            if ($admin->avatar_path) {
                Storage::disk('public')->delete($admin->avatar_path);
            }
            $data['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        } elseif ($request->filled('avatar_cropped')) {
            // Save cropped image sent as data URL (base64)
            $dataUrl = $request->input('avatar_cropped');
            if (preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $type)) {
                $type = strtolower($type[1]); // jpg, png, webp, etc.
                if (in_array($type, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $dataBase64 = substr($dataUrl, strpos($dataUrl, ',') + 1);
                    $imageData = base64_decode($dataBase64);
                    if ($imageData !== false) {
                        $filename = 'avatars/' . uniqid('admin_' . $admin->id . '_') . '.' . ($type === 'jpg' ? 'jpeg' : $type);
                        // Delete old first
                        if ($admin->avatar_path) {
                            Storage::disk('public')->delete($admin->avatar_path);
                        }
                        Storage::disk('public')->put($filename, $imageData);
                        $data['avatar_path'] = $filename;
                    }
                }
            }
        }

        unset($data['avatar']);

        $admin->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $admin = auth('admin')->user();

        $data = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($data['current_password'], $admin->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak sesuai.']);
        }

        $admin->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', 'Kata sandi berhasil diperbarui.');
    }
}

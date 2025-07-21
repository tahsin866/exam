<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class userRegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        // Get madrasha data from session
        $madrasha_data = [
            'madrasha_id' => session('madrasha_id'),
            'madrasha_name' => session('madrasha_name'),
            'thana' => session('thana'),
            'post' => session('post'),
            'custom_code' => session('custom_code')
        ];

        return Inertia::render('Auth/Register', $madrasha_data);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'admin_Designation' => 'required|string|max:255',
            'NID_no' => 'required|string|max:255|unique:users,NID_no',
            'Mobile_no' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'admin_Designation' => $request->admin_Designation,
            'NID_no' => $request->NID_no,
            'Mobile_no' => $request->Mobile_no,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 'madrasa', // Set as madrasa user
            'phone' => $request->Mobile_no, // Map to new field
            'nid' => $request->NID_no, // Map to new field
            'is_active' => true,
            'madrasha_id' => session('madrasha_id'),
            'custom_code' => session('custom_code'),
            'madrasha_name' => session('madrasha_name'),
            'thana' => session('thana'),
            'post' => session('post'),
            'MType' => session('MType'),
            'Stage' => session('Stage'),
            'markaz_serial' => session('markaz_serial'),
        ]);

        event(new Registered($user));
        Auth::login($user);

        // Clear the session data
        $this->clearMadrashaSession();

        return redirect()->route('dashboard');
    }





// রেজিস্টার সম্পন্ন হওয়ার পর এই মেথড কল করুন
protected function clearMadrashaSession()
{
    session()->forget([
        'madrasha_id',
        'madrasha_name',
        'thana',
        'post',
        'Stage',
        'MType',
        'markaz_serial',
        'custom_code',
        'madrasha_verified_at'
    ]);
}




}

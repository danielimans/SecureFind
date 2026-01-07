<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LanguageController extends Controller
{
    /**
     * Change application language
     */
    public function change(Request $request)
    {
        $language = $request->input('language', 'en');
        
        if (!in_array($language, ['en', 'my'])) {
            $language = 'en';
        }
        
        session(['locale' => $language]);
        
        if (Auth::check()) {
            $user = \App\Models\User::find(Auth::id());
            if ($user) {
                $user->update([
                    'language_preference' => $language
                ]);
            }
        }
        
        app()->setLocale($language);
        
        return response()->json([
            'success' => true,
            'language' => $language
        ]);
    }
}
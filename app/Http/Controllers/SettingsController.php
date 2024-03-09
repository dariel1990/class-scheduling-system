<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:settings-list', ['only' => ['index']]);
        $this->middleware('permission:settings-update', ['only' => ['update']]);
    }

    public function index()
    {
        $pageTitle ='Settings';
        $settings = Settings::get();

        return view('admin.settings.index', compact('pageTitle', 'settings'));
    }

    public function update(Request $request, $keyName)
    {
        if($request->ajax()) {
            $settings = Settings::where('Keyname', $keyName)->update([
                'Keyvalue' => $request->keyValue,
            ]);
            return response()->json(['success' => true, 'newValue' => $request->keyValue]);
        }
    }
}

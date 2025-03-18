<?php

namespace App\Http\Controllers;

use App\Models\ScreenSetting;
use Illuminate\Http\Request;
use App\Models\FieldSetting;

class FieldSettingController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index() {
        $screens = ScreenSetting::all();
        $fields = FieldSetting::all()->groupBy('screen_type');
        return view('visitor.field_settings', compact('fields', 'screens'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request) {
        foreach ($request->screens as $screen => $value) {
            ScreenSetting::updateOrCreate(
                ['screen_name' => $screen],
                ['is_visible' => $value]
            );
        }

        foreach ($request->fields as $field => $value) {
            FieldSetting::updateOrCreate(['field_name' => $field], ['is_visible' => $value]);
        }
        return redirect()->back()->with('success', 'Screens and Fields configuration has been updated successfully.');
    }
}

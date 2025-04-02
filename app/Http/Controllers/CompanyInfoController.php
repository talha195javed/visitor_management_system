<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyInfo;
use Illuminate\Support\Facades\File;

class CompanyInfoController extends Controller
{
    public function uploadImages(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|max:255',
            'hr_email' => 'required|email|max:255',
        ]);

        // Fetch the first (and only) record from the company_info table
        $companyInfo = CompanyInfo::firstOrNew([]);

        // Update the fields with the new data
        $companyInfo->company_name = $request->input('company_name');
        $companyInfo->company_email = $request->input('company_email');
        $companyInfo->hr_email = $request->input('hr_email');

        // Handle the Welcome Screen Image
        if ($request->hasFile('welcome_screen_image')) {
            $this->deleteOldImage('welcome_screen_image', $companyInfo);
            $welcomeImage = $request->file('welcome_screen_image');
            $welcomeImageName = 'welcome_screen_image.jpg';
            $welcomeImage->move(public_path('assets/visitor_photos'), $welcomeImageName);
            $companyInfo->welcome_screen_image = $welcomeImageName;
        }

        // Handle the Main Screen Image
        if ($request->hasFile('main_screen_image')) {
            $this->deleteOldImage('main_screen_image', $companyInfo);
            $mainImage = $request->file('main_screen_image');
            $mainImageName = 'main_screen_image.jpg';
            $mainImage->move(public_path('assets/visitor_photos'), $mainImageName);
            $companyInfo->main_screen_image = $mainImageName;
        }

        // Handle the Remaining Screen Image
        if ($request->hasFile('remaining_screen_image')) {
            $this->deleteOldImage('remaining_screen_image', $companyInfo);
            $remainingImage = $request->file('remaining_screen_image');
            $remainingImageName = 'remaining_screen_image.jpg';
            $remainingImage->move(public_path('assets/visitor_photos'), $remainingImageName);
            $companyInfo->remaining_screen_image = $remainingImageName;
        }

        // Save the record (if it's a new record, it will be created)
        $companyInfo->save();

        $request->session()->flash('success', 'Form has been Updated Successfully!');
        return redirect()->back();
    }

    private function deleteOldImage($field, $companyInfo)
    {
        $oldImage = $companyInfo->$field;
        if ($oldImage && File::exists(public_path('assets/visitor_photos/' . $oldImage))) {
            File::delete(public_path('assets/visitor_photos/' . $oldImage));
        }
    }
}

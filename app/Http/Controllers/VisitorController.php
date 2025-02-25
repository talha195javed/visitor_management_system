<?php

namespace App\Http\Controllers;

use App\Mail\AdminSuccessMail;
use App\Models\Employee;
use App\Models\MailLog;
use App\Models\Visitor;
use App\Models\VisitorsEmployer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Mail\VisitorSuccessMail;
use Illuminate\Support\Facades\Mail;

class VisitorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index',
            'showCheckIn', 'storeCheckIn', 'captureImageView',
            'storeCapturedImage', 'showCheckOut', 'storeCheckOut',
            'showRoleSelection', 'setRole', 'selectPurpose', 'storePurpose',
            'captureIdView', 'storeCapturedIdImage', 'checkPreRegistered',
            'showEmergencyContactForm', 'storeEmergencyContact', 'showAgreement',
            'storeAgreement', 'visitor_success']);
    }

    public function index()
    {
        return view('visitor.index');
    }

    public function showCheckIn()
    {
        // Get visitors who haven't checked in yet
        $preRegisteredVisitors = Visitor::whereNotNull('check_out_time')->get();
        return view('visitor.checkin', compact('preRegisteredVisitors'));
    }

    public function pre_registor_visitor(Request $request)
    {
            $request->validate([
                'full_name' => 'required',
                'company' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
            ]);
            $visitor = new Visitor();
            $visitor->full_name = $request->full_name;
            $visitor->company = $request->company;
            $visitor->email = $request->email;
            $visitor->phone = $request->phone;
            $visitor->id_type = $request->id_type;
            $visitor->identification_number = $request->identification_number;
            $visitor->pre_register = 1;
            $visitor->save();

        return response()->json(['success' => true]);
    }

    public function update_visitor(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'id_type' => 'required|string',
            'identification_number' => 'required|string|max:255',
        ]);

        // Find the visitor by the visitor ID and update their information
        $visitor = Visitor::find($request->visitor_id);

        if ($visitor) {
            // Update the visitor's details
            $visitor->update([
                'full_name' => $validated['full_name'],
                'company' => $validated['company'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'id_type' => $validated['id_type'],
                'identification_number' => $validated['identification_number'],
            ]);

            // Return a success response
            return response()->json(['success' => true]);
        } else {
            // Return an error if the visitor was not found
            return response()->json(['success' => false, 'message' => 'Visitor not found']);
        }
    }

    public function storeCheckIn(Request $request)
    {
        try {
            $request->validate([
                'full_name' => 'required',
                'company' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
                'id_type' => 'required',
                'identification_number' => 'required',
            ]);

            $visitor = Visitor::where('email', $request->email)
                ->where('pre_register', 1)
                ->first();
            if ($visitor) {
                $visitor->full_name = $request->full_name;
                $visitor->company = $request->company;
                $visitor->phone = $request->phone;
                $visitor->id_type = $request->id_type;
                $visitor->identification_number = $request->identification_number;
                $visitor->check_in_time = now();
                $visitor->pre_register = 0;
                $visitor->save();
            } else {
                $visitor = new Visitor();
                $visitor->full_name = $request->full_name;
                $visitor->company = $request->company;
                $visitor->email = $request->email;
                $visitor->phone = $request->phone;
                $visitor->id_type = $request->id_type;
                $visitor->identification_number = $request->identification_number;
                $visitor->check_in_time = now();
                $visitor->pre_register = 0;
                $visitor->save();
            }

            // Redirect to image capture page with the visitor ID
            return redirect()->route('visitor.selectRole', ['id' => $visitor->id]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withInput()->withErrors(['email' => 'The email has already been taken.']);
        }
    }



    public function storeCheckOut(Request $request)
    {
        // Validate check-out request
        $request->validate([
            'visitor_id' => 'required|exists:visitors,id',
        ]);

        // Find the visitor and mark them as checked out
        $visitor = Visitor::find($request->visitor_id);
        $visitor->update(['check_out_time' => Carbon::now()]);

        // Redirect to home page after check-out
        return redirect()->route('visitor.home')->with('success', 'Check-out successful!');
    }

    public function showCheckOut()
    {
        // Get visitors who haven't checked out yet
        $visitors = Visitor::whereNull('check_out_time')->get();
        return view('visitor.checkout', compact('visitors'));
    }

    public function showPreRegistrationForm()
    {
        return view('visitor.pre-register');
    }

    public function storePreRegistration(Request $request)
    {
        // Validate pre-registration data
        $request->validate([
            'full_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'identification_number' => 'nullable|string|max:20',
            'id_type' => 'nullable|string|max:20',
        ]);

        // Create a new pre-registered visitor
        $visitor = Visitor::create([
            'full_name' => $request->full_name,
            'company' => $request->company,
            'email' => $request->email,
            'phone' => $request->phone,
            'identification_number' => $request->identification_number,
            'id_type' => $request->id_type,
            'check_in_time' => null, // Pre-registered visitor hasn't checked in yet
            'check_out_time' => null,
        ]);

        // Redirect to home page after pre-registration
        return redirect()->route('visitor.home')->with('success', 'Visitor pre-registered successfully!');
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Store the image
        if ($request->hasFile('photo')) {
            $filePath = $request->file('photo')->store('visitor_photos', 'public');
            return response()->json(['success' => true, 'filename' => $filePath]);
        }

        return response()->json(['success' => false, 'message' => 'Upload failed'], 400);
    }

    public function captureImageView($id)
    {
        $visitor = Visitor::findOrFail($id);
        return view('visitor.capture_image', compact('visitor'));
    }

    public function storeCapturedImage(Request $request, $id)
    {
        $visitor = Visitor::findOrFail($id);

        if ($request->has('photo')) {
            $imageData = $request->input('photo');

            // Extract base64 part (remove the "data:image/jpeg;base64," part)
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData); // Replace spaces with plus signs (base64 encoding)

            // Decode the image data
            $decodedImage = base64_decode($imageData);

            // Generate unique image name
            $imageName = 'visitor_' . $id . '_' . time() . '.jpg';

            // Save the image to the public directory
            $imagePath = public_path('assets/visitor_photos') . '/' . $imageName;

            // Store the image
            file_put_contents($imagePath, $decodedImage);

            // Save image path in the database
            $visitor->photo = $imageName;
            $visitor->save();

            return response()->json(['success' => true, 'message' => 'Photo uploaded successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'No photo data received']);
    }

    public function captureIdView($id)
    {
        $visitor = Visitor::findOrFail($id);
        return view('visitor.capture_id_image', compact('visitor'));
    }

    public function storeCapturedIdImage(Request $request, $id)
    {
        $visitor = Visitor::findOrFail($id);

        if ($request->has('photo')) {
            $imageData = $request->input('photo');

            // Extract base64 part (remove the "data:image/jpeg;base64," part)
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData); // Replace spaces with plus signs (base64 encoding)

            // Decode the image data
            $decodedImage = base64_decode($imageData);

            // Generate unique image name
            $imageName = 'visitor_' . $id . '_' . time() . '.jpg';

            // Save the image to the public directory
            $imagePath = public_path('assets/visitor_photos') . '/' . $imageName;

            // Store the image
            file_put_contents($imagePath, $decodedImage);

            // Save image path in the database
            $visitor->id_photo = $imageName;
            $visitor->save();

            return response()->json(['success' => true, 'message' => 'Photo uploaded successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'No photo data received']);
    }

    public function showRoleSelection($id)
    {
        $visitor = Visitor::findOrFail($id);
        return view('visitor.select_role', compact('visitor'));
    }

    public function setRole(Request $request, $id)
    {
        $visitor = Visitor::findOrFail($id);
        $request->validate([
            'role' => 'required|in:visitor,client,interviewer',
        ]);
        $visitor->role = $request->role;
        $visitor->save();

        session(['visitor_role' => $request->role]);

        return redirect()->route('visitor.selectPurpose', ['id' => $id]);
    }

    public function selectPurpose($id)
    {
        $visitor = Visitor::findOrFail($id);
        $employees = Employee::all();
        return view('visitor.select_purpose', compact('employees', 'visitor'));
    }

    // Step 4: Store Purpose & Employee Selection in Session & Redirect to Capture Image
    public function storePurpose(Request $request, $id)
    {

        $request->validate([
            'purpose' => 'required|string',
            'employee_id' => 'required|exists:employees,id'
        ]);

        session([
            'visit_purpose' => $request->purpose,
            'employee_id' => $request->employee_id,
            'user_id' => $id
        ]);

        VisitorsEmployer::create([
            'visitor_id' => $id,
            'employee_id' => $request->employee_id,
            'purpose' => $request->purpose,
        ]);

        return redirect()->route('visitor.captureImage', ['id' => $id]);
    }

    public function checkPreRegistered(Request $request)
    {
        $visitor = Visitor::where('email', $request->email)->first();

        if ($visitor) {
            return response()->json([
                'success' => true,
                'visitor' => $visitor
            ]);
        } else {
            return response()->json(['success' => false]);
        }
    }

// Show the form for emergency contact details
    public function showEmergencyContactForm($id)
    {
        $visitor = Visitor::findOrFail($id);  // Fetch the visitor by ID
        return view('visitor.emergency_contact', compact('visitor'));
    }

// Store the emergency contact details for the visitor
    public function storeEmergencyContact(Request $request, $id)
    {
        $request->validate([
            'emergency_name' => 'required',
            'emergency_phone' => 'required',
            'emergency_relation' => 'required',
        ]);

        // Find the visitor by ID and store emergency contact details
        $visitor = Visitor::findOrFail($id);
        $visitor->emergency_name = $request->emergency_name;
        $visitor->emergency_phone = $request->emergency_phone;
        $visitor->emergency_relation = $request->emergency_relation;
        $visitor->save();

        // Redirect to a confirmation or other page
        return redirect()->route('visitor.agreement', ['id' => $visitor->id]);
    }

    public function showAgreement($id)
    {
        // Find the visitor by ID
        $visitor = Visitor::findOrFail($id);

        // Pass the visitor data to the view
        return view('visitor.agreement', compact('visitor'));
    }

    public function storeAgreement(Request $request, $id)
    {
        $request->validate([
            'privacy_policy_agreement' => 'accepted', // Ensure the checkbox is checked
        ]);

        // Find the visitor by ID and store the agreement status
        $visitor = Visitor::findOrFail($id);
        $visitor->privacy_policy_agreement = true;
        $visitor->save();

        // Redirect to a confirmation or other page
        return redirect()->route('visitor.success', ['id' => $visitor->id]);
    }

    public function visitor_success($id)
    {
        $visitor = Visitor::findOrFail($id);

        $latestEmployer = $visitor->employers()->latest()->with('employee')->first();

        $employee = $latestEmployer ? $latestEmployer->employee : null;

        $recipientEmails = [$visitor->email];

        if ($employee && $employee->email) {
            $recipientEmails[] = $employee->email;
        }

        $recipientEmails[] = 'hr@gmail.com';

        $toUser = $employee ? $employee->id : null;

        // Send email to visitor, employee, and HR
        foreach ($recipientEmails as $email) {
            if ($email === $visitor->email) {
                Mail::to($email)->send(new VisitorSuccessMail($visitor));
            } elseif ($email === $employee->email) {
                Mail::to($email)->send(new AdminSuccessMail($visitor));
            } else {
                Mail::to($email)->send(new AdminSuccessMail($visitor));
            }
        }

        MailLog::create([
            'to_email' => implode(', ', $recipientEmails), // Store all emails as a comma-separated string
            'subject' => 'Visitor Registration Successful',
            'body' => view('email.visitor_success', compact('visitor'))->render(),
            'user_id' => $visitor->id,
            'to_user' => $toUser,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return view('visitor.visitor_success', compact('visitor'));
    }

    public function admin_list()
    {
        $visitors = Visitor::whereNull('deleted_at')->get();
        return view('visitor.admin_list', compact('visitors'));
    }

    public function admin_preRegister()
    {
        return view('visitor.admin_pre_register');
    }

    public function admin_checkedIn()
    {
        $visitors = Visitor::whereNull('check_out_time')
            ->whereNotNull('check_in_time')
            ->get();
        return view('visitor.admin_checked_in', compact('visitors'));
    }

    public function admin_checkedOut()
    {
        $visitors = Visitor::whereNotNull('check_out_time')->get();
        return view('visitor.admin_checked_out', compact('visitors'));
    }

    public function admin_archive($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->delete(); // Soft delete
        return redirect()->route('visitor.admin_list')->with('success', 'Visitor archived successfully!');
    }

    public function show($id)
    {
        $visitor = Visitor::findOrFail($id);

        $idPhotoPath = $visitor->id_photo ? asset("assets/visitor_photos/{$visitor->id_photo}") : asset('images/default-id.png');
        $photoPath = $visitor->photo ? asset("assets/visitor_photos/{$visitor->photo}") : asset('images/default-user.png');

        return view('visitor.admin_visitor_show', compact('visitor', 'idPhotoPath', 'photoPath'));
    }

    public function archive($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->delete();

        return redirect()->back()->with('success', 'Visitor archived successfully.');
    }

    public function visitors_archive_list()
    {
        $archivedVisitors = Visitor::onlyTrashed()->get();
        return view('visitor.visitors_archived_list', compact('archivedVisitors'));
    }

    public function visitors_restore($id)
    {
        $visitor = Visitor::onlyTrashed()->findOrFail($id);
        $visitor->restore();

        return redirect()->back()->with('success', 'Visitor restored successfully.');
    }
}

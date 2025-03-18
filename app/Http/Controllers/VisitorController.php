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
use App\Models\ScreenSetting;

class VisitorController extends Controller
{

    protected $visibleFields;
    /**
     *
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index',
            'showCheckIn', 'storeCheckIn', 'captureImageView',
            'storeCapturedImage', 'showCheckOut', 'storeCheckOut',
            'showRoleSelection', 'setRole', 'selectPurpose', 'storePurpose',
            'captureIdView', 'storeCapturedIdImage', 'checkPreRegistered',
            'showEmergencyContactForm', 'storeEmergencyContact', 'showAgreement',
            'storeAgreement', 'visitor_success']);

        $this->visibleFields = ScreenSetting::where('is_visible', true)
            ->pluck('is_visible', 'screen_name')
            ->toArray();
    }

    /**
     * @param $fieldName
     * @return false|mixed
     */
    public function checkFieldVisibility($fieldName)
    {
        // Check if the field exists and is visible
        return $this->visibleFields[$fieldName] ?? false;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        return view('visitor.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function showCheckIn()
    {
        $preRegisteredVisitors = Visitor::whereNotNull('check_out_time')->get();
        return view('visitor.checkin', compact('preRegisteredVisitors'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pre_registor_visitor(Request $request)
    {
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_visitor(Request $request)
    {
        $visitor = Visitor::find($request->visitor_id);

        if ($visitor) {
            $visitor->update([
                'full_name' => $validated['full_name'],
                'company' => $validated['company'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'id_type' => $validated['id_type'],
                'identification_number' => $validated['identification_number'],
            ]);

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Visitor not found']);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCheckIn(Request $request)
    {
        try {
            if(isset($request->email)) {
            $visitor = Visitor::where('email', $request->email)
                ->where('pre_register', 1)
                ->first();

            if ($visitor) {
                if(isset($request->full_name)){
                $visitor->full_name = $request->full_name;
                } if(isset($request->company)) {
                    $visitor->company = $request->company;
                } if(isset($request->phone)) {
                    $visitor->phone = $request->phone;
                } if(isset($request->id_type)) {
                    $visitor->id_type = $request->id_type;
                } if(isset($request->identification_number)) {
                    $visitor->identification_number = $request->identification_number;
                }
                $visitor->check_in_time = now();
                $visitor->pre_register = 0;
                $visitor->save();
            } else {
                $visitor = new Visitor();
                if(isset($request->full_name)){
                    $visitor->full_name = $request->full_name;
                } if(isset($request->company)) {
                    $visitor->company = $request->company;
                } if(isset($request->phone)) {
                    $visitor->phone = $request->phone;
                } if(isset($request->id_type)) {
                    $visitor->id_type = $request->id_type;
                } if(isset($request->identification_number)) {
                    $visitor->identification_number = $request->identification_number;
                }
                $visitor->check_in_time = now();
                $visitor->pre_register = 0;
                $visitor->save();
            }
            } else {
                $visitor = new Visitor();
                if(isset($request->full_name)){
                    $visitor->full_name = $request->full_name;
                } if(isset($request->company)) {
                    $visitor->company = $request->company;
                } if(isset($request->phone)) {
                    $visitor->phone = $request->phone;
                } if(isset($request->id_type)) {
                    $visitor->id_type = $request->id_type;
                } if(isset($request->identification_number)) {
                    $visitor->identification_number = $request->identification_number;
                }
                $visitor->check_in_time = now();
                $visitor->pre_register = 0;
                $visitor->save();
            }

            if ($this->checkFieldVisibility('select_role')) {
                return redirect()->route('visitor.selectRole', ['id' => $visitor->id]);
            } elseif ($this->checkFieldVisibility( 'select_purpose')) {
                return redirect()->route('visitor.selectPurpose', ['id' => $visitor->id]);
            } elseif ($this->checkFieldVisibility('capture_image')) {
                return redirect()->route('visitor.captureImage', ['id' => $visitor->id]);
            } elseif ($this->checkFieldVisibility('capture_id')) {
                return redirect()->route('visitor.captureIdView', ['id' => $visitor->id]);
            } elseif ($this->checkFieldVisibility('emergency_contact')) {
                return redirect()->route('visitor.showEmergencyContact', ['id' => $visitor->id]);
            } else {
                return redirect()->route('visitor.agreement', ['id' => $visitor->id]);
            }


        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withInput()->withErrors(['email' => 'There is some issue Please try Again later.']);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCheckOut(Request $request)
    {
        // Validate check-out request
        $request->validate([
            'visitor_id' => 'required|exists:visitors,id',
        ]);

        // Find the visitor and mark them as checked out
        $visitor = Visitor::find($request->visitor_id);

        if ($visitor->check_out_time) {
            return response()->json(['success' => false, 'message' => 'You have already checked out.'], 400);
        }

        $visitor->update(['check_out_time' => Carbon::now()]);

        // Return JSON response for AJAX success handling
        return response()->json(['success' => true, 'message' => 'Check-out successful!']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function showCheckOut()
    {
        // Get visitors who haven't checked out yet
        $visitors = Visitor::whereNull('check_out_time')->get();
        return view('visitor.checkout', compact('visitors'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json([]);
        }

        $visitors = Visitor::where('full_name', 'LIKE', "%{$query}%")
            ->whereNull('check_out_time') // Only visitors who haven't checked out
            ->whereDate('created_at', Carbon::today()) // Only visitors from today
            ->select('id', 'full_name')
            ->limit(10)
            ->get();

        return response()->json($visitors);
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function showPreRegistrationForm()
    {
        return view('visitor.pre-register');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePreRegistration(Request $request)
    {
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function captureImageView($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visibleFields = $this->visibleFields;
        return view('visitor.capture_image', compact('visitor', 'visibleFields'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function captureIdView($id)
    {
        $visibleFields = $this->visibleFields;
        $visitor = Visitor::findOrFail($id);
        return view('visitor.capture_id_image', compact('visitor', 'visibleFields'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function showRoleSelection($id)
    {
        $visitor = Visitor::findOrFail($id);
        return view('visitor.select_role', compact('visitor'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setRole(Request $request, $id)
    {
        $visitor = Visitor::findOrFail($id);
        $request->validate([
            'role' => 'required|in:visitor,client,interviewer',
        ]);
        $visitor->role = $request->role;
        $visitor->save();

        session(['visitor_role' => $request->role]);

        if ($this->checkFieldVisibility( 'select_purpose')) {
            return redirect()->route('visitor.selectPurpose', ['id' => $id]);
        } elseif ($this->checkFieldVisibility('capture_image')) {
            return redirect()->route('visitor.captureImage', ['id' => $id]);
        } elseif ($this->checkFieldVisibility('capture_id')) {
            return redirect()->route('visitor.captureIdView', ['id' => $id]);
        } elseif ($this->checkFieldVisibility('emergency_contact')) {
            return redirect()->route('visitor.showEmergencyContact', ['id' => $id]);
        } else {
            return redirect()->route('visitor.agreement', ['id' => $id]);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function selectPurpose($id)
    {
        $visitor = Visitor::findOrFail($id);
        $employees = Employee::all();
        return view('visitor.select_purpose', compact('employees', 'visitor'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePurpose(Request $request, $id)
    {
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

        if ($this->checkFieldVisibility('capture_image')) {
            return redirect()->route('visitor.captureImage', ['id' => $id]);
        } elseif ($this->checkFieldVisibility('capture_id')) {
            return redirect()->route('visitor.captureIdView', ['id' => $id]);
        } elseif ($this->checkFieldVisibility('emergency_contact')) {
            return redirect()->route('visitor.showEmergencyContact', ['id' => $id]);
        } else {
            return redirect()->route('visitor.agreement', ['id' => $id]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function showEmergencyContactForm($id)
    {
        $visitor = Visitor::findOrFail($id);  // Fetch the visitor by ID
        return view('visitor.emergency_contact', compact('visitor'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeEmergencyContact(Request $request, $id)
    {
        // Find the visitor by ID and store emergency contact details
        $visitor = Visitor::findOrFail($id);
        $visitor->emergency_name = $request->emergency_name;
        $visitor->emergency_phone = $request->emergency_phone;
        $visitor->emergency_relation = $request->emergency_relation;
        $visitor->save();

        // Redirect to a confirmation or other page
        return redirect()->route('visitor.agreement', ['id' => $visitor->id]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function showAgreement($id)
    {
        // Find the visitor by ID
        $visitor = Visitor::findOrFail($id);

        // Pass the visitor data to the view
        return view('visitor.agreement', compact('visitor'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     * @throws \Throwable
     */
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
//        foreach ($recipientEmails as $email) {
//            if ($email === $visitor->email) {
//                Mail::to($email)->send(new VisitorSuccessMail($visitor));
//            } elseif ($email === $employee->email) {
//                Mail::to($email)->send(new AdminSuccessMail($visitor));
//            } else {
//                Mail::to($email)->send(new AdminSuccessMail($visitor));
//            }
//        }

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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function admin_list()
    {
        $visitors = Visitor::whereNull('deleted_at')->get();
        return view('visitor.admin_list', compact('visitors'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function admin_preRegister()
    {
        return view('visitor.admin_pre_register');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function admin_checkedIn()
    {
        $visitors = Visitor::whereNull('check_out_time')
            ->whereNotNull('check_in_time')
            ->get();
        return view('visitor.admin_checked_in', compact('visitors'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function admin_checkedOut()
    {
        $visitors = Visitor::whereNotNull('check_out_time')->get();
        return view('visitor.admin_checked_out', compact('visitors'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function admin_archive($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->delete(); // Soft delete
        return redirect()->route('visitor.admin_list')->with('success', 'Visitor archived successfully!');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function show($id)
    {
        $visitor = Visitor::findOrFail($id);

        $idPhotoPath = $visitor->id_photo ? asset("assets/visitor_photos/{$visitor->id_photo}") : asset('images/default-id.png');
        $photoPath = $visitor->photo ? asset("assets/visitor_photos/{$visitor->photo}") : asset('images/default-user.png');

        return view('visitor.admin_visitor_show', compact('visitor', 'idPhotoPath', 'photoPath'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function archive($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->delete();

        return redirect()->back()->with('success', 'Visitor archived successfully.');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function visitors_archive_list()
    {
        $archivedVisitors = Visitor::onlyTrashed()->get();
        return view('visitor.visitors_archived_list', compact('archivedVisitors'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function visitors_restore($id)
    {
        $visitor = Visitor::onlyTrashed()->findOrFail($id);
        $visitor->restore();

        return redirect()->back()->with('success', 'Visitor restored successfully.');
    }
}

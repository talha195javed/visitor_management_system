<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * @return Factory|View|Application|\Illuminate\View\View
     */

    public function index()
    {
        $currentUser = auth()->user();

        $userType = $currentUser->role;

        if ($userType === 'superAdmin') {
            $employees = Employee::whereNull('deleted_at')
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $employees = Employee::whereNull('deleted_at')
                ->where('client_id', $currentUser->id)
                ->orderBy('id', 'desc')
                ->get();
        }
        return view('employees.index', compact('employees'));
    }

    /**
     * @return Factory|View|Application|\Illuminate\View\View
     */

    public function employers_list()
    {
        $currentUser = auth()->user();

        $userType = $currentUser->role;

        if ($userType === 'superAdmin') {
            $employees = Employee::whereNull('deleted_at')->get();
        } else {
            $employees = Employee::whereNull('deleted_at')
            ->where('client_id', $currentUser->id)
                ->orderBy('id', 'desc')
                ->get();
        }
        return view('employees.employers_list', compact('employees'));
    }

    /**
     * @return Factory|View|Application|\Illuminate\View\View
     */
    public function employers_archive_list()
    {
        $currentUser = auth()->user();

        $userType = $currentUser->role;

        if ($userType === 'superAdmin') {
            $archivedEmployees = Employee::onlyTrashed()->get();
        } else {
            $archivedEmployees = Employee::onlyTrashed()
                ->where('client_id', $currentUser->id)
                ->orderBy('id', 'desc')
                ->get();
        }
        return view('employees.employers_archived_list', compact('archivedEmployees'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function employers_restore($id)
    {
        $employee = Employee::onlyTrashed()->findOrFail($id);
        $employee->restore();

        return redirect()->back()->with('success', 'Employee restored successfully.');
    }

    /**
     * @return Factory|View|Application|\Illuminate\View\View
     */
    public function create_employee()
    {
        return view('employees.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register_employee(Request $request)
    {
        $currentUser = auth()->user();

        $userId = $currentUser->id;

        $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'contact_number' => 'required|string|max:15',
        ]);

        $employee = new Employee();
        $employee->name = $request->name;
        $employee->company = $request->company;
        $employee->email = $request->email;
        $employee->contact_number = $request->contact_number;
        $employee->position = $request->position;
        $employee->client_id = $userId;
        $employee->save();

        return response()->json(['success' => true]);
    }

    /**
     * @param $id
     * @return Factory|View|Application|\Illuminate\View\View
     */
    public function employee_show($id)
    {
        $employee = Employee::findOrFail($id);
        return view('employees.employee_show', compact('employee'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_employee(Request $request)
    {
        {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'company' => 'required|string|max:255',
                'email' => 'required|email',
                'contact_number' => 'required|string|max:15',
                'position' => 'required|string',
            ]);

            $employee = Employee::find($request->employee_id);

            if ($employee) {
                $employee->update([
                    'name' => $validated['name'],
                    'company' => $validated['company'],
                    'email' => $validated['email'],
                    'contact_number' => $validated['contact_number'],
                    'position' => $validated['position'],
                    'client_id' => $validated['client_id'],
                ]);
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Employee not found']);
            }
        }
    }

    /**
     * @param Request $request
     * @param Employee $employee
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'contact_number' => 'required|string|max:15',
        ]);

        $employee->update($request->all());

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully');
    }

    /**
     * @param Employee $employee
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function archive($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete(); // Soft delete the employee

        return redirect()->back()->with('success', 'Employee archived successfully.');
    }
}

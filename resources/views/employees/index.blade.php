@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Company Employees</h2>
    <a href="{{ route('employees.create') }}" class="btn btn-primary">Add Employee</a>
    <table class="table mt-3">
        <thead>
        <tr>
            <th>Name</th>
            <th>Company</th>
            <th>Position</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($employees as $employee)
        <tr>
            <td>{{ $employee->name }}</td>
            <td>{{ $employee->company }}</td>
            <td>{{ $employee->position }}</td>
            <td>{{ $employee->email }}</td>
            <td>{{ $employee->contact_number }}</td>
            <td>
                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('employees.destroy', $employee) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection

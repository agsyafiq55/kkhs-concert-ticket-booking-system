<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role Test</title>
</head>
<body>
    <h1>Role Test</h1>
    
    <h2>User Information</h2>
    <p>Name: {{ Auth::user()->name }}</p>
    <p>Email: {{ Auth::user()->email }}</p>
    
    <h2>Roles</h2>
    <ul>
        @foreach(Auth::user()->roles as $role)
            <li>{{ $role->name }}</li>
        @endforeach
    </ul>
    
    <h2>Role Directive Tests</h2>
    
    <div>
        <h3>Admin Test</h3>
        <p>Should show if you have admin role:</p>
        @role('admin')
            <div style="background-color: green; color: white; padding: 10px;">
                You have the admin role!
            </div>
        @else
            <div style="background-color: red; color: white; padding: 10px;">
                You do NOT have the admin role!
            </div>
        @endrole
    </div>
    
    <div>
        <h3>Teacher Test</h3>
        <p>Should show if you have teacher role:</p>
        @role('teacher')
            <div style="background-color: green; color: white; padding: 10px;">
                You have the teacher role!
            </div>
        @else
            <div style="background-color: red; color: white; padding: 10px;">
                You do NOT have the teacher role!
            </div>
        @endrole
    </div>
    
    <div>
        <h3>Student Test</h3>
        <p>Should show if you have student role:</p>
        @role('student')
            <div style="background-color: green; color: white; padding: 10px;">
                You have the student role!
            </div>
        @else
            <div style="background-color: red; color: white; padding: 10px;">
                You do NOT have the student role!
            </div>
        @endrole
    </div>
</body>
</html> 
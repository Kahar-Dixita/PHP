<x-layout>

<div class="container mt-5">

    <h2 class="mb-4">Student Registration</h2>

    {{-- Error Messages --}}
    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Success Message --}}
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('register.post') }}" method="POST">
        @csrf

        <label>Name:</label>
        <input type="text" name="name" value="{{ old('name') }}" class="form-control mb-3">

        <label>Email:</label>
        <input type="email" name="email" value="{{ old('email') }}" class="form-control mb-3">

        <label>Password:</label>
        <input type="password" name="password" class="form-control mb-3">

        <label>Age:</label>
        <input type="number" name="age" value="{{ old('age') }}" class="form-control mb-3">
        
           <label>Role:</label>
        <select name="role" class="form-control mb-3">
            <option value="">Select Role</option>
            <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
        </select>

        <button type="submit" class="btn btn-primary">Register</button>
        <a href="{{ route('login') }}" class="ms-3">Already have an account? Login</a>
    </form>

</div>

</x-layout>

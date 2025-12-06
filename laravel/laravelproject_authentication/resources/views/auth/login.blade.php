<x-layout>

<div class="container mt-5">

    <h2 class="mb-4">Login</h2>

    {{-- Error Message --}}
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf

        <label>Email:</label>
        <input type="email" name="email" class="form-control mb-3">

        <label>Password:</label>
        <input type="password" name="password" class="form-control mb-3">

        <button type="submit" class="btn btn-primary">Login</button>
        <a href="{{ route('register') }}" class="ms-3">Create Account</a>
    </form>

</div>

</x-layout>

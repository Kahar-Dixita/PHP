<x-layout>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white text-center">
                <h5>Login</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ url('/login') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>

                 <p class="mt-3 text-center">
                    Don’t have an account?
                    <a href="{{ route('register') }}">Register</a>
                </p>
            </div>
        </div>
    </div>
</div>
</x-layout>
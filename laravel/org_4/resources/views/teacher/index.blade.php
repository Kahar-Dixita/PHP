<x-layout>
    <div class="container mt-5">

        {{-- Success message --}}
        @if(session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        <h1 class="text-center mb-5">My Courses</h1>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div></div>
            <a class="btn btn-outline-success" href="{{ route('teacher.create') }}">
                + Create New Course
            </a>
        </div>

        @if($courses->isEmpty())
            <div class="alert alert-info text-center">You have not created any courses yet.</div>
        @else
            <table class="table table-bordered text-center shadow-sm" style="width: 85%; margin: auto;">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Teacher ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $course)
                        <tr>
                            <td>{{ $course->id }}</td>
                            <td>{{ $course->teacher_id }}</td>
                            <td>{{ $course->title }}</td>
                            <td>{{ Str::limit($course->description, 60, '...') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('teacher.edit', $course->id) }}" 
                                       class="btn btn-outline-warning btn-sm">Update</a>

                                    <form action="{{ route('teacher.destroy', $course->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Are you sure you want to delete this course?')"
                                                class="btn btn-outline-danger btn-sm">
                                            Delete
                                        </button>
                                    </form>

                                    <a href="{{ route('teacher.students', $course->id) }}" 
                                       class="btn btn-outline-info btn-sm">Students</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    </div>
</x-layout>

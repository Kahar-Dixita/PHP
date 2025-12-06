 <!-- <x-layout>
 <div class="row">
        @foreach($courses as $course)
            <div class="col-md-4">
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <h5>{{ $course->title }}</h5>
                        <p>{{ $course->description }}</p>
                        <p><strong>Duration:</strong> {{ $course->duration }}</p>

                        <form action="{{ route('student.unenroll', $course->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-danger w-100">Unenroll</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-layout> -->
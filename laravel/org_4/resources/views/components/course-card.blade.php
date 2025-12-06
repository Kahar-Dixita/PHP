<div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5>{{ $course->title }}</h5>
                        <p><strong>Teacher:</strong> {{ $course->teacher->name }}</p>
                        <p>{{ $course->description }}</p>
                        <p><strong>Duration:</strong> {{ $course->duration }}</p>

                        @if(Auth::check() && Auth::user()->role === 'student')
                            @php
                                $isEnrolled = \App\Models\Enrollment::where('student_id', Auth::id())
                                                ->where('course_id', $course->id)
                                                ->exists();
                            @endphp

                            @if($isEnrolled)
                                {{-- ✅ Show Unenroll Button --}}
                                <form action="{{ route('student.unenroll', $course->id) }}" method="POST">
                                     @csrf
                                        <button type="submit" class="btn btn-danger w-100"
                                                     onclick="return confirm('Are you sure you want to unenroll from this course?')">
                                         Unenroll</button>
                                </form>
                            @else
                              {{-- Enroll Button --}}
                                <form action="{{ route('student.enroll', $course->id) }}" method="POST">
                                  @csrf
                                 <button type="submit" class="btn btn-primary w-100">Enroll</button>
                                </form>
                            @endif
                        @endif
                    </div>
</div>
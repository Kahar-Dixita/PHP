<x-layout>
<h4>Available Courses</h4>

<div class="row">
    @foreach($courses as $course)
        <div class="col-md-4">
            <x-course-card :course="$course" isStudent="true" />
        </div>
    @endforeach
</div>
</x-layout>

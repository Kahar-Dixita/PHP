<x-layout>
<h4>Edit Course</h4>

<form action="{{ route('teacher.update', $course->id) }}" method="POST" class="mt-3">
    @csrf @method('PUT')
    <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control" value="{{ $course->title }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ $course->description }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Duration</label>
        <input type="text" name="duration" class="form-control" value="{{ $course->duration }}" required>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('teacher.index') }}" class="btn btn-secondary">Cancel</a>
</form>
</x-layout>

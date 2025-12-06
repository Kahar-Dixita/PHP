<x-layout>
<h4>Add New Course</h4>

<form action="{{ route('teacher.store') }}" method="POST" class="mt-3">
    @csrf
    <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3"></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Duration</label>
        <input type="text" name="duration" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success">Create</button>
    <a href="{{ route('teacher.index') }}" class="btn btn-secondary">Back</a>
</form>

</x-layout>
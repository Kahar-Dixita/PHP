<x-layout>
    <div class="container mt-5">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{route('books.update',['id'=>$book->id])}}" method="post">
            @csrf
            @method('PUT')

          <label> name:</label>
    <input type="text" name="name" value="{{$book->name}}">

    <label> email:</label>
    <input type="email" name="email" value="{{$book->email}}">

    <label> author:</label>
    <input type="text" name="author" value="{{$book->author}}">

    <label> phone:</label>
    <input type="Number" name="phone" value="{{$book->phone}}">

    <label> year:</label>
    <input type="Number" name="year" value="{{$book->year}}">

    <button type="submit" class="btn btn-primary">Submit</button>
    <a href="{{route('books.index')}}">Back</a>
        </form>

    </div>
</x-layout>

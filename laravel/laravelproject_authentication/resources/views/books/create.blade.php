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
</div>

<form action="{{route('books.store')}}" method="post">
    @csrf
    <label> name:</label>
    <input type="text" name="name" value="{{old('name')}}">

    <label> email:</label>
    <input type="email" name="email" value="{{old('email')}}">

    <label> author:</label>
    <input type="text" name="author" value="{{old('author')}}">

    <label> phone:</label>
    <input type="Number" name="phone" value="{{old('phone')}}">

    <label> year:</label>
    <input type="Number" name="year" value="{{old('year')}}">

    <button type="submit" class="btn btn-primary">Submit</button>
    <a href="{{route('books.index')}}">Back</a>

</form>

</x-layout>

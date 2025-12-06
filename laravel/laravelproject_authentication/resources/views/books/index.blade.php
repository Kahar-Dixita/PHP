<x-layout>
    @if(session('success'))
    <div class="alert alert-success">
        {{session('success')}}
    </div>
    @endif

    <h1>Book List</h1>
    <div>
        <a href="{{route('books.create')}}">Create</a>
    </div>

    <table>
        <tr>
            <th>NAME</th>
            <th>EMAIL</th>
            <th>AUTHOR</th>
            <th>PHONE</th>
            <th>YEAR</th>
            <th>ACTION</th>
        </tr>

        @foreach($books as $book)
        <tr>
        <td>{{$book->name}}</td>
        <td>{{$book->email}}</td>
        <td>{{$book->author}}</td>
        <td>{{$book->phone}}</td>
        <td>{{$book->year}}</td>
        <td>
            <a href="{{route('books.edit',['id'=>$book->id])}}">Update</a>
            <form action="{{route('books.destroy',['id'=>$book->id])}}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('are you sure to delete')">DELETE</button>
            </form>
        </td>
        </tr>
        @endforeach
    </table>

</x-layout>
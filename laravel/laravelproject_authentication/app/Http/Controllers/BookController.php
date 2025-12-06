<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{

     public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::all();
        return view('books.index',compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|min:3|max:8',
            'email'=>'required|email',
            'author'=>'required|max:6',
            'phone'=>'required|digits:10|integer',
            'year'=>'required|digits:4|integer',
        ]);

        Book::create($request->all());
        return redirect()->route('books.index')->with('success','created successfully');
        }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return view('books.edit',compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       $request->validate([
            'name'=>'required|min:3|max:8',
            'email'=>'required|email',
            'author'=>'required|max:6',
            'phone'=>'required|digits:10|integer',
            'year'=>'required|digits:4|integer',
        ]);

       $book = Book::findOrFail($id);
       $book->update($request->all());
       return redirect()->route('books.index')->with('success','updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        return redirect()->route('books.index')->With('success','deleted successfully');
    }
}

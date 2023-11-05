<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Movie; /* import model movie */

class MovieController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get movie
        $movie = Movie::latest()->paginate(5);
        //render view with posts
        return view('movie.index', compact('movie'));
    }
    /**
     * create
     *
     * @return void
     */
    public function create()
    {
        return view('movie.create');
    }
    /**
     * store
     *@param mixed $request
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        //Validasi Formulir
        $this->validate(
            $request,
            [
                'title' => 'required',
                'director' => 'required',
                'duration' => 'required',
                'image' => 'required|image|mimes:jpg,png,jpeg',
            ],
            [
                'image.required' => 'file hanya boleh berupa gambar',
            ]
        );
        $image = $request->file('image');
        $image->move(public_path('images'), $image->getClientOriginalName());
        $request->image = $image->getClientOriginalName();

        Movie::create([
            'title' => $request->title,
            'director' => $request->director,
            'duration' => $request->duration,
            'image' => $request->image
        ]);
        try {
            return redirect()->route('movie.index');
        } catch (Exception $e) {
            return redirect()->route('movie.index');
        }
    }
    /**
     * edit
     *
     * @param int $id
     * @return void
     */
    public function edit($id)
    {
        $movie = Movie::find($id);
        return view('movie.edit', compact('movie'));
    }
    /**
     * update
     *
     * @param mixed $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $movie = Movie::find($id);
        //validate form
        $this->validate(
            $request,
            [
                'title' => 'required',
                'director' => 'required',
                'duration' => 'required',
                'image' => 'image|mimes:jpg,png,jpeg',
            ],
            [
                'image.required' => 'file hanya boleh berupa gambar',
            ]
        );
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->move(public_path('images'), $image->getClientOriginalName());
            $request->image = $image->getClientOriginalName();
            $movie_all = Movie::where('image', $movie->image)->get();
            if (count($movie_all) == 1) {
                unlink(public_path('images/' . $movie->image));
            }
        } else {
            $request->image = $request->old_image;
        }
        // update data
        $movie->update([
            'title' => $request->title,
            'director' => $request->director,
            'duration' => $request->duration,
            'image' => $request->image
        ]);
        return redirect()->route('movie.index')->with(['success' => 'Data Berhasil Diubah!']);
    }
    /**
     * destroy
     *
     * @param int $id
     * @return void
     */

    public function destroy($id)
    {
        $movie = Movie::find($id);
        $movie_all = Movie::where('image', $movie->image)->get();
        if (count($movie_all) == 1) {
            unlink(public_path('images/' . $movie->image));
            $movie->delete();
        } else {
            $movie->delete();
        }

        return redirect()->route('movie.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}

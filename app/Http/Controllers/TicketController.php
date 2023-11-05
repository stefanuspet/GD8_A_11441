<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Movie;
use Illuminate\Http\Request;
use Exception;

class TicketController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get ticket
        $ticket = Ticket::latest()->paginate(5);
        //render view with posts
        return view('ticket.index', compact('ticket'));
    }

    public function create()
    {
        $movie = Movie::all();
        return view('ticket.create', compact('movie'));
    }
    /**
     * store
     *
     * @param Request $request
     * @return void
     */

    public function store(Request $request)
    {
        //Validasi Formulir
        $this->validate(
            $request,
            [
                'id_movie' => 'required',
                'class' => 'required',
                'price' => 'required',
            ],
            [
                'id_movie.required' => 'Harus pilih salah satu movie !',
                'class.required' => 'Class tidak boleh kosong !',
                'price.required' => 'Price tidak boleh kosong !',
            ]
        );
        //Fungsi Simpan Data ke dalam Database
        Ticket::create([
            'id_movie' => $request->id_movie,
            'class' => $request->class,
            'price' => $request->price,
        ]);
        try {
            return redirect()->route('ticket.index');
        } catch (Exception $e) {
            return redirect()->route('ticket.index');
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
        //get ticket by ID
        $ticket = Ticket::findOrFail($id);

        $movie = Movie::all();
        //render view with post data
        return view('ticket.edit', compact('ticket', 'movie'));
    }
    /**
     * update
     *
     * @param Request $request
     * @param mixed $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        //Validasi Formulir
        $this->validate(
            $request,
            [
                'id_movie' => 'required',
                'class' => 'required',
                'price' => 'required',
            ],
            [
                'id_movie.required' => 'Harus pilih salah satu movie !',
                'class.required' => 'Class tidak boleh kosong !',
                'price.required' => 'Price tidak boleh kosong !',
            ]
        );
        //Fungsi Simpan Data ke dalam Database
        Ticket::find($id)->update([
            'id_movie' => $request->id_movie,
            'class' => $request->class,
            'price' => $request->price,
        ]);
        try {
            return redirect()->route('ticket.index');
        } catch (Exception $e) {
            return redirect()->route('ticket.index');
        }
    }
    /**
     * destroy
     *
     * @param mixed $id
     * @return void
     */
    public function destroy($id)
    {
        //get ticket by ID
        $ticket = Ticket::findOrFail($id);
        //delete ticket
        $ticket->delete();
        try {
            return redirect()->route('ticket.index');
        } catch (Exception $e) {
            return redirect()->route('ticket.index');
        }
    }
}

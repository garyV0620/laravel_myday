<?php

namespace App\Http\Controllers;

use App\Models\Myday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class MydayController extends Controller
{
    public function visitOther(Request $request)
    {
        $mydays = Myday::with('user')->where('user_id', $request->id)->latest()->get();
        // dd($mydays);
        //test git 
        //second direct push
        //3rd commit
        return view('mydays.visit-other', [
            'mydays' => $mydays,
        ]);
    }
    public function ajaxMyday()
    {   
        $additionalMydays = Myday::with('user')->latest()->get();
       
        return response()->json($additionalMydays);
       
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mydays = Myday::with('user')->latest()->get();
        // $lastId = Myday::latest('id')->first()->id;
         
        return view('mydays.index', [
                'mydays' => $mydays,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255'
        ]);

        $request->user()->mydays()->create($validated);
        return redirect(route('mydays.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Myday  $myday
     * @return \Illuminate\Http\Response
     */
    public function show(Myday $myday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Myday  $myday
     * @return \Illuminate\Http\Response
     */
    public function edit(Myday $myday)
    {
        $this->authorize('update', $myday);

        return view( 'mydays.edit', [
            'myday' => $myday,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Myday  $myday
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Myday $myday)
    {
        $this->authorize('update', $myday);

        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $myday->update($validated);

        return redirect(route('mydays.index'));
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Myday  $myday
     * @return \Illuminate\Http\Response
     */
    public function destroy(Myday $myday)
    {
        $this->authorize('delete', $myday);

        $myday->delete();

        $previousRouteName = Route::getRoutes()->match(app('request')->create(Session::get('_previous')['url']))->getName();
        
        if($previousRouteName == 'profile.edit'){
            return redirect(route('profile.edit'));
        }

        return redirect(route('mydays.index'));
    }
}

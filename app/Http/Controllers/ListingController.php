<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    //
    public function index()
    {
        // dd(request());
        return view('listings.index', [
            'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(6),
        ]);
    }

    public function create()
    {
        return view('listings.create');
    }

    public function show(Listing $listing)
    {

        return view('listings.show', [
            'listing' => $listing,
        ]);
    }

    public function store(Request $request)
    {
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', 'unique:listings'],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email', 'unique:listings'],
            'tags' => 'required',
            'description' => 'required',

        ]);

        Listing::create($formFields);
        return redirect('/')->with('message', 'Listing created');

    }

}

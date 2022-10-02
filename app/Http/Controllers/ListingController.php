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
            'logo' => 'required',
            'description' => 'required',

        ]);
        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }
        $formFields['user_id'] = auth()->id();

        Listing::create($formFields);
        return redirect('/')->with('message', 'Listing created');

    }

    public function edit(Listing $listing)
    {
        return view('listings.edit', [
            'listing' => $listing,
        ]);
    }

    public function update(Request $request, Listing $listing)
    {
        if ($listing->user_id != auth()->id()) {
            abort(403);
        }

        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required'],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            // 'logo' => 'required',
            'description' => 'required',

        ]);
        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }
        $listing->update($formFields);
        return back()->with('message', 'Listing updated');

    }

    public function manage()
    {
        return view('listings.manage', ['listings' => auth()->user()->listings()->get()]);
    }

    public function destroy(Listing $listing)
    {
        if ($listing->user_id != auth()->id()) {
            abort(403);
        }
        $listing->delete();

        return redirect('/')->with('message', 'Listing deleted successfully');
    }
}

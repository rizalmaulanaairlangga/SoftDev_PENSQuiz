<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Folder;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required', 
                'string', 
                'max:255',
                \Illuminate\Validation\Rule::unique('folders')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                }),
            ],
        ], ['name.unique' => 'A folder with this name already exists.']);

        Folder::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
        ]);

        return back()->with('success', 'Folder created successfully.');
    }

    public function update(Request $request, Folder $folder)
    {
        if ($folder->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => [
                'required', 
                'string', 
                'max:255',
                \Illuminate\Validation\Rule::unique('folders')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                })->ignore($folder->id_folder),
            ],
        ], ['name.unique' => 'A folder with this name already exists.']);

        $folder->update($validated);

        return back()->with('success', 'Folder updated successfully.');
    }

    public function destroy(Folder $folder)
    {
        if ($folder->user_id !== Auth::id()) {
            abort(403);
        }

        $folder->delete();

        return back()->with('success', 'Folder deleted successfully.');
    }
}

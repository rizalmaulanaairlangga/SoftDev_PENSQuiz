<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Folder;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    public function show(Request $request, Folder $folder)
    {
        if ($folder->user_id !== Auth::id()) {
            abort(403);
        }

        $visibility = $request->input('visibility', 'all');
        $sort = $request->input('sort', 'latest');
        $search = $request->input('search');
        
        $query = \App\Models\MyQuiz::withCount('questions')
            ->where('author_id', Auth::id())
            ->where('folder_id', $folder->id_folder)
            ->with(['course', 'major', 'tags']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhereHas('major', function ($q2) use ($search) {
                      $q2->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('course', function ($q2) use ($search) {
                      $q2->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('tags', function ($q2) use ($search) {
                      $q2->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($visibility !== 'all') {
            $query->where('visibility', $visibility);
        }

        if ($sort === 'latest') {
            $query->orderByDesc('updated_at');
        } elseif ($sort === 'oldest') {
            $query->orderBy('updated_at', 'asc');
        }

        $perPage = $request->input('per_page', 10);
        $quizzes = $query->paginate($perPage)->appends($request->query());
        
        $folder->loadCount('quizzes');

        $availableQuizzes = \App\Models\MyQuiz::withCount('questions')
            ->where('author_id', Auth::id())
            ->where(function($q) use ($folder) {
                $q->where('folder_id', '!=', $folder->id_folder)
                  ->orWhereNull('folder_id');
            })->orderByDesc('updated_at')->get();

        return view('pages.quiz.folder', compact('folder', 'quizzes', 'visibility', 'sort', 'search', 'availableQuizzes'));
    }

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
                })->ignore($folder->id_folder, 'id_folder'),
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

    public function addQuiz(Request $request, Folder $folder)
    {
        if ($folder->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate(['quiz_id' => 'required|exists:quizzes,id_quiz']);
        
        $quiz = \App\Models\MyQuiz::findOrFail($request->quiz_id);
        
        if ($quiz->author_id !== Auth::id()) {
            abort(403);
        }
        
        $quiz->update(['folder_id' => $folder->id_folder]);
        
        return back()->with('success', 'Quiz added to folder successfully.');
    }
}

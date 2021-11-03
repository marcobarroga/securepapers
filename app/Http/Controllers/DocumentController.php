<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use DataTables;

class DocumentController extends Controller
{
    //
    public function registerDocument(){
        return view('documents.register');

    }
    public function storeDocument(Request $request){
        $rules = [
            'file_description'=>'required',
            'pdf_file'=>'required|mimes:pdf|max:10000'
        ];
        $niceNames = [
            'file_description'=>'File Description',
            'pdf_file'=>'required|mimes:pdf|max:10000'
        ];

        $validator = Validator::make($request->all(),$rules,[],$niceNames);
        if(!$validator->fails()){
            // register file
            if($request->hasFile('pdf_file')){
                $file = $request->file('pdf_file');
                $path = $file->store('files');
            }
            $newDocument = new Document();
            $newDocument->file_description = $request->input('file_description');
            $newDocument->file_name = $path;
            $newDocument->user_id = Auth::user()->id;
            $newDocument->save();

            return redirect()->back()->with('success','Document Registered');
        }else{
            return redirect()->back()->withInput()->withErrors($validator->errors());

        }
    }

    public function userDocs(){
        return view('documents.userdocs');
    }

    public function listUserDocs(){
        $userDocs = Document::where('user_id',Auth::user()->id)->get();
        return DataTables::of($userDocs)
            ->only(['file_description','action'])
            ->addIndexColumn()
            ->addColumn('action', function($doc){
                $id = $doc->id;
                $route = route('user.manage.document',['document'=>$id]);
                return "<a class='btn btn-primary' href='$route' role='button'>Manage</a>";
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function manageDocument(Document $document){
        if($document->user_id != Auth::user()->id){
            abort(403);
        }
        return view('documents.manage',['document'=>$document]);
    }

    public function displayDocument(Document $document){
        $pathToFile = $document->file_name;
        return response()->file(storage_path('app'.DIRECTORY_SEPARATOR.($pathToFile)));
    }
}

<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use Yajra\DataTables\Contracts\DataTable;

class ContactAjaxController extends Controller
{

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Contact::all();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editContact">Edit</a>';

                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteContact">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('contact');
    }

    public function store(Request $request){
        Contact::updateOrCreate(['id'=>$request->contact_id],
            ['name'=>$request->name,'email'=>$request->email,'phone'=>$request->phone]);
        return response()->json(['success'=>'Contact Added Successfully']);
    }
}

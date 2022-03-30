<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $employees = Employee::get();
        if ($request->ajax()) {
            $allData = DataTables::of($employees)            
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a herf="javascript:void(0)" data-toggle="tooltip" data-id=" ' . $row->id . ' " data-original-title="Edit" 
                class="edit btn btn-success btn-sm editEmployee text-light">Edit</a>';

                    $btn .= '<a herf="javascript:void(0)" data-toggle="tooltip" data-id=" ' . $row->id . ' " data-original-title="Delete" 
                class="delete btn btn-danger btn-sm deleteEmployee text-light ml-2">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);

            return $allData;
        }
        return view('home', compact('employees'));
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
        Employee::updateOrCreate(['id'=>$request->employee_id],
        ['name'=>$request->name,
         'email'=>$request->email
        ]
    );
        return response()->json(['success'=>'Employee added successfully...']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $epmolyees = Employee::find($id);
        return response()->json($epmolyees);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Employee::find($id)->delete();
        return response()->json(['success'=>'Employee deleted successfully...']);
    }
}

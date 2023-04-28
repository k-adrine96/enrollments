<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Enrollment::with(['user' => function ($query) {
            $query->select('id','name');
        }])
        ->with(['course' => function ($query) {
            $query->select('id','title');
        }])
        ->latest()->limit(20)->get();

        $dataArray = $data->toArray();
        $records = $this->reformatingData($dataArray);

        return view('home', ['records' => json_encode($records)]);
    }

    public function search(Request $request)
    {
        $searchParams = $request->all();
        $query = Enrollment::with(['user' => function ($query) {
            $query->select('id','name');
        }])
        ->with(['course' => function ($query) {
            $query->select('id','title');
        }])->whereHas('user', function ($query) use($searchParams) {
            return $query->where('name', 'like', '%' . $searchParams['name'] .'%');
        })
        ->whereHas('course', function ($query) use($searchParams) {
            return $query->where('title', 'like', '%' . $searchParams['title'] .'%');
        });

        $dataArray = $query->latest()->get()->toArray();
        $records = $this->reformatingData($dataArray);

        return response()->json([
            'status'      => 'success',
            'enrollments' => json_encode($records),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users   = json_encode(User::select('id','name')->get());
        $courses = json_encode(Course::select('id','title')->get());
        $status  = json_encode([
            ['value' => Enrollment::STATUS['in_progress']],
            ['value' => Enrollment::STATUS['completed']],
        ]);
        
        return view('enrollments.create', compact('courses', 'users', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id'   => 'required|int',
            'course_id' => 'required|int',
            'status'    => 'required|string',
        ]);

        $course = Course::find($validatedData['course_id']);
        $course->users()->syncWithoutDetaching(
            User::find($validatedData['user_id']),
            ['status' => $validatedData['status']
        ]);
   
        return response()->json([
            'status' => 'success',
            'message' => 'Enrollment is successfully saved',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $enrollment =  json_encode(Enrollment::findOrFail($id)->toArray());

        $users   = json_encode(User::select('id','name')->get());
        $courses = json_encode(Course::select('id','title')->get());
        $status  = json_encode([
            ['value' => Enrollment::STATUS['in_progress']],
            ['value' => Enrollment::STATUS['completed']],
        ]);

        return view('enrollments.edit', compact('enrollment', 'courses', 'users', 'status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'user_id'   => 'required|int',
            'course_id' => 'required|int',
            'status'    => 'required|string',
        ]);

        Enrollment::whereId($id)->update($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Enrollment is successfully updated',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Enrollment is successfully deleted',
        ]);
    }

    /**
     * Reformates the resource data to the needed designe.
     *
     */
    public function reformatingData($records)
    {
        $refArray = array();
        foreach ($records as $key => $record) 
        {
            $refArray[$key]['key']            = $records[$key]['id'];
            $refArray[$key]['status']         = $records[$key]['status'];
            $refArray[$key]['joiningdate']    = date("Y-m-d H:i:s", strtotime(str_replace('-','/', $records[$key]['created_at'])));
            $refArray[$key]['username'] = $records[$key]['user'][0]['name'];
            $refArray[$key]['coursename'] = $records[$key]['course'][0]['title'];
            $records[$key]['status'] == 'completed' ?
                $refArray[$key]['complitingdate'] = date("Y-m-d H:i:s", strtotime(str_replace('-','/', $records[$key]['updated_at']))) : "";
        }

        return $refArray;
    }

}

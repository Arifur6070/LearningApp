<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Course;
use Exception;
use Validator;
use DB;
// use Carbon\Carbon;

class CourseController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAllCourses()
    {
        try{
            


            $show_all_courses = DB::table('courses')
            ->join('users', 'courses.teacher_id', '=', 'users.user_id')
            ->get();

            return response()->json(array(
                'status' => true,
                'courses' => $show_all_courses,
            ));
        }
        catch (Exception $e) {
            return response()->json(array(
                'status' => false,
                'status_message' => $e->getMessage(),
            ));
        }
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function displayCourseDetails($id)
    {
        try{
            $specific_course = Course::where('course_id', $id)
                ->first();
            if (!$specific_course) {
                throw new Exception('Course doesnot exist!');
            }

            $display_specific_course = DB::table('courses')
            ->join('course_timing', 'courses.course_id', '=', 'course_timing.course_id')
            ->join('users', 'courses.teacher_id', '=', 'users.user_id')
            ->select('courses.course_name','courses.max_students','courses.student_count','users.email','users.first_name','users.last_name','course_timing.start_time','course_timing.end_time','courses.course_description','courses.course_price','course_timing.day','courses.type')
            ->where('courses.course_id',$id)
            ->get();


            if (!$display_specific_course) {
                throw new Exception('Course details fetching failed!');
            }

            return response()->json(array(
                'status' => true,
                'course' => $display_specific_course,
            ));
        }
        catch (Exception $e) {
            return response()->json(array(
                'status' => false,
                'status_message' => $e->getMessage(),
            ));
        }
    }

    /**
    * Creating a course
    */


    public function createCourse(Request $request)
    {
        try{
            $rules = array(
                'subject_id'             => 'required',
                'teacher_id'             => 'required',
                'course_name'            => 'required',
                'course_description'     => 'required',
                'course_price'           => 'required',
                'student_count'          => 'required',
                'type'                   => 'required',
                'min_students'           => 'required',
                'max_students'           => 'required',
                'start_date'             => 'required',
                'end_date'               => 'required',
                'schedule'               => 'required',
             );
            $validator = Validator::make($request->all(), $rules);
            if (!$validator->passes()) {
                throw new Exception('All fields are required');
            }

          
            $insert_course['subject_id'] = $request->subject_id;
            $insert_course['teacher_id'] = $request->teacher_id;
            $insert_course['course_name'] = $request->course_name;
            $insert_course['course_description'] = $request->course_description;
            $insert_course['course_price'] = $request->course_price;
            $insert_course['student_count'] = $request->student_count;
            $insert_course['type'] = $request->type;
            $insert_course['min_students'] = $request->min_students;
            $insert_course['max_students'] = $request->max_students;
            $insert_course['start_date'] = $request->start_date;
            $insert_course['end_date'] = $request->end_date;

        
            
            $create_course_id = Course::create($insert_course);

            return response()->json(array(
                'status' => true,
                'status_message' => "Course Create Successful!",
                'course' => $create_course_id,
            ));
        }
        catch (Exception $e) {
            return response()->json(array(
                'status' => false,
                'status_message' => $e->getMessage(),
            ));
        }
    }

     /**
     * Searching course by searchKey
     *
     * @param  string  $searchKey
     * @return \Illuminate\Http\Response
     */
    public function displayCourseFromSearchKey($searchKey)
    {
        try{
            // $specific_course = Course::where('course_name', $searchKey)
            //     ->first();
            // if (!$specific_course) {
            //     throw new Exception('Course doesnot exist!');
            // }

            $display_courses_by_searchKey = DB::table('courses')
            ->join('users', 'courses.teacher_id', '=', 'users.user_id')
            ->where('courses.course_name',$searchKey)
            ->get();


            if (!$display_courses_by_searchKey) {
                throw new Exception('Course details fetching failed!');
            }

            return response()->json(array(
                'status' => true,
                'courses' => $display_courses_by_searchKey,
            ));
        }
        catch (Exception $e) {
            return response()->json(array(
                'status' => false,
                'status_message' => $e->getMessage(),
            ));
        }
    }

    /**
     * retrieve all the subject available in the database
     */

    public function getAllSubjects(){

    try{
        $display_subjects = DB::table('subjects')
        ->get();


        if (!$display_subjects) {
            throw new Exception('Subjects fetching failed!');
        }


        return response()->json(array(
            'status' => true,
            'subjects' => $display_subjects,
        ));

    }
    catch (Exception $e) {
        return response()->json(array(
            'status' => false,
            'status_message' => $e->getMessage(),
        ));
    }

}


}

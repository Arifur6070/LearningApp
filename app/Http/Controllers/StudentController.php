<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Course;
use App\Models\User;
use Exception;
use Validator;
use DB;


class StudentController extends Controller
{
    /*
    *Student can get enrolled in a specific course through the following method.
    */
    public function postEnrolledValue(Request $request)
    {

        try{
            $rules = array(
                'course_id'  => 'required',
                'student_id'     => 'required',
            );

            $validator = Validator::make($request->all(), $rules);
            if (!$validator->passes()) {
                throw new Exception('All fields are required');
            }

            $course_id = $request->course_id;


            $max_students = DB::table('courses')
            ->where('course_id',$course_id)
            ->select('max_students')
            ->get();


            $student_count = DB::table('courses')
            ->where('course_id',$course_id)
            ->select('student_count')
            ->get('course_id',$course_id);

            //if maximum student limit gets reached .We will throw an exception.
            if($max_students == $student_count){
                throw new Exception('Maximum student limit reached already!');
            }

            $insert_course_students['course_id'] = $request->course_id;
            $insert_course_students['student_id'] = $request->student_id;

            $enroll_student = DB::table('course_students')->insert($insert_course_students);
            if (!$enroll_student) {
                throw new Exception('student enroll failed!');
            }

            //updating total student count in the db as we already enrolled one student.

            $increment_student_count_column=DB::table('courses')->increment('student_count');

            return response()->json(array(
                'status' => true,
                'status_message' => "Student Enrolled Successful!",
                'teacher' => $enroll_student,
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
     * Student can see their schedule in their dashboard through this method
     */
    public function showEnrolledCourses($id){
        try{

            $show_student = User::where('user_id', $id)
                ->first();

            if (!$show_student) {
                throw new Exception('Student doesnot exist!');
            }



            $enrolled_courses = DB::table('courses')
            ->join('course_students', 'courses.course_id', '=', 'course_students.course_id')
            ->join('users', 'course_students.student_id', '=', 'users.user_id')
            ->join('users as tu', 'courses.teacher_id', '=', 'tu.user_id')
            ->select('courses.course_id','courses.course_name','courses.min_students','courses.type',
                     'courses.min_students','courses.max_students','courses.student_count',
                     'tu.username')
            ->where('users.user_id',$id)
            ->get();

            if (!$enrolled_courses) {
                throw new Exception('courses fetching got failed');
            }


            return response()->json(array(
                'status' => true,
                'enrolledCourses' => $enrolled_courses,
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

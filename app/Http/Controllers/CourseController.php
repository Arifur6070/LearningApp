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

            // return view('admin.student', $show_all_courses);

            return view('admin.student', compact('show_all_courses'));

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

            $display_course_info = DB::table('courses')
            ->join('categories', 'courses.category_id', '=', 'categories.category_id')
            ->select('courses.course_id','courses.course_name','courses.course_image_path','courses.max_students','courses.student_count','courses.start_date','courses.end_date','courses.course_description','courses.course_price','courses.rating','categories.category_name','courses.type')
            ->where('courses.course_id',$id)
            ->get();

            $display_teacher_info = DB::table('courses')
            ->join('users', 'courses.teacher_id', '=', 'users.user_id')
            ->select('users.user_id','users.user_name','users.first_name','users.user_image_path','users.last_name',)
            ->where('courses.course_id',$id)
            ->get();

            $display_lecture_info = DB::table('lectures')
            ->Where('course_id',$id)
            ->get();

            $display_comments=DB::table('courses')
            ->join('comments', 'comments.course_id', '=', 'courses.course_id')
            ->join('users', 'users.user_id', '=', 'courses.teacher_id')
            ->select('comments.comment_text','users.username','users.first_name','users.user_image_path','users.last_name')
            ->where('courses.course_id',$id)
            ->get();


            if (!$display_course_info) {
                throw new Exception('Course details fetching failed!');
            }

            return response()->json(array(
                'status' => true,
                'message'=> 'course details fetching successful',
                'course' => $display_course_info,
                'teacher'=> $display_teacher_info,
                'lectures'=> $display_lecture_info,
                'comments'=> $display_comments
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
            // if (!$create_course_id) {
            //     throw new Exception('Create Course failed!');
            // }


            $json_schedule =$request->schedule;

            for($i=0;$i<sizeof($json_schedule);$i++){
                $insert_schedule['course_id'] = $create_course_id;
                $insert_schedule['day'] = $json_schedule[$i]['day'];
                $insert_schedule['start_time'] = $json_schedule[$i]['start_time'];
                $insert_schedule['end_time'] = $json_schedule[$i]['end_time'];
                $insert_schedule_DB = DB::table('course_timing')->insert($insert_schedule);
                if(!$insert_schedule_DB){
                   throw new Exception('schedule inserting failed');
                }
            }

            // $start_date=$request->start_date;
            // $end_date=$request->end_date;
            // $numberOfDays=0;
            // $checker=[];
            // for($i=0;$i<sizeof($json_schedule);$i++){
            //     $no = 0;
            //     $start = new DateTime($start_date);
            //     $end   = new DateTime($end_date);
            //     $interval = DateInterval::createFromDateString('1 day');
            //     $period = new DatePeriod($start, $interval, $end);
            //     foreach ($period as $dt)
            //     {
            //         if ($dt->format('N') == 7)
            //         {
            //             $no++;
            //         }
            //     }
            // }

        //     for($i=0;$i<sizeof($json_schedule);$i++){
        //         $fridays = [];
        //         $startDate = Carbon::parse($start_date)->modify('this'." ".$json_schedule[$i]['day']); // Get the first friday. If $fromDate is a friday, it will include $fromDate as a friday
        //         $endDate = Carbon::parse($toDate);

        //         for ($date = $startDate; $date->lte($end_date); $date->addWeek()) {
        //             $fridays[] = $date->format('Y-m-d');
        //     }
        //     $checker[]=$fridays;
        // }

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

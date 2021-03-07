<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\User;
use Exception;
use Validator;

class TeacherController extends Controller
{
    /**
     * The following method is for fetching courses taught by a specific teacher
     */
    public function showTeachingCourses($id){

        try{
            $courses_taught_by_a_teacher = Course::where('teacher_id', $id)
                ->get();
            if (!$courses_taught_by_a_teacher) {
                throw new Exception('Teacher currently doesnot have any course to teach');
            }

            return response()->json(array(
                'status' => true,
                'taught_courses' => $courses_taught_by_a_teacher,
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
     * Update the specific resource's password
     *
     * @param \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function updatePassword( Request $request ){

        try{
            $rules = array(
                'user_id'          => 'required',
                'old_password'     => 'required',
                'new_password'     => 'required',
                'confirm_password' => 'required',
            );

            $validator = Validator::make($request->all(), $rules);
            if (!$validator->passes()) {
                throw new Exception('All fields are required');
            }
            $id = $request->user_id;
            $old_password = $request->old_password;

            $user = User::select('password')
            ->where('user_id',$id)->first();
            // return $user;
            if (!Hash::check($old_password,$user->password)) {
            throw new Exception('Old Password not correct.');
        }

            $password = Hash::make($request->new_password);
            User::where('user_id',$id)->update(['password' => $password]);
            return back()->with('success','Password changed successfully.');
        }
        catch (Exception $e) {
            return back()->with('error',$e->getMessage());
        }
     }

     public function getUser($id){
        try{
            $show_user = User::where('user_id', $id)
                ->first();
            if (!$show_user) {
                throw new Exception('User doesnot exist!');
            }

            return response()->json(array(
                'status' => true,
                'user' => $show_user,
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
      * Updating the user profile.
      * there will be two parameter in the payload
      */

     public function updateProfile(Request $request, $id)
     {
         try{
             $rules = array(
                 'username'  => 'required',
                 'password'     => 'required',
                 'role'     => 'required',
                 'first_name'  => 'required',
                 'last_name'   => 'required',
                 'email'         => 'required',
                 'phone'         => 'required',
                 'address'       => 'required',
             );
             $validator = Validator::make($request->all(), $rules);
             if (!$validator->passes()) {
                 throw new Exception('All fields are required');
             }

             $update_user['username'] = $request->username;
             $update_user['role'] = $request->role;
             $update_user['first_name'] = $request->first_name;
             $update_user['last_name'] = $request->last_name;
             $update_user['email'] = $request->email;
             $update_user['phone'] = $request->phone;
             $update_user['address'] = $request->address;
             $update_user['password'] = Hash::make($request->password);

             $update = User::where('user_id', $id)
                 ->update($update_user);
             if (!$update) {
                 throw new Exception('Update user failed!');
             }

             return response()->json(array(
                 'status' => true,
                 'status_message' => "User Update Successful!",
                 // 'teacher' => $update,
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

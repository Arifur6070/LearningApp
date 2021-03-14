<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Course extends Model{

    use HasFactory, Notifiable;

    protected $table = 'courses';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'subject_id',
       'teacher_id',
       'min_students',
       'max_students',
       'student_count',
       'start_date',
       'end_date',
       'type',
       'course_description',
       'course_price',
       'course_name'
   ];

}



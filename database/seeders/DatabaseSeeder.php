<?php

namespace Database\Seeders;

use App\Models\Classes;
use App\Models\School;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // seed the roles
        $this->runRole();
        // seed the permissions
        $this->runPermission();
        // seed the users
        $this->runUser();
        // seed the settings
        $this->runSetting();
        // seed the schools
        $this->runSchool();
    }

    /**
     * Seed the role for the application.
     *
     * @return void
     */
    public function runRole()
    {
        // seed the roles
        $roles = [
            [
                'name' => 'super',
                'description' => 'Super Administrator',
            ],
            [
                'name' => 'admin',
                'description' => 'Administrator',
            ],
            [
                'name' => 'user',
                'description' => 'User',
            ],
            [
                'name' => 'manager',
                'description' => 'School Manager',
            ],
            [
                'name' => 'teacher',
                'description' => 'Teacher',
            ],
            [
                'name' => 'student',
                'description' => 'Student',
            ],
        ];
        foreach ($roles as $role) {
            Role::create($role);
        }
    }

    /**
     * Seed the permission for the application.
     *
     * @return void
     */
    private function runPermission()
    {
        $permissions = [
            // permissions in admin page
            [
                'name' => 'admin.super',
                'description' => 'Give all permissions to user',
            ],
            [
                'name' => 'admin.dashboard',
                'description' => 'Require to access any admin page',
            ],
            [
                'name' => 'admin.analytics',
                'description' => 'Require to access analytics page',
            ],
            [
                'name' => 'admin.setting',
                'description' => 'Require to update setting',
            ],
            // permissions in manager of school
            [
                'name' => 'manager.dashboard',
                'description' => 'Require to access any manager page',
            ],
        ];
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
        // attach permissions to roles
        $role = Role::where('name', 'super')->first();
        $role->permissions()->attach(Permission::all());
        $role = Role::where('name', 'admin')->first();
        $role->permissions()->attach(Permission::where('name', 'like', 'admin.%')->get());
    }

    private function runUser()
    {
        $users = [
            [
                'role_id' => 1, // 'super
                'username' => 'super_admin',
                'email' => 'superadmin@pontas.dev',
                'date_of_birth' => '1990-01-01',
                'status' => 'active',
                'password' => Hash::make('1234567890'),
                'email_verified_at' => '2021-01-01 00:00:00',
            ],
            [
                'role_id' => 2, // 'admin
                'username' => 'admin',
                'email' => 'admin@pontas.dev',
                'date_of_birth' => '1990-01-01',
                'status' => 'active',
                'password' => Hash::make('1234567890'),
                'email_verified_at' => '2021-01-01 00:00:00',
            ],
            [
                'role_id' => 3, // 'user
                'username' => 'user',
                'email' => 'user@pontas.dev',
                'date_of_birth' => '1990-01-01',
                'status' => 'active',
                'password' => Hash::make('1234567890'),
                'email_verified_at' => '2021-01-01 00:00:00',
            ],
        ];
        foreach ($users as $user) {
            User::create($user);
        };
    }

    private function runSetting()
    {
        $settings = [
            [
                'key' => 'name',
                'value' => 'Let Learn',
            ],
            [
                'key' => 'timezone',
                'value' => 'Asia/Ho_Chi_Minh',
            ],
            [
                'key' => 'description',
                'value' => 'Let Learn is a platform for learning',
            ],
            [
                'key' => 'header',
                'value' => '<meta>Let Learn</meta>',
            ],
            [
                'key' => 'keywords',
                'value' => 'Let Learn, Learning, Platform, Online, Education, School, Teacher, Student, Class, Mark, Grade, Subject, Course, Lesson, Test, Exam, Quiz, Question, Answer, Homework, Assignment, Activity, Event, Announcement, Notification, Message, Chat, Forum, Blog, Post, Comment, Review, Rating, Vote, Poll, Survey'
            ],
            [
                'key' => 'favicon',
                'value' => '/favicon.ico',
            ],
            [
                'key' => 'logo',
                'value' => '/logo.png',
            ]
        ];
        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }

    private function runSchool()
    {
        // Fake school
        $school = School::factory(1)->create();
        // Fake school manager
        $manager = User::factory(1)->create([
            'role_id' => 4, // 'manager
            'school_id' => 1,
        ]);
        // Fake class
        $class = Classes::factory(1)->create([
            'school_id' => 1,
        ]);
        // Fake teacher
        $teacher = User::factory(5)->create([
            'role_id' => 5, // 'teacher
            'school_id' => 1,
        ]);
        // Fake student
        $student = User::factory(10)->create([
            'role_id' => 6, // 'student
            'school_id' => 1,
        ]);
    }
}

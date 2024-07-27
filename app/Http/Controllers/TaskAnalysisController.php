<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Models\UserAnalysis;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

class TaskAnalysisController extends Controller
{



    public function userAnalysis()
    {
        $authUser = Auth::user();

        if (!$authUser) {
            return ResponseHelper::error(message: 'Unauthorized', statusCode: 401);
        }

        // Get completed tasks ordered by the completion date
        $completedTasks = $authUser->tasks()->where('task_status', 'task_completed')->orderBy('updated_at')->get();
        $allTasksCount = $completedTasks->count() + 1; //added the one because arrays are 0based;
        $analysis = $authUser->userAnalysis;


        $userLongestStreak = $analysis?->longest_streak;
        $userCurrentStreak = $analysis?->current_streak;





        $currentStreak = 0;
        $longestStreak = 0;
        $streak = 0;
        $previousDate = null;
        // Calculate status ranking as a percentage
        $totalUsers = UserAnalysis::count();
        $userRankings = UserAnalysis::orderBy('longest_streak', 'desc')->pluck('user_id')->toArray();
        $userRank = array_search($authUser->id, $userRankings) + 1; // +1 to convert 0-indexed to 1-indexed rank

        // Calculate percentage rank
        $rankPercentage = ($userRank / $totalUsers) * 100;


        // Calculate current and longest streak
        foreach ($completedTasks as $task) {
            $completedDate = Carbon::parse($task->updated_at)->startOfDay();

            if ($previousDate === null || $completedDate->diffInDays($previousDate) === 1) {
                $streak++;
            } else if ($completedDate->diffInDays($previousDate) > 1) {
                $streak = 1;
            }

            $previousDate = $completedDate;
            $longestStreak = max($longestStreak, $streak);
        }

        $currentStreak = ($previousDate && $previousDate->diffInDays(Carbon::now()->startOfDay()) <= 1) ? $streak : 0;

        // Group completed tasks by date and find the most productive day
        $taskCountsByDate = $completedTasks->groupBy(function ($task) {
            return Carbon::parse($task->updated_at)->toDateString();
        })->map->count();

        $mostProductiveDay = $taskCountsByDate->sortDesc()->keys()->first();

        // Find or create a user analysis record
        $userAnalysis = UserAnalysis::firstOrCreate(
            ['user_id' => $authUser->id],
            [
                'current_badge' => 'Newbie',
                'current_streak' => 0,
                'longest_streak' => 0,
                'most_productive_day' => null,
                'status_ranking' => null,
                'description' => null,
                'qualification' => null
            ]
        );

        // Update the badge based on the number of completed tasks
        if ($allTasksCount <= 5) {
            $userAnalysis->current_badge = 'Newbie';
            $userAnalysis->description = "Welcome! You've completed the baby steps";
            $userAnalysis->qualification  = 'Completed the first 5 tasks';
        } else if ($allTasksCount == 10) {
            $userAnalysis->current_badge = 'Apprentice';
            $userAnalysis->description = "You're getting the hang of it! Keep going";
            $userAnalysis->qualification  = 'Completed 10 tasks';
        } else if ($allTasksCount == 15 && $userLongestStreak == 5) {
            $userAnalysis->current_badge = 'Journeyman';
            $userAnalysis->description = "You're getting the hang of it! Keep going";
            $userAnalysis->qualification  = 'Completed 15 tasks and maintained a 5 day streak';
        } else if ($allTasksCount == 25 && $userLongestStreak == 10) {
            $userAnalysis->current_badge = 'Steady Achiever';
            $userAnalysis->description = "Your steady progress is inspiring!";
            $userAnalysis->qualification  = 'Completed 25 tasks and maintained a 10 day streak';
        } else if ($allTasksCount == 40 && $userLongestStreak == 20) {
            $userAnalysis->current_badge = 'Task Master';
            $userAnalysis->description = "You've mastered the art of task management!";
            $userAnalysis->qualification  = 'Completed 40 tasks and maintained a 20 day streak';
        } else if ($allTasksCount == 50 && $userLongestStreak == 30) {
            $userAnalysis->current_badge = 'Persistent';
            $userAnalysis->description = "Your persistence is truly remarkable";
            $userAnalysis->qualification  = 'Completed 50 tasks and maintained a 30 day streak';
        } else if ($allTasksCount == 150 && $userLongestStreak == 50) {
            $userAnalysis->current_badge = 'Consistent Champion';
            $userAnalysis->description = "You've demonstrated exceptional consistency and dedication";
            $userAnalysis->qualification  = 'Completed 150 tasks and maintained a 50 day streak';
        } else if ($allTasksCount == 200 && $userLongestStreak == 70) {
            $userAnalysis->current_badge = 'Ultimate Achiever';
            $userAnalysis->description = "Your consistency is elite and awe-inspiring";
            $userAnalysis->qualification  = 'Completed 200 tasks and maintained a 70 day streak';
        } else if ($allTasksCount == 300 && $userLongestStreak == 90) {
            $userAnalysis->current_badge = 'Legendary';
            $userAnalysis->description = " You've achieved legendary status with your consistency";
            $userAnalysis->qualification  = 'Completed 300 tasks and maintained a 90 day streak';
        } else if ($allTasksCount == 400 && $userLongestStreak == 110) {
            $userAnalysis->current_badge = 'Consistency Legend';
            $userAnalysis->description = " Your dedication and consistency are mythic";
            $userAnalysis->qualification  = 'Completed 400 tasks and maintained a 110 day streak';
        }



        // Update streaks and productive day
        $userAnalysis->current_streak = $currentStreak;
        $userAnalysis->longest_streak = $longestStreak;
        $userAnalysis->most_productive_day = $mostProductiveDay;
        $userAnalysis->status_ranking = $rankPercentage;

        // Save the updated analysis
        $userAnalysis->update();

        return ResponseHelper::success(message: 'User analysis updated successfully!', data: $userAnalysis, statusCode: 200);
    }


    public function  generalAnalysis()
    {
    }
}

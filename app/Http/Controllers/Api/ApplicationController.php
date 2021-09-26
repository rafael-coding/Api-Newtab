<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Job;
use App\Models\Candidate;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Application::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Application::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Application::findOrFail($id);
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
        $application = Application::findOrFail($id);
        $application->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $application = Application::findOrFail($id);
        $application->delete();
    }

    public function ranking(int $jobId)
    {
        $ranking = array();
        $job = Job::findOrFail($jobId);
        $applications = Application::query()->where('job_id', '=', $jobId)->get();
        foreach ($applications as $application) {
            $candidate = Candidate::query()->where('id', '=', $application->candidate_id)->first();
            $candidate = $this->calculateDistance($job, $candidate);
            $ranking[] = $candidate;
        }
        return $ranking;
    }

    public function calculateDistance(Job $job, Candidate $candidate)
    {
        $distances = [
            'ab' => 5,
            'ac' => 12,
            'ad' => 8,
            'ae' => 16,
            'af' => 16,
            'bc' => 7,
            'bd' => 3,
            'be' => 11,
            'bf' => 11,
            'cd' => 10,
            'ce' => 4,
            'cf' => 18,
            'de' => 10,
            'df' => 8,
            'ef' => 18
        ];

        $nv = $job->level;
        $nc = $candidate->level;
        $n = 100 - 25 * ($nv - $nc);

        if ($candidate->localization == $job->localization) {
            $d = 100;
        } else {
            $candidateDistance = $distances[strtolower($candidate->localization . $job->localization)] ?? $distances[strtolower($job->localization . $candidate->localization)];
            if ($candidateDistance >= 0 && $candidateDistance <= 5) {
                $d = 100;
            } elseif ($candidateDistance <= 10) {
                $d = 75;
            } elseif ($candidateDistance <= 15) {
                $d = 50;
            } elseif ($candidateDistance <= 20) {
                $d = 25;
            } else {
                $d = 0;
            }
        }

        $score = ($n + $d) / 2;
        $candidate->score = $score;

        return $candidate;
    }

}

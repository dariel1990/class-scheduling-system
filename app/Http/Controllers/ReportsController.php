<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\Settings;
use Illuminate\Http\Request;
use App\Services\AnswerService;
use App\Services\EvaluationService;
use Illuminate\Support\Facades\App;
use App\Services\AcademicYearService;
use App\Services\PeerAssignedEvaluationService;
use App\Services\StudentAssignedEvaluationService;
use App\Services\SupervisorAssignedEvaluationService;

class ReportsController extends Controller
{
    protected $evaluationService;
    protected $academicYearService;
    protected $studentAssignedEvaluation;
    protected $peerAssignedEvaluation;
    protected $supervisorAssignedEvaluation;
    protected $answerService;

    public function __construct(EvaluationService $evaluationService,
                                AcademicYearService $academicYearService,
                                StudentAssignedEvaluationService $studentAssignedEvaluation,
                                PeerAssignedEvaluationService $peerAssignedEvaluation,
                                SupervisorAssignedEvaluationService $supervisorAssignedEvaluation,
                                AnswerService $answerService)
    {
        $this->evaluationService    = $evaluationService;
        $this->academicYearService  = $academicYearService;
        $this->studentAssignedEvaluation  = $studentAssignedEvaluation;
        $this->peerAssignedEvaluation  = $peerAssignedEvaluation;
        $this->supervisorAssignedEvaluation  = $supervisorAssignedEvaluation;
        $this->answerService  = $answerService;
    }

    public function comments($evaluationId)
    {
        $evaluationType = '';
        $defaultPeriod = $this->academicYearService->getDefaultPeriod();
        $evaluation = $this->evaluationService->getEvaluationById($evaluationId);
        if($evaluation->type == 'Student'){
            $comments = $this->studentAssignedEvaluation->getCommentsByEvaluationId($evaluationId);
            $evaluationType = 'Student';
        }else if($evaluation->type == 'Peer'){
            $comments = $this->peerAssignedEvaluation->getCommentsByEvaluationId($evaluationId);
            $evaluationType = 'Peer';
        }else if($evaluation->type == 'Supervisor'){
            $comments = $this->supervisorAssignedEvaluation->getCommentsByEvaluationId($evaluationId);
            $evaluationType = 'Supervisor';
        }

        return view('admin.reports.comments',compact('evaluation', 'comments', 'evaluationId', 'evaluationType'));
    }

    public function printComments($evaluationId)
    {
        $settings = [
            'SCHOOL_NAME'                           => Settings::where('Keyname', 'SCHOOL_NAME')->first(),
            'CAMPUS_NAME'                           => Settings::where('Keyname', 'CAMPUS_NAME')->first(),
            'CAMPUS_ADDRESS'                        => Settings::where('Keyname', 'CAMPUS_ADDRESS')->first(),
            'HR'                                    => Settings::where('Keyname', 'HR')->first(),
            'HR_POSITION'                           => Settings::where('Keyname', 'HR_POSITION')->first(),
            'ASSISTANT_CAMPUS_DIRECTOR'             => Settings::where('Keyname', 'ASSISTANT_CAMPUS_DIRECTOR')->first(),
            'ASSISTANT_CAMPUS_DIRECTOR_POSITION'    => Settings::where('Keyname', 'ASSISTANT_CAMPUS_DIRECTOR_POSITION')->first(),
            'DGTT_CHAIRMAN'                         => Settings::where('Keyname', 'DGTT_CHAIRMAN')->first(),
            'DGTT_CHAIRMAN_POSITION'                => Settings::where('Keyname', 'DGTT_CHAIRMAN_POSITION')->first(),
        ];

        $defaultPeriod = $this->academicYearService->getDefaultPeriod();
        $evaluation = $this->evaluationService->getEvaluationById($evaluationId);
        $evaluationType = '';
        if($evaluation->type == 'Student'){
            $comments = $this->studentAssignedEvaluation->getCommentsByEvaluationId($evaluationId);
            $evaluationType = 'Student';
        }else if($evaluation->type == 'Peer'){
            $comments = $this->peerAssignedEvaluation->getCommentsByEvaluationId($evaluationId);
            $evaluationType = 'Peer';
        }else if($evaluation->type == 'Supervisor'){
            $comments = $this->supervisorAssignedEvaluation->getCommentsByEvaluationId($evaluationId);
            $evaluationType = 'Supervisor';
        }

        $pdf = App::make('snappy.pdf.wrapper');

        $pdf->loadView('admin.reports.comments-pdf',
            compact('defaultPeriod', 'evaluation', 'comments', 'evaluationId', 'settings', 'evaluationType'))
            ->setOrientation('portrait')
            ->setOption('page-width', '215.9')
            ->setOption('page-height', '330.2');

        return $pdf->inline();
    }

    public function printEvaluationPerformance($evaluationId)
    {
        $settings = [
            'SCHOOL_NAME'                           => Settings::where('Keyname', 'SCHOOL_NAME')->first(),
            'CAMPUS_NAME'                           => Settings::where('Keyname', 'CAMPUS_NAME')->first(),
            'CAMPUS_ADDRESS'                        => Settings::where('Keyname', 'CAMPUS_ADDRESS')->first(),
            'HR'                                    => Settings::where('Keyname', 'HR')->first(),
            'HR_POSITION'                           => Settings::where('Keyname', 'HR_POSITION')->first(),
            'ASSISTANT_CAMPUS_DIRECTOR'             => Settings::where('Keyname', 'ASSISTANT_CAMPUS_DIRECTOR')->first(),
            'ASSISTANT_CAMPUS_DIRECTOR_POSITION'    => Settings::where('Keyname', 'ASSISTANT_CAMPUS_DIRECTOR_POSITION')->first(),
            'CAMPUS_DIRECTOR'                       => Settings::where('Keyname', 'CAMPUS_DIRECTOR')->first(),
            'CAMPUS_DIRECTOR_POSITION'              => Settings::where('Keyname', 'CAMPUS_DIRECTOR_POSITION')->first(),
            'DFIMES_CHAIRMAN'                       => Settings::where('Keyname', 'DFIMES_CHAIRMAN')->first(),
            'DFIMES_CHAIRMAN_POSITION'              => Settings::where('Keyname', 'DFIMES_CHAIRMAN_POSITION')->first(),
        ];

        $defaultPeriod = $this->academicYearService->getDefaultPeriod();
        $evaluation = $this->evaluationService->getEvaluationById($evaluationId);
        $criterias = Criteria::get();
        $evaluationType = '';
        $answers = $this->answerService->getAllAnswerByEvaluation($evaluationId);

        if($evaluation->type == 'Student'){
            $evaluatorCount = $this->studentAssignedEvaluation->getAllCompletedEvaluator($evaluationId)->count();
            $evaluationType = 'Student';
        }else if($evaluation->type == 'Peer'){
            $evaluatorCount = $this->peerAssignedEvaluation->getAllCompletedEvaluator($evaluationId)->count();
            $evaluationType = 'Peer';
        }else if($evaluation->type == 'Supervisor'){
            $evaluatorCount = $this->supervisorAssignedEvaluation->getAllCompletedEvaluator($evaluationId)->count();
            $evaluationType = 'Supervisor';
        }

        $calculateRating = [];
        foreach($criterias as $criteria){
            $sumPerCriteria = 0;
            foreach($answers as $answer){
                if($criteria->id == $answer->question->criteria_id){
                    $sumPerCriteria += $answer->rate;
                }
            }

            $result = (($sumPerCriteria / ($evaluatorCount * 5) * 100) / 5) * ($criteria->percentage / 100);

            $calculateRating[$criteria->id] = $result;
        }

        $totalSum = array_sum($calculateRating);
        $totalResults = count($calculateRating);

        $pdf = App::make('snappy.pdf.wrapper');

        $pdf->loadView('admin.reports.performance-pdf',
            compact('defaultPeriod', 'evaluation', 'evaluatorCount', 'evaluationId', 'criterias',
            'totalSum', 'calculateRating', 'settings', 'evaluationType'))
            ->setOrientation('portrait')
            ->setOption('page-width', '215.9')
            ->setOption('page-height', '330.2');

        return $pdf->inline();
    }
}

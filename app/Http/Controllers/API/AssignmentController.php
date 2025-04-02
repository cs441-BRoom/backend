<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Assignments\CreateRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Assignments\GetAllRequest;
use App\Http\Resources\Assignments\AssignmentResource;
use App\Models\Assignment;
use App\Repositories\AssignmentRepository;
use App\Repositories\AssignmentSubmissionRepository;
use App\Repositories\WorkspaceRepository;
use Exception;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AssignmentController extends Controller
{
    public function __construct(
        private AssignmentRepository $assignmentRepository,
        private AssignmentSubmissionRepository $assignmentSubmissionRepository,
        private WorkspaceRepository $workspaceRepository
    ) { }

    /**
     * Display a listing of the resource.
     */
    public function index(GetAllRequest $request, int $workspace_id)
    {
        $validated = $request->validated();

        $workspace = $this->workspaceRepository->getById($workspace_id);
        $assignmentSubmissionRepository = $this->assignmentSubmissionRepository;
        try {
            if (auth()->id() === $workspace->created_by) {
                $assignments = $this->assignmentRepository->findByWorkspaceId($workspace_id)->map(function($assignment) use ($assignmentSubmissionRepository) {
                    $assignment->submitted_number = $assignment->assignmentSubmission->whereNotNull('submit_at')->count();
                    $assignment->members = $assignment->assignmentSubmission->count();

                    return $assignment;
                });
            } else
            {
                $submissions = $assignmentSubmissionRepository->getAllSubmissionsByUserId(auth()->id());
                $assignments = $submissions->filter(function($submission) use ($workspace_id) {
                    return $workspace_id === $submission->assignment->workspace->workspace_id;
                })->map(function($submission) {
                    $assignment = $submission->assignment;
                    $assignment->submit_at = $submission->submit_at;
                    $assignment->score = $submission->score;

                    return $assignment;
                });
            }
            $arr = [];
            if ($assignments->isNotEmpty()) {
                $arr = AssignmentResource::collection($assignments);
            } 
            return response()->json([
                'assignments' => $arr 
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        $validated = $request->validated();

        try {
            $assignment = $this->assignmentRepository->create(array_merge(
                $validated,
                [
                    'created_by' => auth()->id()
                ]
            ));
            // create all the assignment_submissions
            $workspace_id = $validated['workspace_id'];
            $members = $this->workspaceRepository->getById($workspace_id)->users;
            foreach ($members as $member) {
                $this->assignmentSubmissionRepository->create([
                    'assignment_id' => $assignment->assignment_id,
                    'user_id' => $member->user_id,
                    'score' => 0
                ], $member->id);
            }

            if ($request->hasFile('files')) {
                $files = $request->file('files');

                foreach ($files as $index => $file) {
                    $file->storeAs('workspaces/' . $validated['workspace_id'] . '/assignments/' . $assignment->assignment_id, $index . '.' . $file->getClientOriginalExtension());
                }
            }

            return response()->json(new AssignmentResource($assignment), 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $assignment_id)
    {
        try {
            $assignment = $this->assignmentRepository->getById($assignment_id);

            $workspace_id = $assignment->workspace->workspace_id;

            $files = Storage::files('workspaces/'. $workspace_id . '/assignments/' . $assignment_id);
            $file_arr = [];
            foreach ($files as $file) {
                $content = Storage::get($file);
                $base64File = base64_encode($content);
                $mime = Storage::mimeType($file);

                $file_arr[] = [
                    'name' => $file,
                    'base64' => $base64File,
                    'mime_type' => $mime
                ];
            }

            if (auth()->id() === $assignment->workspace->created_by) {
                //admin
                $assignment->submitted_number = $assignment->assignmentSubmission->whereNotNull('submit_at')->count();
                $assignment->members = $assignment->assignmentSubmission->count();

                return response()->json(array_merge(new AssignmentResource($assignment)->toArray(request()),[
                    'files' => $file_arr
                ]), 200);
            } else {
                $submission = $assignment->assignmentSubmission->where('user_id', auth()->id())->first();
                // member
        
                if ($submission->submit_at === null) {
                    $now = Carbon::now();
                    if ($now->lessThan($submission->assignment->due_date)) {
                        $status = 'in-progress';

                    } else {
                        $status = 'late';
                    }

                } else {
                    $status = 'submitted';
                }

                $assignment->submit_at = $submission->submit_at;
                $assignment->score = $submission->score;

                return response()->json(array_merge(new AssignmentResource($assignment)->toArray(request()),[
                    'status' => $status,
                    'files' => $file_arr
                ]), 200);
            }


        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assignment $assignment)
    {
        $validated = $request->validated();

        try {
        } catch (Exception $e) {
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assignment $assignment)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Submissions\UpdateRequest;
use App\Http\Requests\Submissions\SubmitRequest;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use App\Repositories\AssignmentSubmissionRepository;
use App\Http\Resources\Submissions\SubmissionResource;
use Carbon\Carbon;

class SubmissionController extends Controller
{
    public function __construct(
        // private AssignmentRepository $assignmentRepository,
        private AssignmentSubmissionRepository $assignmentSubmissionRepository,
        // private WorkspaceRepository $workspaceRepository
    ) { }

    /**
     * Display a listing of the resource.
     */
    public function index(int $assignment_id)
    {
        try {
            $submissions = $this->assignmentSubmissionRepository->findByAssignmentId($assignment_id);

                    return response()->json([
                        'submissions' => SubmissionResource::collection($submissions),
                        // 'assigned' => $assignment->assignmentSubmission->whereNotNull('submit_at')->count(),
                        // 'submitted' => $assignment->assignmentSubmission->count()
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $submission_id)
    {
        try {
            $submission = $this->assignmentSubmissionRepository->getById($submission_id);

            $assignment_id = $submission->assignment->assignment_id;

            $workspace_id = $submission->assignment->workspace->workspace_id;

            $files = Storage::files('workspaces/'. $workspace_id . '/assignments/' . $assignment_id . '/submissions/' . $submission_id . '/user_id/' . $submission->user_id);
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

            return response()->json([
                'submission' => new SubmissionResource($submission),
                'status' => $status,
                'files' => $file_arr
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->assignmentSubmissionRepository->update([
                'score' => $validated['score'],
            ], $validated['submission_id']);

            $submission = $this->assignmentSubmissionRepository->getById($validated['submission_id']);

            return response()->json(
                new SubmissionResource($submission),
            200);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function submit(SubmitRequest $request)
    {
        $validated = $request->validated();

        try {
            $submission = $this->assignmentSubmissionRepository->findByAssignmentIdAndUserId($validated['assignment_id'], auth()->id());
            $now = Carbon::now();
            if ($now->greaterThanOrEqualTo($submission->assignment->due_date)) {
                return response()->json([
                    'message' => 'overtime'
                ], 400);
            }

            $this->assignmentSubmissionRepository->update([
                'submit_at' => $now
            ], $submission->submission_id);
            $submission = $this->assignmentSubmissionRepository->findByAssignmentIdAndUserId($validated['assignment_id'], auth()->id());

            $assignment = $submission->assignment;
            $workspace = $submission->assignment->workspace;

            if ($request->hasFile('files')) {
                $files = $request->file('files');

                foreach ($files as $index => $file) {
                    $file->storeAs('workspaces/' . $workspace->workspace_id . '/assignments/' . $validated['assignment_id'] . '/submissions/' . $submission->submission_id . '/user_id/' . auth()->id(), $index . '.' . $file->getClientOriginalExtension());
                }
            }

            return response()->json(
                new SubmissionResource($submission),
            200);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssignmentSubmission $assignmentSubmission)
    {
        //
    }
}

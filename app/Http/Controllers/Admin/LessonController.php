<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LessonController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Return list of all lesson
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $set = Lesson::all();
            $set = $set->map(function ($item) {
                return [
                    "" . $item->id,
                    $item->name,
                    $item->description,
                    $item->user->username,
                    $item->is_public ? 'public' : 'private',
                    Carbon::parse($item->updated_at)->format('d/m/Y'),
                    $item->status,
                ];
            });


            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'data' => $set
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'type' => 'required|in:general,text,file',
                'name' => 'required|string',
                'description' => 'required|string',
                'detail' => 'required_if:type,general|array',
                'raw_data' => 'required_if:type,text|string',
                'detail_separator' => 'required_if:type,text|string',
                'term_separator' => 'required_if:type,text|string',
                'file' => 'required_if:type,file|file|mimes:xls,xlsx,csv',
            ]);
            $lesson = auth()->user()->lesson()->create([
                'name' => $request->name,
                'description' => $request->description,
                'password' => null,
                'is_public' => true,
            ]);
            try {
                switch ($request->type) {
                    case 'general':
                        $details = $request->detail;
                        // request have more or equal 3 details
                        if (count($details) >= 3) {
                            // add lesson detail
                            $lesson->lessonDetail()->createMany($details);
                        } else {
                            // throw error
                            throw new \Exception('Lesson must have more or equal 3 details');
                        }
                        break;
                    case 'text':
                        $raw_detail = explode($request->detail_separator, $request->raw_data);
                        if(count($raw_detail) < 3) {
                            throw new \Exception('Lesson must have more or equal 3 details');
                        }
                        foreach ($raw_detail as $item) {
                            try {
                                $raw = explode($request->term_separator, $item);
                                $term = $raw[0];
                                $definition = $raw[1];
                                $lesson->lessonDetail()->create([
                                    'term' => $term,
                                    'definition' => $definition
                                ]);
                            } catch (\Throwable $th) {
                                continue;
                            }
                        }
                        break;
                    case 'file':
                        $file = $request->file('file');
                        $file_extension = $file->getClientOriginalExtension();
                        if ($file_extension == 'xls' || $file_extension == 'xlsx') {
                            $data = Excel::toArray((object)[], $file);
                            // check data have more than 4 rows
                            if (count($data[0]) > 4) {
                                // check data have more than 2 columns
                                if (count($data[0][0]) > 2) {
                                    foreach ($data[0] as $key => $item) {
                                        if ($key >= 2) {
                                            $lesson->lessonDetail()->create([
                                                'term' => $item[1],
                                                'definition' => $item[2]
                                            ]);
                                        }
                                    }
                                } else {
                                    // throw error
                                    throw new \Exception('File must have 3 columns ( no,term, definition,... )');
                                }
                            } else {
                                // throw error
                                throw new \Exception('File must have more than 5 rows');
                            }
                        } else {
                            $csv = array_map('str_getcsv', file($file));
                            // check data have more than 4 rows
                            if (count($csv) > 4) {
                                // check data have 2 columns
                                if (count($csv[0]) > 2) {
                                    foreach ($csv as $key => $item) {
                                        if ($key >= 2) {
                                            $lesson->lessonDetail()->create([
                                                'term' => $item[1],
                                                'definition' => $item[2]
                                            ]);
                                        }
                                    }
                                } else {
                                    // throw error
                                    throw new \Exception('File must have 3 columns ( no,term, definition,... )');
                                }
                            } else {
                                // throw error
                                throw new \Exception('File must have more than 5 rows');
                            }
                        }
                        break;
                }

                return response()->json([
                    'status' => 'success',
                    'status_code' => 200,
                    'message' => 'Lesson created successfully',
                    'id' => $lesson->id
                ], 200);
            } catch (\Throwable $th) {
                // delete lesson
                $lesson->delete();
                return response()->json([
                    'status' => 'error',
                    'status_code' => 400,
                    'message' => $th->getMessage()
                ], 400);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Export lesson data to csv file
     *
     * @param int $id
     * @return BinaryFileResponse | JsonResponse
     */
    public function show(int $id)
    {
        try {
            $lesson = Lesson::findOrfail($id);
            $lessonData = $lesson->lessonDetail()->get()->toArray();
            if (empty($lessonData)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Lesson is empty'
                ], 400);
            }
            // write lesson data to file
            $file_name = 'lesson_' . $id . '_' . date('Ymd_His') . '.csv';
            $file = fopen($file_name, 'w');
            fputcsv($file, ['name', $lesson->name]);
            fputcsv($file, ['description', $lesson->description]);
            fputcsv($file, ['public', $lesson->public]);
            fputcsv($file, ['password', $lesson->password]);
            fputcsv($file, ['created_at', $lesson->created_at]);
            fputcsv($file, ['updated_at', $lesson->updated_at]);
            fputcsv($file, ['id', 'term', 'definition', 'image', 'audio', 'video', 'status', 'created_at', 'updated_at']);
            foreach ($lessonData as $line) {
                fputcsv($file, $line);
            }
            fclose($file);
            // lesson header
            $headers = [
                'Content-Type' => 'text/csv',
            ];
            // download file
            return response()->download($file_name, $file_name, $headers)->deleteFileAfterSend(true);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Get the specified resource to edit.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        try {
            $lesson = Lesson::findOrFail($id);
            $detail = $lesson->lessonDetail()->get();
            if (empty($detail)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Lesson is empty'
                ], 400);
            }
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'data' => [
                    'lesson' => $lesson,
                    'detail' => $detail
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'description' => 'string',
                'status' => 'required|in:active,inactive',
                'is_public' => 'required|in:1,0',
                'password' => 'nullable|string',
                'data' => 'required|array',
                'data.lessonDetail' => 'required|array',
                'data.lessonDetail.*.id' => 'required|integer',
                'data.lessonDetail.*.term' => 'required|string',
                'data.lessonDetail.*.definition' => 'required|string',
                'data.delete_id' => 'nullable|array',
            ]);
            // find lesson by id
            $lesson = Lesson::findOrFail($id);
            // update lesson
            $lesson->update([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
                'is_public' => $request->is_public,
                'password' => $request->password,
            ]);
            // delete lesson detail
            if (!empty($request->data['delete_id'])) {
                $lesson->lessonDetail()->whereIn('id', $request->data['delete_id'])->delete();
            }
            // update lesson detail
            foreach ($request->data['lessonDetail'] as $item) {
                $lesson->lessonDetail()->updateOrCreate(
                    ['id' => $item['id']],
                    [
                        'term' => $item['term'],
                        'definition' => $item['definition'],
                    ]
                );
            }
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Lesson updated successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            // delete lesson by id
            $set = Lesson::findOrFail($id);
            $set->delete();
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Lesson deleted successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}

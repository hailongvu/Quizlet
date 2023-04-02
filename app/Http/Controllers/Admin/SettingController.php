<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    // constructor
    public function __construct()
    {
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $setting = Setting::all();
            // convert to key value pair
            $setting = $setting->mapWithKeys(function ($item) {
                return [$item['key'] => $item['value']];
            });
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'data' => $setting
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified setting key
     * @param Request $request
     * @param string $key
     * @return JsonResponse
     */
    public function update(Request $request, string $key): JsonResponse
    {
        try {
            // check ico file when key is favicon and png file when key is logo and string when key is others
            if ($key == 'favicon') {
                $request->validate([
                    'value' => 'required|file|mimes:ico'
                ]);
            } else if ($key == 'logo') {
                $request->validate([
                    'value' => 'required|file|mimes:png'
                ]);
            } else {
                $request->validate([
                    'value' => 'required|string'
                ]);
            }
            // get setting or return error
            $setting = Setting::where('key', $key)->firstOrFail();
            // check if key is favicon or logo
            if ($key == 'favicon' || $key == 'logo') {
                $file = $request->file('value');
                $filename = $key == 'favicon' ? 'favicon.ico' : 'logo.png';
                $file->move(public_path(), $filename);
            } else {
                // update setting
                $setting->update([
                    'value' => $request->value
                ]);
            }
            // delete meta data from cache
            cache()->forget('meta_data');
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Setting updated successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Get meta data
     * @return JsonResponse
     */
    public function meta(): JsonResponse
    {
        try {
            // Check if meta data exists in cache
//            if (cache()->has('meta_data')) {
//                $meta = cache()->get('meta_data');
//            } else {
                // get title, description, keywords
                $meta_data = Setting::whereIn('key', ['name', 'description', 'keywords', 'header', 'onesignal_app_id'])->get();
                // convert to key value pair
                $meta_data = $meta_data->mapWithKeys(function ($item) {
                    return [$item['key'] => $item['value']];
                });
                // lesson meta data to cache
                cache()->put('meta_data', $meta_data);
                $meta = $meta_data;
//            }
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'data' => $meta
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

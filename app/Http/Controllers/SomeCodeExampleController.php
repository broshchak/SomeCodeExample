<?php

namespace App\Http\Controllers;

use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class SomeCodeExampleController extends Controller
{


    /** Methods in this controller took from other projects
     * and only for show some code example
     */

    /**  Other code that includes some big methods with pdf working and other. Can show through screen sharing */

    public function someEloquentQueriesExamples()
    {
        $source_file = SourceFile::where('name', $file_name)
            ->where(function ($q) {
                $q->where('in_process', true)
                    ->orWhere('parsed', false);
            })->first();

        $workers = $workers->where('api_token', 'like', '%' . $request->search . '%');

        $source_files = SourceFile::with('imfxData', 'files')
            ->whereNotNull('results_to_email')
            ->where('email_sent', false)
            ->whereNotNull('finished_at')
            ->get();

        $source_files = SourceFile::whereNull('finished_at')
            ->with('imfxData', 'task')
            ->whereDoesntHave('task')->get();


        $need_start_files = Pdf::with('sourceFile.imfxData')
            ->whereNull('path_translated')
            ->whereNotNull('path_origin')
            ->whereHas('sourceFile', function ($q) {
                $q->where(function ($q1) {
                    $q1->whereNull('finished_at');
                });
            })
            ->get();

        $task = Task::where('status', 'Queue')
            ->orWhere(function ($q) {
                $q->where('status', 'Processing')
                    ->where('active_until', '<', Carbon::now()->toDateTimeString());
            })->orderBy('created_at')->first();


        //....
    }


    public function someCurlRequest()
    {
        $endpoint = 'https://api....';
        $client = new  \GuzzleHttp\Client();
        $user = 'user';
        $password = 'password';
        $response = $client->request('POST', $endpoint, ['query' => [
            'user' => $user,
            'pass' => $password,
            'mode' => "login",
        ]]);

        return $response->getBody()->__toString();
    }


    public function otherCurlRequest()
    {
        $client = new Client();

        $response = $client->request('GET', $endpoint, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Host' => 'api-ssl.bitly.com',
                'Authorization' => "Bearer " . $api_token,
            ],
            'params' => [
                'default_group_guid' => $default_group_guid,
                'name' => $name,
            ],
        ]);

        if ($response->getStatusCode() != 200) {
            $error_message = 'Bitly Request error: url: ' . $endpoint . ' Status: ' . $response->getStatusCode() . 'Can not get user data.';
            LogHelper::sendToLog($error_message);

            return false;
        } else {
            $user_data = $response->getBody();

            return $user_data;
        }

        return false;
    }


    static function makeCsvFileStatic($source_file_id)
    {
        $source_file = SourceFile::find($source_file_id);
        if (!empty($source_file)) {
            $file_extension = 'csv';
            $file_name = rtrim($source_file->name, '.txt');
            $file_name = $file_name . '.' . $file_extension;
            $full_path = '/files_for_download/' . $file_name;

            $handle = fopen(public_path($full_path), 'w+');

            // put csv header
            fputcsv($handle, Result::getAllFields());
            // put all data
            Result::chunk(100, function ($results) use ($full_path, $handle) {
                if (count($results)) {
                    foreach ($results as $result) {
                        fputcsv($handle, $result->toArray());
                    }
                }
            });
            fclose($handle);

            $source_lines_count = Car::whereNotNull('id')->count();
            $result_lines_count = Result::whereNotNull('id')->count();
            $goal_quality_progress = $result_lines_count * 100 / $source_lines_count;
            $source_file->update([
                'csv_file' => $full_path,
                'parsed' => true,
                'goal_progress' => $goal_quality_progress,
                'parsed_date' => Carbon::now(),
            ]);
            return round($goal_quality_progress);
        }

        return 0;
    }


    public function downloadCsv(Request $request)
    {
        $source_file = SourceFile::find($request->source_file_id);
        if (!empty($source_file)) {
            $file = public_path($source_file->csv_file);
            $headers = array(
                'Content-Type: text/csv',
            );

            return Response::download($file, rtrim($source_file->name, '.txt') . '.csv', $headers);
        }

        return false;
    }


    static function isValidFileName($file_name)
    {
        return !preg_match('/[^A-Za-z0-9.#\\-$]/', $file_name);
    }


    static function randomUuid()
    {
        return Uuid::generate();
    }


    static function sendErrorEmail($message)
    {
        //...
        $error_email = new SendError($message);
        Mail::to(config('custom.errors_emails_array'))
            ->send($error_email
                ->subject(config('custom.errors_emails_subject'))
            );
        //.....
    }


    static function convertText($text, $mode = 'cyr')
    {
        if ($mode == 'cyr') {
            return iconv('UTF-8', 'ISO-8859-5//IGNORE', $text);  //ukrainian
        }

        return iconv('UTF-8', 'ISO-8859-1//IGNORE', $text);  //german
    }


    static function readFileByLines($path)
    {
        $lines = [];
        $handle = fopen($path, "r");
        while (!feof($handle)) {
            $lines[] = trim(fgets($handle));
        }
        fclose($handle);

        return $lines;
    }


    static function changeBodyFont($lines)
    {
        $new_lines = [];
        foreach ($lines as $key => $line) {
            if (preg_match('/^<body*/', $line)) {
                $new_lines[] = '<body  bgcolor="white">';
            } elseif (preg_match('/^<style\s*/', $line)) {
                $new_lines[] = $line;
                $new_lines[] = "body { font-family: DejaVu Sans, sans-serif !important; }";
            } else {
                $new_lines[] = $line;
            }
        }

        return $new_lines;
    }


}

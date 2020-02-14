<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StackImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stack:import 
                           {file     : XML file to import}
                           {--table= : Where to import data}
                           {--clear  : Clear table before the import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import XML data file to table';

    /** @var string */
    protected $nowStr;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->nowStr = now()->format('Y-m-d H:i:s');

        $filename = $this->argument('file');
        if (is_null($filename)) {
            $this->error('Filename required');
            return 1;
        }

        $tablename = $queueName = $this->option('table') ?? $this->guessTable($filename);
        $clear = $queueName = $this->option('clear');

        $bar = $this->output->createProgressBar();
        $bar->setFormatDefinition('custom', 'Records: %current%');
        $bar->setFormat('custom');

        if ($clear) {
            DB::table($tablename)->delete();
        }

        $n = 0;
        $fh = fopen($filename, 'r');
        while (($line = fgets($fh)) !== false) {
            if ($data = $this->parseRow($line)) {

                $safeData = $this->translateData($tablename, $data);
                DB::table($tablename)->insert($safeData);

                if (++$n == 1000) {
                    $bar->advance($n); // it's step, not the total value
                    $n = 0;
                }
            }
        }
        fclose($fh);

        $bar->advance($n);
        $bar->finish();
    }

    private function guessTable($filename)
    {
        return Str::snake(basename($filename, '.xml'));
    }

    private function parseRow($text)
    {
        $text = trim(strtr($text, ["\r" => '']));
        if (strpos($text, '<row') !== 0) {
            return false;
        }
        $tmp = (array)simplexml_load_string($text);
        return $tmp['@attributes'];
    }

    function uniqueEmail($data)
    {
        if (!empty($data['name'])) {
            $email = Str::slug($data['name'], '.');
        } else {
            $email = Str::random(8);
        }
        if (!empty($data['id'])) {
            $email .= '.' . $data['id'];
        }
        $email .= '@example.com';

        return $email;
    }

    private function translateData($tablename, $data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            // We use unsigned integer for IDs
            if (substr($key, -2) === 'Id' && $value === '-1') {
                $value = 1;
            }
            switch ($key) {
                case 'CreationDate':
                case 'Date':
                    $result['created_at'] = Carbon::make($value)->format('Y-m-d H:i:s');
                    break;

                case 'LastEditDate':
                    $result['updated_at'] = Carbon::make($value)->format('Y-m-d H:i:s');
                    break;

                case 'LastActivityDate':
                    $result['activity_at'] = Carbon::make($value)->format('Y-m-d H:i:s');
                    break;

                case 'LastAccessDate':
                    $result['last_accessed_at'] = Carbon::make($value)->format('Y-m-d H:i:s');
                    break;

                case 'ClosedDate':
                    $result['closed_at'] = Carbon::make($value)->format('Y-m-d H:i:s');
                    break;

                case 'CommunityOwnedDate':
                    $result['owned_at'] = Carbon::make($value)->format('Y-m-d H:i:s');
                    break;

                case 'AboutMe':
                    $result['about_me'] = html_entity_decode($value);
                    break;

                case 'Body':
                case 'Text':
                    $result['body'] = html_entity_decode($value);
                    break;

                case 'Count':
                    $result[rtrim($tablename, 's') . '_count'] = $value;
                    break;

                case 'Name':
                    $result[rtrim($tablename, 's') . '_name'] = $value;
                    break;

                case 'DisplayName':
                    $result['name'] = $value;
                    break;

                case 'UserDisplayName':
                    $result['user_name'] = $value;
                    break;

                case 'Class':
                    $result[rtrim($tablename, 's') . '_class'] = $value;
                    break;

                case 'LastEditorDisplayName':
                    $result['editor_name'] = $value;
                    break;

                case 'OwnerDisplayName':
                    $result['owner_name'] = $value;
                    break;

                case 'TagBased':
                    $result['tag_based'] = ($value == 'True' ? 1 : 0);
                    break;

                case 'AcceptedAnswerId':
                    $result['accepted_id'] = $value;
                    break;

                case 'OwnerUserId':
                    $result['owner_id'] = $value;
                    break;

                case 'LastEditorUserId':
                    $result['editor_id'] = $value;
                    break;

                case 'PostHistoryTypeId':
                    $result['history_type_id'] = $value;
                    break;

                case 'Tags':
                    $result['tags'] = html_entity_decode($value);
                    break;

                case 'RevisionGUID':
                    $result['revision_guid'] = uuid2bin($value);
                    break;

                default:
                    $result[Str::snake($key)] = $value;
            }
        }

        if ($tablename == 'users') {
            $result += [
                'email' => $this->uniqueEmail($result),
                'email_verified_at' => $this->nowStr,
                'password' => Str::random(10), // unreal hash
                'remember_token' => Str::random(10),
            ];
        }

        if (isset($result['created_at']) && !isset($result['updated_at'])) {
            $result['updated_at'] = $result['created_at'];
        }

        return $result;
    }
}

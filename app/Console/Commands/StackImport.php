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

        $nowStr = now()->format('Y-m-d H:i:s');
        $n = 0;
        $fh = fopen($filename, 'r');
        while (($line = fgets($fh)) !== false) {
            if ($data = $this->parseRow($line)) {
                $safeData = $this->translateData($tablename, $data);

                if ($tablename == 'users') {
                    $safeData += [
                        'email' => $this->uniqueEmail($safeData),
                        'email_verified_at' => $nowStr,
                        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                        'remember_token' => Str::random(10),
                    ];
                }
                DB::table($tablename)->insert($safeData);

                if (++$n % 1000 == 0) {
                    $bar->advance($n);
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

                case 'LastAccessDate':
                case 'LastEditDate':
                    $result['updated_at'] = Carbon::make($value)->format('Y-m-d H:i:s');
                    break;

                case 'LastActivityDate':
                    $result['activity_at'] = Carbon::make($value)->format('Y-m-d H:i:s');
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

                case 'DisplayName':
                    $result['name'] = $value;
                    break;

                case 'UserDisplayName':
                    $result['display_name'] = $value;
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
        return $result;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class StackPostTag extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stack:post-tag
                           {--clear  : Clear table before the import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill post_tag pivot table based on posts denormalized data';

    /** @var array */
    private $tagNames = [];

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
        $clear = $queueName = $this->option('clear');

        $bar = $this->output->createProgressBar();
        $bar->setFormatDefinition('custom', 'Records: %current%');
        $bar->setFormat('custom');

        if ($clear) {
            DB::table('post_tag')->delete();
        }

        $n = 0;
        DB::table('posts')->orderBy('id')->chunk(100, function ($chunk) use ($bar, &$n) {
            foreach ($chunk as $row) {
                if ($tagNames = $this->extractTagNames($row->tag_list)) {
                    $data = $this->composeDataToInsert($row->id, $tagNames);
                    DB::table('post_tag')->insert($data);
                    $n += count($data);
                    if ($n >= 1000) {
                        $bar->advance($n); // it's step, not the total value
                        $n = 0;
                    }
                }
            }
        });

        $bar->advance($n);
        $bar->finish();
    }

    /**
     * @param string $rowTags
     * @return string[]
     */
    private function extractTagNames($rowTags)
    {
        return preg_split('/[<>]/', $rowTags, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * @param int      $postId
     * @param string[] $tagNames
     * @return array
     */
    private function composeDataToInsert($postId, array $tagNames)
    {
        $notUsedYet = array_diff($tagNames, $this->tagNames);
        if ($notUsedYet) {
            $justFound = DB::table('tags')
                ->whereIn('tag_name', $notUsedYet)
                ->pluck('tag_name', 'id')
                ->toArray();
            $this->tagNames += $justFound;
        }
        $data = [];
        foreach ($tagNames as $name) {
            $data[] = [
                'post_id' => $postId,
                'tag_id' => array_search($name, $this->tagNames),
            ];
        }
        return $data;
    }
}

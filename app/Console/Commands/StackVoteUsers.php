<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\Console\Helper\ProgressBar;
use Illuminate\Support\Collection;

class StackVoteUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stack:vote-users
                            {--action= : Vote type: accept|up-down}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill votes user_id by plausible values';

    /** @var array */
    private $actionToTypes = [
        'accept' => [1],
        'up-down' => [2, 3],
    ];

    /** @var ProgressBar */
    private $bar;

    /** @var int */
    private $n;

    /** @var int */
    private $maxUserId;

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
        $action = $this->option('action');
        if (!array_key_exists($action, $this->actionToTypes)) {
            $this->error('Wrong action');
            exit(1);
        }
        $methodName = 'action' . Str::studly($action);

        $this->bar = $this->output->createProgressBar();
        $this->bar->setFormatDefinition('custom', 'Records: %current%');
        $this->bar->setFormat('custom');

        $this->n = 0;
        DB::table('votes')
            ->select('id')
            ->whereIn('vote_type_id', $this->actionToTypes[$action])
            ->orderBy('vote_type_id')->orderBy('created_at')
            ->chunk(100, [$this, $methodName]);

        $this->bar->advance($this->n);
        $this->bar->finish();
    }

    /**
     * @param Collection $chunk
     */
    public function actionAccept($chunk)
    {
        foreach ($chunk as $row) {
            DB::table('votes')
                ->join('posts', 'posts.id', '=', 'votes.post_id')
                ->where('votes.id', $row->id)
                ->update(['votes.user_id' => DB::raw("posts.owner_id")]);
            if (++$this->n >= 1000) {
                $this->bar->advance($this->n);
                $this->n = 0;
            }
        }
    }

    /**
     * @param Collection $chunk
     */
    public function actionUpDown($chunk)
    {
        if (!isset($this->maxUserId)) {
            $this->maxUserId = DB::table('users')->orderBy('id', 'desc')->value('id');
            $this->info("Max user id is {$this->maxUserId}");
        }

        foreach ($chunk as $row) {

            $subQuery = "SELECT u.id
                FROM users AS u
                WHERE u.id >= RAND() * {$this->maxUserId}
                AND (posts.owner_id is null OR u.id <> posts.owner_id)
                ORDER BY u.id 
                LIMIT 1";

            DB::table('votes')
                ->join('posts', 'posts.id', '=', 'votes.post_id')
                ->where('votes.id', $row->id)
                ->update(['votes.user_id' => DB::raw("({$subQuery})")]);

            if (++$this->n >= 1000) {
                $this->bar->advance($this->n);
                $this->n = 0;
            }
        }
    }
}

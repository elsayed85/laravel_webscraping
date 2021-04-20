<?php

namespace App\Console\Commands\GenLib;

use goutte;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\Table;

class Search extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'genlib:search {query} {--page=1} {--sort=} {--sort_by=ASC} {--columns=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'search in genlib site ,
                                {query : book name}
                                {--columns : specify columns [id , author , title , md5 , publisher , year , pages , language , size , extension]}
                                {--sort : column name to sort}
                                {--sort_by : ASC or DESC}
                                {--sort_by : ASC or DESC}';

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
     * @return int
     */
    public function handle()
    {
        $query = $this->argument('query');
        $page = $this->option('page');
        $sortBy = $this->option('sort_by');
        $sort = $this->option('sort');
        $columns = $this->columns();

        $crawler = goutte::request('GET', "http://libgen.rs/search.php?req={$query}&open=0&view=simple&column=def&page={$page}&sort={$sort}&sortmode={$sortBy}");
        $results = collect();

        $bar = $this->output->createProgressBar($crawler->filter('table.c tr:not(:first-child)')->count());
        $bar->start();

        $crawler->filter('table.c tr:not(:first-child)')->each(function ($node) use ($results, $columns, $bar) {
            $childs = $node->children();
            $book = collect();

            $columns->contains('id') ?  $book->put("id",  $childs->eq(0)->text()) : null;
            $md5 = substr($childs->filter("a#{$book->get('id')}")->attr('href'), 19);
            $columns->contains('author') ? $book->put("author",  $childs->eq(1)->text()) : null;
            $columns->contains('title') ? $book->put("title",  $childs->eq(2)->text()) : null;
            $columns->contains('md5') ? $book->put("md5",  $md5) : null;
            $columns->contains('publisher') ? $book->put("publisher",  $childs->eq(3)->text()) : null;
            $columns->contains('year') ? $book->put("year",  $childs->eq(4)->text()) : null;
            $columns->contains('pages') ? $book->put("pages",  $childs->eq(5)->text()) : null;
            $columns->contains('language') ? $book->put("language",  $childs->eq(6)->text()) : null;
            $columns->contains('size') ? $book->put("size",  $childs->eq(7)->text()) : null;
            $columns->contains('extension') ? $book->put("extension",  $childs->eq(8)->text()) : null;
            $columns->contains('download') ? $book->put("download",  "http://library.lol/main/{$md5}") : null;
            $results->push($book);
            $bar->advance();
        });

        $this->table($columns->toArray(), $results->toArray());

        $bar->finish();
    }

    public function columns()
    {
        if (is_null($this->option('columns'))) {
            return collect([
                'id',
                'author',
                'title',
                'publisher',
                'pages',
                'extension',
                'download'
            ]);
        }
        return collect(explode(',', $this->option('columns')));
    }
}

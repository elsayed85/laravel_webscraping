<?php

namespace App\Services;

use goutte;


class GenLib
{
    public static function search($query, $page = 1 , $perPage = 25, $sort = null,  $sortBy = "ASC" )
    {
        $crawler = goutte::request('GET', "http://libgen.rs/search.php?req={$query}&res={$perPage}&open=0&view=simple&column=def&page={$page}&sort={$sort}&sortmode={$sortBy}");
        $results = collect();
        $crawler->filter('table.c tr:not(:first-child)')->each(function ($node) use ($results) {
            $childs = $node->children();
            $book = collect();
            $book->put("id",  $childs->eq(0)->text());
            $book->put("author",  $childs->eq(1)->text());
            $book->put("title", $childs->eq(2)->text());
            $book->put("html_title", str_replace('href="', 'target="_blank" href="http://libgen.rs/', $childs->eq(2)->html()));
            $book->put("md5",  substr($childs->filter("a#{$book->get('id')}")->attr('href'), 19));
            $book->put("publisher",  $childs->eq(3)->text());
            $book->put("year",  $childs->eq(4)->text());
            $book->put("pages",  $childs->eq(5)->text());
            $book->put("language",  $childs->eq(6)->text());
            $book->put("size",  $childs->eq(7)->text());
            $book->put("extension",  $childs->eq(8)->text());
            $book->put("mirrors",  [
                ['title' => $childs->eq(9)->filter('a')->attr('title'), 'link' => $childs->eq(9)->filter('a')->attr('href')],
                ['title' => $childs->eq(10)->filter('a')->attr('title'), 'link' => $childs->eq(10)->filter('a')->attr('href')],
                ['title' => $childs->eq(11)->filter('a')->attr('title'), 'link' => $childs->eq(11)->filter('a')->attr('href')],
                ['title' => $childs->eq(12)->filter('a')->attr('title'), 'link' => $childs->eq(12)->filter('a')->attr('href')],
                ['title' => $childs->eq(13)->filter('a')->attr('title'), 'link' => $childs->eq(13)->filter('a')->attr('href')],
            ]);
            $results->push($book);
        });
        return $results;
    }

    public static function download($md5)
    {
        $crawler = goutte::request('GET', "http://library.lol/main/{$md5}");
        $links = $crawler->filter("#download");
        $Cloudflare = $links->selectLink("Cloudflare")->link()->getUri();
        $IPFSIO = $links->selectLink("IPFS.io")->link()->getUri();
        $Infura = $links->selectLink("Infura")->link()->getUri();
        return collect(["Cloudflare" => $Cloudflare, "IPFS.io" => $IPFSIO, "Infura" => $Infura]);
    }
}

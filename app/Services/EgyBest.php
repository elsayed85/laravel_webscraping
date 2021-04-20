<?php

namespace App\Services;

use goutte;

class EgyBest
{
    public static $baseUrl = "https://back.egybest.co";

    public  static function search($title, $page = 1)
    {
        $crawler = goutte::request('GET', self::$baseUrl .  "/explore/?q={$title}&page={$page}");
        $results = collect();

        $crawler->filter(".movies")->filter("a.movie")->each(function ($movie) use ($results) {
            $movieInfo = collect();
            $movieInfo->put("url", str_replace("/?ref=search-p1", "", $movie->link()->getUri()));
            $movieInfo->put("slug", substr(strrchr(parse_url($movieInfo->get('url'))['path'], '/'), 1));
            $movieInfo->put("img", $movie->filter('img')->attr('src'));
            $movieInfo->put("title", $movie->filter('.title')->text("--"));
            $movieInfo->put("quality", $movie->filter('.ribbon')->text("--"));
            $movieInfo->put("rating", $movie->filter('.rating')->text("--"));
            $results->push($movieInfo);
            return $movie;
        });

        return $results;
    }

    public static function show($slug)
    {
        $crawler = goutte::request('GET', self::$baseUrl . "/movie/{$slug}");
        $error = $crawler->filter('.msg_box.error')->text('no_error');

        if ($error != "no_error") {
            return $error;
        }

        $movie = collect();
        $crawler->filter('.full_movie')->each(function ($details) use ($movie) {
            $movie->put("img", $details->filter(".movie_img img")->attr("src"));
            $movie->put("title", $details->filter(".movie_title")->text());

            $info = $details->filter(".movieTable tr");

            $movie->put("lang_country", $info->eq(1)->filter("td:nth-child(2) a")->each(function ($moreDetails) {
                return $moreDetails->text("--");
            }));

            $movie->put("rate", $info->eq(2)->filter("td:nth-child(2) a")->text("--"));
            $movie->put("rate_text", $info->eq(2)->filter("td:nth-child(2)")->text('--'));

            $movie->put("types", $info->eq(3)->filter("td:nth-child(2) a")->each(function ($moreDetails) {
                return $moreDetails->text("--");
            }));

            $movie->put("raring_value", $info->eq(4)->filter('span[itemprop="ratingValue"]')->text('--'));
            $movie->put("bestRating", $info->eq(4)->filter('span[itemprop="bestRating"]')->text('--'));
            $movie->put("ratingCount", $info->eq(4)->filter('span[itemprop="ratingCount"]')->text('--'));

            $movie->put("period", $info->eq(5)->filter("td:nth-child(2)")->text('--'));

            $movie->put("quality", $info->eq(6)->filter("td:nth-child(2) a")->each(function ($moreDetails) {
                return $moreDetails->text("--");
            }));

            $movie->put("translated", $info->eq(7)->filter("td:nth-child(2) a")->text('--'));

            $movie->put("users_rating", $details->filter('.ItemRatingScore')->text('--'));
        });

        return $movie;

        $movie->put("users_rating", $crawler->filter('.cpnt')->text('--'));

        $movieStory = $crawler->filter('.full_movie')->nextAll()->eq(1);
        $movie->put("story_title", $movieStory->filter('.pda:nth-child(2) strong')->text('--'));
        $movie->put("story_description", str_replace("<strong>{$movie->get('story_title')}</strong><br>", "", $movieStory->filter('.pda:nth-child(2)')->html()));

        $movie->put("trailer_url", $crawler->filter('#yt_trailer div')->attr("url"));

        $movie->put("cast", $crawler->filter('.cast_item')->each(function ($cast) {
            $img = $cast->filter('img')->attr('src');
            $name = $cast->filter('img')->attr('alt');
            $role = $cast->filter('div.td:nth-child(2) span')->attr('title');
            return ['img' => $img, 'name' => $name, 'role' => $role];
        }));

        $movie->put("watch", self::$baseUrl . $crawler->filter('#watch_dl iframe')->attr('src'));

        $movie->put("watch_download", $crawler->filter('.dls_table tbody tr')->each(function ($row) {
            $col = $row->filter('td');
            $quality = $col->eq(0)->text();
            $quality_text = $col->eq(1)->text();
            $size = $col->eq(2)->text();

            $wd = $col->eq(3)->filter('a._open_window')->each(function ($el) {
                return ['text' => $el->text("--"), "url" => self::$baseUrl . $el->attr('data-url')];
            });

            return ['quality' =>  $quality, 'quality_text' => $quality_text, 'size' => $size, 'links' => $wd];
        }));

        $movie->put("related", $crawler->filter('.contents.movies_small a.movie')->each(function ($relatedMovie) {
            $url = str_replace("/?ref=similar", "",  $relatedMovie->attr('href'));
            $img_url = $relatedMovie->filter('img')->attr('src');
            $title = $relatedMovie->filter('span.title')->text('--');
            return ['url' => $url, 'title' => $title, 'img' => $img_url, 'slug' => substr(strrchr(parse_url($url)['path'], '/'), 1)];
        }));

        return $movie;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Laravel\Dusk\Browser;
use Symfony\Component\DomCrawler\Crawler;

use Revolution\Salvager\Client;
use Revolution\Salvager\Drivers\Chrome;


class SalvagerController extends Controller
{


    public function crawlerlocal(){

            $options = [
                '--window-size=1920,1080',
                '--start-maximized',
                '--headless',
                '--lang=ja_JP',
            ];


            $client = new Client(new Chrome($options));

            $client->browse(function (Browser $browser) use (&$crawler) {

                  $crawler = $browser
                                ->visit('https://www.google.co.jp/search?q=Freedive')

                                ->crawler();

                                $results = [];

                                $crawler->filter('div.g')->each(function($node) use(&$results) {
                                    if (count($node->filter('a')) !== 0 && count($node->filter('h3')) !== 0) {
                                        $href = $node->filter('a')->attr('href');
                                            $info = [];
                                            $info['title'] = $node->filter('h3')->text();

                                            preg_match('(https?://[-_.!~*\'()a-zA-Z0-9;/?:@=+$,%#]+)', $href, $match);
                                            $info['url'] = urldecode($match[0]);
                                            $results[] = $info;
                                        
                                    }
                                });


                                dd($results);

            });
            


    }
}



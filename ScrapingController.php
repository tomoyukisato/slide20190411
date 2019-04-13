<?php
namespace App\Http\Controllers;
 
 use Illuminate\Http\Request;
 use App\Http\Controllers\Controller;
 
 // Goutte ライブラリの読み込み
 use Goutte\Client;
 
 class ScrapingController extends Controller
 {
 
     /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function index()
     {
         $url_format = 'https://www.google.co.jp/search?q=%query%&num=%num%';
 
         $keyword = 'FREEDiVE';

         $replace = [urlencode($keyword), 100];
         $search = ['%query%', '%num%'];
         
         $url = str_replace($search, $replace, $url_format);
 
         $client = new Client();
         $guzzleClient = new \GuzzleHttp\Client(['verify' => false]);

        $client->setClient($guzzleClient);

        $result = [];

        $crawler = $client->request('GET', $url);

        $crawler->filter('div.g')->each(function($node) use(&$result) {
            if (count($node->filter('a')) !== 0 && count($node->filter('h3')) !== 0) {
                $href = $node->filter('a')->attr('href');
                if (preg_match('/url\?/', $href)) {
                    $info = [];
                    $info['title'] = $node->filter('h3')->text();

                    preg_match('(https?://[-_.!~*\'()a-zA-Z0-9;/?:@=+$,%#]+)', $href, $match);
                    $info['url'] = urldecode($match[0]);
                    $result[] = $info;
                    
                }
            }
        });
    dd($result);
    }
    

}
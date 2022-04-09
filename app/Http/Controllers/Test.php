<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Options;

class Test extends BaseController
{
    public const MAX_ITEM_COUNT = 100;

    public static function index() {
        echo 'Start getItemsFromSteam command';

        $items = [];

        for ($start = 0; $start <= 10000; $start += self::MAX_ITEM_COUNT) {
            do {
                $response = Http::get('https://steamcommunity.com/market/search/render/?query=&start=' . $start . '&count=' . self::MAX_ITEM_COUNT . '&search_descriptions=0&sort_column=popular&sort_dir=desc&appid=730&category_730_ItemSet%5B%5D=any&category_730_ProPlayer%5B%5D=any&category_730_StickerCapsule%5B%5D=any&category_730_TournamentTeam%5B%5D=any&category_730_Weapon%5B%5D=any');
                sleep(15);
            } while ($response->failed());

            $html = $response->json()['results_html'];

            $dom = new Dom();
            $dom->setOptions(
                ['strict' => TRUE]
            );
            $dom->loadStr($response->json()['results_html']);
            $itemRows = $dom->getElementsByClass('market_listing_row');

            $i = 0;

            foreach ($itemRows as $row) {
                $itemDom = new Dom();
                $itemDom->loadStr($row->outerHtml());

                $itemName = $itemDom->getElementsByClass('market_listing_item_name')->text;
                $itemQuantity = $itemDom->getElementsByClass('market_listing_num_listings_qty')->text;
                $itemPrice = substr(explode(' ', $itemDom->getElementsByClass('sale_price')->text)[0], 1);
//            $itemImg = $itemDom->getElementsByTag('#result_' . $i . '_image')->text;

                $items[] = [
                    'name' => $itemName,
                    'quantity' => $itemQuantity,
                    'price' => $itemPrice,
//                'image' => $itemImg
                ];

                echo '[itemName] ' . $itemName . PHP_EOL;
            }
        }

        echo '<pre>';
        print_r($items);
        die;
    }
}

<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Options;

class Test extends Controller
{
    public const MAX_ITEM_COUNT = 100;

    public static function index() {
        echo 'Start getItemsFromSteam command' . PHP_EOL;

        $requestCount = 1;
        $totalPagesCount = self::getTotalPagesCount();
        $isRequestFailed = FALSE;

        for ($start = 0; $start <= $totalPagesCount; $start += self::MAX_ITEM_COUNT) {
            do {
                echo '[Request counts: ' . $requestCount . '] Make steam request. Limit = ' . $start . PHP_EOL;
                $requestCount++;

                try {
                    $response = Http::get('https://steamcommunity.com/market/search/render/?query=&norender=1&start=' . $start . '&count=' . self::MAX_ITEM_COUNT . '&search_descriptions=0&sort_column=popular&sort_dir=desc&appid=730&category_730_ItemSet%5B%5D=any&category_730_ProPlayer%5B%5D=any&category_730_StickerCapsule%5B%5D=any&category_730_TournamentTeam%5B%5D=any&category_730_Weapon%5B%5D=any');
                } catch (ConnectionException $e) {
                    $isRequestFailed = TRUE;
                    continue;
                }

                $isRequestFailed = FALSE;
                sleep(10);
            } while ($isRequestFailed);

            $result = $response->json()['results'];

            foreach ($result as $item) {
                $itemName = $item['name'];
                $itemQuantity = $item['sell_listings'];
                $itemPrice = $item['sell_price'];
                $itemType = $item['asset_description']['type'];
                $itemImg = $item['asset_description']['icon_url'];

                DB::table('items')
                    ->updateOrInsert(
                        ['name' => $itemName, 'type' => $itemType],
                        [
                            'name' => $itemName,
                            'quantity' => $itemQuantity,
                            'price' => $itemPrice,
                            'type' => $itemType,
                            'icon_url' => $itemImg
                        ]
                    );

                echo '[itemName] ' . $itemName . PHP_EOL;
            }
        }

        echo 'Stop getItemsFromSteam command' . PHP_EOL;
    }

    private static function getTotalPagesCount(): int {
        $requestCount = 1;

        do {
            echo '[' . __METHOD__ . '][Request counts: ' . $requestCount . '] Get total pages limit from steam' . PHP_EOL;
            $requestCount++;
            $response = Http::get('https://steamcommunity.com/market/search/render/?query=&norender=1&start=' . 0 . '&count=' . self::MAX_ITEM_COUNT . '&search_descriptions=0&sort_column=popular&sort_dir=desc&appid=730&category_730_ItemSet%5B%5D=any&category_730_ProPlayer%5B%5D=any&category_730_StickerCapsule%5B%5D=any&category_730_TournamentTeam%5B%5D=any&category_730_Weapon%5B%5D=any');
            sleep(10);
        } while ($response->failed());

        return $response->json()['total_count'];
    }
}

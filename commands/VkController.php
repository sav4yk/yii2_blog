<?php
/**
 * @link http://www.sav4yk.ru/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Exception;

/**
 * This command downloads data from vk-group sevkogdavoda.
 *
 * The received data is parsed and stored in the database.
 *
 * @author Sav4yk <mail@sav4yk.ru>
 */
class VkController extends Controller
{

    /**
     * This command downloads, parse and store data to the database .
     * @return int Exit code
     * @throws Exception
     */
    public function actionIndex()
    {
        $access_token = 'e61167d1e61167d1e61167d132e6658601ee611e61167d1b9bd9dbb9f22962a91111b9a';
        $wall = file_get_contents("https://api.vk.com/method/wall.get?v=5.126&filter=all&domain=sevkogdavoda" .
            "&count=200&access_token=" . $access_token . ""); // Отправляем запрос
        $wall = json_decode($wall, true); // Преобразуем JSON-строку в массив

        $InsertArray = [];
        foreach ($wall['response']['items'] as $item) {
            if (!isset($item['is_pinned']) && $item['marked_as_ads'] != 1) {
                $InsertArray[] = [
                    'text' => $item['text'],
                    'source' => 'sevkogdavoda',
                    'created_at' => $item['date'],
                    'updated_at' => isset($item['edited']) ? $item['edited'] : '',
                ];
            }
        }
        if (count($InsertArray) > 0) {
            $columnNameArray = ['text', 'source', 'created_at', 'updated_at'];
            $insertCount = Yii::$app->db->createCommand()
                ->batchInsert(
                    "news_source", $columnNameArray, $InsertArray
                )
                ->execute();
            print "--------------------------------\n";
            print "Saved " . $insertCount . " news\n";
        } else {
            print "--------------------------------\n";
            print "Saved 0 news\n";
        }
        return ExitCode::OK;
    }

}

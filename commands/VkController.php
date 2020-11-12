<?php
/**
 * @link http://www.sav4yk.ru/
 */

namespace app\commands;

use app\models\NewsSource;
use GuzzleHttp\Client;
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
        $client = new Client();
        $access_token = '';

        $res = $client->request('GET', 'https://api.vk.com/method/wall.get?v=5.126&filter=all&domain=sevkogdavoda' .
                '&count=200&access_token=' . $access_token , [
            'timeout' => 10,
            'read_timeout' => 10,
            'http_errors' => true
        ]);

        if ($res->getStatusCode() == 200) {
            $wall = $res->getBody()->getContents();
            $wall = json_decode($wall, true); // Преобразуем JSON-строку в массив

            $InsertArray = [];
            foreach ($wall['response']['items'] as $item) {
                if (!isset($item['is_pinned']) && $item['marked_as_ads'] != 1) {
                    $news = NewsSource::find()->where([
                        'created_at' => $item['date'],
                    ])
                        ->one();
                    if (!$news) {
                        $InsertArray[] = [
                            'text' => $item['text'],
                            'source' => 'sevkogdavoda',
                            'created_at' => $item['date'],
                            'updated_at' => isset($item['edited']) ? $item['edited'] : '',
                        ];
                    } else {
                        if (isset($item['edited']) && $news->updated_at != $item['edited']) {
                            $news->updated_at= $item['edited'];
                            $news->save(false);
                            var_dump($news);
                        }


                    }
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
        }
        return ExitCode::OK;
    }

}

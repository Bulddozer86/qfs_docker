<?php


namespace FlatParserBundle\Resources\Classes\FlatEntities;

use FlatParserBundle\Resources\Classes\ParserFlatData;
use FlatParserBundle\Resources\Services\Downloader;
use QFS\BusinessLogicBundle\Resources\Services\Helpers\DateManager;

class Olx extends ParserFlatData
{
    /**
     * Parsing element
     */
    public function parsing($html)
    {
        if (!$html) {
            return null;
        }

        \phpQuery::newDocumentHTML($html);

        $info = explode(',', pq('.pdingleft10.brlefte5')->text());
        $detail = [];

        foreach (pq('td.value') as $e) {
            $detail[] = trim(pq($e)->text());
        }

        $locationInfo = explode(
          ',',
          pq('#offerdescription > div.offer-titlebox > div.offer-titlebox__details > a > strong')->text()
        );

        $data = [
          'price' => pq('.fright.optionsbar > .pricelabel.tcenter > strong')->text(),
          'rooms' => $detail[2],
          'date' => strtotime($this->getDate(trim($info[1]))),
          'headline' => trim(pq('#offerdescription > div.offer-titlebox > h1')->text()),
          'district' => $locationInfo[2],
          'resource' => $this->getName(),
          'main_data' => trim(pq('#textContent')->html()),
        ];

        $links = pq('.photo-glow img.bigImage');

        foreach ($links as $link) {
            $data['images'][] = pq($link)->attr('src');
        }

        \phpQuery::unloadDocuments();

        return $data;
    }

    /**
     * Parsing phone number
     * @return array
     */
    public function getPhone($link)
    {
        preg_match('/ID(.*?)\./', $link, $matches);
        $id = $matches[1];

        if (!$id) {
            return null;
        }

        $ajaxLink = strtr($this->phoneLink(), ['_ID_' => $id]);
        $response = Downloader::download([$ajaxLink]);
        $result = json_decode($response[0], true);

        if (!$result['value']) {
            return null;
        }

        $regex = '/\+?[(0-9)][\d-\()\-\s+]{5,12}[1-9]/';
        preg_match_all($regex, $result['value'], $matches);

        return $matches[0];
    }

    public function getDate($date)
    {
        $dateItems = explode(' ', $date);
        $dateItems[1] = DateManager::getNumberByName(trim($dateItems[1]));

        return implode('.', $dateItems);
    }

}
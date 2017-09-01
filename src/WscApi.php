<?php


namespace Wsc\Wsc;

use GuzzleHttp\Client;

class WscApi
{
    /**
     * Your WSC API key
     */
    protected $apiKey;

    /**
     * Guzzle HTTP Client instance
     */
    protected $client;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client();
    }

    /**
     * Retreives species information
     *
     * @param $lsid
     * @return mixed
     */
    public function fetchSpecies($lsid)
    {
        $res = $this->client->request(
            'GET',
            'http://wsc.nmbe.ch/api/lsid/urn:lsid:nmbe.ch:spidersp:' . $lsid  . '?apiKey=' . $this->apiKey
        );

        return json_decode($res->getBody());
    }

    /**
     * Retreives genus information
     *
     * @param $lsid
     * @return mixed
     */
    public function fetchGenus($lsid)
    {
        $res = $this->client->request(
            'GET',
            'http://wsc.nmbe.ch/api/lsid/urn:lsid:nmbe.ch:spidergen:' . $lsid  . '?apiKey=' . $this->apiKey
        );

        return json_decode($res->getBody());
    }

    /**
     * Retreives family information
     *
     * @param $lsid
     * @return mixed
     */
    public function fetchFamily($lsid){
        $res = $this->client->request(
            'GET',
            'http://wsc.nmbe.ch/api/lsid/urn:lsid:nmbe.ch:spiderfam:' . $lsid  . '?apiKey=' . $this->apiKey
        );

        return json_decode($res->getBody());
    }

    /**
     * Retreives all updated taxa for the given period
     *
     * @param $date
     * @return array
     */
    public function fetchUpdatedTaxa($type = null, $date = null)
    {
        $supportedTypes = ['family', 'genus', 'species'];

        $taxa = [];

        $updated = [];

        if($date){
            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
                die('invalid date');
            }
        }

        if($type){
            if(!in_array($type, $supportedTypes)) die('invalid type');
        }



        if($type){
            $url = $date ? 'http://wsc.nmbe.ch/api/updates?type=' . $type . '&date=' . $date . '&apiKey=' . $this->apiKey :
                'http://wsc.nmbe.ch/api/updates?type=' . $type . '&apiKey=' . $this->apiKey;
        }else{
            $url = $date ? 'http://wsc.nmbe.ch/api/updates?date=' . $date . '&apiKey=' . $this->apiKey :
                'http://wsc.nmbe.ch/api/updates?apiKey=' . $this->apiKey;

        }

        $res = $this->client->request('GET', $url);

        $temp = json_decode($res->getBody());

        if(isset($temp->updates)){
            foreach($temp->updates as $update){
                $taxa[] = $update;
            }
        }

        if(isset($temp->_links->next)){
            $next = $this->nextLink($temp->_links->next);
            $taxa = array_merge($taxa, $next);
        }

        foreach($taxa as $tx){
            if (strpos($tx, 'urn:lsid:nmbe.ch:spiderfam') !== false){
                $updated['families'][] = str_replace('urn:lsid:nmbe.ch:spiderfam:', '', $tx);
            }
            if (strpos($tx, 'urn:lsid:nmbe.ch:spidergen') !== false){
                $updated['genera'][] = str_replace('urn:lsid:nmbe.ch:spidergen:', '', $tx);
            }
            if (strpos($tx, 'urn:lsid:nmbe.ch:spidersp') !== false){
                $updated['species'][] = str_replace('urn:lsid:nmbe.ch:spidersp:', '', $tx);
            }
        }

        ksort($updated);

        return $updated;
    }

    /**
     * Retreives the valid taxon details for synonym
     *
     * @param $link
     * @return mixed
     */
    public function fetchValidTaxon($link)
    {
        $res = $this->client->request('GET', $link . '?apiKey=' . $this->apiKey);
        return json_decode($res->getBody());
    }

    protected function nextLink($link){
        $res = $this->client->request('GET', $link);
        $temp = json_decode($res->getBody());
        $taxa = [];
        if(isset($temp->updates)){
            foreach($temp->updates as $update){
                $taxa[] = $update;
            }
        }
        if(isset($temp->_links->next)){
            $next = $this->nextLink($temp->_links->next);
            $taxa = array_merge($taxa, $next);
        }
        return $taxa;
    }
}
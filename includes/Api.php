<?php

namespace Awps;

use Exception;

class Api
{

    private const ENDPOINT = 'https://api.meaningcloud.com/summarization-1.0';
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function get_text_summary($text, $sentences)
    {
        $summary = '';
        $params = [
            'key' => $this->key,
            'sentences' => $sentences,
            'txt' => $text
        ];

        try {

            $url = self::ENDPOINT . '?' . http_build_query($params, '', '&');
            $request = wp_remote_post($url);

            if (!is_wp_error($request)) {
                $response = json_decode($request['body'], true);

                if (array_key_exists('summary', $response)) {
                    return $response['summary'];
                } else {
                    throw new Exception("Error fetching summary");
                }
            } else {
                throw new Exception("Error fetching summary");
            }
        } catch (Exception $e) {
            // show error
            error_log($e);
        }

        return $summary;
    }
}

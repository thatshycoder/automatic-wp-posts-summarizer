<?php

namespace Awps;

defined('ABSPATH') || exit;

class Utils
{
    private const SETTINGS_KEY = 'awps_settings_key';

    /**
     * Sanitize settings input fields
     * 
     * @param array $inputs
     * @param array $options
     * @return array
     */
    public static function sanitize_inputs($inputs, $options, $api_keys): array
    {
        $sanitized_input = [];

        foreach ($inputs as $input_key => $input_value) {

            if (empty($input_value)) {
                $sanitized_input[$input_key] = $options[$input_key];
            } else {

                $input_value = str_replace(' ', '', $input_value);
                $input_value = trim(strip_tags(stripslashes($input_value)));
                $input_value = sanitize_text_field($input_value);

                // encrypt api key fields
                if (!empty($api_keys)) {

                    // TODO: check call_user_func
                    foreach ($api_keys as $api_key) {

                        if ($input_key === $api_key) {

                            $input_value = self::encrypt($input_value);
                        }
                    }
                }

                $sanitized_input[$input_key] = $input_value;
            }
        }

        return $sanitized_input;
    }

    /**
     * Save a random key for settings field encryption
     */
    public static function store_key(): void
    {
        $key = bin2hex(random_bytes(16)); // generate random opensll compatible string
        add_option(self::SETTINGS_KEY, $key);
    }

    /**
     * Encrypt an input
     * 
     * @param string
     */
    private static function encrypt($key)
    {
        $encryption_keys = self::get_keys();

        if (!empty($encryption_keys)) {
            return base64_encode(openssl_encrypt($key, AWPS_ENCRYPTION_METHOD, $encryption_keys['key'], 0, $encryption_keys['iv']));
        }
    }

    /**
     * Decrypt and input
     * 
     * @param string
     */
    public static function decrypt($key)
    {
        $encryption_keys = self::get_keys();

        if (!empty($keys)) {
            return openssl_decrypt(base64_decode($key), AWPS_ENCRYPTION_METHOD, $encryption_keys['key'], 0, $encryption_keys['iv']);
        }
    }

    private static function get_keys(): array
    {
        $option = get_option(self::SETTINGS_KEY);

        if ($option) {

            $secrete_key = hash('sha256', $option);
            $secrete_iv =  substr(hash('sha256', $option), 0, 16);

            return ['key' => $secrete_key, 'iv' => $secrete_iv];
        }

        return [];
    }
}

<?php



// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);





class MailChimpConnector {
    public function __construct($apiKey, $apiUri) {

        $this->APIKey = $apiKey;
        $this->APIUri = $apiUri;
        $this->AuthToken = base64_encode("user:".$this->APIKey);
    }


    public function subscribe_user($data) {
        $ch = curl_init();

        $post_data = array(
            "email_address" => $data["email"],
            "email_type" => "html",
            "status" => "subscribed",
            "merge_fields" => array(
                "MMERGE1" => $data["nombre"]
            )

        );


        curl_setopt($ch, CURLOPT_URL, $this->APIUri."/lists/".$data["list_id"]."/members/");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Basic ".$this->AuthToken
        ));
        curl_setopt($ch, CURLOPT_USERAGENT, "PHP-MCAPI/2.0");
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result);


        if($response->title == "Member Exists") {
            return json_encode(array("code" => 1, "message" => "Ya estás suscrito a esta lista"));
        }
        else {
            return json_encode(array("code" => 0, "message" => "¡Bienvenido a la familia de la bomba, a partir de ahora estarás al día de nuestros sorteos y promociones!"));
        }


    }


    public function user_add_tag($data) {
        $ch = curl_init();

        $hash_user = md5(strtolower($data["email"]));

        $post_data = array(
           "tags" => array(
               "name" => $data["tag_name"],
               "status" => $data["active"]
           )

        );

        curl_setopt($ch, CURLOPT_URL, $this->APIUri."/lists/".$data["list_id"]."/members/".$hash_user."/tags/");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Basic ".$this->AuthToken
        ));
        curl_setopt($ch, CURLOPT_USERAGENT, "PHP-MCAPI/2.0");
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result);


    }

}
?>
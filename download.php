<?php
function url_parts($URI) //for fetching domain,id & code
{
    $values = parse_url($URI);
    $domain = $values['host'];
    $id   = substr($values['path'], 1, 6);
    $code = substr($values['path'], 8, 7);
    return array(
        $domain,
        $id,
        $code
    );
}
function find_key($URI) //for finding the key to access the filekey
{
    $data = file_get_contents($URI);
    preg_match_all('/<a[^>]+>/i', $data, $result);
    $link = $result[0][7];
    $key  = substr($link, strpos($link, '&key='));
    $arr  = explode('"', $key, 2);
    $key  = $arr[0];
    $key  = substr($key, 5);
    return $key;
}
function find_file_key($URI) //for finding the file_key to get the file
{
    $data = file_get_contents($URI);
    preg_match_all('/<a[^>]+>/i', $data, $res);
    $new_link = $res[0][6];
    $file_key = substr($new_link, strpos($new_link, '&file_key='));
    $file_key = substr($file_key, 10);
    $file_key = substr($file_key, 0, -9);
    return $file_key;
}
if (isset($_GET['url'])) {
    $URI = $_GET['url'];
    list($domain, $id, $code) = url_parts($URI);
    $key      = find_key($URI);
    $key_url  = "https://$domain/?page=mirror&id=$id&code=$code&key=$key";
    $file_key = find_file_key($key_url);
    header("Location: https://$domain/download?file_id=$id&file_code=$code&file_key=$file_key&serv=1");
}
?>

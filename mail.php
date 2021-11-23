<?php

$name = 'testname';
$phone = htmlspecialchars($_POST['phone'], ENT_NOQUOTES, 'UTF-8');
$email = htmlspecialchars($_POST['email'],ENT_NOQUOTES,'UTF-8');

$from = "info@testsite.pp.ua";
$to = "order@salesgenerator.pro";
$subject = "заявка Стойко";

$message = "Email:" . $email . "\nPhone:" . $phone;
$headers = "From:" . $from;
mail($to, $subject, $message, $headers);

$subdomain = 'mmrjacondastrider'; //Поддомен нужного аккаунта
$link = 'https://' . $subdomain . '.amocrm.ru/api/v2/account'; //Формируем URL для запроса
$access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjE4ZGRkMjZmOGRlYTBkMWRjYzM3NTM1MDgzYzE3MGMwYWNkNTQ4ZGI5ZGQ0MjRlMTdkNzU4ZWI5YmM0MmIxNWM5OTAwZjkyNGI4MGNkMjkxIn0.eyJhdWQiOiI5ZDljMThlMy0zOGQ4LTRmODktYjhlOS1iYTNjOWRhYzYxMGMiLCJqdGkiOiIxOGRkZDI2ZjhkZWEwZDFkY2MzNzUzNTA4M2MxNzBjMGFjZDU0OGRiOWRkNDI0ZTE3ZDc1OGViOWJjNDJiMTVjOTkwMGY5MjRiODBjZDI5MSIsImlhdCI6MTYzNTQ5NzM1NiwibmJmIjoxNjM1NDk3MzU2LCJleHAiOjE2MzU1ODM3NTYsInN1YiI6Ijc1NDkzMTUiLCJhY2NvdW50X2lkIjoyOTc2NjIyMywic2NvcGVzIjpbInB1c2hfbm90aWZpY2F0aW9ucyIsImNybSIsIm5vdGlmaWNhdGlvbnMiXX0.YCy9Cz2DxuSiHJzeOz0dIA6eWgpHakfQ7FoYb8jAhd0fyD8Z3tR63K4pfAWG4tl_HMS2CBjvIcRLPD1YgbRXOqgboSMOSkda_id23vX_rRKL8w1sFxVLgVK4P902YxkkTOgbdzCj6DZ7sJDcC6MGqyQr5ExhopAqBrTp-Y7AzG3UGlQg7M9CAfB0PeYPRbW8Rd0Yx1efDKlk0iLEOWq2khFtlDq93yh8n_KwAiYMtSRoGAxOTSQcRLx8eb0JwZtFUFAoibZbdYKdI9paYuqYYtenbCZiY7deHZ97ef000s1Q5VDLIX8bby498cLFZ5AsKwbWPpI4ItGiPpFWBvNnjw';

$headers = array(
	'Authorization: Bearer ' . $access_token
);

$curl = curl_init(); //Сохраняем дескриптор сеанса cURL
/** Устанавливаем необходимые опции для сеанса cURL  */
curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
curl_setopt($curl,CURLOPT_URL, $link);
curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl,CURLOPT_HEADER, false);
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
$out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

/** Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
$code = (int)$code;

// коды возможных ошибок
$errors = array(
	400 => 'Bad request',
	401 => 'Unauthorized',
	403 => 'Forbidden',
	404 => 'Not found',
	500 => 'Internal server error',
	502 => 'Bad gateway',
	503 => 'Service unavailable',
);

try
{
	/** Если код ответа не успешный - возвращаем сообщение об ошибке  */
	if ($code < 200 || $code > 204) {
		throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
	}
}
catch(\Exception $e)
{
	die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
}

$phoneFieldId = '84135'; //ID поля "Телефон" в amocrm
$emailFieldId = '84133'; //ID поля "Email" в amocrm

$dealName = 'Заявка Стойко';
$dealStatusID = '38122756';
$dealSale = '1'; //Сумма сделки
$dealTags = 'test';  //Теги для сделки
$contactTags = 'test'; //Теги для контакта

$arrContactParams = array(
	// поля для сделки 
	"PRODUCT" => array(
		"namePerson"	=> $name,
		"phonePerson"	=> $phone,
		"emailPerson"	=> $email,
    ),
	// поля для контакта 
	"CONTACT" => array(
		"namePerson"	=> $name,
		"phonePerson"	=> $phone,
		"emailPerson"	=> $email,
    )
);

$arrTaskParams = array(  
'add' => array(
    0 => array(
        'name'  => $dealName,
            'status_id'     => $dealStatusID,
            'custom_fields'	=> array(
                /* ТЕЛЕФОН */
                array(
                    'id'	=> $phoneFieldId,
                    "values" => array(
                        array(
                            "value" => $arrContactParams["PRODUCT"]["phonePerson"],
                        )
                    )
                ),
                /* EMAIL */
                array(
                    'id'	=> $emailFieldId,
                    "values" => array(
                        array(
                            "value" => $arrContactParams["PRODUCT"]["emailPerson"],
                        )
                    )
                ),
            ),

            'contacts_id' => array(
                0 => $emailFieldId,
            ),
        ),
    ),
);

$link = "https://mmrjacondastrider.amocrm.ru/api/v2/leads";

$headers = array(
    "Accept: application/json",
    'Authorization: Bearer ' . $access_token
);

$curl = curl_init();
curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($arrTaskParams));
curl_setopt($curl,CURLOPT_URL, $link);
curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl,CURLOPT_HEADER, false);
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
$out = curl_exec($curl);
curl_close($curl);
$result = json_decode($out,TRUE);

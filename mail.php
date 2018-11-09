<?php

$name = $_POST["name"];
$phone = $_POST["phone"];
$subject = 'Новая заявка с сайта - ' . date('Y-m-d');
$sitename = 'trademark';

$body = "
<html>
<head>
<title>$sitename</title>
</head>
<body>
<table border='1'>
 <caption>Перезвоните!!!!</caption>
   <tr>
	    <th>Имя</th>
	    <th>Телефон</th>
   </tr>
   <tr>
	   <td>$name</td>
	   <td>$phone</td>
   </tr>
</table>
</body>
</html>
";
$fromName = 'trademark';
$attachmentArr = array();

/////////////////////////////////////////////////////////
$addressArr = array();
$addressArr[] = 'dode@xcodes.net';
//$addressArr[] = 'sdobrovol@mail.ru';


$se = DobrMailSender::sendMailGetaway($addressArr, $subject, $attachmentArr, $body, $fromName, 0);

if ($se == true) {
	echo "Спасибо! Скоро мы с вами свяжемся.";
} else {
	echo "Что то пошло не так!!";
}


class DobrMailSender
{
	
	/**
	 *
	 * @param array | string $to
	 * @param string $subject
	 * @param array $files
	 * @param string $body
	 * @param string $fromName
	 * @param integer $debugMode
	 * @return boolean
	 */
	static public function sendMailGetaway($to, $subject, $files = array(), $body = null, $fromName = null, $debugMode = 0)
	{
		$ret = false;
		
		if (!empty($to)) {
			if (!is_array($to)) {
				$to = array($to);
			}
			if (!empty($files) && !is_array($files)) {
				$files = array($files);
			}
			$postData = array(
				'use' => 'baribardacall',
				'pfgbplfnj' => true,
				'addressees' => json_encode($to),
				'Subject' => $subject,
				'SMTPDebug' => $debugMode
			);
			if ($body) {
				$postData['Body'] = $body;
			}
			if ($fromName) {
				$postData['FromName'] = $fromName;
			}
			
			foreach ($files as $fileItem) {
				$info = pathinfo($fileItem);
				$postData[$info['filename']] = '@' . $fileItem . ';filename=' . $info['basename'] . ';type=' . mime_content_type($fileItem);
			}
			
			$ret = self::_sendCurlRequest('http://92.46.122.98/call.baribarda.com/mailer/send.php', $postData);
		}
		
		return $debugMode > 0 ? $ret : !empty($ret['success']);
	}
	
	static private function _sendCurlRequest($url, $postData = null)
	{
		// инициализируем сеанс
		$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_URL, $url);
		// максимальное время выполнения скрипта
		curl_setopt($curl, CURLOPT_TIMEOUT, 40);
		// теперь curl вернет нам ответ, а не выведет
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// Отключаем ssl проверку
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		
		if ($postData !== null) {
			// передаем данные по методу post
			curl_setopt($curl, CURLOPT_POST, 1);
			// переменные, которые будут переданные по методу post
			curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
		}
		
		// отправка запроса
		$result = curl_exec($curl);
		
		$info = curl_getinfo($curl);
		if (empty($result)) {
			$result = array("http_code" => $info['http_code'], "error" => "Server is not responding");
		} else {
			$result = json_decode($result, true);
			$result['http_code'] = $info['http_code'];
		}
		// закрываем соединение
		curl_close($curl);
		
		return $result;
	}
	
}

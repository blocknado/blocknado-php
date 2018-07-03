<?php 

class Blocknado {

	protected $api_key = "Your API Key";
	protected $api_secret = "Your API Secret";
	protected $api_url = "https://blocknado.com/api/v1/";
		
	private function privateAPI($command, array $request = array())
	{
		$request['nonce'] = time();
		$url = $this->api_url.$command;
		$post = http_build_query($request, '', '&');
		$sign = hash_hmac('sha512', $post, $this->api_secret);

		$header = array(
			'Key: '.$this->api_key,
			'Sign: '.$sign,
		);

		static $ch = null;

		if(is_null($ch))
		{
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERAGENT, 
				'blocknado-php'
			);
		}

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$res = curl_exec($ch);

		if($res === false) {
			throw new Exception('Error: '.curl_error($ch));
		}

		$dec = json_decode($res, true);

		if(!$dec) {
			throw new Exception('Error: '.curl_error($res));
		}

		return $dec;
	}
	
	protected function publicAPI($url)
	{
		$options = array('https' =>
			array(
				'method'  => 'GET',
				'timeout' => 10 
			)
		);

		$context = stream_context_create($options);
		$feed = file_get_contents($url, false, $context);
		$json = json_decode($feed, true);

		return $json;
	}

	public function buy($pair, $amount, $price)
	{
		$params = array(
			'market' => $pair,
			'amount' => number_format($amount, 8, ".", ""),
			'price' => number_format($price, 8, ".", "")
		);

		$buy = $this->privateAPI('buy', $params);

		return $buy;
	}

	public function sell($pair, $amount, $price)
	{
		$params = array(
			'market' => $pair,
			'amount' => number_format($amount, 8, ".", ""),
			'price' => number_format($price, 8, ".", "")
		);

		$sell = $this->privateAPI('sell', $params);

		return $sell;
	}

	public function cancel($orderNumber)
	{
		$orderNumber = array('orderNumber' => $orderNumber);
		$cancel = $this->privateAPI('cancel', $orderNumber);

		return $cancel;
	}

	public function order($orderNumber)
	{
		$orderNumber = array('orderNumber' => $orderNumber);
		$order = $this->privateAPI('order', $orderNumber);

		return $order;
	}

	public function open($pair = null)
	{
		if($pair) {
			$open = $this->privateAPI('open', array('market' => $pair));
		}

		if(!$pair) {
			$open = $this->privateAPI('open');
		}

		return $open;
	}

	public function orderbook($pair)
	{
		return $this->publicAPI($this->api_url.'orderbook/'.$pair);
	}

	public function markets()
	{
		return $this->publicAPI($this->api_url.'markets');
	}

	public function balances()
	{
		return $this->privateAPI('balances');
	}
}

?>

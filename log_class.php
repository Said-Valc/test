<?php


class LogReturn
{
	
	protected $pattern = '#([0-9]{1,3}[\.][0-9]{1,3}[\.][0-9]{1,3}[\.][0-9]{1,3}) - - (\[.+\]) "([a-z]{3,4}) (\/.+\.php\??[a-z]*=?[a-z0-9]*) ([a-z]+\/[0-9]+\.[0-9]+)" [0-9]+ [0-9]+ "((http|https|ftp|ftps)\:\/\/.+)" "(Mozilla\/5\.0.+)"#i';
	
	public $views;
	public $urls;
	public $crawlers = [];
	public $statusCodes = [];
	public $arr = [];
	public $file = 'log.log';
	public $json_data;
	
	public function __construct()
	{
	    $file = file_get_contents($this->file);
	    preg_match_all($this->pattern, $file, $this->arr);
		$this->addViews();
		$this->addUrls();
		$this->addCrawlers();
		$this->addStatusCodes();
		$this->json_data = json_encode(['views' =>$this->views, 'urls' => $this->urls, 'crawlers' => $this->crawlers, 'statusCodes' => $this->statusCodes]);
	}
	
	private function addViews()
	{		
		if(count($this->arr) == 1) return false;
		$this->views = count($this->arr[0]);
	}
	
	private function addUrls()
	{
		if(count($this->arr) == 1) return false;
		$this->urls = count(array_unique($this->arr[6]));
		
	}
	
	private function addCrawlers()
	{
		$google = 0;
		$yandex = 0;
		$bing = 0;
		$baidu = 0;
		if(count($this->arr) == 1) return false;
			foreach($this->arr[8] as $key => $value){
				if(strpos($value, 'Google')){
					$google++;
				}
				if(strpos($value, 'Yandex')){
					$yandex++;
				}
				if(strpos($value, 'Bing')){
					$bing++;
				}
				if(strpos($value, 'Baidu')){
					$baidu++;
				}
				
			}
		$this->crawlers = ['Google' => $google,'Yandex' => $yandex, 'Bing' => $bing, 'Baidu' => $baidu];
	}
	
	private function addStatusCodes()
	{
		
		$_200 = 0;
		$_301 = 0;
		if(count($this->arr) == 1) return false;
			foreach($this->arr[0] as $key => $value){
				if(strpos($value, ' 200 ')){
					$_200++;
				}
				if(strpos($value, ' 301 ')){
					$_301++;
				}
			}
		$this->statusCodes = ['200' => $_200, '301' => $_301];
	}
	
	public function __toString(){
		return $this->json_data;
	}
	
}


?>
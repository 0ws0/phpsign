<?php
require_once './vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use SignLib\ApiSign;

class ApiSignTest extends TestCase
{   
    private $apiSign;
    private $requestData;
    private $sign;

    protected function setUp()
    {
    
        $this->apiSign = new ApiSign();
        $this->requestData =  ['appid' => 5288971,'menu'=> '客户服务列表', 'lat'=> 21.223,'lng'=>131.334,'timestamp'=>1525771205];
        $this->sign ='457C26F1AE70CE60452A0B6511E1DA0F0062D132';// '38E09D558901D8FB9507BCC44613BB2CA9948EE6';

    }
     
    public function testCreateSign()
    {
        $result = $this->apiSign->createSign($this->requestData);
        var_dump($result);
        $type = gettype($result);
        $this->assertEquals('string',$type);
    }

    public function testCheckSign()
    {
        $result = $this->apiSign->checkSign($this->requestData,$this->sign);
        $this->assertEquals(true,$result);
    }
}

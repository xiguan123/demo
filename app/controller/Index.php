<?php
// +----------------------------------------------------------------------
// | 文件: index.php
// +----------------------------------------------------------------------
// | 功能: 提供todo api接口
// +----------------------------------------------------------------------
// | 时间: 2021-11-15 16:20
// +----------------------------------------------------------------------
// | 作者: rangangwei<gangweiran@tencent.com>
// +----------------------------------------------------------------------

namespace app\controller;

use Error;
use Exception;
use app\model\Counters;
use think\response\Html;
use think\response\Json;
use think\facade\Log;
use think\facade\Request;

class Index
{

    /**
     * 主页静态页面
     * @return Html
     */
    public function index(): Html
    {
        # html路径: ../view/index.html
        return response(file_get_contents(dirname(dirname(__FILE__)).'/view/index.html'));
    }


    /**
     * 获取todo list
     * @return Json
     */
    public function getCount(): Json
    {
        $header = Request::header();
        return json($header);
        try {
            $data = (new Counters)->find(1);
            if ($data == null) {
                $count = 0;
            }else {
                $count = $data["count"];
            }
            $res = [
                "code" => 0,
                "data" =>  $count
            ];
            Log::write('getCount rsp: '.json_encode($res));
            return json($res);
        } catch (Error $e) {
            $res = [
                "code" => -1,
                "data" => [],
                "errorMsg" => ("查询计数异常" . $e->getMessage())
            ];
            Log::write('getCount rsp: '.json_encode($res));
            return json($res);
        }
    }


    /**
     * 根据id查询todo数据
     * @param $action `string` 类型，枚举值，等于 `"inc"` 时，表示计数加一；等于 `"reset"` 时，表示计数重置（清零）
     * @return Json
     */
    public function updateCount($action): Json
    {
        try {
            if ($action == "inc") {
                $data = (new Counters)->find(1);
                if ($data == null) {
                    $count = 1;
                }else {
                    $count = $data["count"] + 1;
                }
    
                $counters = new Counters;
                $counters->create(
                    ["count" => $count, 'id' => 1],
                    ["count", 'id'],
                    true
                );
            }else if ($action == "clear") {
                Counters::destroy(1);
                $count = 0;
            }

            $res = [
                "code" => 0,
                "data" =>  $count
            ];
            Log::write('updateCount rsp: '.json_encode($res));
            return json($res);
        } catch (Exception $e) {
            $res = [
                "code" => -1,
                "data" => [],
                "errorMsg" => ("更新计数异常" . $e->getMessage())
            ];
            Log::write('updateCount rsp: '.json_encode($res));
            return json($res);
        }
    }
    
//     //获取素材列表
//     public function getList()
//     {
//         $url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material";
//         $param['type'] = "news";
//         $param['offset'] = 0;
//         $param['count'] = 20;
//         $result = $this->requestCURL($url, $param);
//         return json($result);
//     }

//     public function requestCURL($url, $param = null)
//     {
//         $ch = curl_init();//初始化curl
//         curl_setopt($ch, CURLOPT_URL,$url); //要访问的地址
//         curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
//         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//跳过证书验证
//         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
//         if (!empty($param)){
//             curl_setopt($ch, CURLOPT_POST, 1);
//             curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
//         }
//         $data = json_decode(curl_exec($ch), true);
//         // $data = curl_exec($ch);
//         if(curl_errno($ch)){
//           print_r(curl_error($ch)); //若错误打印错误信息 
//         }
//         curl_close($ch);//关闭curl
//         return $data; //打印信息
//     }
    
    public function getList()
    {
        
        $vsersion = \think\facade\App::version();
        return json($version);
//         $url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material";
//         $param['type'] = "news";
//         $param['offset'] = 0;
//         $param['count'] = 20;
//         $data = http_build_query($param);

//         $options = array(
//             'http' => array(
//                 'method' => 'POST',
//                 //'header' => 'Content-type:application/x-www-form-urlencoded',
//                 'content' => $data
//                 //'timeout' => 60 // 超时时间（单位:s）
//                 )
//         );
//         $context = stream_context_create($options);
//         $result = file_get_contents($url, false, $context);
//         return json($result);
    }
}

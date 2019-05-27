<?php
/**
 * Created by PhpStorm.
 * User: smallForest<1032817724@qq.com>
 * Date: 2019-05-24
 * Time: 08:55
 */

namespace application;

use ToolSpace\Tool;

include_once __DIR__ . '/Base.php';

class Server extends Base
{

    public function index()
    {
        $fd_arr = [];
        //接收http请求从get获取message参数的值，给用户推送
        //$this->server->connections 遍历所有websocket连接用户的fd，给所有用户推送
        //经过我的测试$this->server->connections包含request请求的数量
        foreach ($this->server->connections as $fd) {
            // 需要先判断是否是正确的websocket连接，否则有可能会push失败
            if ($this->server->isEstablished($fd)) {
                $fd_arr[] = $fd;
            }
        }

        return Tool::print_json(1, "获取成功", $fd_arr);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: smallForest<1032817724@qq.com>
 * Date: 2019-05-27
 * Time: 09:16
 */
use ToolSpace\Main;

include_once __DIR__ . '/tool/Tool.php';
include_once __DIR__ . '/library/Medoo/Medoo.php';
include_once __DIR__ . '/library/php-jwt/src/JWT.php';

//这里是socketServer
class Websocket
{
    protected $server = null;
    protected $open_model = true;//是否是公开模式 在公开模式下不用鉴权随意发送消息

    public function __construct()
    {
        echo "http://127.0.0.1:9501" . PHP_EOL;
        // 创建socket服务器
        $this->server = new Swoole\WebSocket\Server("127.0.0.1", 9501);
        //监听连接
        $this->server->on('open', function (swoole_websocket_server $server, $request) {
            $this->server->push($request->fd, $request->fd);
            echo "server: handshake success with fd{$request->fd}\n";
        });
        //收到消息
        $this->server->on('message', function (Swoole\WebSocket\Server $server, $frame) {
            echo "连接总数:" . count($this->server->connections) . PHP_EOL;
            echo "receive from fd:{$frame->fd},data:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
            //解析data查询到聊天对象
            if ($this->open_model) {
                $arr     = json_decode($frame->data, true);
                $to_fd   = $arr['fd'];
                $message = $arr['message'];
                if (is_null($to_fd)) {
                    $server->push($frame->fd, "数据格式错误！");
                }
                if (is_null($message) || $message == '') {
                    $server->push($frame->fd, "小心内容错误！");
                }
                $server->push($to_fd, $frame->data);
            }
        });
        //关闭
        $this->server->on('close', function ($ser, $fd) {
            echo "client {$fd} closed\n";
        });

        $this->server->on('request', function ($request, $response) {

            if ($request->server['request_uri'] == '/favicon.ico' || $request->server['request_uri'] == '/undefined') {
                $response->status(404);
                $response->end();
            }
            //注册自动引入方法
            spl_autoload_register("ToolSpace\Tool::autoload");
            $obj = new Main();
            //返回json格式的数据
            $response->header('Content-type', 'application/json');
            $response->end($obj->do($request,$this->server));
            unset($obj);
        });
        $this->server->start();
    }
}

new Websocket();

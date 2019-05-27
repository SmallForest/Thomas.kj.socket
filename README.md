1. 需要swoole扩展
2. php run.php运行
3. 错误信息在php_error.log中查看
4. Medoo
5. JWT
6. 路由
7. 数据库配置 application/Base.php中
8. JWT token有效期以及key在tool/Tool.php中查看
9. 自动加载tool和application中的一级类文件
10. 已经继承了socket $this-server可以在项目任何位置使用，方便推送消息。
11. 适合做实时项目
#### 增加新的application/中的类的步骤
1. 比如在application中创建类Server.php
2. 增加tool/Route.php访问路由

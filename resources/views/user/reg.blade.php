<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>用户注册</title>
</head>
<body>
    <form action="/register" method="post">
        {{csrf_field()}}
        <table>
            <tr>
                <td>账号:</td>
                <td><input type="text" name="uname"></td>
            </tr>
            <tr>
                <td>密码:</td>
                <td><input type="password" name="upwd"></td>
            </tr>
            <tr>
                <td>年龄:</td>
                <td><input type="text" name="uage"></td>
            </tr>
            <tr>
                <td>邮箱:</td>
                <td><input type="text" name="uemail"></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input type="submit" value="注册"></td>
            </tr>
        </table>
    </form>
</body>
</html>
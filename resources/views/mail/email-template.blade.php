<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact Mail</title>
</head>

<body>
    <h1><strong>Tên khách hàng: {{ $data['name'] }}</strong></h1>
    <p>Email: {{ $data['email'] }}</p>
    <p>Số điện thoại: {{ $data['phone'] }}</p>
    <p>Nội dung: {{ $data['message'] }}</p>
</body>

</html>

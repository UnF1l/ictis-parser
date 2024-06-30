<?php
    header('Content-Type: application/json');
    $conn = mysqli_connect('MySQL-8.2', 'root', '', 'ictis');
	
    $res = mysqli_query($conn, 'SELECT Title, Date, QR FROM Announcements') or die(mysqli_error($conn));
    $ann = [];
    for($i = 0; $i<6; $i++)
    {
        $ann[$i] = mysqli_fetch_assoc($res);
    }

    $res = mysqli_query($conn, 'SELECT Title, Img, QR FROM News') or die(mysqli_error($conn));
    $new = [];
    for($i = 0; $i<6; $i++)
    {
        $new[$i] = mysqli_fetch_assoc($res);
    }

    $timeFormat = datefmt_create('ru_RU', IntlDateFormatter::MEDIUM, IntlDateFormatter::NONE, 'Europe/Moscow', IntlDateFormatter::GREGORIAN, 'E,dd  MMMM');
	$time = datefmt_format($timeFormat, time());
    $tmp = mb_strtoupper(substr($time,0,2));
    $time[0] = $tmp[0];
    $time[1] = $tmp[1];
    $res = mysqli_query($conn, "SELECT Classroom, lesson_1, lesson_2, lesson_3, lesson_4, lesson_5, lesson_6, lesson_7 FROM Schedule WHERE Date='$time'") or die(mysqli_error($conn));
    $sch = [];
    for($i = 0; $i<14; $i++)
    {
        $sch[$i] = mysqli_fetch_assoc($res);
    }
    $answer = ['Announcements'=>$ann,
        'News'=> $new,
        'Schedule'=> $sch];//;
    echo json_encode($answer);
?>
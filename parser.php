<?php
    require_once './vendor/autoload.php';
    require_once './phpqrcode/qrlib.php';
    use DiDom\Document;
	
	$conn = mysqli_connect('MySQL-8.2', 'root', '', 'ictis');
	
    $document = new Document("https://ictis.sfedu.ru/news/category/featured/", true);

    $posts = $document->find('.new');
    for($i = 0; $i<6; $i++)
    {
        $img = $posts[$i]->first('img')->src;
        $link = $posts[$i]->first('.textnew-p')->href;
        $text = preg_replace('#\}1 #', '', $posts[$i]->first('.textnew-p')->text());
        $rnd = rand(1, 5);
        $QR = 'https://example.local/QRs/' . $text . $rnd . '.png';
        QRcode::png($link, './QRs/' . $text . $rnd . '.png', 'H', 8);
        mysqli_query($conn, "REPLACE INTO News (Title, URL, Img, QR) VALUES ('$text', '$link', '$img', '$QR')") or die(mysqli_error($conn));
    }
    
    $document->loadHtmlFile('https://ictis.sfedu.ru/news/category/announcement/', true);

    $posts = $document->find('.new');
    for($i = 0; $i<6; $i++)
    {
        $link = $posts[$i]->first('.textnew-p')->href;
        $text = $posts[$i]->first('.textnew-p')->text();
        $date = $posts[$i]->first('span')->text();
        $rnd = rand(1, 5);
        $QR = 'https://example.local/QRs/' . $text . $rnd . '.png';
        QRcode::png($link, './QRs/' . $text . $rnd . '.png', 'H', 8);
        mysqli_query($conn, "REPLACE INTO Announcements (Title, URL, Date, QR) VALUES ('$text', '$link', '$date', '$QR')") or die(mysqli_error($conn));
    }

    function parse_classroom($conn,$link, $classroom)
    {
        $document = file_get_contents("https://webictis.sfedu.ru/schedule-api/?group=$link.html");
        preg_match('#05"\], \["(.+?)\], \["Вс#su', $document, $week);
        $days = explode('], ["', $week[1]);
        for($i = 0; $i<6; $i++)
        {
            $tmp = preg_replace('#\"\"#', "\"Cвободно\"", $days[$i]);
            $lessons = explode('", "', $tmp);
            $lessons[7] = substr($lessons[7],0,-1);
            $lessons[0]= preg_replace('#..,#', ",", $lessons[0]);
            mysqli_query($conn, "REPLACE INTO Schedule (Classroom, Date, lesson_1, lesson_2, lesson_3, lesson_4, lesson_5, lesson_6, lesson_7) VALUES ('$classroom', '$lessons[0]', '$lessons[1]', '$lessons[2]', '$lessons[3]', '$lessons[4]', '$lessons[5]', '$lessons[6]', '$lessons[7]')") or die(mysqli_error($conn));
        }
    }
    parse_classroom($conn, 'a14', 'Г-401');
    parse_classroom($conn, 'a15', 'Г-405');
    parse_classroom($conn, 'a16', 'Г-410');
    parse_classroom($conn, 'a17', 'Г-412');
    parse_classroom($conn, 'a18', 'Г-413');
    parse_classroom($conn, 'a19', 'Г-418');
    parse_classroom($conn, 'a20', 'Г-423');
    parse_classroom($conn, 'a21', 'Г-424');
    parse_classroom($conn, 'a22', 'Г-425');
    parse_classroom($conn, 'a23', 'Г-427');
    parse_classroom($conn, 'a24', 'Г-431');
    parse_classroom($conn, 'a179', 'Г-437');
    parse_classroom($conn, 'a25', 'Г-438');
    parse_classroom($conn, 'a26', 'Г-439');
?>
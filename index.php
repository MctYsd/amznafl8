<!DOCTYPE html>
<html lang="ja">

<head>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="HandheldFriendly" content="True" />
	<title>amazon affiliate Allowance</title>
	<link rel="icon" href="favicon.ico" type="image/png">

</head>

<body>

<?php

$mes='
    <h2>amazonからの御駄賃を計算</h2>
    
    <a href="https://affiliate.amazon.co.jp/home" target="_blank">ホーム</a> > アカウントの管理 > <a href="https://affiliate.amazon.co.jp/home/account/payment/history" target="_blank">支払い履歴の確認</a><br>
    のページでcntrl+A で選択してフォームにコピペして送信！

';

/**
 * こんなデータが入ってくるのを想定
 * 
Jul 01 2024	05/2024コミッション収入	¥84.	¥528.
Jun 01 2024	04/2024コミッション収入	¥189.	¥444.
May 01 2024	03/2024コミッション収入	¥255.	¥255.
Apr 29 2024	ギフト券による支払い	
-¥1,421.
¥0.
Apr 01 2024	02/2024コミッション収入	¥1,211.	¥1,421.
Mar 01 2024	01/2024コミッション収入	¥140.	¥210.
Feb 01 2024	12/2023コミッション収入	¥70.	¥70.
Jan 30 2024	ギフト券による支払い	
-¥637.
¥0.
Jan 01 2024	11/2023コミッション収入	¥207.	¥637.
Dec 01 2023	10/2023コミッション収入	¥153.	¥430.
Oct 01 2023	08/2023コミッション収入	¥125.	¥277.
Sep 01 2023	07/2023コミッション収入	¥23.	¥152.
Aug 01 2023	06/2023コミッション収入	¥37.	¥129.
Jul 01 2023	05/2023コミッション収入	¥92.	¥92.
Jun 29 2023	ギフト券による支払い	
-¥622.
¥0.

 */

 if(!empty($_REQUEST["text"])){

    // 入力データ
    $data =$_REQUEST["text"];
    
    //echo $data;
    //echo $date."=>".$amount."<hr>"; 

    // 正規表現で「ギフト券による支払い」を抽出
    // amazonのさじ加減で変える場合もありえる。改行が含まれる正規表現であることに注意。
    preg_match_all("/(\w{3} \d{2} \d{4})\tギフト券による支払い\t(\r\n|\n|\r)-¥([\d,]+)\./", $data, $matches, PREG_SET_ORDER);

    //var_dump($matches);

    $payments = [];
    $totalAmount = 0;

    // 抽出したデータを処理
    foreach ($matches as $match) {
        $date = $match[1];
        $amount = str_replace(',', '', $match[3]);
        $amount = (int) $amount;

        // 配列に保存
        $payments[] = [
            'date' => $date,
            'amount' => $amount
        ];

        // 合計金額を更新
        $totalAmount += $amount;
        $startDate=$date;
    }

    // 日付の降順で並べ替え
    usort($payments, function ($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

    // 結果を表示
    foreach ($payments as $payment) {
        echo date("Y/m - ",strtotime($payment['date'])) . " ¥" . number_format($payment['amount']) . "<hr>\n";
    }

 
    // 勤続期間を「○年○ヶ月」で表示
    $date1 = new DateTime($startDate);
    $date2 = new DateTime();
    $interval = $date1->diff($date2);

    echo "" .date("Y/m ~ ",strtotime($startDate)).date("Y/m"). "\n (";
    echo $interval->y . "年" . $interval->m . "ヶ月" . ")\n<br>";

   // 合計金額を表示    
    echo "<h3>ギフト券による支払い額の合計: ¥" . number_format($totalAmount) . "\n</h3><hr>";
    echo '<a href="'.$_SERVER["PHP_SELF"].'">HOME</a>' . "\n";

 }else{
    echo$mes;
    echo'<form action="'.$_SERVER["PHP_SELF"].'" method="POST">
    <textarea cols="100" rows="20" name="text"></textarea><br>
    <input type="submit" value="calculate!">
    </form>';
 }


?>
</body>
</html>
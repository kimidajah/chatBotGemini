<?php
$apiKey = 'AIzaSyCkFF34Jq9j8z5wwfJw-jSolvMC8yWq4SU'; // Ganti dengan API key Anda jika diperlukan
$botResponse = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $userMessage = $_POST['message'];

    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $apiKey;

    $data = [
        'contents' => [
            [
                'parts' => [
                    [
                        'text' => $userMessage
                    ]
                ]
            ]
        ]
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
            'ignore_errors' => true
        ]
    ];

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        $botResponse = 'Maaf, terjadi kesalahan saat menghubungi AI.';
    } else {
        $responseDecoded = json_decode($result, true);
        if (isset($responseDecoded['candidates'][0]['content']['parts'][0]['text'])) {
            $botResponse = $responseDecoded['candidates'][0]['content']['parts'][0]['text'];
        } else {
            $botResponse = 'Maaf, saya tidak mengerti. Coba lagi.';
            // Untuk debugging: $botResponse .= '<pre>' . print_r($responseDecoded, true) . '</pre>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple ChatBot</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <h2>PHP ChatBot</h2>
        </div>
        <div class="chat-box">
            <?php if (!empty($botResponse)): ?>
                <div class="message bot-message">
                    <p><?php echo nl2br(htmlspecialchars($botResponse)); ?></p>
                </div>
            <?php endif; ?>
             <?php if (isset($userMessage) && !empty($userMessage)): ?>
                <div class="message user-message">
                    <p><?php echo htmlspecialchars($userMessage); ?></p>
                </div>
            <?php endif; ?>
        </div>
        <div class="chat-input">
            <form action="" method="post">
                <input type="text" name="message" placeholder="Ketik pesan Anda..." required autofocus>
                <button type="submit">Kirim</button>
            </form>
        </div>
    </div>
</body>
</html>

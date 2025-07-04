<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('error_log', 'error.log');

require_once 'ResponWebhookFormatter.php';
// this is simple php webhook for mpwa, not recommended using thi procedural pattern if you have a lot of keywrds!
header('content-type: application/json; charset=utf-8');
$data = json_decode(file_get_contents('php://input'), true);
if (!$data)  die('this url is for webhook.');

file_put_contents('whatsapp.txt', '[' . date('Y-m-d H:i:s') . "]\n" . json_encode($data) . "\n\n", FILE_APPEND);
$message = strtolower($data['message']); // this is incoming message from whatsapp
$from = strtolower($data['from']); // this is the sender's whatsapp number
$bufferimage = isset($data['bufferImage']) ? $data['bufferImage'] : null; // this is the image buffer if the message is image

$respon = false; // 
$responFormatter = new ResponWebhookFormater();
// EXAMPLE RESPONS
// to bold the message just add ->bold('text') before function respon;
// to italic the message just add ->italic('text') before function respon;
// to add new line the message just add ->line('text') before function respon;


// -------------------------------------------

// respon text
if ($message == 'halo') {
    $respon = $responFormatter->line('Hello {name}')->line('Anything I can help?')->responAsText();
}
// respon text with quoted
if ($message == 'hi') {
    $respon = $responFormatter->quoted()->line('Hello')->line('Anything I can help?')->responAsText();
}
// respon media (support imgage,audio,document and video)
if ($message == 'media') {
    $respon = $responFormatter->line('Caption for you {name}')->responAsMedia('https://upload.wikimedia.org/wikipedia/commons/thumb/6/6b/WhatsApp.svg/512px-WhatsApp.svg.png');
}

if ($message == 'media2') {
    $respon = $responFormatter->line('This is a document for you {name}')
        ->responAsMedia('https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf', 'document', 'dummy.pdf');
}
// respon button
if ($message == 'button') {
    $respon = $responFormatter->line('This is the message content')->bold("thick")
        ->addButton("just reply", 'reply', 'just reply')
        ->addButton("Call me", 'call', '6281222xxxxxx')
        ->addButton("copy me", 'copy', '123123')
        ->addButton('visit me', 'url', 'https://google.com')
        ->image('https://upload.wikimedia.org/wikipedia/commons/thumb/6/6b/WhatsApp.svg/512px-WhatsApp.svg.png')
        ->footer('This is footer')
        ->responAsButton();
}



// respon list message
if ($message == 'list') {
    $respon = $responFormatter->line('This is the message content')->bold("thick")
        ->addSection(
            "First menu",
            [
                ['title' => 'List Item 1', 'rowId' => 'id2', 'description' => ''],
                ['title' => 'List Item 2', 'rowId' => 'id3', 'description' => ''],
            ]
        )->addSection(
            "Second menu",
            [
                ['title' => 'List Item 1', 'rowId' => 'id2', 'description' => ''],
                ['title' => 'List Item 2', 'rowId' => 'id3', 'description' => ''],
            ]
        )->footer('This is footer')
        ->image('https://upload.wikimedia.org/wikipedia/commons/thumb/6/6b/WhatsApp.svg/512px-WhatsApp.svg.png')
        ->responAsList();
}



// save respon to file
if ($respon) {
    file_put_contents('response.txt', '[' . date('Y-m-d H:i:s') . "]\n" . $respon . "\n\n", FILE_APPEND);
}
echo $respon;

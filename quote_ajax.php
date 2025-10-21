<?php
header("Content-Type: application/json");

$quotes = [
    ["quote_text" => "The future belongs to those who believe in the beauty of their dreams.", "author" => "Eleanor Roosevelt"],
    ["quote_text" => "Success is not final, failure is not fatal: it is the courage to continue that counts.", "author" => "Winston Churchill"],
    ["quote_text" => "Education is the most powerful weapon which you can use to change the world.", "author" => "Nelson Mandela"],
    ["quote_text" => "The only way to do great work is to love what you do.", "author" => "Steve Jobs"],
    ["quote_text" => "Believe you can and you're halfway there.", "author" => "Theodore Roosevelt"],
    ["quote_text" => "Don't watch the clock; do what it does. Keep going.", "author" => "Sam Levenson"],
    ["quote_text" => "The expert in anything was once a beginner.", "author" => "Helen Hayes"],
    ["quote_text" => "Your limitation—it's only your imagination.", "author" => "Unknown"],
    ["quote_text" => "Great things never come from comfort zones.", "author" => "Unknown"],
    ["quote_text" => "Dream it. Wish it. Do it.", "author" => "Unknown"]
];

$randomQuote = $quotes[array_rand($quotes)];

echo json_encode($randomQuote);
?>

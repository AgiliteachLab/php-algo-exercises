<?php

function runTest($exNumber, $expectedOutput) {
    $file = __DIR__ . "/exercises/ex" . str_pad($exNumber, 2, "0", STR_PAD_LEFT) . ".php";
    ob_start();
    include $file;
    $output = trim(ob_get_clean());
    return $output === $expectedOutput;
}

$results = [];
$score = 0;

$tests = [
    1 => "1
2
3
4
5
6
7
8
9
10",
    2 => "0
2
4
6
8
10
12
14
16
18
20"
    // Compléter avec plus de tests...
];

foreach ($tests as $num => $expected) {
    $results[$num] = runTest($num, $expected);
    if ($results[$num]) $score++;
}

$finalNote = $score / 2;
echo "Score brut: $score/40\n";
echo "Note finale: $finalNote / 20\n";

// Créer un badge de réussite
$badgeColor = $finalNote >= 10 ? "brightgreen" : "red";
$badge = "![Score](https://img.shields.io/badge/Note-$finalNote/20-$badgeColor)";

// Enregistrer la note dans un fichier JSON
$username = getenv("GITHUB_ACTOR") ?: "inconnu";
$dataFile = __DIR__ . "/scores.json";

$scores = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
$scores[$username] = [
    "note" => $finalNote,
    "badge" => $badge,
    "timestamp" => date("Y-m-d H:i:s")
];
file_put_contents($dataFile, json_encode($scores, JSON_PRETTY_PRINT));

// Générer un email personnalisé
$emailTemplate = file_get_contents(__DIR__ . "/email_template.txt");
$emailBody = str_replace("{{note}}", $finalNote, $emailTemplate);

// Simuler l'envoi de l'email
file_put_contents(__DIR__ . "/mail_to_{$username}.txt", $emailBody);

?>
